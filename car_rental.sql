-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 21, 2025 at 03:54 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `car_rental`
--

-- --------------------------------------------------------

--
-- Table structure for table `vehicle`
--

CREATE TABLE `vehicle` (
  `VehicleID` varchar(50) NOT NULL,
  `PlateNumber` varchar(20) NOT NULL,
  `Make` varchar(50) NOT NULL,
  `Model` varchar(50) NOT NULL,
  `Year` int NOT NULL,
  `IsHastaOwned` tinyint(1) DEFAULT '1',
  `CurrentFuelBars` int DEFAULT NULL,
  `Status` varchar(20) DEFAULT 'Available',
  `TypeID` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `vehicle`
--

INSERT INTO `vehicle` (`VehicleID`, `PlateNumber`, `Make`, `Model`, `Year`, `IsHastaOwned`, `CurrentFuelBars`, `Status`, `TypeID`) VALUES
('V001', 'MCP 6113', 'PERODUA', 'AXIA', 2014, 1, 8, 'Available', 1),
('V002', 'JQU 1957', 'PERODUA', 'AXIA', 2015, 1, 9, 'Available', 1),
('V003', 'NDD 7803', 'PERODUA', 'AXIA', 2016, 1, 7, 'Available', 1),
('V004', 'CEX 5224', 'PERODUA', 'AXIA', 2024, 1, 9, 'Available', 1),
('V005', 'UTM 3365', 'PERODUA', 'AXIA', 2024, 1, 8, 'Available', 1),
('V006', 'JPN 1416', 'PERODUA', 'MYVI', 2013, 1, 7, 'Available', 3),
('V007', 'VC 6522', 'PERODUA', 'MYVI', 2016, 1, 6, 'Available', 3),
('V008', 'UTM 3655', 'PERODUA', 'BEZZA', 2023, 1, 9, 'Available', 2),
('V009', 'UTM 3057', 'PERODUA', 'BEZZA', 2023, 1, 7, 'Available', 2),
('V010', 'QRP 5205', 'HONDA', 'DASH 125', 2021, 1, 5, 'Available', 5),
('V011', 'JWD 9496', 'HONDA', 'BEAT 110', 2023, 1, 6, 'Available', 5);

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_type`
--

CREATE TABLE `vehicle_type` (
  `TypeID` int NOT NULL,
  `TypeName` varchar(30) NOT NULL,
  `HourlyRate` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `vehicle_type`
--

INSERT INTO `vehicle_type` (`TypeID`, `TypeName`, `HourlyRate`) VALUES
(1, 'AXIA', 35.00),
(2, 'BEZZA', 40.00),
(3, 'MYVI', 40.00),
(4, 'SAGA', 40.00),
(5, 'MOTORCYCLE', 15.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `vehicle`
--
ALTER TABLE `vehicle`
  ADD PRIMARY KEY (`VehicleID`),
  ADD UNIQUE KEY `PlateNumber` (`PlateNumber`),
  ADD KEY `TypeID` (`TypeID`);

--
-- Indexes for table `vehicle_type`
--
ALTER TABLE `vehicle_type`
  ADD PRIMARY KEY (`TypeID`),
  ADD UNIQUE KEY `TypeName` (`TypeName`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `vehicle_type`
--
ALTER TABLE `vehicle_type`
  MODIFY `TypeID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `vehicle`
--
ALTER TABLE `vehicle`
  ADD CONSTRAINT `vehicle_ibfk_1` FOREIGN KEY (`TypeID`) REFERENCES `vehicle_type` (`TypeID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
