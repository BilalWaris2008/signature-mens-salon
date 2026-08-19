-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 14, 2026 at 08:02 PM
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
-- Database: `salon_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `appointment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `service_id` int(11) NOT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `status` enum('Accepted','In Progress','Completed','Cancelled') DEFAULT 'Accepted',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`appointment_id`, `user_id`, `name`, `email`, `phone`, `service_id`, `staff_id`, `appointment_date`, `appointment_time`, `status`, `created_at`) VALUES
(1, 5, 'Ahmed Farooq', 'ahmed@gmail.com', '03479098761', 1, 3, '2026-07-30', '08:00:00', 'Accepted', '2026-07-22 17:42:25'),
(2, 6, 'Rayyan Imran', 'rayyan@gmail.com', '03214790741', 17, 2, '2026-07-29', '10:00:00', 'Accepted', '2026-07-22 17:46:10'),
(3, 9, 'Fahad Shoaib', 'fahad@gmail.com', '03273124781', 12, 4, '2026-07-31', '14:00:00', 'Accepted', '2026-07-22 17:59:48'),
(4, 12, 'Shariq Shahzad', 'shariq@gmail.com', '03459029087', 36, 2, '2026-07-30', '16:00:00', 'Accepted', '2026-07-22 18:05:19');

-- --------------------------------------------------------

--
-- Table structure for table `blog`
--

CREATE TABLE `blog` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `post_date` date DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blog`
--

INSERT INTO `blog` (`id`, `title`, `content`, `image`, `post_date`, `category`, `tags`) VALUES
(1, '5 Beard Care Tips Every Gentleman Should Follow', 'A well-maintained beard enhances your appearance and confidence. Learn five essential beard care tips, including proper washing, moisturizing, trimming, and using beard oil to keep your facial hair healthy, soft, and perfectly styled every day.', 'images/blog/beardcare.jpg', '2026-07-25', 'Beard Care', 'beard,beard care,grooming,tips'),
(2, 'Why Every Man Should Use a Professional Hair Care Routine', 'Healthy hair begins with the right routine. Discover how using professional shampoo, conditioner, and nourishing products helps reduce hair damage, strengthens roots, and keeps your hairstyle looking fresh throughout the day.', 'images/blog/haircare.jpg', '2026-07-25', 'Hair Care', 'shampoo,conditioner,hair care,men'),
(3, 'Upgrade Your Style with Trending Men\'s Haircuts', 'Looking for a fresh new hairstyle? Discover the most popular men\'s haircuts of 2026, including fades, textured crops, pompadours, and modern classics. Choose a style that matches your personality and keep your look sharp all year round.', 'images/blog/haircut.png', '2026-07-25', 'Haircut', 'men\'s haircut,fade,pompadour,style'),
(4, 'How to Choose the Right Hair Styling Product?', 'Choosing the right styling product makes all the difference. Compare hair wax, pomade, clay, and gel to find the perfect option for your hair type, desired finish, and long-lasting hold without damaging your hair.', 'images/blog/hairstyle.png', '2026-07-25', 'Hair Styling', 'hair wax,pomade,clay,styling');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `blog_id` int(11) NOT NULL,
  `author` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `blog_id`, `author`, `email`, `content`, `created_at`) VALUES
(1, 1, 'Rayyan Imran', 'rayyan@gmail.com', 'Very helpful advice for keeping beards soft, clean, healthy, and attractive always.', '2026-07-25 19:01:19'),
(2, 2, 'Ali Irfan', 'ali@gmail.com', 'Excellent hair care routine for stronger, healthier, and shinier hair every day.', '2026-07-25 19:02:52'),
(3, 3, 'Shafay Khan', 'shafay@gmail.com', 'Excellent haircut guide with trendy styles for every gentleman\'s unique personality.', '2026-07-25 19:04:15'),
(4, 4, 'Shariq Shahzad', 'shariq@gmail.com', 'Amazing styling tips that made choosing the right hair product much easier.', '2026-07-25 19:05:17');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `phone`, `subject`, `message`, `submitted_at`) VALUES
(1, 'Rayyan', 'rayyan@gmail.com', '03214790741', 'Excellent Salon Experience', 'Excellent service and professional staff. Highly recommended for quality haircuts, beard grooming, and an outstanding salon experience.', '2026-07-22 17:50:39'),
(2, 'Shafay', 'shafay@gmail.com', '03215732134', 'Friendly and Professional Service', 'I had a wonderful experience at this salon. The staff was welcoming, skilled, and attentive to every detail. The service exceeded my expectations, and I left feeling confident and refreshed. Highly recommended!', '2026-07-22 17:54:59'),
(3, 'Fahad', 'fahad@gmail.com', '03273124781', 'Outstanding Service', 'The staff were courteous, the service was excellent, and the overall experience exceeded my expectations. I left feeling refreshed, confident, and completely satisfied. Highly recommended!', '2026-07-22 18:01:38'),
(4, 'Shariq', 'shariq@gmail.com', '03459029087', 'Highly Recommended Salon', 'I had an amazing experience from start to finish. The staff were professional, the atmosphere was relaxing, and the results were outstanding. I\'ll definitely be returning for my next visit!', '2026-07-22 18:07:49');

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gallery`
--

