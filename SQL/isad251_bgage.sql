-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: proj-mysql.uopnet.plymouth.ac.uk
-- Generation Time: Jan 09, 2020 at 07:43 PM
-- Server version: 8.0.16
-- PHP Version: 7.2.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `isad251_bgage`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`ISAD251_BGage`@`%` PROCEDURE `AddItemToOrder` (IN `in_orderid` INT, IN `in_title` VARCHAR(255), IN `in_quantity` INT)  NO SQL
BEGIN

SET @ITEMID = (SELECT ItemId FROM menuitems WHERE Title = in_title);

SET @count = (SELECT COUNT(*) FROM orderdetails 
WHERE OrderId = in_orderid AND ItemId = @ITEMID);

IF @count != 0 THEN
	UPDATE orderdetails SET Quantity = Quantity + in_quantity 
    WHERE OrderId = in_orderid AND ItemId = @ITEMID;
    
    CALL UpdateStock(@ITEMID, in_quantity);
    
ELSE
	INSERT INTO orderdetails(OrderId, ItemId, Quantity) 
	VALUES(in_orderid, @ITEMID, in_quantity);
    
    CALL UpdateStock(@ITEMID, in_quantity);
END IF;

END$$

CREATE DEFINER=`ISAD251_BGage`@`%` PROCEDURE `AddMenuItem` (IN `IN_TITLE` VARCHAR(255), IN `IN_DETAILS` VARCHAR(255), IN `IN_ISFOOD` TINYINT, IN `IN_PRICE` INT, IN `IN_WITHDRAWN` TINYINT)  NO SQL
BEGIN
INSERT INTO menuitems(Title, Details, IsFood, Price, Withdrawn) 
VALUES (IN_TITLE, IN_DETAILS, IN_ISFOOD, IN_PRICE, IN_WITHDRAWN);
END$$

CREATE DEFINER=`ISAD251_BGage`@`%` PROCEDURE `CancelOrder` (IN `in_orderId` INT)  NO SQL
BEGIN
DELETE FROM orderdetails WHERE OrderId = in_orderId;
DELETE FROM orders WHERE OrderId = in_orderId;
END$$

CREATE DEFINER=`ISAD251_BGage`@`%` PROCEDURE `CancelOrderItem` (IN `in_itemId` INT, IN `in_quantity` INT)  NO SQL
BEGIN
UPDATE stock SET Quantity = Quantity + in_quantity
WHERE ItemId = in_itemId;

END$$

CREATE DEFINER=`ISAD251_BGage`@`%` PROCEDURE `CheckOutOrder` (IN `in_orderId` INT)  NO SQL
BEGIN
DELETE FROM orderdetails WHERE OrderId = in_orderId;
DELETE FROM orders WHERE OrderId = in_orderId;
END$$

CREATE DEFINER=`ISAD251_BGage`@`%` PROCEDURE `CreateNewOrder` (IN `ORDERID` INT, IN `ORDERDATE` DATE, IN `CUSTOMERID` INT)  NO SQL
BEGIN

SET @count = (SELECT COUNT(*) FROM orders 
WHERE OrderId = ORDERID AND CustomerId = CUSTOMERID);

IF @count = 0 THEN
	INSERT INTO orders(OrderId, OrderDate, CustomerId) 
	VALUES(ORDERID, ORDERDATE, CUSTOMERID);
END IF;

END$$

CREATE DEFINER=`ISAD251_BGage`@`%` PROCEDURE `DeleteMenuItem` (IN `in_itemId` INT)  NO SQL
BEGIN
DELETE FROM menuitems WHERE ItemId = in_itemId;
END$$

CREATE DEFINER=`ISAD251_BGage`@`%` PROCEDURE `EditMenuItem` (IN `in_itemId` INT, IN `in_title` VARCHAR(255), IN `in_details` VARCHAR(255), IN `in_isFood` TINYINT, IN `in_price` INT, IN `in_withdrawn` TINYINT)  NO SQL
BEGIN
UPDATE menuitems 
SET Title = in_title, Details = in_details,
IsFood = in_isFood, Price = in_price, Withdrawn = in_withdrawn
WHERE ItemId = in_itemId;
END$$

