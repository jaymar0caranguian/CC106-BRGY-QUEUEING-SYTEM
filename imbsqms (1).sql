-- phpMyAdmin SQL Dump
-- version 5.2.1deb1ubuntu1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 19, 2024 at 04:47 PM
-- Server version: 8.0.36-0ubuntu0.23.10.1
-- PHP Version: 8.2.10-2ubuntu1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `imbsqms`
--

-- --------------------------------------------------------

--
-- Table structure for table `DocumentRequest`
--

CREATE TABLE `DocumentRequest` (
  `ReferenceID` int NOT NULL,
  `ResidentID` int DEFAULT NULL,
  `DocumentType` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Purpose` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Quantity` int DEFAULT NULL,
  `RequestDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ApproveDate` timestamp NULL DEFAULT NULL,
  `ReleaseDate` timestamp NULL DEFAULT NULL,
  `StaffID` int DEFAULT NULL,
  `Status` varchar(50) COLLATE utf8mb4_general_ci DEFAULT 'Pending',
  `Reason` varchar(500) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `DocumentRequest`
--

INSERT INTO `DocumentRequest` (`ReferenceID`, `ResidentID`, `DocumentType`, `Purpose`, `Quantity`, `RequestDate`, `ApproveDate`, `ReleaseDate`, `StaffID`, `Status`, `Reason`) VALUES
(116, 8, 'Barangay ID', 'school req', 1, '2024-05-14 09:47:14', '2024-05-14 09:51:22', '2024-05-14 09:54:22', NULL, 'Claimed', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `Staff`
--

CREATE TABLE `Staff` (
  `StaffID` int NOT NULL,
  `FullName` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Username` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Password` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Role` enum('Staff','Admin') COLLATE utf8mb4_general_ci DEFAULT 'Staff',
  `Status` enum('Activate','Deactivate') COLLATE utf8mb4_general_ci DEFAULT 'Activate'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Staff`
--

INSERT INTO `Staff` (`StaffID`, `FullName`, `Username`, `Password`, `Role`, `Status`) VALUES
(1, 'Admin', 'admin', '$2y$10$3SkcHP96q.DbMPYqgCmQp.x555hU8IoRbsy518mL103TNU3ed9x.u', 'Admin', 'Activate'),
(8, 'Jaymar Caranguian', 'jaymarcaranguian', '$2y$10$BxgZU8.U6.SMVDb8IncNGOuPmGp0gZKF2IhzoR9TMN7e79y5VqFvm', 'Staff', 'Activate'),
(9, 'Ivy Bayonon', 'ivybayonon', '$2y$10$fRRecUZ88LgCgydg7atFReu8px1qv.ejliMabXgvT9ZluZsSrlBRy', 'Staff', 'Deactivate');

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE `Users` (
  `ResidentID` int NOT NULL,
  `Username` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Password` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ResetToken` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ResetTokenExpiry` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `FirstName` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `MiddleName` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `LastName` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Suffix` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `HouseNo` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Barangay` enum('Nagkaisang Nayon') COLLATE utf8mb4_general_ci DEFAULT 'Nagkaisang Nayon',
  `City` enum('Quezon City') COLLATE utf8mb4_general_ci DEFAULT 'Quezon City',
  `Gender` enum('Male','Female','Other') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Citizenship` enum('Filipino','Other') COLLATE utf8mb4_general_ci DEFAULT 'Filipino',
  `Occupation` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Birthdate` date DEFAULT NULL,
  `Birthplace` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `PhoneNumber` varchar(11) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `CivilStatus` enum('Single','Married','Divorced','Widowed','Separated') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `EducationalAttainment` enum('None','Elementary','High School','Vocational','College','Post Graduate') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Course` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `GuardianFirstName` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `GuardianMiddleName` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `GuardianLastName` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `GuardianSuffix` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `GuardianRelationship` enum('Father','Mother','Son','Daughter','Grandfather','Grandmother','Uncle','Aunt','Cousin','Nephew','Niece','Brother','Sister','Spouse','Legal Guardian','Foster Parent','Stepfather','Stepmother','Other') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `GuardianContactNumber` varchar(11) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`ResidentID`, `Username`, `Email`, `Password`, `ResetToken`, `ResetTokenExpiry`, `FirstName`, `MiddleName`, `LastName`, `Suffix`, `HouseNo`, `Barangay`, `City`, `Gender`, `Citizenship`, `Occupation`, `Birthdate`, `Birthplace`, `PhoneNumber`, `CivilStatus`, `EducationalAttainment`, `Course`, `GuardianFirstName`, `GuardianMiddleName`, `GuardianLastName`, `GuardianSuffix`, `GuardianRelationship`, `GuardianContactNumber`) VALUES
(7, 'sad', 'sad@gmail.com', '$2y$10$iXoUXhXZnv1ZEjD2MJfq8e3VZUouQYcMS/2yFkwFwwNF3ZI7D7JkS', NULL, '2024-05-05 04:31:02', NULL, NULL, NULL, NULL, NULL, 'Nagkaisang Nayon', 'Quezon City', NULL, 'Filipino', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(8, 'pixel1', 'pixelpixel0011@gmail.com', '$2y$10$MOt1iXjqenCegnQCxjj8geY645Ey2U7Q1EIZjgS5tmm68qtwyHoEa', NULL, '2024-05-14 10:00:00', 'Marj', 'Cho', 'Magdalena', '', 'qc', 'Nagkaisang Nayon', 'Quezon City', 'Male', 'Filipino', 'Pilot', '2001-10-19', 'qc', '09999999939', 'Married', 'College', 'computer science', 'robert', '', 'rodriguez', '', 'Father', '09999999123'),
(9, 'Maceh', 'maricel.g.barrameda@gmail.com', '$2y$10$BIYxcGB9K4oGoHGJiBuX0O3FBKwg2EnWxDvmuh/ZTfV7oHKNjbQ8u', NULL, '2024-05-14 23:27:11', NULL, NULL, NULL, NULL, NULL, 'Nagkaisang Nayon', 'Quezon City', NULL, 'Filipino', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `DocumentRequest`
--
ALTER TABLE `DocumentRequest`
  ADD PRIMARY KEY (`ReferenceID`),
  ADD KEY `StaffID_fk` (`StaffID`),
  ADD KEY `FK_ResidentID_UserID` (`ResidentID`);

--
-- Indexes for table `Staff`
--
ALTER TABLE `Staff`
  ADD PRIMARY KEY (`StaffID`);

--
-- Indexes for table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`ResidentID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `DocumentRequest`
--
ALTER TABLE `DocumentRequest`
  MODIFY `ReferenceID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;

--
-- AUTO_INCREMENT for table `Staff`
--
ALTER TABLE `Staff`
  MODIFY `StaffID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `Users`
--
ALTER TABLE `Users`
  MODIFY `ResidentID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `DocumentRequest`
--
ALTER TABLE `DocumentRequest`
  ADD CONSTRAINT `DocumentRequest_ibfk_2` FOREIGN KEY (`StaffID`) REFERENCES `Staff` (`StaffID`),
  ADD CONSTRAINT `FK_ResidentID_UserID` FOREIGN KEY (`ResidentID`) REFERENCES `Users` (`ResidentID`) ON DELETE CASCADE,
  ADD CONSTRAINT `StaffID_fk` FOREIGN KEY (`StaffID`) REFERENCES `Staff` (`StaffID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