INSERT INTO `gallery` (`id`, `title`, `category`, `file_path`) VALUES
(1, 'Classic Hair Cut 1', 'hair cutting', 'images/gallery/1.png'),
(2, 'Classic Beard Cut 1', 'beard cutting', 'images/gallery/4.png'),
(3, 'Classic Hair Style 1', 'hair styling', 'images/gallery/2.png'),
(4, 'Classic Hair Styling 2', 'hair styling', 'images/gallery/6.png'),
(5, 'Classic Facial 1', 'facial', 'images/gallery/3.png'),
(6, 'Classic Hair Cut 2', 'hair cutting', 'images/gallery/5.png');

-- --------------------------------------------------------

--
-- Table structure for table `newsletter_subscribers`
--

CREATE TABLE `newsletter_subscribers` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subscribed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `newsletter_subscribers`
--

INSERT INTO `newsletter_subscribers` (`id`, `email`, `subscribed_at`) VALUES
(1, 'rayyan@gmail.com', '2026-07-22 17:47:53'),
(2, 'shafay@gmail.com', '2026-07-22 17:52:52'),
(3, 'fahad@gmail.com', '2026-07-22 17:58:14');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telephone` varchar(15) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `postal_code` varchar(20) NOT NULL,
  `country` varchar(100) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `payment_method` enum('online_payment','cod') NOT NULL DEFAULT 'online_payment',
  `status` enum('unpaid','pending','paid','packed','shipped','delivered','cancelled') DEFAULT 'unpaid',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `first_name`, `last_name`, `email`, `telephone`, `address`, `city`, `postal_code`, `country`, `total`, `payment_method`, `status`, `created_at`, `updated_at`) VALUES
(1, 6, 'Rayyan', 'Imran', 'rayyan@gmail.com', '03214790741', 'Tariq Road', 'Karachi', '75400', 'Pakistan', 8500.00, 'online_payment', 'pending', '2026-07-22 17:46:59', '2026-07-22 17:46:59'),
(2, 6, 'Rayyan', 'Imran', 'rayyan@gmail.com', '03214790741', 'Tariq Road', 'Karachi', '75400', 'Pakistan', 2200.00, 'online_payment', 'pending', '2026-07-22 17:47:19', '2026-07-22 17:47:19'),
(3, 8, 'Shafay', 'Khan', 'shafay@gmail.com', '03215732134', 'Nazimabad', 'Karachi', '74600', 'Pakistan', 2200.00, 'online_payment', 'pending', '2026-07-22 17:52:18', '2026-07-22 17:52:18'),
(4, 9, 'Fahad', 'Shoaib', 'fahad@gmail.com', '03273124781', 'Gulshan-e-Iqbal', 'Karachi', '75300', 'Pakistan', 9000.00, 'cod', 'pending', '2026-07-22 17:58:00', '2026-07-22 17:58:00'),
(5, 12, 'Shariq', 'Shahzad', 'shariq@gmail.com', '03459029087', 'Saddar', 'Karachi', '74400', 'Pakistan', 5500.00, 'cod', 'pending', '2026-07-22 18:08:33', '2026-07-22 18:08:33'),
(6, 12, 'Shariq', 'Shahzad', 'shariq@gmail.com', '03459029087', 'Saddar', 'Karachi', '74400', 'Pakistan', 4500.00, 'cod', 'pending', '2026-07-22 18:08:47', '2026-07-22 18:08:47');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `total` decimal(10,2) GENERATED ALWAYS AS (`quantity` * `price`) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `product_id`, `product_name`, `quantity`, `price`) VALUES
(1, 1, 2, 'Beard Trimmer', 1, 8500.00),
(2, 2, 13, 'Beard Comb', 1, 2200.00),
(3, 3, 12, 'Face Scrub', 1, 2200.00),
(4, 4, 8, 'Hair Mask', 1, 9000.00),
(5, 5, 9, 'Beard Oil', 1, 5500.00),
(6, 6, 15, 'Hair Gel', 1, 4500.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `stock_status` enum('in_stock','out_of_stock') DEFAULT 'in_stock',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `description`, `category`, `price`, `image_url`, `stock_status`, `created_at`, `updated_at`) VALUES
(1, 'Hair Shampoo', 'A nourishing shampoo for all hair types.', 'Hair Care', 4300.00, 'images/shop/1.jpg', 'in_stock', '2026-05-19 10:36:34', '2026-07-14 19:15:49'),
(2, 'Beard Trimmer', 'Precision beard trimmer for all styles.', 'Beard Care', 8500.00, 'images/shop/2.jpg', 'in_stock', '2026-05-19 10:36:34', '2026-07-14 19:16:02'),
(3, 'Shaving Cream', 'Rich shaving cream for a smooth, comfortable shave.', 'Beard Care', 2500.00, 'images/shop/3.jpg', 'in_stock', '2026-05-19 10:36:34', '2026-07-22 11:06:49'),
(4, 'Face Wash', 'Deep-cleansing face wash for daily use.', 'Skincare', 3500.00, 'images/shop/4.jpg', 'out_of_stock', '2026-05-19 10:36:34', '2026-07-22 11:07:01'),
(5, 'Hair Spray', 'Volumizing hair spray with extra hold.', 'Hair Care', 3400.00, 'images/shop/5.jpg', 'in_stock', '2026-05-19 10:36:34', '2026-07-14 19:16:17'),
(6, 'Beard Wax', 'Beard wax to style and shape facial hair.', 'Beard Care', 5500.00, 'images/shop/6.jpg', 'in_stock', '2026-05-19 10:36:34', '2026-07-14 19:16:32'),
(7, 'Hair Serum', 'Shine-enhancing serum for smooth hair.', 'Hair Care', 8500.00, 'images/shop/7.jpg', 'in_stock', '2026-05-19 10:36:34', '2026-07-14 19:16:43'),
(8, 'Hair Mask', 'Deep conditioning hair mask for damaged hair.', 'Hair Care', 9000.00, 'images/shop/8.jpg', 'in_stock', '2026-05-19 10:36:34', '2026-07-14 19:17:04'),
(9, 'Beard Oil', 'Nourishing beard oil to soften and condition.', 'Beard Care', 5500.00, 'images/shop/9.jpg', 'in_stock', '2026-05-19 10:36:34', '2026-07-14 19:17:16'),
(10, 'Hair Shining Oil', 'Adds shine and smoothness to hair.', 'Hair Care', 7800.00, 'images/shop/10.jpg', 'in_stock', '2026-05-19 10:36:34', '2026-07-14 19:17:29'),
(11, 'Electric Shaver', 'High-performance electric shaver for clean cuts.', 'Beard Tools', 14500.00, 'images/shop/11.jpg', 'out_of_stock', '2026-05-19 10:36:34', '2026-07-14 19:17:49'),
(12, 'Face Scrub', 'Exfoliating scrub that removes dead skin cells.', 'Skincare', 2200.00, 'images/shop/12.jpg', 'in_stock', '2026-05-19 10:36:34', '2026-07-22 11:07:13'),
(13, 'Beard Comb', 'Wooden comb designed specifically for beards.', 'Beard Tools', 2200.00, 'images/shop/13.jpg', 'in_stock', '2026-05-19 10:36:34', '2026-07-14 19:18:01'),
(14, 'Hair Clippers', 'Professional hair clippers for salon-quality cuts.', 'Hair Tools', 12500.00, 'images/shop/14.jpg', 'in_stock', '2026-05-19 10:36:34', '2026-07-14 19:18:14'),
(15, 'Hair Gel', 'Strong hold hair gel for all-day control.', 'Hair Care', 4500.00, 'images/shop/15.jpg', 'in_stock', '2026-05-19 10:36:34', '2026-07-14 19:18:23');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `service_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `member_price` decimal(10,2) NOT NULL,
  `duration` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`service_id`, `name`, `category`, `description`, `price`, `member_price`, `duration`, `image`, `created_at`, `updated_at`) VALUES
