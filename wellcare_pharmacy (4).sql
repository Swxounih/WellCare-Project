-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 04, 2026 at 11:59 AM
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
-- Database: `wellcare_pharmacy`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cartID` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `name`, `description`, `image`, `created_at`) VALUES
(1, 'Medicines & Treatments', 'Over-the-counter medicines and pain relief', 'Medicine.jpg', '2026-04-04 09:22:14'),
(2, 'Baby Kids', 'Baby care products and children wellness', 'BabyKids.jpg', '2026-04-04 09:22:14'),
(3, 'Personal Care', 'Skincare and personal hygiene products', 'PersonalCare.jpg', '2026-04-04 09:22:14'),
(4, 'Medical Supplies', 'Medical equipment and first aid supplies', 'MedicalSupp.jpg', '2026-04-04 09:22:14');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `message_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_ID` int(11) NOT NULL,
  `message_text` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `body` text DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `orderDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `totalAmount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('To Pay','To Ship','To Receive','Completed','Cancelled','Return/Refund') NOT NULL DEFAULT 'To Pay',
  `shipping_address` varchar(255) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT 'Cash on Delivery',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `orderDate`, `totalAmount`, `status`, `shipping_address`, `payment_method`, `updated_at`) VALUES
(1, 2, '2026-04-04 09:57:01', 396.00, 'To Pay', '0183 BAAY EAST LINGAYEN PANGASINAN', 'Cash on Delivery', '2026-04-04 09:57:01'),
(2, 2, '2026-04-04 09:58:51', 250.80, 'To Pay', '0183 BAAY EAST LINGAYEN PANGASINAN', 'Cash on Delivery', '2026-04-04 09:58:51');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 3, 3, 120.00),
(2, 2, 3, 1, 120.00),
(3, 2, 1, 1, 108.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `category_id`, `name`, `description`, `price`, `stock`, `image`, `is_featured`, `created_at`, `updated_at`) VALUES
(1, 1, 'Paracetamol Biogesic 500mg Tablet', 'Paracetamol Biogesic 500mg 10 Tablets - Everyday Pain and Fever Support', 108.00, 150, '0', 0, '2026-04-04 09:22:14', '2026-04-04 09:22:14'),
(2, 1, 'Paracetamol Biogesic Syrup 60ml', 'Paracetamol Biogesic for Kids 250mg/5ml Syrup 60ml - Melon Flavor', 156.00, 120, '0', 0, '2026-04-04 09:22:14', '2026-04-04 09:22:14'),
(3, 1, 'Neozep Forte Tablet', 'Neozep Forte 10 Tablet Strips - Multi-Symptom Relief for Colds, Fever & Flu', 120.00, 200, '0', 0, '2026-04-04 09:22:14', '2026-04-04 09:22:14'),
(4, 1, 'Bioflu Tablet 100s', 'Bioflu Phenylephrine HCl 100 Film-Coated Tablets - Cold & Flu Relief', 185.00, 100, '0', 0, '2026-04-04 09:22:14', '2026-04-04 09:22:14'),
(5, 1, 'Fern-C Gold 30 Capsules', 'Fern-C Gold 27+3 Pack - Vitamin C with Cholecalciferol & Zinc Capsules', 371.25, 180, '0', 0, '2026-04-04 09:22:14', '2026-04-04 09:22:14'),
(6, 2, 'Cetaphil Baby Wash 400ml', 'Cetaphil Baby Moisturizing Bath & Wash 400ml - For Sensitive Skin', 606.00, 75, '0', 0, '2026-04-04 09:22:14', '2026-04-04 09:22:14'),
(7, 3, 'NIVEA Creme 75ml', 'NIVEA Creme 75ml - All-Purpose Moisturizer for Dry Skin', 125.00, 200, '0', 0, '2026-04-04 09:22:14', '2026-04-04 09:22:14'),
(8, 3, 'NIVEA Body Lotion 400ml', 'NIVEA In-Shower Body Lotion 400ml - Moisturizes While Showering', 195.00, 150, '0', 0, '2026-04-04 09:22:14', '2026-04-04 09:22:14'),
(9, 3, 'NIVEA Soft Cream 200ml', 'NIVEA Soft Cream 200ml - Light Moisturizer for All Skin Types', 155.00, 120, '0', 0, '2026-04-04 09:22:14', '2026-04-04 09:22:14'),
(10, 4, 'Medical Oxygen Tank 10L', 'Portable Oxygen Tank 10 Liters with Regulator - For Emergency Use', 1850.00, 30, '0', 0, '2026-04-04 09:22:14', '2026-04-04 09:22:14'),
(11, 4, 'Blood Pressure Monitor Digital', 'Automatic Digital Blood Pressure Monitor - Easy Home Monitoring', 899.00, 45, '0', 0, '2026-04-04 09:22:14', '2026-04-04 09:22:14'),
(12, 4, 'First Aid Kit Complete', 'Complete First Aid Kit with Medical Supplies and Emergency Essentials', 450.00, 60, '0', 0, '2026-04-04 09:22:14', '2026-04-04 09:22:14'),
(13, 1, 'Tramadol HCl Tablets 50mg', 'Tramadol Hydrochloride 50mg Tablets - Pain Relief (Prescription Required)', 280.00, 40, '0', 0, '2026-04-04 09:22:14', '2026-04-04 09:22:14'),
(14, 1, 'Amoxicillin Capsule 500mg', 'Amoxicillin 500mg Capsules - Antibiotic (Prescription Required)', 195.00, 50, '0', 0, '2026-04-04 09:22:14', '2026-04-04 09:22:14'),
(15, 1, 'Cough Syrup 120ml', 'Cough Syrup 120ml - Effective Relief from Dry & Wet Cough', 89.50, 100, '0', 0, '2026-04-04 09:22:14', '2026-04-04 09:22:14'),
(16, 1, 'Multivitamin Plus 30 Tablets', 'Multivitamin Plus Iron & Calcium 30 Tablets - Daily Supplement', 245.00, 180, '0', 0, '2026-04-04 09:22:14', '2026-04-04 09:22:14'),
(17, 1, 'Aspirin 100 Tablets', 'Aspirin 500mg 100 Tablets - For Headache & Pain Relief', 125.00, 200, '0', 0, '2026-04-04 09:22:14', '2026-04-04 09:22:14'),
(18, 1, 'Antacid Tablets 20s', 'Antacid Tablets 20 Pieces - Quick Relief from Heartburn', 45.00, 250, '0', 0, '2026-04-04 09:22:14', '2026-04-04 09:22:14'),
(19, 1, 'Vitamin D3 Soft Gels 60s', 'Vitamin D3 1000 IU 60 Soft Gels - Bone & Immune Support', 325.00, 150, '0', 0, '2026-04-04 09:22:14', '2026-04-04 09:22:14'),
(20, 3, 'Skin Care Essentials Bundle', 'Complete skin care package with moisturizers and cleansers', 285.00, 95, '0', 0, '2026-04-04 09:22:14', '2026-04-04 09:22:14');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `username` varchar(15) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` char(15) NOT NULL,
  `address` varchar(255) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `civil_status` enum('Single','Married','Widowed','Separated') NOT NULL,
  `birthdate` date NOT NULL,
  `profile_img` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `fullname`, `username`, `email`, `password`, `phone`, `address`, `gender`, `civil_status`, `birthdate`, `profile_img`, `created_at`, `updated_at`) VALUES