CREATE DEFINER=`ISAD251_BGage`@`%` PROCEDURE `EditOrderItemQuantity` (IN `in_orderId` INT, IN `in_itemId` INT, IN `in_newQuantity` INT, IN `in_oldQuantity` INT)  NO SQL
BEGIN
UPDATE orderdetails SET Quantity = in_newQuantity 
WHERE OrderId = in_orderId AND ItemId = in_itemId;

UPDATE stock SET Quantity = Quantity + in_oldQuantity
WHERE ItemId = in_itemId;

UPDATE stock SET Quantity = Quantity - in_newQuantity 
WHERE ItemId = in_itemId;

END$$

CREATE DEFINER=`ISAD251_BGage`@`%` PROCEDURE `GetAllMenuItems` ()  NO SQL
SELECT * FROM menuitems$$

CREATE DEFINER=`ISAD251_BGage`@`%` PROCEDURE `GetAllOrders` ()  NO SQL
SELECT * FROM orders$$

CREATE DEFINER=`ISAD251_BGage`@`%` PROCEDURE `GetAmountInStock` (IN `IN_ItemId` INT)  NO SQL
BEGIN
SELECT Quantity FROM stock WHERE ItemId = IN_ItemId;

END$$

CREATE DEFINER=`ISAD251_BGage`@`%` PROCEDURE `GetMenuItemPrice` (IN `in_itemId` INT)  NO SQL
BEGIN
SELECT Price FROM menuitems WHERE itemId = in_itemId;
END$$

CREATE DEFINER=`ISAD251_BGage`@`%` PROCEDURE `GetMenuItemsArray` ()  NO SQL
SELECT ItemId, Title, Details, IsFood, Price, Withdrawn FROM menuitems$$

CREATE DEFINER=`ISAD251_BGage`@`%` PROCEDURE `GetMenuItemTitle` (IN `in_itemId` INT)  NO SQL
BEGIN
SELECT Title FROM menuitems WHERE itemId = in_itemId;
END$$

CREATE DEFINER=`ISAD251_BGage`@`%` PROCEDURE `GetOrderDetailsByOrderId` (IN `in_orderId` INT)  NO SQL
BEGIN
SELECT * FROM orderdetails WHERE OrderId = in_orderId;
END$$

CREATE DEFINER=`ISAD251_BGage`@`%` PROCEDURE `GetOrders` ()  NO SQL
SELECT * FROM orders$$

CREATE DEFINER=`ISAD251_BGage`@`%` PROCEDURE `GetStockItems` ()  NO SQL
BEGIN
SELECT * FROM stock;
END$$

CREATE DEFINER=`ISAD251_BGage`@`%` PROCEDURE `SetStockQuantity` (IN `in_itemId` INT, IN `in_quantity` INT)  NO SQL
BEGIN
UPDATE stock SET Quantity = in_quantity WHERE ItemId = in_itemId;
END$$

CREATE DEFINER=`ISAD251_BGage`@`%` PROCEDURE `SubtractItemFromOrder` (IN `in_orderId` INT, IN `in_menuItemTitle` VARCHAR(255), IN `in_quantity` INT)  NO SQL
BEGIN

SET @ITEMID = (SELECT ItemId FROM menuitems WHERE Title = in_title);

SET @count = (SELECT COUNT(*) FROM orderdetails 
WHERE OrderId = in_orderid AND ItemId = @ITEMID);

IF @count != 0 THEN
	UPDATE orderdetails SET Quantity = Quantity - in_quantity 
    WHERE OrderId = in_orderid AND ItemId = @ITEMID;
    
    CALL UpdateStock(@ITEMID, in_quantity);
    
ELSE
	INSERT INTO orderdetails(OrderId, ItemId, Quantity) 
	VALUES(in_orderid, @ITEMID, in_quantity);
    
    CALL UpdateStock(@ITEMID, in_quantity);
END IF;

END$$

CREATE DEFINER=`ISAD251_BGage`@`%` PROCEDURE `UpdateStock` (IN `IN_ItemId` INT, IN `IN_Quantity` INT)  NO SQL
BEGIN
UPDATE stock SET Quantity = Quantity - IN_Quantity 
WHERE ItemId = IN_ItemId;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `CustomerId` int(11) NOT NULL,
  `Username` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`CustomerId`, `Username`) VALUES
(1, 'user 1'),
(2, 'user 2'),
(3, 'user 3');

-- --------------------------------------------------------

--
-- Table structure for table `menuitems`
--

