// SPDX-License-Identifier: MIT
pragma solidity ^0.8.19;

/**
 * @title CertificateRegistry
 * @dev Smart contract for storing and verifying certificate data on Polygon blockchain
 * @notice This contract stores full certificate information for SertiKu
 */
contract CertificateRegistry {
    // Struct to store certificate information
    struct Certificate {
        string certificateNumber;  // e.g., "SERT-ABC123"
        string recipientName;      // Nama penerima
        string courseName;         // Nama kursus/pelatihan
        string issueDate;          // Tanggal terbit (YYYY-MM-DD)
        string issuerName;         // Nama lembaga penerbit
        bytes32 dataHash;          // SHA-256 hash of all data
        address issuerAddress;     // Wallet address of issuer
        uint256 timestamp;         // When stored on blockchain
        bool exists;
        bool revoked;
    }

    // Mapping from certificate hash to Certificate struct
    mapping(bytes32 => Certificate) public certificates;

    // Mapping from certificate number to hash (for lookup by number)
    mapping(string => bytes32) public certificateNumberToHash;

    // Array to keep track of all certificate hashes
    bytes32[] public certificateHashes;

    // Owner of the contract
    address public owner;

    // Events
    event CertificateStored(
        bytes32 indexed dataHash,
        string certificateNumber,
        string recipientName,
        string courseName,
        address indexed issuer,
        uint256 timestamp
    );

    event CertificateRevoked(
        bytes32 indexed dataHash,
        string certificateNumber,
        address indexed revokedBy,
        uint256 timestamp
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
     * @dev Store a certificate with full information
     */
    function storeCertificate(
        bytes32 _dataHash,
        string calldata _certificateNumber,
        string calldata _recipientName,
        string calldata _courseName,
        string calldata _issueDate,
        string calldata _issuerName
    ) external returns (bool) {
        require(_dataHash != bytes32(0), "Invalid data hash");
        require(!certificates[_dataHash].exists, "Certificate already exists");
        require(bytes(_certificateNumber).length > 0, "Certificate number required");

        certificates[_dataHash] = Certificate({
            certificateNumber: _certificateNumber,
            recipientName: _recipientName,
            courseName: _courseName,
            issueDate: _issueDate,
            issuerName: _issuerName,
            dataHash: _dataHash,
            issuerAddress: msg.sender,
            timestamp: block.timestamp,
            exists: true,
            revoked: false
        });

        certificateNumberToHash[_certificateNumber] = _dataHash;
        certificateHashes.push(_dataHash);

        emit CertificateStored(
            _dataHash,
            _certificateNumber,
            _recipientName,
            _courseName,
            msg.sender,
            block.timestamp
        );

        return true;
    }

    /**
     * @dev Verify certificate by hash - returns full info
     */
    function verifyCertificate(bytes32 _dataHash) 
        external 
        view 
        returns (
            bool exists,
            bool revoked,
            string memory certificateNumber,
            string memory recipientName,
            string memory courseName,
            string memory issueDate,
            string memory issuerName,
            address issuerAddress,
            uint256 timestamp
        ) 
    {
        Certificate memory cert = certificates[_dataHash];
        return (
            cert.exists,
            cert.revoked,
            cert.certificateNumber,
            cert.recipientName,
            cert.courseName,
            cert.issueDate,
            cert.issuerName,
            cert.issuerAddress,
            cert.timestamp
        );
    }

    /**
     * @dev Verify certificate by certificate number
     */
    function verifyCertificateByNumber(string calldata _certNumber)
        external
        view
        returns (
            bool exists,
            bool revoked,
            string memory recipientName,
            string memory courseName,
            string memory issueDate,
            bytes32 dataHash,
            uint256 timestamp
        )
    {
        bytes32 hash = certificateNumberToHash[_certNumber];
        Certificate memory cert = certificates[hash];
        return (
            cert.exists,
            cert.revoked,
            cert.recipientName,
            cert.courseName,
            cert.issueDate,
            cert.dataHash,
            cert.timestamp
        );
    }

    /**
     * @dev Check if a certificate exists
     */
    function certificateExists(bytes32 _dataHash) external view returns (bool) {
        return certificates[_dataHash].exists;
    }

    /**
     * @dev Get certificate info (simplified)
     */
    function getCertificateInfo(bytes32 _dataHash) 
        external 
        view 
        returns (
            string memory certificateNumber,
            string memory recipientName,
            string memory courseName,
            uint256 timestamp
        ) 
    {
        require(certificates[_dataHash].exists, "Certificate does not exist");
        Certificate memory cert = certificates[_dataHash];
        return (cert.certificateNumber, cert.recipientName, cert.courseName, cert.timestamp);
    }

    /**
     * @dev Revoke a certificate (only issuer can revoke)
     */
    function revokeCertificate(bytes32 _dataHash) external returns (bool) {
        require(certificates[_dataHash].exists, "Certificate does not exist");
        require(
            certificates[_dataHash].issuerAddress == msg.sender || msg.sender == owner,
            "Only issuer or owner can revoke"
        );
        require(!certificates[_dataHash].revoked, "Already revoked");

        certificates[_dataHash].revoked = true;

        emit CertificateRevoked(
            _dataHash,
            certificates[_dataHash].certificateNumber,
            msg.sender,
            block.timestamp
        );

        return true;
    }

    /**
     * @dev Get total number of certificates
     */
    function getTotalCertificates() external view returns (uint256) {
        return certificateHashes.length;
    }

    /**
     * @dev Transfer ownership
     */
    function transferOwnership(address newOwner) external onlyOwner {
        require(newOwner != address(0), "Invalid address");
        owner = newOwner;
    }
}
