-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- 主機: localhost
-- 產生時間： 2025-05-21 13:18:52
-- 伺服器版本: 5.7.17-log
-- PHP 版本： 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 資料表的匯出資料 `accountmanagement`
--

INSERT INTO `accountmanagement` (`AccountID`, `UserID`, `Username`, `Password`, `PermissionLevel`) VALUES
('L001', 'L001', 'L001', '001100', '房東'),
('L002', 'L002', 'L002', '002', '房東'),
('M001', 'M001', 'M001', 'pass', '管理員'),
('M002', 'M002', 'M002', 'pass', '管理員'),
('S001', 'S001', 'S001', '000', '學生'),
('S002', 'S002', 'S002', 'pass', '學生'),
('T001', 'T001', 'T001', 'pass', '導師'),
('T002', 'T002', 'T002', 'pass', '導師'),
('T003', 'T003', 'T003', 'pass', '導師');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 資料表的匯出資料 `comments`
--

INSERT INTO `comments` (`CommentID`, `PostID`, `CommenterID`, `CommentContent`, `CommentDate`) VALUES
('C001', 'P001', 'S001', '真的不錯', '2024-06-16'),
('C002', 'P001', 'T001', 'ok', '2024-06-16'),
('C003', 'P002', 'T001', '123333', '2024-06-16');

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
  `CommentCount` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 資料表的匯出資料 `forum`
--

INSERT INTO `forum` (`PostID`, `PosterID`, `PostType`, `Title`, `Content`, `PostDate`, `CommentCount`) VALUES
('P001', 'S001', '學生', '你好 ', '這間套房不錯', '2024-06-16', 2),
('P002', 'T001', '老師', '好嗎', '111', '2024-06-16', 1),
('P003', 'T002', '老師', 'wefdds', 'ef', '2024-06-17', 0);

-- --------------------------------------------------------

--
-- 資料表結構 `landlords`
--