CREATE TABLE `menuitems` (
  `ItemId` int(11) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `Details` varchar(255) NOT NULL,
  `IsFood` tinyint(1) NOT NULL,
  `Price` float NOT NULL,
  `Withdrawn` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `menuitems`
--

INSERT INTO `menuitems` (`ItemId`, `Title`, `Details`, `IsFood`, `Price`, `Withdrawn`) VALUES
(16, 'Wine', 'Its wine', 0, 15, 0),
(17, 'Tea', 'Hot leaf water', 0, 10, 0),
(18, 'Biscuits', 'Our biscuits are fancy crackers', 1, 3, 0),
(19, 'Crackers', 'Our crackers are regular crackers', 1, 1, 0),
(37, 'Cookies', 'These are cookies', 1, 2, 0);

--
-- Triggers `menuitems`
--
DELIMITER $$
CREATE TRIGGER `CreateStockEntry` AFTER INSERT ON `menuitems` FOR EACH ROW BEGIN
INSERT INTO stock(ItemId, Quantity) VALUES (NEW.ItemId, 0);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `DeleteOrderDetailsEntry` BEFORE DELETE ON `menuitems` FOR EACH ROW BEGIN
DELETE FROM orderdetails WHERE ItemId = OLD.ItemId;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `DeleteStockEntry` BEFORE DELETE ON `menuitems` FOR EACH ROW BEGIN
DELETE FROM stock WHERE ItemId = OLD.ItemId;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `orderdetails`
--

CREATE TABLE `orderdetails` (
  `OrderId` int(11) NOT NULL,
  `ItemId` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orderdetails`
--

INSERT INTO `orderdetails` (`OrderId`, `ItemId`, `Quantity`) VALUES
(1, 16, 5),
(1, 19, 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `OrderId` int(11) NOT NULL,
  `OrderDate` date NOT NULL,
  `CustomerId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`OrderId`, `OrderDate`, `CustomerId`) VALUES
(1, '2020-01-08', 1);

-- --------------------------------------------------------

--
-- Stand-in structure for view `ordersview`
-- (See below for the actual view)
--
CREATE TABLE `ordersview` (
`OrderId` int(11)
,`OrderDate` date
,`CustomerId` int(11)
);

-- --------------------------------------------------------

--
-- Table structure for table `stock`
--

CREATE TABLE `stock` (
  `ItemId` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `stock`
--

INSERT INTO `stock` (`ItemId`, `Quantity`) VALUES
(16, 95),
(17, 100),
(18, 99),
(19, 99),
(37, 100);

-- --------------------------------------------------------

--
-- Structure for view `ordersview`
--
DROP TABLE IF EXISTS `ordersview`;

CREATE ALGORITHM=UNDEFINED DEFINER=`ISAD251_BGage`@`%` SQL SECURITY DEFINER VIEW `ordersview`  AS  select `orders`.`OrderId` AS `OrderId`,`orders`.`OrderDate` AS `OrderDate`,`orders`.`CustomerId` AS `CustomerId` from `orders` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`CustomerId`);

--
-- Indexes for table `menuitems`
--
ALTER TABLE `menuitems`
  ADD PRIMARY KEY (`ItemId`);

--
-- Indexes for table `orderdetails`
--
ALTER TABLE `orderdetails`
  ADD PRIMARY KEY (`OrderId`,`ItemId`),
  ADD KEY `ItemId` (`ItemId`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`OrderId`),
  ADD KEY `CustomerId` (`CustomerId`);

--
-- Indexes for table `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`ItemId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `CustomerId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `menuitems`
--
ALTER TABLE `menuitems`
  MODIFY `ItemId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `OrderId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orderdetails`
--
ALTER TABLE `orderdetails`
  ADD CONSTRAINT `orderdetails_ibfk_1` FOREIGN KEY (`OrderId`) REFERENCES `orders` (`OrderId`),
  ADD CONSTRAINT `orderdetails_ibfk_2` FOREIGN KEY (`ItemId`) REFERENCES `menuitems` (`ItemId`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`CustomerId`) REFERENCES `customers` (`CustomerId`);

--
-- Constraints for table `stock`
--
ALTER TABLE `stock`
  ADD CONSTRAINT `stock_ibfk_1` FOREIGN KEY (`ItemId`) REFERENCES `menuitems` (`ItemId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
