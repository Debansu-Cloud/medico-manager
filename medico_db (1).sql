-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 07, 2025 at 07:40 PM
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
-- Database: `medico_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `booking_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `medicine_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `utr_no` varchar(50) NOT NULL,
  `status` varchar(50) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `name`, `phone`, `total_price`, `booking_date`, `medicine_id`, `qty`, `price`, `utr_no`, `status`) VALUES
(1, 1, 'Bishal', '9876543210', NULL, '2025-12-06 19:54:27', 5, 6, 20.00, '48968097', 'Success');

-- --------------------------------------------------------

--
-- Table structure for table `booking_details`
--

CREATE TABLE `booking_details` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `medicine_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `medicine_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `added_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_form_submissions`
--

CREATE TABLE `contact_form_submissions` (
  `id` int(11) NOT NULL,
  `user` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `website` varchar(150) DEFAULT NULL,
  `message` text NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_form_submissions`
--

INSERT INTO `contact_form_submissions` (`id`, `user`, `name`, `email`, `phone`, `website`, `message`, `submitted_at`) VALUES
(1, 'user', 'User', 'medicare@pharma.com', '9876543210', 'Medico Manager', 'test1', '2025-12-07 08:24:34');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `email`, `message`, `created_at`) VALUES
(1, 'medicare@pharma.com', 'Hi Developers', '2025-11-01 11:07:57'),
(2, 'rup@gmail.com', 'test3', '2025-12-06 17:02:44');

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `qualification` varchar(255) DEFAULT NULL,
  `specialization` varchar(100) DEFAULT NULL,
  `available_times` varchar(255) DEFAULT NULL,
  `booking_fee` decimal(10,2) DEFAULT 0.00,
  `doctor_code` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `name`, `qualification`, `specialization`, `available_times`, `booking_fee`, `doctor_code`) VALUES
(1, 'Dr Ashis', 'MBBS,PHD', 'Medicine', '9.00 am - 11.00am', 3000.00, ''),
(3, 'Dr. Ravi', 'MMB', 'Psycologist', '5.00 pm - 6.00pm', 500.00, 'DR.69357D92B6015');

-- --------------------------------------------------------

--
-- Table structure for table `doctor_appointments`
--

CREATE TABLE `doctor_appointments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `patient_name` varchar(100) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `status` enum('Pending','Confirmed','Cancelled') DEFAULT 'Pending',
  `payment_proof` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctor_appointments`
--

