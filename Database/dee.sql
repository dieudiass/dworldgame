-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 09, 2016 at 04:36 PM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 5.5.35

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dee`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounttype`
--

CREATE TABLE `accounttype` (
  `Type_id` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Description` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `accounttype`
--

INSERT INTO `accounttype` (`Type_id`, `Name`, `Description`) VALUES
(1, 'Admin', 'An administrator account'),
(2, 'Customer', 'A typical customer account ');

-- --------------------------------------------------------

--
-- Table structure for table `cashreceipt`
--

CREATE TABLE `cashreceipt` (
  `Cash_receipt_id` int(11) NOT NULL,
  `Date` date NOT NULL,
  `Time` time NOT NULL,
  `Amount` decimal(7,2) NOT NULL,
  `Sale_ref_no` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `Category_id` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Description` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`Category_id`, `Name`, `Description`) VALUES
(1, 'Action', 'Action'),
(2, 'Sports', 'Sports'),
(3, 'Racing', 'Racing'),
(4, 'Adventure', 'Games that allow you to discover new places'),
(5, 'Academic', 'Games that train your mind, sometimes');

-- --------------------------------------------------------

--
-- Table structure for table `game`
--

CREATE TABLE `game` (
  `Game_ref_no` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` varchar(100) DEFAULT NULL,
  `Price` decimal(7,2) NOT NULL,
  `Stock` int(11) NOT NULL,
  `Category_id` int(11) DEFAULT NULL,
  `Supplier_id` int(11) NOT NULL,
  `Image` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `game`
--

INSERT INTO `game` (`Game_ref_no`, `Name`, `Description`, `Price`, `Stock`, `Category_id`, `Supplier_id`, `Image`) VALUES
(14, 'god of war', 'roman history game', '350.00', 15, 1, 1, 'Great.jpg'),
(15, 'Hero ', 'Second bggest war in atlanta', '250.90', 11, 4, 1, 'Dark.jpg'),
(16, 'Ninja', 'Chinese myth', '360.00', 18, 1, 2, 'Ninja.jpg'),
(17, 'Ship battle', 'Ocean adventure', '400.00', 9, 4, 7, 'war.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `gamekey`
--

CREATE TABLE `gamekey` (
  `Game_key_serial_no` varchar(30) NOT NULL,
  `Status` char(1) NOT NULL,
  `Game_ref_no` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sale`
--

CREATE TABLE `sale` (
  `Sale_ref_no` int(11) NOT NULL,
  `Date` date NOT NULL,
  `Time` time NOT NULL,
  `Total_amount` double(7,2) NOT NULL,
  `Username` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sale`
--

INSERT INTO `sale` (`Sale_ref_no`, `Date`, `Time`, `Total_amount`, `Username`) VALUES
(1, '2016-11-07', '10:51:19', 1225.97, 'Test'),
(2, '2016-11-07', '14:32:34', 955.00, 'Test'),
(3, '2016-11-07', '15:26:55', 775.00, 'Dummy2'),
(4, '2016-11-09', '11:07:19', 205.00, 'Dummy2'),
(5, '2016-11-09', '11:08:46', 940.50, 'Dummy2'),
(6, '2016-11-09', '11:11:11', 865.00, 'Test'),
(7, '2016-11-09', '11:23:09', 1035.50, 'johnny'),
(8, '2016-11-09', '12:28:30', 775.00, 'johnny'),
(9, '2016-11-09', '12:51:11', 1960.50, 'Dummy1'),
(10, '2016-11-09', '12:55:26', 1080.50, 'Dummy1'),
(11, '2016-11-09', '12:57:38', 205.00, 'Dummy1'),
(12, '2016-11-09', '13:02:50', 205.00, 'Dummy1'),
(13, '2016-11-09', '13:10:37', 425.00, 'Dummy1'),
(14, '2016-11-09', '13:12:24', 205.00, 'Dummy1');

-- --------------------------------------------------------

--
-- Table structure for table `saleitem`
--

CREATE TABLE `saleitem` (
  `Sale_ref_no` int(11) NOT NULL,
  `Game_ref_no` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `Amount` decimal(7,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `Supplier_id` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Country` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`Supplier_id`, `Name`, `Country`) VALUES
(1, 'CAPCOM', 'Japan'),
(2, 'EA Games', 'United States'),
(3, 'EA Sports', 'United States'),
(4, 'KONAMI', 'Japan'),
(6, 'Midway Games', 'South Africa'),
(7, 'Childish Things', 'United Kingdom'),
(8, 'Atari', 'Japan');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `Username` varchar(50) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `First_name` varchar(50) NOT NULL,
  `Last_name` varchar(50) NOT NULL,
  `Gender` char(1) NOT NULL,
  `Contact` varchar(10) NOT NULL,
  `Account_type` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`Username`, `Email`, `Password`, `First_name`, `Last_name`, `Gender`, `Contact`, `Account_type`) VALUES