(1, 'Classic Haircut', 'Haircuts', 'Traditional haircut with clean finishing.', 2500.00, 2300.00, 30, 'classic-haircut.webp', '2026-05-19 10:36:33', '2026-08-13 18:51:08'),
(2, 'Skin Fade', 'Haircuts', 'Modern skin fade with precision blending.', 3000.00, 2800.00, 40, 'skin-fade.webp', '2026-05-19 10:36:33', '2026-08-13 18:51:21'),
(3, 'Taper Fade', 'Haircuts', 'Stylish taper fade for a sharp appearance.', 2800.00, 2600.00, 35, 'taper-fade.webp', '2026-05-19 10:36:33', '2026-08-13 18:51:30'),
(4, 'Crew Cut', 'Haircuts', 'Short and clean military-inspired haircut.', 2200.00, 2000.00, 25, 'crew-cut.webp', '2026-05-19 10:36:33', '2026-08-13 18:51:42'),
(5, 'Buzz Cut', 'Haircuts', 'Low-maintenance clipper haircut.', 1800.00, 1600.00, 20, 'buzz-cut.webp', '2026-05-19 10:36:33', '2026-08-13 18:51:49'),
(6, 'Pompadour Cut', 'Haircuts', 'Classic pompadour with volume and style.', 3200.00, 3000.00, 45, 'pompadour-cut.webp', '2026-05-19 10:36:33', '2026-08-13 18:51:58'),
(7, 'Textured Crop', 'Haircuts', 'Trendy textured haircut with natural finish.', 3000.00, 2800.00, 40, 'textured-crop.webp', '2026-05-19 10:36:33', '2026-08-13 18:53:08'),
(8, 'Undercut Style', 'Haircuts', 'Modern undercut with customized styling.', 3200.00, 3000.00, 45, 'undercut-style.webp', '2026-05-19 10:36:33', '2026-08-13 18:53:38'),
(9, 'Long Hair Trim', 'Haircuts', 'Trim and reshape long hairstyles.', 2200.00, 2000.00, 30, 'long-hair-trim.webp', '2026-05-19 10:36:33', '2026-08-13 18:54:08'),
(10, 'Kids Haircut', 'Haircuts', 'Comfortable haircut for children.', 1800.00, 1600.00, 25, 'kids-haircut.webp', '2026-05-19 10:36:33', '2026-08-13 18:54:36'),
(11, 'Beard Trim', 'Beard Grooming', 'Precision beard trimming and shaping.', 1200.00, 1000.00, 20, 'beard-trim.webp', '2026-05-19 10:36:33', '2026-08-14 14:57:56'),
(12, 'Beard Styling', 'Beard Grooming', 'Professional beard styling and detailing.', 1800.00, 1600.00, 30, 'beard-styling.webp', '2026-05-19 10:36:33', '2026-08-14 15:00:11'),
(13, 'Royal Beard Grooming', 'Beard Grooming', 'Complete beard care with premium finish.', 2500.00, 2300.00, 40, 'royal-beard-grooming.webp', '2026-05-19 10:36:33', '2026-08-14 15:02:07'),
(14, 'Hot Towel Shave', 'Beard Grooming', 'Traditional hot towel shave experience.', 2200.00, 2000.00, 35, 'hot-towel-shave.webp', '2026-05-19 10:36:33', '2026-08-14 15:07:58'),
(15, 'Razor Line-Up', 'Beard Grooming', 'Sharp beard and hairline detailing.', 1000.00, 900.00, 15, 'razor-lineup.webp', '2026-05-19 10:36:33', '2026-08-14 15:08:11'),
(16, 'Moustache Styling', 'Beard Grooming', 'Trim and shape your moustache.', 800.00, 700.00, 15, 'moustache-styling.webp', '2026-05-19 10:36:33', '2026-08-14 15:08:21'),
(17, 'Hair Wash', 'Hair Care', 'Deep cleansing with premium shampoo.', 800.00, 700.00, 15, 'hair-wash.webp', '2026-05-19 10:36:33', '2026-08-14 15:08:34'),
(18, 'Hair Spa', 'Hair Care', 'Nourishing treatment for healthy hair.', 3200.00, 3000.00, 45, 'hair-spa.webp', '2026-05-19 10:36:33', '2026-08-14 15:13:27'),
(19, 'Deep Conditioning', 'Hair Care', 'Moisturizing treatment for dry hair.', 2500.00, 2300.00, 40, 'deep-conditioning.webp', '2026-05-19 10:36:33', '2026-08-14 15:13:40'),
(20, 'Anti-Dandruff Treatment', 'Hair Care', 'Professional scalp treatment.', 3000.00, 2800.00, 45, 'anti-dandruff-treatment.webp', '2026-05-19 10:36:33', '2026-08-14 15:13:51'),
(21, 'Hair Smoothening', 'Hair Care', 'Smooth and manageable hair treatment.', 7000.00, 6600.00, 60, 'hair-smoothening.webp', '2026-05-19 10:36:33', '2026-08-14 15:18:17'),
(22, 'Keratin Treatment', 'Hair Care', 'Keratin therapy for frizz-free hair.', 12000.00, 11500.00, 120, 'keratin-treatment.webp', '2026-05-19 10:36:33', '2026-08-14 15:18:28'),
(23, 'Hair Coloring', 'Hair Color', 'Full hair coloring with premium products.', 5000.00, 4700.00, 90, 'hair-coloring.webp', '2026-05-19 10:36:33', '2026-08-14 15:18:39'),
(24, 'Grey Coverage', 'Hair Color', 'Natural grey hair coverage.', 4000.00, 3800.00, 60, 'grey-coverage.webp', '2026-05-19 10:36:33', '2026-08-14 15:23:57'),
(25, 'Beard Coloring', 'Hair Color', 'Color enhancement for beard.', 2200.00, 2000.00, 30, 'beard-coloring.webp', '2026-05-19 10:36:33', '2026-08-14 15:24:10'),
(26, 'Highlights', 'Hair Color', 'Stylish hair highlights.', 6500.00, 6200.00, 120, 'highlights.webp', '2026-05-19 10:36:33', '2026-08-14 15:24:21'),
(27, 'Face Cleanup', 'Facial', 'Refreshing facial cleanup for men.', 2500.00, 2300.00, 40, 'face-cleanup.webp', '2026-05-19 10:36:33', '2026-08-14 15:24:29'),
(28, 'Men\'s Facial', 'Facial', 'Deep cleansing facial treatment.', 4500.00, 4200.00, 60, 'mens-facial.webp', '2026-05-19 10:36:33', '2026-08-14 15:30:06'),
(29, 'Gold Facial', 'Facial', 'Luxury gold facial for glowing skin.', 6000.00, 5700.00, 75, 'gold-facial.webp', '2026-05-19 10:36:33', '2026-08-14 15:30:17'),
(30, 'Charcoal Facial', 'Facial', 'Detoxifying charcoal facial.', 5000.00, 4700.00, 60, 'charcoal-facial.webp', '2026-05-19 10:36:33', '2026-08-14 15:30:25'),
(31, 'Head Massage', 'Massage', 'Relaxing oil head massage.', 1800.00, 1600.00, 30, 'head-massage.webp', '2026-05-19 10:36:33', '2026-08-14 15:39:42'),
(32, 'Shoulder Massage', 'Massage', 'Stress-relieving shoulder massage.', 2200.00, 2000.00, 30, 'shoulder-massage.webp', '2026-05-19 10:36:33', '2026-08-14 15:39:51'),
(33, 'Foot Massage', 'Massage', 'Refreshing foot relaxation therapy.', 2500.00, 2300.00, 40, 'foot-massage.webp', '2026-05-19 10:36:33', '2026-08-14 15:40:00'),
(34, 'Premium Grooming Package', 'Packages', 'Haircut, beard, wash, and styling.', 6000.00, 5700.00, 90, 'premium-grooming-package.webp', '2026-05-19 10:36:33', '2026-08-14 15:44:14'),
(35, 'Executive Grooming Package', 'Packages', 'Haircut, hair wash, relaxing massage and hair styling', 9000.00, 8500.00, 150, 'executive-grooming-package.webp', '2026-05-19 10:36:33', '2026-08-14 17:54:54'),
(36, 'Royal Gentleman Package', 'Packages', 'Haircut, shave, facial, massage, and styling.', 10000.00, 9500.00, 150, 'royal-gentleman-package.webp', '2026-05-19 10:36:33', '2026-08-14 17:59:55');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `telephone` varchar(15) NOT NULL,
  `fax` varchar(50) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT NULL,
  `role` enum('user','admin','staff') DEFAULT 'user',
  `specialization` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `email`, `password`, `telephone`, `fax`, `address`, `city`, `country`, `postal_code`, `role`, `specialization`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'User1', 'sufiyanshahiddev@gmail.com', '$2y$10$.MoAxKtKImVbhoHpXE.lQ.t78YXSL/99Cl6Y0frB.8gOw2ucGQlSS', '03214789246', '', NULL, NULL, NULL, NULL, 'admin', NULL, '2026-07-22 10:15:14', '2026-07-22 10:53:03'),
