// SPDX-License-Identifier: MIT
pragma solidity ^0.8.19;

/**
 * @title CertificateRegistry
 * @dev ULTRA MINIMAL - Only stores hash for maximum gas efficiency
 * @notice SertiKu Certificate Verification - Optimized Version
 */
contract CertificateRegistry {
    struct Certificate {
        address issuer;
        uint256 timestamp;
        bool exists;
        bool revoked;
    }

    mapping(bytes32 => Certificate) public certificates;
    uint256 public totalCertificates;
    address public owner;

    event CertificateStored(bytes32 indexed dataHash, address indexed issuer, uint256 timestamp);
    event CertificateRevoked(bytes32 indexed dataHash, address indexed revokedBy);

    modifier onlyOwner() {
        require(msg.sender == owner, "Not owner");
        _;
    }

    constructor() {
        owner = msg.sender;
    }

    /**
     * @dev Store certificate - ONLY HASH, no other parameters
     */
    function storeCertificate(bytes32 _dataHash) external returns (bool) {
        require(_dataHash != bytes32(0), "Invalid hash");
        require(!certificates[_dataHash].exists, "Already exists");

        certificates[_dataHash] = Certificate({
            issuer: msg.sender,
            timestamp: block.timestamp,
            exists: true,
            revoked: false
        });

        totalCertificates++;
        emit CertificateStored(_dataHash, msg.sender, block.timestamp);
        return true;
    }

    /**
     * @dev Verify certificate
     */
    function verifyCertificate(bytes32 _dataHash) external view returns (
        bool exists,
        bool revoked,
        address issuer,
        uint256 timestamp
    ) {
        Certificate memory cert = certificates[_dataHash];
        return (cert.exists, cert.revoked, cert.issuer, cert.timestamp);
    }

    function certificateExists(bytes32 _dataHash) external view returns (bool) {
        return certificates[_dataHash].exists;
    }

    function getTotalCertificates() external view returns (uint256) {
        return totalCertificates;
    }

    function revokeCertificate(bytes32 _dataHash) external returns (bool) {
        require(certificates[_dataHash].exists, "Not found");
        require(certificates[_dataHash].issuer == msg.sender || msg.sender == owner, "Not authorized");
        require(!certificates[_dataHash].revoked, "Already revoked");
        certificates[_dataHash].revoked = true;
        emit CertificateRevoked(_dataHash, msg.sender);
        return true;
    }

    function transferOwnership(address newOwner) external onlyOwner {
        require(newOwner != address(0), "Invalid");
        owner = newOwner;
    }
}
