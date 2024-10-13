-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 07, 2023 at 02:36 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: outfit_store
--

-- --------------------------------------------------------

--
-- Table structure for table orders
--

CREATE TABLE orders (
  username varchar(20) NOT NULL,
  product_name varchar(50) NOT NULL,
  quantity int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table orders
--

INSERT INTO orders (username, product_name, quantity) VALUES
('admin', 'Summer Dress', 1),
('admin', 'Leather Jacket', 2),
('admin', 'Casual Sneakers', 3);

-- --------------------------------------------------------

--
-- Table structure for table users
--

CREATE TABLE users (
  username varchar(20) NOT NULL,
  password varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table users
--

INSERT INTO users (username, password) VALUES
('admin', '$2y$10$Vw9S7/xyz12345examplehash');

-- --------------------------------------------------------

-- Indexes for dumped tables

--
-- Indexes for table users
--
ALTER TABLE users
  ADD PRIMARY KEY (username);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;