('admin', 'smoke@admin.com', 'admin', 'Don', 'Smoke', 'M', '0846123905', 1),
('Dido', 'dummy@site.com', 'pass123', 'Dido', 'Kabwe', 'M', '0796592165', 1),
('Dummy1', 'dummy@site.com', 'pass123', 'John1', 'Doe1', 'M', '0712538469', 2),
('Dummy2', 'dummy@site.com', 'pass123', 'John2', 'Doe2', 'M', '0796592165', 2),
('johndoe', 'johndoe@gmail.com', '147258', 'John', 'Doe', 'M', '0624568237', 2),
('johnny', 'johnyb@gmail.com', '147258369', 'Johny', 'Bravo', 'M', '0823694521', 2),
('Test', 'mymail@gmail.com', '147258369', 'Firstname', 'Lastname', 'M', '0615823695', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounttype`
--
ALTER TABLE `accounttype`
  ADD PRIMARY KEY (`Type_id`);

--
-- Indexes for table `cashreceipt`
--
ALTER TABLE `cashreceipt`
  ADD PRIMARY KEY (`Cash_receipt_id`),
  ADD KEY `CashReceipt_Sale_ref_no_FK` (`Sale_ref_no`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`Category_id`);

--
-- Indexes for table `game`
--
ALTER TABLE `game`
  ADD PRIMARY KEY (`Game_ref_no`),
  ADD KEY `Game_Supplier_id_FK` (`Supplier_id`),
  ADD KEY `FK_game_category` (`Category_id`);

--
-- Indexes for table `gamekey`
--
ALTER TABLE `gamekey`
  ADD PRIMARY KEY (`Game_key_serial_no`),
  ADD KEY `GameKey_Game_ref_no_FK` (`Game_ref_no`);

--
-- Indexes for table `sale`
--
ALTER TABLE `sale`
  ADD PRIMARY KEY (`Sale_ref_no`),
  ADD KEY `Sale_Username_FK` (`Username`);

--
-- Indexes for table `saleitem`
--
ALTER TABLE `saleitem`
  ADD PRIMARY KEY (`Sale_ref_no`,`Game_ref_no`),
  ADD KEY `Sale_Item_Game_ref_no_FK` (`Game_ref_no`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`Supplier_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`Username`),
  ADD KEY `User_Account_type_FK` (`Account_type`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounttype`
--
ALTER TABLE `accounttype`
  MODIFY `Type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `cashreceipt`
--
ALTER TABLE `cashreceipt`
  MODIFY `Cash_receipt_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `Category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `game`
--
ALTER TABLE `game`
  MODIFY `Game_ref_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `sale`
--
ALTER TABLE `sale`
  MODIFY `Sale_ref_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `Supplier_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `cashreceipt`
--
ALTER TABLE `cashreceipt`
  ADD CONSTRAINT `CashReceipt_Sale_ref_no_FK` FOREIGN KEY (`Sale_ref_no`) REFERENCES `sale` (`Sale_ref_no`);

--
-- Constraints for table `game`
--
ALTER TABLE `game`
  ADD CONSTRAINT `FK_game_category` FOREIGN KEY (`Category_id`) REFERENCES `category` (`Category_id`),
  ADD CONSTRAINT `Game_Supplier_id_FK` FOREIGN KEY (`Supplier_id`) REFERENCES `supplier` (`Supplier_id`);

--
-- Constraints for table `gamekey`
--
ALTER TABLE `gamekey`
  ADD CONSTRAINT `GameKey_Game_ref_no_FK` FOREIGN KEY (`Game_ref_no`) REFERENCES `game` (`Game_ref_no`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sale`
--
ALTER TABLE `sale`
  ADD CONSTRAINT `Sale_Username_FK` FOREIGN KEY (`Username`) REFERENCES `user` (`Username`);

--
-- Constraints for table `saleitem`
--
ALTER TABLE `saleitem`
  ADD CONSTRAINT `Sale_Item_Game_ref_no_FK` FOREIGN KEY (`Game_ref_no`) REFERENCES `game` (`Game_ref_no`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Sale_Item_Sale_ref_no_FK` FOREIGN KEY (`Sale_ref_no`) REFERENCES `sale` (`Sale_ref_no`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `User_Account_type_FK` FOREIGN KEY (`Account_type`) REFERENCES `accounttype` (`Type_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