CREATE TABLE `landlords` (
  `LandlordID` varchar(10) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `ContactPhone` varchar(15) NOT NULL,
  `L_Email` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 資料表的匯出資料 `landlords`
--

INSERT INTO `landlords` (`LandlordID`, `Name`, `ContactPhone`, `L_Email`) VALUES
('L001', '張文華', '0957138509', 'l001@gmail.com'),
('L002', '劉英晴', '0978542731', 'l002@gmail.com');

-- --------------------------------------------------------

--
-- 資料表結構 `rentalimages`
--

CREATE TABLE `rentalimages` (
  `ImageID` int(11) NOT NULL,
  `InformationID` varchar(255) NOT NULL,
  `Image` mediumblob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
  `Detail` text,
  `Review` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 資料表的匯出資料 `rentalinformation`
--

INSERT INTO `rentalinformation` (`InformationID`, `RentTitle`, `Username`, `Age`, `Type`, `Address`, `Rent`, `WaterFee`, `ElectricityFee`, `ContactPerson`, `Photo`, `StartDate`, `EndDate`, `Internet`, `Bed`, `AirConditioner`, `Refrigerator`, `PetAllowed`, `WashingMachine`, `TV`, `CableTV`, `WaterHeater`, `Gas`, `Wardrobe`, `Desk`, `Elevator`, `CarParkingSpace`, `MotorbikeParkingSpace`, `Detail`, `Review`) VALUES
('R001', '溫馨小屋', 'l001', 20, '套房', '???', '5000.00', '300.00', '800.00', '0900123456', NULL, '2024-05-01', '2024-05-24', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, '無', 'valid'),
('R002', '時尚公寓', 'l002', 11, '公寓', '???', '6000.00', '400.00', '700.00', '0900000000', NULL, '2024-05-01', '2024-05-24', 1, 1, 1, 1, 0, 1, 1, 1, 1, 1, 0, 1, 1, 1, 1, '', 'valid'),
('R003', '學生宿舍', 'l001', 37, '套房', '???', '3000.00', '200.00', '300.00', '0900123456', NULL, '2024-05-08', '2024-05-24', 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 0, 0, '無', 'valid'),
('R004', '晴美公寓', 'l002', 34, '公寓', '???', '8000.00', '400.00', '1000.00', '0900000000', NULL, '2024-05-08', '2024-05-17', 1, 1, 1, 1, 0, 1, 1, 1, 1, 0, 0, 1, 1, 0, 1, '', 'invalid'),
('R005', '幸福小窩', 'l001', 32, '套房', '???', '6500.00', '100.00', '800.00', '0900123456', NULL, '2024-05-15', '2024-05-17', 1, 1, 1, 0, 0, 0, 1, 0, 1, 0, 0, 1, 1, 0, 0, '', 'valid'),
('R006', '樂活居所', 'l001', 15, '套房', '???', '9900.00', '100.00', '600.00', '0900123456', NULL, '2024-05-07', '2024-05-15', 1, 1, 1, 0, 1, 0, 1, 1, 1, 0, 0, 1, 1, 0, 1, '', 'invalid'),
('R007', '時尚套房', 'l001', 40, '套房', '???', '5000.00', '100.00', '600.00', '0900123456', NULL, '2024-05-02', '2024-05-10', 1, 1, 1, 1, 0, 0, 1, 1, 1, 0, 0, 1, 1, 0, 1, '', 'valid'),
('R008', '舒適雅房', 'l001', 11, '雅房', '???', '2000.00', '100.00', '600.00', '0900123456', NULL, '2024-05-01', '2024-05-02', 0, 1, 1, 0, 0, 0, 1, 1, 1, 0, 0, 1, 1, 0, 1, '', 'valid'),
('R009', '學區雅房', 'l001', 12, '雅房', '???', '1000.00', '100.00', '400.00', '0900123456', NULL, '2024-05-09', '2024-05-30', 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', 'valid');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 資料表的匯出資料 `studentaccommodation`
--

INSERT INTO `studentaccommodation` (`AccommodationID`, `StudentID`, `department`, `Name`, `Phone`, `Address`, `AcademicYear`, `Semester`, `LandlordName`, `LandlordPhoneNumber`, `MonthlyRent`, `Deposit`, `HousingType`, `RentalType`, `RecommendOthers`, `WoodenPartitionsOrIronSheet`, `HighPowerDevicesOnSingleExtension`, `FireAlarmOrSmokeDetector`, `FunctionalFireExtinguisher`, `SafeWaterHeater`, `ClearEscapeRoute`, `GoodSecurity`, `MoreThan6RoomsOr10Beds`, `InstalledLighting`, `InstalledCCTV`, `FamiliarWithSafetyProcedures`, `StandardLeaseContract`, `FamiliarWithEmergencyContacts`) VALUES
('S0011121', 'S001', '資工系', '許大名', '0975666111', '高雄市楠梓區高雄大學路500號', 112, 1, '張大大', '0957138509', 5000, 10000, '雅房', '公寓(五樓以下)', '是', '否', '是', '是', '是', '是', '是', '是', '是', '否', '是', '是', '是', '是'),
('S0011131', 'S001', '資工系', '許大名', '0975666111', '高雄市楠梓區高雄大學路500號', 113, 1, '王小小', '0933111116', 6000, 12000, '套房', '獨棟透天', '否', '是', '否', '否', '是', '是', '是', '是', '是', '是', '是', '是', '是', '是'),
('S0021121', 'S002', '建築系', '王安婷', '0983222111', '台中市南屯區文心路493號', 112, 1, '陳美華', '0976553224', 4500, 9000, '雅房', '公寓(五樓以下)', '是', '否', '否', '是', '是', '是', '否', '否', '是', '否', '否', '否', '否', '是');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 資料表的匯出資料 `students`
--

INSERT INTO `students` (`StudentID`, `Department`, `Name`, `Grade`, `Gender`, `AdvisorID`, `ContactPhone`, `S_Email`, `HomeAddress`, `HomePhone`, `EmergencyContactName`, `EmergencyContactPhone`) VALUES
('S001', '資訊工程系', '許大名', NULL, NULL, 'T001', NULL, NULL, NULL, NULL, NULL, NULL),
('S002', '建築系', '王安婷', 2, '女', 'T002', '0983222111', 'S002@gmail.com', '台中市南屯區文心路493號', '0424667777', '王柏安', '0988222111');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 資料表的匯出資料 `teachers`
--

INSERT INTO `teachers` (`TeacherID`, `Name`, `Rank`, `ContactPhone`, `T_Email`, `OfficeAddress`, `OfficePhone`) VALUES
('T001', '王宇凡', '教授', '0983821294', 'weifan.wf@gmail.com', '高雄市楠梓區大學路', '023929392'),
('T002', '陳大安', '教授', '0923673215', 'Andy.chen@gmail.com', '高雄市楠梓區大學路', '024882914');

-- --------------------------------------------------------

--
-- 資料表結構 `users`
--

CREATE TABLE `users` (
  `UserID` varchar(10) NOT NULL,
  `UserType` enum('學生','導師','房東','管理員') NOT NULL,
  `first_login` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 資料表的匯出資料 `users`
--

INSERT INTO `users` (`UserID`, `UserType`, `first_login`) VALUES
('L001', '房東', 0),
('L002', '房東', 0),
('M001', '管理員', 0),
('M002', '管理員', 0),
('S001', '學生', 0),
('S002', '學生', 0),
('T001', '導師', 0),
('T002', '導師', 0),
('T003', '導師', 0);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 資料表的匯出資料 `visitrecord`
--

INSERT INTO `visitrecord` (`VisitID`, `AccommodationID`, `TeacherID`, `VisitDate`, `StudentID`, `DepositRequirement`, `UtilityRequirement`, `LivingEnvironment`, `LivingFacilities`, `VisitStatus`, `HostGuestInteraction`, `GoodCondition`, `ContactParents`, `AssistanceNeeded`, `AdditionalNotes`, `TrafficSafety`, `NoSmoking`, `NoDrugs`, `DenguePrevention`, `Other`) VALUES
('VS0011121', 'S0011121', 'T001', '2024-06-04', 'S001', '合理', '合理', '適中', '適中', '適中', '和睦', '是', '是', '否', '無', '否', '是', '是', '否', '');

--
-- 已匯出資料表的索引
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
-- 已匯出資料表的限制(Constraint)
--

--
-- 資料表的 Constraints `accountmanagement`
--
ALTER TABLE `accountmanagement`
  ADD CONSTRAINT `FK_AccountManagement_UserID` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`);

--
-- 資料表的 Constraints `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `FK_Comments_CommenterID` FOREIGN KEY (`CommenterID`) REFERENCES `users` (`UserID`),
  ADD CONSTRAINT `FK_Comments_PostID` FOREIGN KEY (`PostID`) REFERENCES `forum` (`PostID`);

--
-- 資料表的 Constraints `forum`
--
ALTER TABLE `forum`
  ADD CONSTRAINT `FK_Forum_PosterID` FOREIGN KEY (`PosterID`) REFERENCES `users` (`UserID`);

--
-- 資料表的 Constraints `roommates`
--
ALTER TABLE `roommates`
  ADD CONSTRAINT `FK_Roommates_AccommodationID` FOREIGN KEY (`AccommodationID`) REFERENCES `studentaccommodation` (`AccommodationID`),
  ADD CONSTRAINT `FK_Roommates_StudentID` FOREIGN KEY (`StudentID`) REFERENCES `students` (`StudentID`);

--
-- 資料表的 Constraints `studentaccommodation`
--
ALTER TABLE `studentaccommodation`
  ADD CONSTRAINT `FK_StudentAccommodation_StudentID` FOREIGN KEY (`StudentID`) REFERENCES `students` (`StudentID`);

--
-- 資料表的 Constraints `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `FK_Students_AdvisorID` FOREIGN KEY (`AdvisorID`) REFERENCES `teachers` (`TeacherID`);

--
-- 資料表的 Constraints `visitrecord`
--
ALTER TABLE `visitrecord`
  ADD CONSTRAINT `FK_TeacherVisitRecord_AccommodationID` FOREIGN KEY (`AccommodationID`) REFERENCES `studentaccommodation` (`AccommodationID`),
  ADD CONSTRAINT `FK_TeacherVisitRecord_TeacherID` FOREIGN KEY (`TeacherID`) REFERENCES `teachers` (`TeacherID`),
  ADD CONSTRAINT `fk_student` FOREIGN KEY (`StudentID`) REFERENCES `students` (`StudentID`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