INSERT INTO `doctor_appointments` (`id`, `user_id`, `patient_name`, `doctor_id`, `appointment_date`, `appointment_time`, `status`, `payment_proof`) VALUES
(9, 1, 'Gaurav', 1, '2025-12-22', '09:56:00', 'Confirmed', NULL),
(20, 1, 'Ramesh', 1, '2025-12-23', '10:47:00', 'Pending', NULL),
(21, 1, 'Gavi', 3, '2025-12-12', '17:43:00', 'Pending', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `medicines`
--

CREATE TABLE `medicines` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `generic` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL,
  `manufacturer` varchar(255) NOT NULL,
  `supplierId` int(11) NOT NULL,
  `batchNumber` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `selling_price` decimal(10,2) NOT NULL,
  `mrp` decimal(10,2) NOT NULL,
  `reorder_level` int(11) NOT NULL,
  `rack` varchar(50) NOT NULL,
  `manufacture_date` date NOT NULL,
  `expiry_date` date NOT NULL,
  `description` text DEFAULT NULL,
  `prescription` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medicines`
--

INSERT INTO `medicines` (`id`, `name`, `generic`, `category`, `manufacturer`, `supplierId`, `batchNumber`, `quantity`, `unit_price`, `selling_price`, `mrp`, `reorder_level`, `rack`, `manufacture_date`, `expiry_date`, `description`, `prescription`) VALUES
(1, 'Paracetamol', 'Acetaminophen', 'Pain Relief', 'Para Medico', 1, '0', 96, 1.20, 25.00, 50.00, 20, '5', '2025-10-14', '2026-04-23', '', 1),
(3, 'Ibuprofen', 'Ibuprofen', 'Pain Relief', 'PFIZER', 1, '0', 80, 7.00, 145.00, 200.00, 5, '0', '2025-10-20', '2027-10-19', '', 1),
(4, 'Aspirin', 'Acetylsailicylic', 'Pain Relief', 'BAYER', 1, '0', 120, 4.00, 80.00, 110.00, 45, '0', '2025-10-23', '2027-10-22', '', 1),
(5, 'Amoxicillin', 'Amoxicillin', 'Antibiotics', 'GSK', 4, '0', 38, 2.00, 20.00, 25.00, 10, '0', '2025-10-21', '2025-11-03', '', 1),
(6, 'Ciprofloxacin', 'Ciprofloxacin', 'Antibiotics', 'BAYER', 4, '0', 70, 2.50, 40.00, 60.00, 5, '0', '2025-10-05', '2028-10-17', '', 0),
(7, 'Metronidazole', 'Metronidazole', 'Antibiotics', 'SANOFI', 1, '0', 60, 10.00, 100.00, 150.00, 20, '0', '2025-10-14', '2029-12-10', '', 1),
(8, 'Vitamin C Tablets', 'Ascorbic Acid', 'Vitamins', 'NATURE\'S BOUNTY', 3, '0', 200, 2.00, 40.00, 62.00, 30, '0', '2025-10-23', '2027-09-19', '', 0),
(9, 'Multivitamin', 'Multivitamin', 'Vitamins', 'PHARMALINK', 1, '0', 150, 6.00, 160.00, 237.00, 40, '4', '2025-10-10', '2029-06-29', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `medicine_app_users`
--

CREATE TABLE `medicine_app_users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medicine_app_users`
--

INSERT INTO `medicine_app_users` (`id`, `username`, `password`) VALUES
(1, 'user', '$2y$10$8mklbQ2o/vSUkBCmPvQdeut5DYhFkgK/IcJftxOjd40/akQEaGFeS'),
(2, 'Bishal', '$2y$10$bMXjOmXT4U5YDvOhU9M1Eerjuyd2JlVygFXcLm138ckHzrsVZd6de');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `gst_number` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `contact_person`, `phone`, `email`, `address`, `gst_number`) VALUES
(1, 'ABC Pharma', 'John Doe', '+1-555-0001', 'abc@pharma.com', '123 Street', 'GST123456'),
(2, 'MediCare Ltd.', 'Jane Smith', '+1-555-0002', 'medicare@pharma.com', '456 Street', 'GST987654'),
(3, 'HealthCo', 'Mike Johnson', '+1-555-0003', 'healthco@pharma.com', '789 Street', 'GST567890'),
(4, 'HealthLTD', 'Michael Brown', '9876543210', 'medicare@pharma.com', 'NEWTOWN\r\n\r\n\r\n', 'GST67890');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'storemanager', 'MD'),
(2, 'storemanager2', 'MP');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `booking_details`
--
ALTER TABLE `booking_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_form_submissions`
--
ALTER TABLE `contact_form_submissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `doctor_code` (`doctor_code`);

--
-- Indexes for table `doctor_appointments`
--
ALTER TABLE `doctor_appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctor_id` (`doctor_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `medicines`
--
ALTER TABLE `medicines`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `medicine_app_users`
--
ALTER TABLE `medicine_app_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `booking_details`
--
ALTER TABLE `booking_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact_form_submissions`
--
ALTER TABLE `contact_form_submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `doctor_appointments`
--
ALTER TABLE `doctor_appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `medicines`
--
ALTER TABLE `medicines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `medicine_app_users`
--
ALTER TABLE `medicine_app_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `doctor_appointments`
--
ALTER TABLE `doctor_appointments`
  ADD CONSTRAINT `doctor_appointments_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`),
  ADD CONSTRAINT `doctor_appointments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
