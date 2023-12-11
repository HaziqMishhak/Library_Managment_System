-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 23, 2023 at 04:24 AM
-- Server version: 5.7.43-cll-lve
-- PHP Version: 8.1.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `libraryu_final`
--

-- --------------------------------------------------------

--
-- Table structure for table `Books`
--

CREATE TABLE `Books` (
  `BookID` bigint(20) NOT NULL,
  `BookName` varchar(255) NOT NULL,
  `AuthorName` varchar(255) NOT NULL,
  `PublishDate` date DEFAULT NULL,
  `IsActive` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Books`
--

INSERT INTO `Books` (`BookID`, `BookName`, `AuthorName`, `PublishDate`, `IsActive`) VALUES
(584190814360, 'Arkitek Pelukis Jalanan', 'Teme Abdullah', '2018-05-01', 1),
(584190814617, 'Cloud Server For Beginner', 'Tom Fallner', '2021-10-14', 1),
(584190814874, 'The Key for the genius', 'Albert Hamble', '2020-08-19', 1),
(584190815131, 'Python For Beginner ', 'Timothy C. Needham', '2017-09-21', 1),
(584190815388, 'Senyumlah', 'Syed Alwi Alatas', '2013-01-01', 1),
(585509761074, 'Book test 1', 'Ali bin abu', '2000-09-08', 1),
(585516312128, 'Murder on the orient express', 'Agatha Christe', '1994-04-17', 1),
(585517097905, 'Tamadun Islam dan Tamadun Asia', 'Penerbit Universiti Malaya', '2018-01-17', 1),
(585518674315, 'Book test 2', 'Abu bin rabu', '1994-02-21', 1),
(585520966899, 'Pengantar psikologi islam', 'maizan binti mat@muhammad', '2019-01-01', 1),
(585522145258, 'Ketika Azazil Berputus Asa', 'Hadi Fayyadh', '2017-01-12', 1),
(585524701900, 'ALGEBRA', 'Muahmmad bin Musa Al-Khawarizmi', '1937-09-26', 1);

-- --------------------------------------------------------

--
-- Table structure for table `BorrowBook`
--

CREATE TABLE `BorrowBook` (
  `BorrowID` int(11) NOT NULL,
  `BookID` bigint(20) NOT NULL,
  `StudentID` varchar(10) NOT NULL,
  `BorrowDateTime` datetime NOT NULL,
  `DueTime` datetime NOT NULL,
  `Status` enum('Not Due','Late','Returned') DEFAULT 'Not Due'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `BorrowBook`
--

INSERT INTO `BorrowBook` (`BorrowID`, `BookID`, `StudentID`, `BorrowDateTime`, `DueTime`, `Status`) VALUES
(1, 584190815388, '22BB0212', '2023-11-01 23:03:00', '2023-11-09 23:03:00', 'Returned'),
(5, 584190814874, '20BT02002', '2023-10-30 17:01:00', '2023-11-07 17:01:00', 'Returned'),
(6, 584190814874, '21MS31013', '2023-11-08 09:00:00', '2023-11-16 09:00:00', 'Returned'),
(7, 585517097905, '19BB05003', '2023-11-01 23:08:00', '2023-11-09 23:08:00', 'Returned'),
(8, 584190815388, '19BB05003', '2023-11-08 23:08:00', '2023-11-16 23:08:00', 'Returned'),
(9, 584190814617, '20BT02002', '2023-11-09 13:42:00', '2023-11-17 13:42:00', 'Returned'),
(10, 584190815131, '19BB05003', '2023-10-31 13:46:00', '2023-11-08 13:46:00', 'Returned'),
(12, 585517097905, '21MS31013', '2023-11-01 13:22:00', '2023-11-09 13:22:00', 'Returned'),
(13, 585517097905, '22BB0212', '2023-11-10 17:37:00', '2023-11-18 17:37:00', 'Returned'),
(14, 585524701900, '19BB05003', '2023-10-26 21:54:00', '2023-11-03 21:54:00', 'Returned'),
(15, 584190814360, '22BB0212', '2023-11-12 21:56:00', '2023-11-20 21:56:00', 'Returned'),
(17, 585516312128, '20BT02002', '2023-11-01 22:33:00', '2023-11-09 22:33:00', 'Returned'),
(19, 585524701900, '20BT02002', '2023-11-15 06:35:00', '2023-11-23 06:35:00', 'Returned'),
(21, 585522145258, '20BT02002', '2023-11-01 06:52:00', '2023-11-09 06:52:00', 'Returned');

--
-- Triggers `BorrowBook`
--
DELIMITER $$
CREATE TRIGGER `set_due_time` BEFORE INSERT ON `BorrowBook` FOR EACH ROW BEGIN
    SET NEW.DueTime = DATE_ADD(NEW.BorrowDateTime, INTERVAL 8 DAY);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `Fine`
--

CREATE TABLE `Fine` (
  `FineID` int(11) NOT NULL,
  `ReturnID` int(11) NOT NULL,
  `AmountDue` decimal(10,2) NOT NULL,
  `PaymentStatus` enum('Pending','Paid') DEFAULT 'Pending',
  `TransactionID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Fine`
--

INSERT INTO `Fine` (`FineID`, `ReturnID`, `AmountDue`, `PaymentStatus`, `TransactionID`) VALUES
(2, 4, 4.00, 'Paid', 2),
(3, 7, 2.00, 'Paid', 3),
(4, 9, 6.00, 'Paid', 78),
(5, 10, 6.00, 'Paid', 79),
(6, 12, 20.00, 'Pending', NULL),
(8, 15, 12.00, 'Paid', 80),
(9, 16, 14.00, 'Paid', 80);

-- --------------------------------------------------------

--
-- Table structure for table `PaymentTransaction`
--

CREATE TABLE `PaymentTransaction` (
  `TransactionID` int(11) NOT NULL,
  `StudentID` varchar(10) NOT NULL,
  `TotalAmount` decimal(10,2) NOT NULL,
  `NumberOfBooks` int(11) NOT NULL,
  `ReceiptImagePath` varchar(255) NOT NULL,
  `PaymentDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `PaymentTransaction`
--

INSERT INTO `PaymentTransaction` (`TransactionID`, `StudentID`, `TotalAmount`, `NumberOfBooks`, `ReceiptImagePath`, `PaymentDate`) VALUES
(2, '20bt02002', 4.00, 1, '../upload/8c5f3294e9861824104a8a7acc2a5769c2b3c15f.png', '2023-11-09 05:03:29'),
(3, '19BB05003', 2.00, 1, '../upload/2430e57063c7e69a637f2e97417c54ec87a4bfce.png', '2023-11-10 10:13:55'),
(78, '19BB05003', 6.00, 1, '../upload/66e10cdece9287e70e50c10b5e5335d84a637815.jpg', '2023-11-11 00:52:56'),
(79, '21MS31013', 6.00, 1, '../upload/f007a9b7dee8ae1e46faffba2fe6c18d910b7074.png', '2023-11-14 01:55:02'),
(80, '20bt02002', 26.00, 2, '../upload/e18193d1bb756f9f5af8c7a5406cddc0f4c759a4.jpg', '2023-11-15 22:04:14');

-- --------------------------------------------------------

--
-- Table structure for table `ReturnBook`
--

CREATE TABLE `ReturnBook` (
  `ReturnID` int(11) NOT NULL,
  `BorrowID` int(11) NOT NULL,
  `ReturnDate` datetime NOT NULL,
  `Status` enum('On-Time','Late') DEFAULT 'On-Time'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ReturnBook`
--

INSERT INTO `ReturnBook` (`ReturnID`, `BorrowID`, `ReturnDate`, `Status`) VALUES
(3, 1, '2023-11-09 16:53:37', 'On-Time'),
(4, 5, '2023-11-09 17:59:54', 'Late'),
(5, 6, '2023-11-09 18:00:36', 'On-Time'),
(6, 8, '2023-11-10 23:10:05', 'On-Time'),
(7, 7, '2023-11-10 23:13:07', 'Late'),
(8, 9, '2023-11-11 13:44:24', 'On-Time'),
(9, 10, '2023-11-11 13:48:35', 'Late'),
(10, 12, '2023-11-12 13:24:21', 'Late'),
(11, 13, '2023-11-12 18:26:42', 'On-Time'),
(12, 14, '2023-11-14 14:57:55', 'Late'),
(13, 15, '2023-11-14 20:19:28', 'On-Time'),
(15, 17, '2023-11-15 22:35:00', 'Late'),
(16, 21, '2023-11-16 10:59:15', 'Late'),
(17, 19, '2023-11-16 10:59:53', 'On-Time');

--
-- Triggers `ReturnBook`
--
DELIMITER $$
CREATE TRIGGER `after_returnbook_insert` AFTER INSERT ON `ReturnBook` FOR EACH ROW BEGIN
    UPDATE BorrowBook SET Status = 'Returned' WHERE BorrowID = NEW.BorrowID;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `Students`
--

CREATE TABLE `Students` (
  `StudentID` varchar(10) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Course` varchar(255) NOT NULL,
  `PhoneNumber` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Students`
--

INSERT INTO `Students` (`StudentID`, `Name`, `Email`, `Course`, `PhoneNumber`) VALUES
('19BB05003', 'Aishah Binti Rahman', '19BB05003@student.kuis.edu.my', 'Islamic Finance (Banking) (Hons)', '0108764930'),
('20BT02002', 'Haziq Syahmi bin Mishhak ', '20bt02002@student.kuis.edu.my', 'BCNT', '0108764942'),
('21MS31013', 'Nurul Farhana Binti Ismail', '21MS31013@student.kuis.edu.my', 'Accounting', '0108764931'),
('22BB0212', 'Muhammad Firdaus Bin Zainal', '22BB0212@student.kuis.edu.my', 'Human Resource Management (Hons)', '0108764932');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `phone`, `email`, `password`) VALUES
(1, 'this is testing', '01112249205', 'test@kuis.edu.my', '$2y$10$8IWFTlXFNUhVQcXkGTmwG.OSrqoGyCQjbJEUJbiwRT0RbQI2/QnRi'),
(4, 'Jamal Ali bin Abu haz', '01112249205', 'jamal@kuis.edu.my', '$2y$10$Zmf.bBxLjrmc/FMG4ku0x.wAfhzPyfTn/1CoKy.bG320nEzYb5N1.');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Books`
--
ALTER TABLE `Books`
  ADD PRIMARY KEY (`BookID`);

--
-- Indexes for table `BorrowBook`
--
ALTER TABLE `BorrowBook`
  ADD PRIMARY KEY (`BorrowID`),
  ADD KEY `StudentID` (`StudentID`),
  ADD KEY `BookID` (`BookID`);

--
-- Indexes for table `Fine`
--
ALTER TABLE `Fine`
  ADD PRIMARY KEY (`FineID`),
  ADD KEY `ReturnID` (`ReturnID`),
  ADD KEY `TransactionID` (`TransactionID`);

--
-- Indexes for table `PaymentTransaction`
--
ALTER TABLE `PaymentTransaction`
  ADD PRIMARY KEY (`TransactionID`),
  ADD KEY `StudentID` (`StudentID`);

--
-- Indexes for table `ReturnBook`
--
ALTER TABLE `ReturnBook`
  ADD PRIMARY KEY (`ReturnID`),
  ADD KEY `BorrowID` (`BorrowID`);

--
-- Indexes for table `Students`
--
ALTER TABLE `Students`
  ADD PRIMARY KEY (`StudentID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `BorrowBook`
--
ALTER TABLE `BorrowBook`
  MODIFY `BorrowID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `Fine`
--
ALTER TABLE `Fine`
  MODIFY `FineID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `PaymentTransaction`
--
ALTER TABLE `PaymentTransaction`
  MODIFY `TransactionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `ReturnBook`
--
ALTER TABLE `ReturnBook`
  MODIFY `ReturnID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `BorrowBook`
--
ALTER TABLE `BorrowBook`
  ADD CONSTRAINT `BorrowBook_ibfk_1` FOREIGN KEY (`StudentID`) REFERENCES `Students` (`StudentID`),
  ADD CONSTRAINT `BorrowBook_ibfk_2` FOREIGN KEY (`BookID`) REFERENCES `Books` (`BookID`);

--
-- Constraints for table `Fine`
--
ALTER TABLE `Fine`
  ADD CONSTRAINT `Fine_ibfk_1` FOREIGN KEY (`ReturnID`) REFERENCES `ReturnBook` (`ReturnID`),
  ADD CONSTRAINT `Fine_ibfk_2` FOREIGN KEY (`TransactionID`) REFERENCES `PaymentTransaction` (`TransactionID`);

--
-- Constraints for table `PaymentTransaction`
--
ALTER TABLE `PaymentTransaction`
  ADD CONSTRAINT `PaymentTransaction_ibfk_1` FOREIGN KEY (`StudentID`) REFERENCES `Students` (`StudentID`);

--
-- Constraints for table `ReturnBook`
--
ALTER TABLE `ReturnBook`
  ADD CONSTRAINT `ReturnBook_ibfk_1` FOREIGN KEY (`BorrowID`) REFERENCES `BorrowBook` (`BorrowID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
