-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 25, 2024 at 11:11 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `craft_treasure`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(20) NOT NULL,
  `imgName` varchar(50) NOT NULL,
  `imgUrl` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `imgName`, `imgUrl`) VALUES
(1, 'Kids Craft', 'images/paperplate.jpg'),
(2, 'Home Decor', 'images/resincover.jpg'),
(3, 'Jewellery', 'images/jewcover.jpg'),
(4, 'Craft suppliers', 'images/treadb.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `userID` int(20) NOT NULL,
  `userName` varchar(50) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`userID`, `userName`, `phone`, `address`, `email`, `password`) VALUES
(5, 'user4', '0113344556', 'Matara', 'user4@gmail.com', 'Galle@12'),
(6, 'user00', '0115566778', 'No. 124/B, New road, Kelaniya', 'john00@gmail.com', 'JohN@000'),
(7, 'user2', '0110099099', 'Negombo', 'user2@gmail.com', 'User@000');

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `messageID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `fullName` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`messageID`, `userID`, `fullName`, `email`, `message`) VALUES
(1, 7, 'Oli U.', 'user2@gmail.com', 'I didn\'t receive my order'),
(2, 7, 'Oli U.', 'user2@gmail.com', 'I didn\'t receive my order'),
(4, 5, 'Darvin Perera', 'user4@gmail.com', 'My order is bit uncolored');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `orderID` int(11) NOT NULL,
  `productID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `orderDate` date NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`orderID`, `productID`, `userID`, `orderDate`, `status`) VALUES
(1, 1, 6, '2024-09-13', 'shipped'),
(2, 1, 6, '2024-09-15', 'Processing'),
(6, 1, 7, '2024-09-17', 'Delivered'),
(11, 2, 7, '2024-09-17', 'Delivered'),
(23, 1, 7, '2024-09-25', 'Order Confirmed'),
(24, 4, 5, '2024-09-25', 'Order Confirmed'),
(26, 1, 5, '2024-09-25', 'Order Confirmed'),
(29, 5, 5, '2024-09-25', 'Order Confirmed'),
(30, 2, 5, '2024-09-25', 'Order Confirmed'),
(31, 4, 5, '2024-09-25', 'Order Confirmed'),
(32, 1, 5, '2024-09-25', 'Order Confirmed'),
(33, 5, 6, '2024-09-25', 'Order Confirmed'),
(34, 5, 6, '2024-09-25', 'Order Confirmed');

-- --------------------------------------------------------

--
-- Table structure for table `productstab`
--

CREATE TABLE `productstab` (
  `imgID` int(20) NOT NULL,
  `imgName` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `Description` text NOT NULL,
  `image1` varchar(255) NOT NULL,
  `image2` varchar(255) NOT NULL,
  `image3` varchar(255) NOT NULL,
  `imgUrl` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `productstab`
--

INSERT INTO `productstab` (`imgID`, `imgName`, `price`, `Description`, `image1`, `image2`, `image3`, `imgUrl`) VALUES
(1, 'Embroidery Beginner Starter Kit', 1199.00, '1 Linen fabric with patterns printed on it\r\n1 Needle, 1 set of cotton threads for embroidery\r\n1 Hoop size 20cm 8inch\r\nInstructions and sample picture\r\nHome decoration: The embroidery pattern can be framed in the round embroidery as home decoration after completion.\r\nBest gift: Pattern embroidery starter kit can be a perfect product for your family or friends.', 'images/embro2.jpg', 'images/embro3.jpg', 'images/bestsellcover.jpg', 'images/bestsellcover.jpg'),
(2, 'Animal Carving Craft Wood', 3700.00, 'Wood Carving Item #Handmade', 'images/animal1.jpg', 'images/animal2.jpg', 'images/newcover.jpg', 'images/newcover.jpg'),
(4, 'Fancy Candles', 530.00, 'Wax Type: 100% natural soy wax for a cleaner and longer-lasting burn.\r\nFragrance: Premium-grade essential oils, offering a wide range of soothing and refreshing scents.', 'images/candle2.jpg', 'images/candle3.jpg', 'images/candle.jpg', 'images/candle.jpg'),
(5, 'Feather Dream Catcher', 600.00, 'Handcrafted fairy dream catcher with intricate designs and delicate embellishments\r\nHanging loop for easy display\r\nCare card with tips on maintaining the dream catcher\r\nSmall gift box for presentation or gifting', 'images/dream1.jpg', 'images/dream6.jpg', 'images/dream2.jpg', 'images/dream2.jpg'),
(7, 'Felt Balls', 200.00, 'Mini Wool Felt Balls DIY decorations', 'images/balls2.jpg', 'images/balls4.jpg', 'images/balls.jpg', 'images/balls.jpg'),
(10, 'Paper Craft', 900.00, 'Paper based item\r\nMulti color\r\nGift someone', '', '', '', 'uploads/rainbow craft1.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `reviewID` int(11) NOT NULL,
  `userID` int(11) DEFAULT NULL,
  `orderID` int(11) DEFAULT NULL,
  `review` text DEFAULT NULL,
  `rate` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`reviewID`, `userID`, `orderID`, `review`, `rate`) VALUES
(4, 7, 6, 'love it', 5),
(5, 7, 11, 'Well crafted wall decor. Highly recommended.', 4);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `email`, `password`, `is_admin`) VALUES
(1, 'admin1', 'admin11@gmail.com', 'adMinx00', 1),
(5, 'user4', 'user4@gmail.com', 'Galle@123', 0),
(6, 'user00', 'john00@gmail.com', 'JohN@000', 0),
(7, 'user2', 'user2@gmail.com', 'User@000', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`messageID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`orderID`),
  ADD KEY `productID` (`productID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `productstab`
--
ALTER TABLE `productstab`
  ADD PRIMARY KEY (`imgID`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`reviewID`),
  ADD KEY `userID` (`userID`),
  ADD KEY `reviews_ibfk_2` (`orderID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `messageID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `orderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `productstab`
--
ALTER TABLE `productstab`
  MODIFY `imgID` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `reviewID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `customer`
--
ALTER TABLE `customer`
  ADD CONSTRAINT `customer_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `message_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `customer` (`userID`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`productID`) REFERENCES `productstab` (`imgID`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `customer` (`userID`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`orderID`) REFERENCES `orders` (`orderID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
