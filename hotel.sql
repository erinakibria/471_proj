CREATE TABLE `Booking` (
  `BookingID` int(11) NOT NULL,
  `CheckIn` date NOT NULL,
  `CheckOut` date NOT NULL,
  `RoomID` int(11) NOT NULL,
  `TransID` int(11) NOT NULL,
  `GuestID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `Booking` (`BookingID`, `CheckIn`, `CheckOut`, `RoomID`, `TransID`, `GuestID`) VALUES
(1, '2025-01-12', '2025-01-15', 1, 1, 1),
(2, '2024-01-11', '2024-01-14', 2, 2, 2),
(5, '2024-12-06', '2024-12-17', 1, 3, 1),
(7, '2024-12-03', '2024-12-06', 3, 4, 3),
(8, '2024-12-08', '2024-12-09', 3, 20, 2),
(9, '2024-12-11', '2024-12-14', 3, 21, 2),
(10, '2024-12-23', '2024-12-24', 1, 22, 2),
(11, '2024-12-24', '2024-12-26', 1, 23, 2),
(12, '2025-01-24', '2025-01-25', 2, 24, 2),
(13, '2024-12-13', '2024-12-25', 2, 25, 2),
(14, '2024-12-17', '2024-12-19', 1, 26, 2),
(15, '2024-12-09', '2024-12-11', 2, 27, 2);

CREATE TABLE `Course` (
  `CourseID` int(11) NOT NULL,
  `Title` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `Course` (`CourseID`, `Title`) VALUES
(1, 'Customer Service Training'),
(2, 'Advanced Housekeeping Skills'),
(3, 'Website Navigation');

CREATE TABLE `Department` (
  `DepartmentID` int(11) NOT NULL,
  `Type` varchar(50) NOT NULL,
  `Roles` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `Department` (`DepartmentID`, `Type`, `Roles`) VALUES
(-1, 'No department assigned', 'No roles assigned'),
(1, 'Reception', 'Check-in, Customer Support'),
(3, 'Security', 'Security Officer, Night Watch'),
(4, 'Housekeeping', 'Room cleaning');

CREATE TABLE `Guest` (
  `GuestID` int(11) NOT NULL,
  `Password` varchar(20) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Phone` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `Guest` (`GuestID`, `Password`, `Name`, `Phone`) VALUES
(1, 'sWa$I&re$hanaMEzuC5l', 'Keziah Chong', '4034567890'),
(2, 'Test@123', 'Erina Kibria', '5876543210'),
(3, 'sl=ithUC*LF062iTet_I', 'Katarina Komar', '4031234567'),
(6, 'Smith@123', 'Alice Smith', '3829994747');

INSERT INTO `Management` (`ManagerID`, `StaffID`, `DepartmentID`) VALUES
(4, 1, 1),
(5, 1, -1),
(2, 2, 1),
(6, 2, -1);

CREATE TABLE `Room` (
  `RoomID` int(11) NOT NULL,
  `Description` varchar(250) DEFAULT NULL,
  `Type` enum('Standard','Deluxe') NOT NULL,
  `Price` decimal(6,2) NOT NULL,
  `Status` enum('Vacant','Occupied') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Room`
--

INSERT INTO `Room` (`RoomID`, `Description`, `Type`, `Price`, `Status`) VALUES
(1, 'Two Doubles, Housekeeping in progress', 'Standard', '100.00', 'Occupied'),
(2, 'Junior, Housekeeping in progress', 'Deluxe', '200.00', 'Vacant'),
(3, 'King, Cleaned', 'Standard', '150.00', 'Vacant');

CREATE TABLE `Staff` (
  `StaffID` int(11) NOT NULL,
  `Password` varchar(20) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Phone` varchar(15) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `DepartmentID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Staff`
--

INSERT INTO `Staff` (`StaffID`, `Password`, `Name`, `Phone`, `Email`, `DepartmentID`) VALUES
(1, 'Test@123', 'Jane Doe', '1239876544', 'JaneDoe@gmail.com', 3),
(2, 'baCiMlD#ZA9rec6+!pRi', 'John Taylor', '4563217890', 'johnTaylor@gmail.com', 4),
(4, 'Mk@2024', 'Twilight Sparkle', '3637474888', 'tsp@gmail.com', 1),
(5, 'Test', 'Test Test', '5555555555', 'test@test.ca', -1);

CREATE TABLE `TrainingLog` (
  `LogID` int(11) NOT NULL,
  `Start` date NOT NULL,
  `End` date NOT NULL,
  `CourseID` int(11) NOT NULL,
  `StaffID` int(11) NOT NULL,
  `Status` enum('Complete','Incomplete') DEFAULT 'Incomplete'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `TrainingLog`
--

INSERT INTO `TrainingLog` (`LogID`, `Start`, `End`, `CourseID`, `StaffID`, `Status`) VALUES
(5, '2024-01-01', '2024-01-03', 1, 1, 'Complete'),
(6, '2024-01-04', '2024-01-06', 2, 2, 'Incomplete'),
(7, '2024-12-06', '2024-12-31', 2, 1, 'Incomplete'),
(8, '2024-12-09', '2024-12-16', 3, 5, 'Incomplete'),
(9, '2024-12-09', '2024-12-16', 3, 5, 'Incomplete');

CREATE TABLE `Transaction` (
  `TransID` int(11) NOT NULL,
  `Date` date NOT NULL,
  `Amount` decimal(6,2) NOT NULL,
  `CreditCard` varchar(16) NOT NULL,
  `GuestID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Transaction`
--

INSERT INTO `Transaction` (`TransID`, `Date`, `Amount`, `CreditCard`, `GuestID`) VALUES
(1, '2025-01-15', '1000.00', '-1', 1),
(2, '2024-01-14', '500.00', '5209322125025193', 2),
(3, '2024-12-17', '100.00', '4333333333333333', 3),
(4, '2024-12-06', '900.00', '3273719382192', 2),
(20, '2024-12-08', '150.00', '2374382409231', 2),
(21, '2024-12-08', '450.00', '-1', 2),
(22, '2024-12-08', '100.00', '-1', 2),
(23, '2024-12-08', '200.00', '-1', 2),
(24, '2024-12-08', '200.00', '-1', 2),
(25, '2024-12-08', '2400.00', '-1', 2),
(26, '2024-12-09', '200.00', '2789476356789765', 2),
(27, '2024-12-09', '400.00', '-1', 2);

ALTER TABLE `Booking`
  ADD PRIMARY KEY (`BookingID`),
  ADD KEY `RoomID` (`RoomID`),
  ADD KEY `TransID` (`TransID`),
  ADD KEY `GuestID` (`GuestID`);

--
-- Indexes for table `Course`
--
ALTER TABLE `Course`
  ADD PRIMARY KEY (`CourseID`);

--
-- Indexes for table `Department`
--
ALTER TABLE `Department`
  ADD PRIMARY KEY (`DepartmentID`);

--
-- Indexes for table `Guest`
--
ALTER TABLE `Guest`
  ADD PRIMARY KEY (`GuestID`);

  ALTER TABLE `Management`
  ADD PRIMARY KEY (`ManagerID`,`DepartmentID`),
  ADD KEY `StaffID` (`StaffID`),
  ADD KEY `DepartmentID` (`DepartmentID`);

--
-- Indexes for table `Room`
--
ALTER TABLE `Room`
  ADD PRIMARY KEY (`RoomID`);

--
-- Indexes for table `Staff`
--
ALTER TABLE `Staff`
  ADD PRIMARY KEY (`StaffID`),
  ADD KEY `DepartmentID` (`DepartmentID`);

  ALTER TABLE `TrainingLog`
  ADD PRIMARY KEY (`LogID`),
  ADD KEY `CourseID` (`CourseID`),
  ADD KEY `StaffID` (`StaffID`);

--
-- Indexes for table `Transaction`
--
ALTER TABLE `Transaction`
  ADD PRIMARY KEY (`TransID`),
  ADD KEY `GuestID` (`GuestID`);

  ALTER TABLE `Booking`
  MODIFY `BookingID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `Course`
--
ALTER TABLE `Course`
  MODIFY `CourseID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `Department`
--
ALTER TABLE `Department`
  MODIFY `DepartmentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `Guest`
--
ALTER TABLE `Guest`
  MODIFY `GuestID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `Management`
--
ALTER TABLE `Management`
  MODIFY `ManagerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

  ALTER TABLE `Room`
  MODIFY `RoomID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `Staff`
--
ALTER TABLE `Staff`
  MODIFY `StaffID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `TrainingLog`
--
ALTER TABLE `TrainingLog`
  MODIFY `LogID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `Transaction`
--
ALTER TABLE `Transaction`
  MODIFY `TransID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

  ALTER TABLE `Booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`RoomID`) REFERENCES `Room` (`RoomID`),
  ADD CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`TransID`) REFERENCES `Transaction` (`TransID`),
  ADD CONSTRAINT `booking_ibfk_3` FOREIGN KEY (`GuestID`) REFERENCES `Guest` (`GuestID`);

  ALTER TABLE `Management`
  ADD CONSTRAINT `management_ibfk_1` FOREIGN KEY (`StaffID`) REFERENCES `Staff` (`StaffID`),
  ADD CONSTRAINT `management_ibfk_2` FOREIGN KEY (`DepartmentID`) REFERENCES `Department` (`DepartmentID`);

  ALTER TABLE `Staff`
  ADD CONSTRAINT `staff_ibfk_1` FOREIGN KEY (`DepartmentID`) REFERENCES `Department` (`DepartmentID`);

  ALTER TABLE `TrainingLog`
  ADD CONSTRAINT `traininglog_ibfk_1` FOREIGN KEY (`CourseID`) REFERENCES `Course` (`CourseID`),
  ADD CONSTRAINT `traininglog_ibfk_2` FOREIGN KEY (`StaffID`) REFERENCES `Staff` (`StaffID`);

  ALTER TABLE `Transaction`
  ADD CONSTRAINT `transaction_ibfk_1` FOREIGN KEY (`GuestID`) REFERENCES `Guest` (`GuestID`);
COMMIT;