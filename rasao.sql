-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 14, 2024 at 05:39 PM
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
-- Database: `rasao`
--

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `dpt_id` int(11) NOT NULL,
  `dpt_name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`dpt_id`, `dpt_name`) VALUES
(1, 'Dept. Founder'),
(2, 'Dept. HR'),
(3, 'Dept. Accounting'),
(4, 'Dept. IT'),
(5, 'Dept. Sales'),
(6, 'Dept. Marketing'),
(7, 'Recruit');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `enable` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `product_name`, `enable`) VALUES
(1, 'Punai', 1);

-- --------------------------------------------------------

--
-- Table structure for table `rank`
--

CREATE TABLE `rank` (
  `rank_id` int(11) NOT NULL,
  `rank_name` varchar(255) NOT NULL,
  `dpt_id` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rank`
--

INSERT INTO `rank` (`rank_id`, `rank_name`, `dpt_id`) VALUES
(0, 'ツ', 7),
(1, 'Founder', 1),
(2, 'Co. Founder', 1),
(3, 'Administrator', 2),
(4, 'Compliance', 2),
(5, 'Payroll and Recruit', 2),
(6, 'Senior Accounting', 3),
(7, 'Junior Accounting', 3),
(8, 'Senior IT', 4),
(9, 'Junior IT', 4),
(10, 'Sales Manager', 5),
(11, 'Sales Assistant', 5),
(12, 'Marketing Manager', 6),
(13, 'Marketing Assistant', 6);

-- --------------------------------------------------------

--
-- Table structure for table `rank_permission`
--

CREATE TABLE `rank_permission` (
  `permission_id` int(11) NOT NULL,
  `rank_id` int(11) NOT NULL DEFAULT 0,
  `loa` int(11) NOT NULL DEFAULT 0,
  `sale_approval` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rank_permission`
--

INSERT INTO `rank_permission` (`permission_id`, `rank_id`, `loa`, `sale_approval`) VALUES
(1, 1, 1, 0),
(5, 2, 0, 0),
(6, 3, 0, 0),
(7, 4, 0, 0),
(8, 5, 0, 0),
(9, 6, 0, 0),
(10, 7, 0, 0),
(11, 8, 0, 0),
(12, 9, 0, 0),
(13, 10, 0, 0),
(14, 11, 0, 0),
(15, 12, 0, 0),
(16, 13, 0, 0),
(18, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `sale_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL DEFAULT 0,
  `product_id` int(11) NOT NULL DEFAULT 0,
  `approval` int(11) NOT NULL DEFAULT 0,
  `checked_date` varchar(100) DEFAULT NULL,
  `sale_date` varchar(100) DEFAULT NULL,
  `sale_total` varchar(255) DEFAULT NULL,
  `approved_by` int(11) DEFAULT 0,
  `remark` varchar(255) DEFAULT NULL,
  `month` varchar(20) NOT NULL,
  `year` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`sale_id`, `staff_id`, `product_id`, `approval`, `checked_date`, `sale_date`, `sale_total`, `approved_by`, `remark`, `month`, `year`) VALUES
(1, 1, 1, 2, '14-08-2024', '12-08-2024', '80', 1, 'test', '08', '2024'),
(2, 2, 1, 1, '26-09-2024', '12-08-2024', '70', 1, 'mantap', '10', '2024'),
(3, 1, 1, 1, '14-08-2024', '12-08-2024', '111', 1, 'nono', '08', '2024');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `staff_id` int(11) NOT NULL,
  `username` varchar(10) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rank_id` int(11) NOT NULL DEFAULT 0,
  `admin` int(11) NOT NULL DEFAULT 0,
  `ban_status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`staff_id`, `username`, `fullname`, `email`, `password`, `rank_id`, `admin`, `ban_status`) VALUES
(1, 'muchub', '', 'muchub@test.com', '$2y$10$vwgrmc3Tg/AU37I3mO7lGu1S7T6xj/usxrJpy./j8hsTNWgQRmOCG', 0, 1, 0),
(2, 'muchub22', '', 'musab.johari@gmail.com', '$2y$10$lYoxrtz0ytgaLA1zoQ3twu6AhJ/m92BjralvrbVTMFn9bfmwANld6', 0, 0, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`dpt_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `rank`
--
ALTER TABLE `rank`
  ADD PRIMARY KEY (`rank_id`);

--
-- Indexes for table `rank_permission`
--
ALTER TABLE `rank_permission`
  ADD PRIMARY KEY (`permission_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`sale_id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`staff_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `dpt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `rank_permission`
--
ALTER TABLE `rank_permission`
  MODIFY `permission_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `sale_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `staff_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
