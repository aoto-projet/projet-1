-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 06, 2024 at 03:16 AM
-- Server version: 8.0.17
-- PHP Version: 7.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `room_lingling`
--

-- --------------------------------------------------------

--
-- Table structure for table `add_billdeposit`
--

CREATE TABLE `add_billdeposit` (
  `id` int(20) NOT NULL,
  `active_id` int(11) NOT NULL,
  `bill_number` varchar(50) NOT NULL,
  `room_number` varchar(50) NOT NULL,
  `print_date` varchar(50) DEFAULT NULL,
  `name_tenant` varchar(50) NOT NULL,
  `room_zone` varchar(50) NOT NULL,
  `phone_number` varchar(10) NOT NULL,
  `check_in_date` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `credit_rent` varchar(50) NOT NULL,
  `advance_rent` varchar(50) NOT NULL,
  `credit_unit` int(11) NOT NULL,
  `advance_unit` int(11) NOT NULL,
  `sum_credit` int(11) NOT NULL,
  `sum_advance` int(11) NOT NULL,
  `grand_total` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `add_billdeposit`
--

INSERT INTO `add_billdeposit` (`id`, `active_id`, `bill_number`, `room_number`, `print_date`, `name_tenant`, `room_zone`, `phone_number`, `check_in_date`, `credit_rent`, `advance_rent`, `credit_unit`, `advance_unit`, `sum_credit`, `sum_advance`, `grand_total`) VALUES
(27, 11, '001', 'A03', '25/09/2024 ', 'นาย ธนพล พิชญะ', 'โซนA', '0623456789', '13/08/2024', '200.00', '500.00', 1, 1, 200, 500, 700),
(28, 11, '003', 'A03', '25/09/2024 ', 'นาย ธนพล พิชญะ', 'โซนA', '0623456789', '13/08/2024', '200.00', '500.00', 1, 1, 200, 500, 700),
(29, 11, '001', 'A03', '03/10/2024 ', 'นาย ธนพล พิชญะ', 'โซนA', '0623456789', '13/08/2024', '3500.00', '7000.00', 1, 1, 3500, 7000, 10500),
(30, 13, '002', 'A01', '03/10/2024 ', 'นายทดสอบ ', 'โซนA', '05444454', '02/10/2024', '3500.00', '7000.00', 1, 1, 3500, 7000, 10500);

-- --------------------------------------------------------

--
-- Table structure for table `bill`
--

CREATE TABLE `bill` (
  `id` int(11) NOT NULL,
  `active_id` int(11) NOT NULL,
  `room_number` varchar(10) NOT NULL,
  `loop_bill` varchar(20) NOT NULL,
  `rent` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `water_meter` int(11) NOT NULL,
  `last_water` int(11) NOT NULL,
  `unit_water` int(11) NOT NULL,
  `price_unit` int(11) NOT NULL,
  `sum_water` int(11) NOT NULL,
  `electricity_meter` int(11) NOT NULL,
  `last_meter` int(11) NOT NULL,
  `unit_electricity` int(11) NOT NULL,
  `price_electricity` int(11) NOT NULL,
  `sum_electricity` int(11) NOT NULL,
  `sum` int(11) NOT NULL,
  `day_process` datetime DEFAULT NULL,
  `status` enum('ยังไม่จ่าย','จ่ายแล้ว','','') NOT NULL,
  `sleep` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `bill`
--

INSERT INTO `bill` (`id`, `active_id`, `room_number`, `loop_bill`, `rent`, `name`, `water_meter`, `last_water`, `unit_water`, `price_unit`, `sum_water`, `electricity_meter`, `last_meter`, `unit_electricity`, `price_electricity`, `sum_electricity`, `sum`, `day_process`, `status`, `sleep`) VALUES
(3, 1, 'A01', 'มกราคม', 3500, 'มี', 10, 0, 10, 4, 40, 10, 0, 10, 5, 50, 3590, '0000-00-00 00:00:00', 'ยังไม่จ่าย', '5.png'),
(8, 10, 'A08', 'มกราคม', 3500, 'กำ', 10, 0, 10, 4, 40, 10, 0, 10, 5, 50, 3590, '2024-09-26 07:57:06', 'ยังไม่จ่าย', ''),
(13, 5, 'A03', 'มกราคม', 3500, 'รอ', 10, 0, 10, 4, 40, 10, 0, 10, 5, 50, 3590, NULL, 'ยังไม่จ่าย', ''),
(14, 7, 'A05', 'มกราคม', 3500, 'ทิว', 10, 0, 10, 4, 40, 10, 0, 10, 5, 50, 3590, NULL, 'ยังไม่จ่าย', ''),
(15, 6, 'A04', 'มกราคม', 3500, 'มอ', 10, 0, 10, 4, 40, 10, 0, 10, 5, 50, 3590, NULL, 'ยังไม่จ่าย', ''),
(16, 8, 'A06', 'มกราคม', 3500, 'หา', 10, 0, 10, 4, 40, 10, 0, 10, 5, 50, 3590, '2024-10-03 00:00:00', 'ยังไม่จ่าย', 'logo coding.png'),
(22, 9, 'A07', 'มกราคม', 3500, 'กา', 10, 0, 10, 4, 40, 10, 0, 10, 5, 50, 3590, '0000-00-00 00:00:00', 'ยังไม่จ่าย', 'test_DFD=1.png'),
(23, 11, 'A09', 'มกราคม', 3500, 'ม', 10, 0, 10, 4, 40, 10, 0, 10, 5, 50, 3590, NULL, 'ยังไม่จ่าย', ''),
(25, 13, 'B01', 'มกราคม', 3000, '555', 10, 0, 10, 4, 40, 10, 0, 10, 5, 50, 3090, NULL, 'ยังไม่จ่าย', ''),
(27, 14, 'B02', 'มกราคม', 3000, '55', 10, 0, 10, 4, 40, 10, 0, 10, 5, 50, 3090, NULL, 'ยังไม่จ่าย', ''),
(28, 12, 'A10', 'มกราคม', 3500, '555', 10, 0, 10, 4, 40, 10, 0, 10, 5, 50, 3590, '2024-09-28 09:31:55', 'ยังไม่จ่าย', 'logo coding.png'),
(29, 15, 'B03', 'มกราคม', 3000, 'นนน', 10, 0, 10, 4, 40, 10, 0, 10, 5, 50, 3090, NULL, 'ยังไม่จ่าย', ''),
(30, 16, 'B04', 'มกราคม', 3000, 'กกกก', 10, 0, 10, 4, 40, 10, 0, 10, 5, 50, 3090, NULL, 'ยังไม่จ่าย', ''),
(31, 17, 'B05', 'มกราคม', 3000, 'กกกกก', 10, 0, 10, 4, 40, 10, 0, 10, 5, 50, 3090, NULL, 'ยังไม่จ่าย', 'logo coding.png'),
(37, 3, 'A02', 'มกราคม', 3500, 'นาย มอส ทอสอบ', 10, 0, 10, 4, 40, 10, 0, 10, 5, 50, 3590, '2024-09-28 10:26:50', 'ยังไม่จ่าย', '5.png'),
(40, 3, 'A02', 'กุมภาพันธ์', 3500, 'นายทดสอบ', 20, 10, 10, 4, 40, 20, 10, 10, 5, 50, 3590, '2024-10-06 09:42:09', 'จ่ายแล้ว', 'download.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `chat`
--

CREATE TABLE `chat` (
  `id` int(11) NOT NULL,
  `active_admin` int(20) DEFAULT NULL,
  `send_admin` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `active_user` int(20) DEFAULT NULL,
  `send_user` varchar(20) DEFAULT NULL,
  `active_bill` int(20) DEFAULT NULL,
  `text` text NOT NULL,
  `time` timestamp(6) NOT NULL,
  `status` enum('ยังไม่แก้ไข','แก้ไขแล้ว','','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `chat`
--

INSERT INTO `chat` (`id`, `active_admin`, `send_admin`, `active_user`, `send_user`, `active_bill`, `text`, `time`, `status`) VALUES
(10, 1, '', NULL, '2', NULL, 'รักนะ', '2024-10-05 04:22:57.000000', 'แก้ไขแล้ว'),
(11, 1, '', NULL, '2', NULL, 'ห้องทำครัวชำรุด', '2024-10-05 05:09:35.000000', 'แก้ไขแล้ว'),
(14, 3, '', NULL, '2', NULL, 'แก้ไช', '2024-10-05 05:21:38.000000', 'แก้ไขแล้ว'),
(15, 0, '', NULL, '2', NULL, 'ระมัดระวัง', '2024-10-05 05:40:32.000000', 'ยังไม่แก้ไข'),
(16, 3, '', NULL, '2', NULL, '555555', '2024-10-05 05:40:48.000000', 'แก้ไขแล้ว'),
(17, 3, '', NULL, '2', NULL, 'รักนะ', '2024-10-05 05:53:38.000000', 'แก้ไขแล้ว'),
(22, NULL, '3', 1, NULL, 15, 'กรุณาจ่ายบิล', '2024-10-05 15:39:59.000000', 'ยังไม่แก้ไข'),
(23, 3, NULL, NULL, '2', NULL, '5566223\r\n2365+65+62\r\n629+6', '2024-10-05 15:41:34.000000', 'แก้ไขแล้ว'),
(25, 3, NULL, NULL, '2', NULL, 'ขี้บ่ออก', '2024-10-06 02:45:50.000000', 'แก้ไขแล้ว');

-- --------------------------------------------------------

--
-- Table structure for table `getout`
--

CREATE TABLE `getout` (
  `id` int(11) NOT NULL,
  `room_number` varchar(10) NOT NULL,
  `national_id` varchar(13) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `emergency_contact` text NOT NULL,
  `day` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `getout`
--

INSERT INTO `getout` (`id`, `room_number`, `national_id`, `name`, `phone_number`, `emergency_contact`, `day`) VALUES
(6, 'A01', '55555', '555555', '06555', '55555', '2024-09-17'),
(7, 'A01', '55555', '555555', '06555', '55555', '2024-09-17'),
(8, 'B01', '144444444', '6219195', '2156+4+654', '5546+25656', '2024-09-23'),
(10, 'A02', '153200000001', ' นาย กฤษฎา สมิทธิ์', ' 0612345678', 'ผู้ติดต่อกรณีฉุกเฉิน: นาย ชาญชัย พิชญะ\r\nเบอร์ฉุกเฉิน: 0634567890', '2024-09-16');

-- --------------------------------------------------------

--
-- Table structure for table `guests`
--

CREATE TABLE `guests` (
  `guests_id` int(11) NOT NULL,
  `room_number` varchar(50) NOT NULL,
  `national_id` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `emergency_contact` varchar(100) NOT NULL,
  `check_in_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `guests`
--

INSERT INTO `guests` (`guests_id`, `room_number`, `national_id`, `name`, `phone_number`, `emergency_contact`, `check_in_date`) VALUES
(11, 'A03', '153200000002', 'นาย ธนพล พิชญะ', '0623456789', 'ผู้ติดต่อกรณีฉุกเฉิน: นาย ชาญชัย พิชญะ\r\nเบอร์ฉุกเฉิน: 0634567890', '2024-08-13'),
(12, 'A05', '143323623', 'นายใจดี ดีแท้ๆ', '0565656256', 'fkbkllnk;f;,b', '2024-09-19'),
(13, 'A01', '1566666666', 'นายทดสอบ ', '05444454', 'หอหกดหกด', '2024-10-02');

-- --------------------------------------------------------

--
-- Table structure for table `rate`
--

CREATE TABLE `rate` (
  `id` int(11) UNSIGNED NOT NULL,
  `water_rate` decimal(10,2) NOT NULL,
  `electricity_rate` decimal(10,2) NOT NULL,
  `effective_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `rate`
--

INSERT INTO `rate` (`id`, `water_rate`, `electricity_rate`, `effective_date`) VALUES
(1, '4.00', '5.00', '2024-09-12');

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE `room` (
  `room_id` int(11) NOT NULL,
  `room_number` varchar(50) NOT NULL,
  `room_zone` varchar(50) NOT NULL,
  `room_status` enum('occupied','available','','') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`room_id`, `room_number`, `room_zone`, `room_status`) VALUES
(1, 'A01', 'โซนA', 'occupied'),
(3, 'A02', 'โซนA', 'occupied'),
(5, 'A03', 'โซนA', 'occupied'),
(6, 'A04', 'โซนA', 'available'),
(7, 'A05', 'โซนA', 'occupied'),
(8, 'A06', 'โซนA', 'available'),
(9, 'A07', 'โซนA', 'available'),
(10, 'A08', 'โซนA', 'available'),
(11, 'A09', 'โซนA', 'available'),
(12, 'A10', 'โซนA', 'available'),
(13, 'B01', 'โซนB', 'available'),
(14, 'B02', 'โซนB', 'available'),
(15, 'B03', 'โซนB', 'available'),
(16, 'B04', 'โซนB', 'available'),
(17, 'B05', 'โซนB', 'available');

-- --------------------------------------------------------

--
-- Table structure for table `room_types`
--

CREATE TABLE `room_types` (
  `room_type_id` int(11) UNSIGNED NOT NULL,
  `type_zone` varchar(50) NOT NULL,
  `room_description` text,
  `monthly_rent` decimal(10,2) NOT NULL,
  `advance_rent` decimal(10,2) NOT NULL,
  `credit_rent` decimal(10,2) NOT NULL,
  `room_image` varchar(255) DEFAULT NULL,
  `gallery_images` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `room_types`
--

INSERT INTO `room_types` (`room_type_id`, `type_zone`, `room_description`, `monthly_rent`, `advance_rent`, `credit_rent`, `room_image`, `gallery_images`) VALUES
(1, 'โซนA', '<h2 style=\"font-style:italic\"><strong>โซนA</strong></h2>\r\n\r\n<p><strong>*ห้องพัดลม</strong></p>\r\n\r\n<p><strong>ทีวี , ตู้เย็น</strong></p>\r\n', '3500.00', '7000.00', '3500.00', '8ff04a65-3a1b-40bd-be94-81142d1f7a41.jpg', ',0fcc9909-ca3a-43af-88d6-2896e31cb28f.jpg,4d7d1002-ebe7-4a46-80f3-9137428c2661.jpg,4f158d97-9c10-4f4a-b261-e49f4db99ee3.jpg,6cbf3e3e-3681-457e-acfb-d5b09bf0f1b1.jpg,46e683b9-adc0-4d24-9d5b-1d624bc38f60.jpg,78d520e6-f6a9-4036-9eb3-2f050323ae24.jpg'),
(2, 'โซนB', '<h2 style=\"font-style:italic\"><em><strong>โซน B</strong></em></h2>\r\n\r\n<p><em><strong>ห้องพักดี มีระดับ</strong></em></p>\r\n\r\n<p><em><strong>ทีวีตู้เย็น</strong></em></p>\r\n', '3000.00', '6000.00', '3000.00', '15d7629b-1cac-4c84-9eb2-e7c3456bd548.jpg', ',15d7629b-1cac-4c84-9eb2-e7c3456bd548.jpg,46e683b9-adc0-4d24-9d5b-1d624bc38f60.jpg,a9c5ef9b-1694-410d-b5bb-900a05bb27e4.jpg,b7c74436-f049-43a7-91e7-65717b442fd4.jpg,bdd3aca5-0b9b-4066-9e45-9c84b8ff23bc.jpg,c0b0e0d9-ab27-4f45-b268-60074c8f34e7.jpg,c266eb98-592a-4b1c-bc42-476051c04fca.jpg,d8825605-c8c0-44a0-adfe-07d9789f6d83.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `urole` enum('user','admin') NOT NULL DEFAULT 'user',
  `messenger_bill` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `active_bill` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `password`, `created_at`, `urole`, `messenger_bill`, `active_bill`) VALUES
(1, 'บุญมา', 'ดวงใจ', 'aa@gmail.com', '$2y$10$iMlHZ532Cnb3EClcXSAH1eO4o2MZ77B1kk8p4S5psm6Ke79BYn2d6', '2024-09-11 15:42:12', 'admin', '', '11'),
(2, 'ใจดี', 'ยึดมั่น', 'bb@gmail.com', '$2y$10$FGT/zsVv7y8S2DLIHU2.x.iLqlA42aQwpuId.iG99YadM5iI8G6Gm', '2024-09-12 05:04:11', 'user', '', '39'),
(3, 'ทดสอบ', 'ทดสอบ', 'sdr@gmail.com', '$2y$10$nldhshcezXYv/ESTMgg07uPpIU7WgjvN0EkkllKZVONaoGNZQmJPC', '2024-10-05 05:11:13', 'admin', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `add_billdeposit`
--
ALTER TABLE `add_billdeposit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bill`
--
ALTER TABLE `bill`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `getout`
--
ALTER TABLE `getout`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `guests`
--
ALTER TABLE `guests`
  ADD PRIMARY KEY (`guests_id`),
  ADD KEY `room_number` (`room_number`);

--
-- Indexes for table `rate`
--
ALTER TABLE `rate`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_effective_date` (`effective_date`);

--
-- Indexes for table `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`room_id`),
  ADD KEY `fk_room_zone` (`room_zone`),
  ADD KEY `idx_room_number` (`room_number`);

--
-- Indexes for table `room_types`
--
ALTER TABLE `room_types`
  ADD PRIMARY KEY (`room_type_id`),
  ADD KEY `idx_type_zone` (`type_zone`);

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
-- AUTO_INCREMENT for table `add_billdeposit`
--
ALTER TABLE `add_billdeposit`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `bill`
--
ALTER TABLE `bill`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `chat`
--
ALTER TABLE `chat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `guests`
--
ALTER TABLE `guests`
  MODIFY `guests_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `rate`
--
ALTER TABLE `rate`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `room`
--
ALTER TABLE `room`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `room_types`
--
ALTER TABLE `room_types`
  MODIFY `room_type_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `guests`
--
ALTER TABLE `guests`
  ADD CONSTRAINT `guests_ibfk_1` FOREIGN KEY (`room_number`) REFERENCES `room` (`room_number`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `room`
--
ALTER TABLE `room`
  ADD CONSTRAINT `fk_room_zone` FOREIGN KEY (`room_zone`) REFERENCES `room_types` (`type_zone`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