(1, '', '', 'armel@gmail.com', 'Password@123', '', '', '', '', '0000-00-00', NULL, '2026-04-03 16:49:49', '2026-04-03 16:55:17'),
(2, 'Armel Cruz', 'armel123', 'armelcruz831@gmail.com', '$2y$10$t347wMORLrg0Xt67ilcYteLkpJepCxO4WqUuSy0Msr5l3H7Wsf5be', '09318388423', '0183 BAAY EAST LINGAYEN PANGASINAN', 'Male', 'Married', '2005-07-15', NULL, '2026-04-04 06:18:54', '2026-04-04 06:18:54');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `wishlist_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`wishlist_id`, `user_id`, `product_id`, `added_at`) VALUES
(3, 2, 3, '2026-04-04 09:54:59'),
(4, 2, 1, '2026-04-04 09:58:21');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cartID`),
  ADD UNIQUE KEY `unique_cart_item` (`user_id`,`product_id`),
  ADD KEY `fk_cart_prod` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `fk_msg_sender` (`sender_id`),
  ADD KEY `fk_msg_receiver` (`receiver_ID`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `fk_notif_cust` (`user_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `fk_ord_cust` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `fk_oi_order` (`order_id`),
  ADD KEY `fk_oi_prod` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `fk_prod_cat` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`wishlist_id`),
  ADD UNIQUE KEY `unique_wishlist_item` (`user_id`,`product_id`),
  ADD KEY `fk_wish_prod` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cartID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `wishlist_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `fk_cart_cust` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cart_prod` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `fk_msg_receiver` FOREIGN KEY (`receiver_ID`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_msg_sender` FOREIGN KEY (`sender_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `fk_notif_cust` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_ord_cust` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_oi_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_oi_prod` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON UPDATE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_prod_cat` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON UPDATE CASCADE;

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `fk_wish_cust` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_wish_prod` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