(2, 'Azan', 'Khan', 'roghani@gmail.com', '$2y$10$52Ul.YTsgfArPcOC8RTDr.gVT.9sictSTSwCInYM70oTF4.2iAZvW', '03457890246', NULL, NULL, NULL, NULL, NULL, 'staff', NULL, '2026-07-22 10:17:48', '2026-07-22 10:17:48'),
(3, 'Hamdan', 'Khan', 'hamdan@gmail.com', '$2y$10$uXaPFE3xODHU4ri0D1Efbe3HlY6zegYh9A.sUqdG4SezfgLli3I96', '03213709876', NULL, NULL, NULL, NULL, NULL, 'staff', NULL, '2026-07-22 10:18:41', '2026-07-22 10:18:41'),
(4, 'Bilal', 'Waris', 'bilal@gmail.com', '$2y$10$oxZtiQfQDocJUBy7o.BlCeLsh1DPv3/t5hF1s/l.mH15wcEBn5i.K', '03279076893', NULL, NULL, NULL, NULL, NULL, 'staff', NULL, '2026-07-22 10:19:18', '2026-07-22 10:19:18'),
(5, 'Ahmed', 'Farooq', 'ahmed@gmail.com', '$2y$10$bV4H/mIQODdgI55AzR2DYuREqGXrVmLgQ..zCCHlzahiDP7kzs2Xa', '03479098761', NULL, 'Bahadurabad', 'Karachi', 'Pakistan', '74200', 'user', NULL, '2026-07-22 10:20:06', '2026-07-22 11:42:54'),
(6, 'Rayyan', 'Imran', 'rayyan@gmail.com', '$2y$10$3JaVqFpRbKWwj/NngOJC5eQsQgBu67Urezk8793CghQViDbKuDFvi', '03214790741', NULL, 'Tariq Road', 'Karachi', 'Pakistan', '75400', 'user', NULL, '2026-07-22 10:21:03', '2026-07-22 15:32:17'),
(7, 'Ali', 'Irfan', 'ali@gmail.com', '$2y$10$LiYe6aUXBfYaBxQFYkmTUOMkAZBye62InsP0geU2nEfGBEv1g99AW', '03379014908', NULL, 'DHA Phase 2', 'Karachi', 'Pakistan', '75500', 'user', NULL, '2026-07-22 10:21:49', '2026-07-22 15:33:25'),
(8, 'Shafay', 'Khan', 'shafay@gmail.com', '$2y$10$/BOO8/.5SXTlhDzH5KsyKe1KydDsyAVb5oOt8rE7LeLXSQQtq4FnW', '03215732134', '', 'Nazimabad', 'Karachi', 'Pakistan', '74600', 'user', NULL, '2026-07-22 10:22:24', '2026-07-22 17:51:41'),
(9, 'Fahad', 'Shoaib', 'fahad@gmail.com', '$2y$10$4VKoHPVXCjLfV92MVPqTbOWwDb2XMEircgnyEaMBtkTVes7.qGtlm', '03273124781', NULL, 'Gulshan-e-Iqbal', 'Karachi', 'Pakistan', '75300', 'user', NULL, '2026-07-22 10:23:32', '2026-07-22 15:37:08'),
(10, 'Yousuf', 'Ali', 'yousuf@gmail.com', '$2y$10$1KPscBZPoSnJroahrNVSVOHTC558rxD1yRDlzaXqipV.LfBClvWeO', '03903782156', NULL, 'Gulistan-e-Jauhar', 'Karachi', 'Pakistan', '75290', 'user', NULL, '2026-07-22 10:24:40', '2026-07-22 15:42:23'),
(11, 'Faizan', 'Younus', 'faizan@gmail.com', '$2y$10$WhQvC2Y.6knIOBwaeMXpeu1ohMG35bAJHNLyRlnGl3pPBkK8Pp8aK', '03879347126', NULL, 'Dhoraji', 'Karachi', 'Pakistan', '74800', 'user', NULL, '2026-07-22 10:26:38', '2026-07-22 15:44:05'),
(12, 'Shariq', 'Shahzad', 'shariq@gmail.com', '$2y$10$uMTfeiyWqxU24IYwmCHBneTrISLpLI8zqG/6zhd189u9cLi3adZ7O', '03459029087', NULL, 'Saddar', 'Karachi', 'Pakistan', '74400', 'user', NULL, '2026-07-22 10:27:26', '2026-07-22 15:45:29');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `wishlist_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`wishlist_id`, `user_id`, `product_id`, `date_added`) VALUES
(1, 6, 4, '2026-07-22 17:47:37'),
(2, 8, 5, '2026-07-22 17:51:54'),
(3, 9, 1, '2026-07-22 17:58:07'),
(4, 12, 2, '2026-07-22 18:09:46');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`appointment_id`),
  ADD KEY `service_id` (`service_id`),
  ADD KEY `staff_id` (`staff_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `blog`
--
ALTER TABLE `blog`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blog_id` (`blog_id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `newsletter_subscribers`
--
ALTER TABLE `newsletter_subscribers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`service_id`);

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
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `appointment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `blog`
--
ALTER TABLE `blog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `newsletter_subscribers`
--
ALTER TABLE `newsletter_subscribers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `wishlist_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `services` (`service_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`staff_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `appointments_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`blog_id`) REFERENCES `blog` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`);

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
