-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2024-06-15 11:37:32
-- 伺服器版本： 10.4.32-MariaDB
-- PHP 版本： 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `sams`
--

-- --------------------------------------------------------

--
-- 資料表結構 `accountmanagement`
--

CREATE TABLE `accountmanagement` (
  `AccountID` varchar(10) NOT NULL,
  `UserID` varchar(10) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(50) NOT NULL,
  `PermissionLevel` enum('管理員','導師','學生','房東') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `accountmanagement`
--

INSERT INTO `accountmanagement` (`AccountID`, `UserID`, `Username`, `Password`, `PermissionLevel`) VALUES
('asd10933', 'Admin001', 'JIN_Chen', 'asd550144', '管理員'),
('L001', 'L001', 'L001', '001100', '房東'),
('S001', 'S001', 'Student_JIN', '000', '學生'),
('S002', 'S002', 'Student_JIN2', '000', '學生');

-- --------------------------------------------------------

--
-- 資料表結構 `comments`
--

CREATE TABLE `comments` (
  `CommentID` varchar(10) NOT NULL,
  `PostID` varchar(10) NOT NULL,
  `CommenterID` varchar(10) NOT NULL,
  `CommentContent` text NOT NULL,
  `CommentDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `forum`
--

CREATE TABLE `forum` (
  `PostID` varchar(10) NOT NULL,
  `PosterID` varchar(10) NOT NULL,
  `PostType` enum('老師','學生') NOT NULL,
  `Title` varchar(100) NOT NULL,
  `Content` text NOT NULL,
  `PostDate` date NOT NULL,
  `CommentCount` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `landlords`
--

CREATE TABLE `landlords` (
  `LandlordID` varchar(10) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `ContactPhone` varchar(15) NOT NULL,
  `L_Email` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `landlords`
--

INSERT INTO `landlords` (`LandlordID`, `Name`, `ContactPhone`, `L_Email`) VALUES
('L001', '張房東', '0957138509', NULL);

-- --------------------------------------------------------

--
-- 資料表結構 `rentalimages`
--

CREATE TABLE `rentalimages` (
  `ImageID` int(11) NOT NULL,
  `InformationID` varchar(255) NOT NULL,
  `Image` mediumblob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `rentalinformation`
--

CREATE TABLE `rentalinformation` (
  `InformationID` varchar(255) NOT NULL,
  `RentTitle` varchar(255) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Age` int(11) DEFAULT NULL,
  `Type` varchar(50) DEFAULT NULL,
  `Address` varchar(100) NOT NULL,
  `Rent` decimal(10,2) NOT NULL,
  `WaterFee` decimal(10,2) DEFAULT NULL,
  `ElectricityFee` decimal(10,2) DEFAULT NULL,
  `ContactPerson` varchar(50) NOT NULL,
  `Photo` varchar(200) DEFAULT NULL,
  `StartDate` date NOT NULL,
  `EndDate` date DEFAULT NULL,
  `Internet` tinyint(1) DEFAULT NULL,
  `Bed` tinyint(1) DEFAULT NULL,
  `AirConditioner` tinyint(1) DEFAULT NULL,
  `Refrigerator` tinyint(1) DEFAULT NULL,
  `PetAllowed` tinyint(1) DEFAULT NULL,
  `WashingMachine` tinyint(1) DEFAULT NULL,
  `TV` tinyint(1) DEFAULT NULL,
  `CableTV` tinyint(1) DEFAULT NULL,
  `WaterHeater` tinyint(1) DEFAULT NULL,
  `Gas` tinyint(1) DEFAULT NULL,
  `Wardrobe` tinyint(1) DEFAULT NULL,
  `Desk` tinyint(1) DEFAULT NULL,
  `Elevator` tinyint(1) DEFAULT NULL,
  `CarParkingSpace` tinyint(1) DEFAULT NULL,
  `MotorbikeParkingSpace` tinyint(1) DEFAULT NULL,
  `Detail` text DEFAULT NULL,
  `Review` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `rentalinformation`
--

INSERT INTO `rentalinformation` (`InformationID`, `RentTitle`, `Username`, `Age`, `Type`, `Address`, `Rent`, `WaterFee`, `ElectricityFee`, `ContactPerson`, `Photo`, `StartDate`, `EndDate`, `Internet`, `Bed`, `AirConditioner`, `Refrigerator`, `PetAllowed`, `WashingMachine`, `TV`, `CableTV`, `WaterHeater`, `Gas`, `Wardrobe`, `Desk`, `Elevator`, `CarParkingSpace`, `MotorbikeParkingSpace`, `Detail`, `Review`) VALUES
('R001', '溫馨小屋', 'l001', 20, '套房', '???', 5000.00, 300.00, 800.00, '0900123456', NULL, '2024-05-01', '2024-05-24', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, '無', 'invalid'),
('R002', '時尚公寓', 'l002', 11, '公寓', '???', 6000.00, 400.00, 700.00, '0900000000', NULL, '2024-05-01', '2024-05-24', 1, 1, 1, 1, 0, 1, 1, 1, 1, 1, 0, 1, 1, 1, 1, '', ''),
('R003', '學生宿舍', 'l001', 37, '套房', '???', 3000.00, 200.00, 300.00, '0900123456', NULL, '2024-05-08', '2024-05-24', 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 0, 0, '無', ''),
('R004', '晴美公寓', 'l002', 34, '公寓', '???', 8000.00, 400.00, 1000.00, '0900000000', NULL, '2024-05-08', '2024-05-17', 1, 1, 1, 1, 0, 1, 1, 1, 1, 0, 0, 1, 1, 0, 1, '', ''),
('R005', '幸福小窩', 'l001', 32, '套房', '???', 6500.00, 100.00, 800.00, '0900123456', NULL, '2024-05-15', '2024-05-17', 1, 1, 1, 0, 0, 0, 1, 0, 1, 0, 0, 0, 1, 0, 0, '', ''),
('R006', '樂活居所', 'l001', 15, '套房', '???', 9900.00, 100.00, 600.00, '0900123456', NULL, '2024-05-07', '2024-05-15', 1, 1, 1, 0, 1, 0, 1, 1, 1, 0, 0, 1, 1, 0, 1, '', ''),
('R007', '時尚套房', 'l001', 40, '套房', '???', 5000.00, 100.00, 600.00, '0900123456', NULL, '2024-05-02', '2024-05-10', 1, 1, 1, 1, 0, 0, 1, 1, 1, 0, 0, 1, 1, 0, 1, '', ''),
('R008', '舒適雅房', 'l001', 11, '雅房', '???', 2000.00, 100.00, 600.00, '0900123456', NULL, '2024-05-01', '2024-05-02', 0, 1, 1, 0, 0, 0, 1, 1, 1, 0, 0, 1, 1, 0, 1, '', ''),
('R009', '學區雅房', 'l001', 12, '雅房', '???', 1000.00, 100.00, 400.00, '0900123456', NULL, '2024-05-09', '2024-05-30', 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '');

-- --------------------------------------------------------

--
-- 資料表結構 `roommates`
--

CREATE TABLE `roommates` (
  `StudentID` varchar(10) NOT NULL,
  `RoommateID` varchar(10) DEFAULT NULL,
  `AccommodationID` varchar(10) NOT NULL,
  `RoommateName` varchar(50) DEFAULT NULL,
  `Contact` varchar(15) DEFAULT NULL,
  `SameSchool` enum('是','否') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `studentaccommodation`
--

CREATE TABLE `studentaccommodation` (
  `AccommodationID` varchar(10) NOT NULL,
  `StudentID` varchar(10) NOT NULL,
  `department` varchar(50) DEFAULT NULL,
  `Name` varchar(50) NOT NULL,
  `Phone` varchar(15) NOT NULL,
  `Address` varchar(100) DEFAULT NULL,
  `AcademicYear` int(11) DEFAULT NULL,
  `Semester` int(11) DEFAULT NULL,
  `LandlordName` varchar(50) DEFAULT NULL,
  `LandlordPhoneNumber` varchar(15) DEFAULT NULL,
  `MonthlyRent` int(10) NOT NULL,
  `Deposit` int(11) DEFAULT NULL,
  `HousingType` enum('套房','雅房') NOT NULL,
  `RentalType` enum('獨棟透天','公寓(五樓以下)','大樓(六樓以上)','大型學舍') NOT NULL,
  `RecommendOthers` enum('是','否') NOT NULL,
  `WoodenPartitionsOrIronSheet` enum('是','否') NOT NULL,
  `HighPowerDevicesOnSingleExtension` enum('是','否') NOT NULL,
  `FireAlarmOrSmokeDetector` enum('是','否') NOT NULL,
  `FunctionalFireExtinguisher` enum('是','否') NOT NULL,
  `SafeWaterHeater` enum('是','否') NOT NULL,
  `ClearEscapeRoute` enum('是','否') NOT NULL,
  `GoodSecurity` enum('是','否') NOT NULL,
  `MoreThan6RoomsOr10Beds` enum('是','否') NOT NULL,
  `InstalledLighting` enum('是','否') NOT NULL,
  `InstalledCCTV` enum('是','否') NOT NULL,
  `FamiliarWithSafetyProcedures` enum('是','否') NOT NULL,
  `StandardLeaseContract` enum('是','否') NOT NULL,
  `FamiliarWithEmergencyContacts` enum('是','否') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `studentaccommodation`
--

INSERT INTO `studentaccommodation` (`AccommodationID`, `StudentID`, `department`, `Name`, `Phone`, `Address`, `AcademicYear`, `Semester`, `LandlordName`, `LandlordPhoneNumber`, `MonthlyRent`, `Deposit`, `HousingType`, `RentalType`, `RecommendOthers`, `WoodenPartitionsOrIronSheet`, `HighPowerDevicesOnSingleExtension`, `FireAlarmOrSmokeDetector`, `FunctionalFireExtinguisher`, `SafeWaterHeater`, `ClearEscapeRoute`, `GoodSecurity`, `MoreThan6RoomsOr10Beds`, `InstalledLighting`, `InstalledCCTV`, `FamiliarWithSafetyProcedures`, `StandardLeaseContract`, `FamiliarWithEmergencyContacts`) VALUES
('s0011032', 'S001', '資工系', '陳建穎', '0965294688', '楠梓區', 103, 2, '張房東', '0999', 5000, 4900, '套房', '公寓(五樓以下)', '是', '是', '是', '是', '是', '是', '否', '是', '是', '是', '是', '是', '是', '是'),
('S0011102', 'S001', '資訊工程學系114級', '陳建穎', '0912345679', '新北市', 110, 2, '張房東', '096666666', 5000, 5000, '套房', '獨棟透天', '是', '是', '是', '是', '是', '是', '是', '是', '是', '是', '是', '是', '是', '否');

-- --------------------------------------------------------

--
-- 資料表結構 `students`
--

CREATE TABLE `students` (
  `StudentID` varchar(10) NOT NULL,
  `Department` varchar(50) DEFAULT NULL,
  `Name` varchar(50) NOT NULL,
  `Grade` int(11) DEFAULT NULL,
  `Gender` varchar(10) DEFAULT NULL,
  `AdvisorID` varchar(10) DEFAULT NULL,
  `ContactPhone` varchar(15) DEFAULT NULL,
  `S_Email` varchar(50) DEFAULT NULL,
  `HomeAddress` varchar(100) DEFAULT NULL,
  `HomePhone` varchar(15) DEFAULT NULL,
  `EmergencyContactName` varchar(50) DEFAULT NULL,
  `EmergencyContactPhone` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `students`
--

INSERT INTO `students` (`StudentID`, `Department`, `Name`, `Grade`, `Gender`, `AdvisorID`, `ContactPhone`, `S_Email`, `HomeAddress`, `HomePhone`, `EmergencyContactName`, `EmergencyContactPhone`) VALUES
('S001', '資訊工程學系114級', '陳建穎', 4, '男', NULL, '0912345679', 'a1105508@mail.nuk.edu.tw', '新北市', '02-88888829', '父親', '0905777889'),
('S002', '資訊工程學系114級', '陳建穎2', 3, '男', NULL, '0912345680', 'a1105508_test@mail.nuk.edu.tw', '高雄市', '02-77777729', '母親', '0911000223');

-- --------------------------------------------------------

--
-- 資料表結構 `teachers`
--

CREATE TABLE `teachers` (
  `TeacherID` varchar(10) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Rank` varchar(20) DEFAULT NULL,
  `ContactPhone` varchar(15) DEFAULT NULL,
  `T_Email` varchar(50) DEFAULT NULL,
  `OfficeAddress` varchar(100) DEFAULT NULL,
  `OfficePhone` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `users`
--

CREATE TABLE `users` (
  `UserID` varchar(10) NOT NULL,
  `UserType` enum('學生','導師','房東','管理員') NOT NULL,
  `first_login` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `users`
--

INSERT INTO `users` (`UserID`, `UserType`, `first_login`) VALUES
('Admin001', '管理員', 0),
('L001', '房東', 0),
('S001', '學生', 0),
('S002', '學生', 0);

-- --------------------------------------------------------

--
-- 資料表結構 `visitrecord`
--

CREATE TABLE `visitrecord` (
  `VisitID` varchar(10) NOT NULL,
  `AccommodationID` varchar(10) NOT NULL,
  `TeacherID` varchar(10) NOT NULL,
  `VisitDate` date NOT NULL,
  `StudentID` varchar(255) DEFAULT NULL,
  `DepositRequirement` varchar(255) DEFAULT NULL,
  `UtilityRequirement` varchar(255) DEFAULT NULL,
  `LivingEnvironment` varchar(255) DEFAULT NULL,
  `LivingFacilities` varchar(255) DEFAULT NULL,
  `VisitStatus` varchar(255) DEFAULT NULL,
  `HostGuestInteraction` varchar(255) DEFAULT NULL,
  `GoodCondition` varchar(255) DEFAULT NULL,
  `ContactParents` varchar(255) DEFAULT NULL,
  `AssistanceNeeded` varchar(255) DEFAULT NULL,
  `AdditionalNotes` varchar(255) DEFAULT NULL,
  `TrafficSafety` varchar(255) DEFAULT NULL,
  `NoSmoking` varchar(255) DEFAULT NULL,
  `NoDrugs` varchar(255) DEFAULT NULL,
  `DenguePrevention` varchar(255) DEFAULT NULL,
  `Other` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `accountmanagement`
--
ALTER TABLE `accountmanagement`
  ADD PRIMARY KEY (`AccountID`),
  ADD KEY `FK_AccountManagement_UserID` (`UserID`);

--
-- 資料表索引 `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`CommentID`),
  ADD KEY `FK_Comments_PostID` (`PostID`),
  ADD KEY `FK_Comments_CommenterID` (`CommenterID`);

--
-- 資料表索引 `forum`
--
ALTER TABLE `forum`
  ADD PRIMARY KEY (`PostID`),
  ADD KEY `FK_Forum_PosterID` (`PosterID`);

--
-- 資料表索引 `landlords`
--
ALTER TABLE `landlords`
  ADD PRIMARY KEY (`LandlordID`);

--
-- 資料表索引 `rentalinformation`
--
ALTER TABLE `rentalinformation`
  ADD PRIMARY KEY (`InformationID`);

--
-- 資料表索引 `roommates`
--
ALTER TABLE `roommates`
  ADD PRIMARY KEY (`StudentID`),
  ADD KEY `FK_Roommates_AccommodationID` (`AccommodationID`);

--
-- 資料表索引 `studentaccommodation`
--
ALTER TABLE `studentaccommodation`
  ADD PRIMARY KEY (`AccommodationID`),
  ADD KEY `FK_StudentAccommodation_StudentID` (`StudentID`);

--
-- 資料表索引 `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`StudentID`),
  ADD KEY `FK_Students_AdvisorID` (`AdvisorID`);

--
-- 資料表索引 `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`TeacherID`);

--
-- 資料表索引 `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`);

--
-- 資料表索引 `visitrecord`
--
ALTER TABLE `visitrecord`
  ADD PRIMARY KEY (`VisitID`),
  ADD KEY `FK_TeacherVisitRecord_AccommodationID` (`AccommodationID`),
  ADD KEY `FK_TeacherVisitRecord_TeacherID` (`TeacherID`),
  ADD KEY `fk_student` (`StudentID`);

--
-- 已傾印資料表的限制式
--

--
-- 資料表的限制式 `accountmanagement`
--
ALTER TABLE `accountmanagement`
  ADD CONSTRAINT `FK_AccountManagement_UserID` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`);

--
-- 資料表的限制式 `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `FK_Comments_CommenterID` FOREIGN KEY (`CommenterID`) REFERENCES `users` (`UserID`),
  ADD CONSTRAINT `FK_Comments_PostID` FOREIGN KEY (`PostID`) REFERENCES `forum` (`PostID`);

--
-- 資料表的限制式 `forum`
--
ALTER TABLE `forum`
  ADD CONSTRAINT `FK_Forum_PosterID` FOREIGN KEY (`PosterID`) REFERENCES `users` (`UserID`);

--
-- 資料表的限制式 `roommates`
--
ALTER TABLE `roommates`
  ADD CONSTRAINT `FK_Roommates_AccommodationID` FOREIGN KEY (`AccommodationID`) REFERENCES `studentaccommodation` (`AccommodationID`),
  ADD CONSTRAINT `FK_Roommates_StudentID` FOREIGN KEY (`StudentID`) REFERENCES `students` (`StudentID`);

--
-- 資料表的限制式 `studentaccommodation`
--
ALTER TABLE `studentaccommodation`
  ADD CONSTRAINT `FK_StudentAccommodation_StudentID` FOREIGN KEY (`StudentID`) REFERENCES `students` (`StudentID`);

--
-- 資料表的限制式 `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `FK_Students_AdvisorID` FOREIGN KEY (`AdvisorID`) REFERENCES `teachers` (`TeacherID`);

--
-- 資料表的限制式 `visitrecord`
--
ALTER TABLE `visitrecord`
  ADD CONSTRAINT `FK_TeacherVisitRecord_AccommodationID` FOREIGN KEY (`AccommodationID`) REFERENCES `studentaccommodation` (`AccommodationID`),
  ADD CONSTRAINT `FK_TeacherVisitRecord_TeacherID` FOREIGN KEY (`TeacherID`) REFERENCES `teachers` (`TeacherID`),
  ADD CONSTRAINT `fk_student` FOREIGN KEY (`StudentID`) REFERENCES `students` (`StudentID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
