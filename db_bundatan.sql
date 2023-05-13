-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 13, 2023 at 02:22 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_bundatan`
--

-- --------------------------------------------------------

--
-- Table structure for table `ingredients`
--

CREATE TABLE `ingredients` (
  `IngredientID` int(11) NOT NULL,
  `IngredientName` varchar(255) NOT NULL,
  `Quantity` decimal(10,2) DEFAULT NULL,
  `MeasurementID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ingredients`
--

INSERT INTO `ingredients` (`IngredientID`, `IngredientName`, `Quantity`, `MeasurementID`) VALUES
(1, 'Miki', '122.00', 11),
(2, 'Soy Sauce', '10.30', 13),
(3, 'Oyster sauce', '12.80', 13),
(4, 'Pepper', '22.40', 11),
(5, 'Egg', '120.00', 18),
(6, 'Ground Pork', '22.20', 11),
(7, 'Flour', '52.80', 11),
(8, 'Magic sarap', '4.00', 17),
(9, 'Cassava', '7.90', 11);

-- --------------------------------------------------------

--
-- Table structure for table `measurements`
--

CREATE TABLE `measurements` (
  `MeasurementID` int(11) NOT NULL,
  `MeasurementName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `measurements`
--

INSERT INTO `measurements` (`MeasurementID`, `MeasurementName`) VALUES
(1, 'Teaspoon '),
(2, 'Tablespoon '),
(3, 'Cup'),
(4, 'ounce '),
(5, 'Pint'),
(6, 'Quart'),
(7, 'Gallon'),
(8, 'Pound '),
(9, 'Ounce '),
(10, 'Gram '),
(11, 'Kilogram '),
(12, 'Milliliter '),
(13, 'Liter '),
(14, 'Pinch'),
(15, 'Dash'),
(16, 'Drop'),
(17, 'Pack'),
(18, 'Pieces'),
(19, 'as');

-- --------------------------------------------------------

--
-- Table structure for table `menuitemingredients`
--

CREATE TABLE `menuitemingredients` (
  `MenuItemIngredientID` int(11) NOT NULL,
  `MenuItemID` int(11) NOT NULL,
  `IngredientID` int(11) NOT NULL,
  `Quantity` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menuitemingredients`
--

INSERT INTO `menuitemingredients` (`MenuItemIngredientID`, `MenuItemID`, `IngredientID`, `Quantity`) VALUES
(1, 1, 1, '0.20'),
(2, 1, 2, '0.30'),
(3, 1, 3, '0.20'),
(4, 1, 4, '0.40'),
(5, 1, 5, '1.00'),
(6, 1, 6, '0.20'),
(7, 1, 7, '0.60'),
(8, 1, 8, '1.00'),
(9, 1, 9, '0.50'),
(10, 3, 1, '0.40'),
(11, 3, 2, '0.20'),
(12, 3, 3, '0.50'),
(13, 3, 4, '0.10'),
(14, 3, 5, '1.00'),
(15, 3, 6, '0.30'),
(16, 3, 7, '0.30'),
(17, 3, 8, '1.00'),
(18, 3, 9, '0.30');

-- --------------------------------------------------------

--
-- Table structure for table `menuitems`
--

CREATE TABLE `menuitems` (
  `MenuItemID` int(11) NOT NULL,
  `MenuItemName` varchar(255) NOT NULL,
  `MenuItemPrice` decimal(10,2) NOT NULL,
  `MenuItemImage` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menuitems`
--

INSERT INTO `menuitems` (`MenuItemID`, `MenuItemName`, `MenuItemPrice`, `MenuItemImage`) VALUES
(1, 'Regular Lomi ', '59.00', '../images/batangas-lomi-94455057-jpg (1).jpg'),
(3, 'Regular Guisado', '60.00', '../images/14df69ccb41e3c52b191430c3b0b373d_Supreme_Pansit_Miki_944_531.jpg'),
(4, '', '0.00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `SaleID` int(11) NOT NULL,
  `MenuItemID` int(11) NOT NULL,
  `SaleDate` datetime NOT NULL,
  `QuantitySold` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`SaleID`, `MenuItemID`, `SaleDate`, `QuantitySold`) VALUES
(1, 1, '2023-05-13 14:16:09', 1),
(2, 3, '2023-05-13 14:17:07', 2);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`) VALUES
(1, 'as', '', 'as'),
(2, 'admin', '', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ingredients`
--
ALTER TABLE `ingredients`
  ADD PRIMARY KEY (`IngredientID`),
  ADD KEY `MeasurementID` (`MeasurementID`);

--
-- Indexes for table `measurements`
--
ALTER TABLE `measurements`
  ADD PRIMARY KEY (`MeasurementID`);

--
-- Indexes for table `menuitemingredients`
--
ALTER TABLE `menuitemingredients`
  ADD PRIMARY KEY (`MenuItemIngredientID`),
  ADD KEY `MenuItemID` (`MenuItemID`),
  ADD KEY `IngredientID` (`IngredientID`);

--
-- Indexes for table `menuitems`
--
ALTER TABLE `menuitems`
  ADD PRIMARY KEY (`MenuItemID`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`SaleID`),
  ADD KEY `MenuItemID` (`MenuItemID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ingredients`
--
ALTER TABLE `ingredients`
  MODIFY `IngredientID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `measurements`
--
ALTER TABLE `measurements`
  MODIFY `MeasurementID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `menuitemingredients`
--
ALTER TABLE `menuitemingredients`
  MODIFY `MenuItemIngredientID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `menuitems`
--
ALTER TABLE `menuitems`
  MODIFY `MenuItemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `SaleID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ingredients`
--
ALTER TABLE `ingredients`
  ADD CONSTRAINT `ingredients_ibfk_1` FOREIGN KEY (`MeasurementID`) REFERENCES `measurements` (`MeasurementID`);

--
-- Constraints for table `menuitemingredients`
--
ALTER TABLE `menuitemingredients`
  ADD CONSTRAINT `menuitemingredients_ibfk_1` FOREIGN KEY (`MenuItemID`) REFERENCES `menuitems` (`MenuItemID`),
  ADD CONSTRAINT `menuitemingredients_ibfk_2` FOREIGN KEY (`IngredientID`) REFERENCES `ingredients` (`IngredientID`);

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`MenuItemID`) REFERENCES `menuitems` (`MenuItemID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
