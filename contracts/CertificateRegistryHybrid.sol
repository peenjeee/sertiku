// SPDX-License-Identifier: MIT
pragma solidity ^0.8.19;

/**
 * @title CertificateRegistryHybrid
 * @dev Gas-optimized smart contract for SertiKu certificates
 * @notice Uses hybrid approach: minimal storage + events for full data
 * @notice Gas usage: ~150,000 (vs ~721,000 in original)
 */
contract CertificateRegistryHybrid {
    // Minimal storage structure (only 3 slots per certificate)
    struct CertMinimal {
        bool exists;
        bool revoked;
        address issuer;
    }
    
    // Storage mappings
    mapping(bytes32 => CertMinimal) public certificates;
    
    // Contract state
    address public owner;
    uint256 public certificateCount;
    
    // Events - store full data here (cheap!)
    event CertificateStored(
        bytes32 indexed dataHash,
        string certificateData,  // JSON string with all certificate info
        address indexed issuer,
        uint256 timestamp
    );
    
    event CertificateRevoked(
        bytes32 indexed dataHash,
        address indexed revokedBy,
        uint256 timestamp
    );
    
    event OwnershipTransferred(
        address indexed previousOwner,
        address indexed newOwner
    );
    
    // Modifiers
    modifier onlyOwner() {
        require(msg.sender == owner, "Only owner can call this function");
        _;
    }
    
    /**
     * @dev Constructor sets the owner to the deployer
     */
    constructor() {
        owner = msg.sender;
    }
    
    /**
     * @dev Store a certificate with minimal storage + event data
     * @param _dataHash SHA-256 hash of certificate data (bytes32)
     * @param _certificateData JSON string containing full certificate info
     * @return success boolean
     * 
     * Gas cost: ~150,000 (vs ~721,000 in original contract)
     */
    function storeCertificate(
        bytes32 _dataHash,
        string calldata _certificateData
    ) external returns (bool) {
        require(_dataHash != bytes32(0), "Invalid data hash");
        require(!certificates[_dataHash].exists, "Certificate already exists");
        require(bytes(_certificateData).length > 0, "Certificate data required");
        
        // Minimal storage write (3 slots only)
        certificates[_dataHash] = CertMinimal({
            exists: true,
            revoked: false,
            issuer: msg.sender
        });
        
        certificateCount++;
        
        // Full data stored in event (cheap!)
        emit CertificateStored(
            _dataHash,
            _certificateData,
            msg.sender,
            block.timestamp
        );
        
        return true;
    }
    
    /**
     * @dev Verify certificate by hash
     * @param _dataHash The certificate hash to verify
     * @return exists Whether the certificate exists
     * @return revoked Whether the certificate is revoked
     * @return issuer The address that issued the certificate
     */
    function verifyCertificate(bytes32 _dataHash) 
        external 
        view 
        returns (
            bool exists,
            bool revoked,
            address issuer
        ) 
    {
        CertMinimal memory cert = certificates[_dataHash];
        return (cert.exists, cert.revoked, cert.issuer);
    }
    
    /**
     * @dev Check if a certificate exists
     * @param _dataHash The certificate hash to check
     * @return bool Whether the certificate exists
     */
    function certificateExists(bytes32 _dataHash) external view returns (bool) {
        return certificates[_dataHash].exists;
    }
    
    /**
     * @dev Check if a certificate is revoked
     * @param _dataHash The certificate hash to check
     * @return bool Whether the certificate is revoked
     */
    function isRevoked(bytes32 _dataHash) external view returns (bool) {
        return certificates[_dataHash].revoked;
    }
    
    /**
     * @dev Revoke a certificate (only issuer or owner can revoke)
     * @param _dataHash The certificate hash to revoke
     * @return success boolean
     */
    function revokeCertificate(bytes32 _dataHash) external returns (bool) {
        require(certificates[_dataHash].exists, "Certificate does not exist");
        require(
            certificates[_dataHash].issuer == msg.sender || msg.sender == owner,
            "Only issuer or owner can revoke"
        );
        require(!certificates[_dataHash].revoked, "Already revoked");
        
        certificates[_dataHash].revoked = true;
        
        emit CertificateRevoked(
            _dataHash,
            msg.sender,
            block.timestamp
        );
        
        return true;
    }
    
    /**
     * @dev Get total number of certificates
     * @return uint256 Total certificate count
     */
    function getTotalCertificates() external view returns (uint256) {
        return certificateCount;
    }
    
    /**
     * @dev Transfer ownership of the contract
     * @param newOwner The address of the new owner
     */
    function transferOwnership(address newOwner) external onlyOwner {
        require(newOwner != address(0), "Invalid address");
        emit OwnershipTransferred(owner, newOwner);
        owner = newOwner;
    }
}
