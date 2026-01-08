-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 08, 2026 at 10:57 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vulpecola`
--

-- --------------------------------------------------------

--
-- Table structure for table `factiuni`
--

CREATE TABLE `factiuni` (
  `IdFactiune` int(11) NOT NULL,
  `OWNER` int(11) NOT NULL,
  `NumeFactiune` varchar(50) NOT NULL,
  `Descriere` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `membrii_factiunii`
--

CREATE TABLE `membrii_factiunii` (
  `IdUtilizator` int(11) NOT NULL,
  `IdFactiune` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mesaje`
--

CREATE TABLE `mesaje` (
  `Id_mesaj` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `sent_time` datetime DEFAULT current_timestamp(),
  `seen_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `players_stats`
--

CREATE TABLE `players_stats` (
  `IdUtilizator` int(11) NOT NULL,
  `total_kills` int(11) DEFAULT 0,
  `days_survived` int(11) DEFAULT 0,
  `Rank` varchar(50) DEFAULT NULL,
  `Portofel` decimal(15,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prietenii`
--

CREATE TABLE `prietenii` (
  `IdUtilizator` int(11) NOT NULL,
  `friend_id` int(11) NOT NULL,
  `sent_time` datetime DEFAULT current_timestamp(),
  `status` enum('pending','accepted','blocked') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shop`
--

CREATE TABLE `shop` (
  `Item_id` int(11) NOT NULL,
  `Item` varchar(100) NOT NULL,
  `Type_Item` varchar(50) DEFAULT NULL,
  `Cost` decimal(15,2) NOT NULL,
  `Image_URL` varchar(255) DEFAULT 'default_item.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shop`
--

INSERT INTO `shop` (`Item_id`, `Item`, `Type_Item`, `Cost`, `Image_URL`) VALUES
(1, 'M4S1', 'Weapons', 4000.00, 'weapon1.png'),
(2, 'Sword', 'Weapons', 500.00, 'weapon2.png'),
(3, 'Spear', 'Weapons', 600.00, 'weapon3.png'),
(4, 'Taxi', 'Cars', 1200.00, 'car1.png'),
(5, 'Bullet Proof Dacia', 'Cars', 1400.00, 'car2.png'),
(6, 'Grandfather car', 'Cars', 1000.00, 'car3.png'),
(7, 'Mustang', 'Cars', 2000.00, 'car4.png'),
(8, 'Medkit kit', 'Starter-kits', 1500.00, 'medkit.png');

-- --------------------------------------------------------

--
-- Table structure for table `tranzactii`
--

CREATE TABLE `tranzactii` (
  `Id_Tranzactie` int(11) NOT NULL,
  `IdUtilizator` int(11) NOT NULL,
  `Item_Id` int(11) NOT NULL,
  `Data` datetime DEFAULT current_timestamp(),
  `Type` enum('cumparare','vanzare','transfer') NOT NULL,
  `Total` decimal(15,2) NOT NULL,
  `Portofelul_Inainte` decimal(15,2) DEFAULT NULL,
  `Portofelul_Dupa_Tranzactie` decimal(15,2) DEFAULT NULL,
  `Schimbarea_Portofelului` decimal(15,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `IdUtilizator` int(11) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Nume` varchar(50) NOT NULL,
  `Parola` varchar(255) NOT NULL,
  `JoinDate` datetime DEFAULT current_timestamp(),
  `ProfilePicture` varchar(255) DEFAULT 'default-avatar.png',
  `Banner` varchar(255) DEFAULT 'default-banner.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`IdUtilizator`, `Email`, `Nume`, `Parola`, `JoinDate`, `ProfilePicture`, `Banner`) VALUES
(1, 'gabitmc9@gmail.com', 'Rosca Gabriel', '$2y$10$OZQxCdW9HdmXvv4X81yNkuqEHVrIwqbcJFDW75948xza8Ij8sTHdy', '2026-01-08 21:17:36', 'uploads/profiles/profile_1_1767902692.png', 'default-banner.png'),
(2, 'marian23@yahoo.com', 'Marian Fasole', '$2y$10$A0Hpq//Z2QlxefylecSXEeEXvhvxAhOzA8HJPMAYZ8ODFoQ/tZ.rG', '2026-01-08 22:48:58', 'default-avatar.png', 'default-banner.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `factiuni`
--
ALTER TABLE `factiuni`
  ADD PRIMARY KEY (`IdFactiune`),
  ADD UNIQUE KEY `NumeFactiune` (`NumeFactiune`),
  ADD KEY `OWNER` (`OWNER`);

--
-- Indexes for table `membrii_factiunii`
--
ALTER TABLE `membrii_factiunii`
  ADD PRIMARY KEY (`IdUtilizator`),
  ADD KEY `IdFactiune` (`IdFactiune`);

--
-- Indexes for table `mesaje`
--
ALTER TABLE `mesaje`
  ADD PRIMARY KEY (`Id_mesaj`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indexes for table `players_stats`
--
ALTER TABLE `players_stats`
  ADD PRIMARY KEY (`IdUtilizator`);

--
-- Indexes for table `prietenii`
--
ALTER TABLE `prietenii`
  ADD PRIMARY KEY (`IdUtilizator`,`friend_id`),
  ADD KEY `friend_id` (`friend_id`);

--
-- Indexes for table `shop`
--
ALTER TABLE `shop`
  ADD PRIMARY KEY (`Item_id`);

--
-- Indexes for table `tranzactii`
--
ALTER TABLE `tranzactii`
  ADD PRIMARY KEY (`Id_Tranzactie`),
  ADD KEY `IdUtilizator` (`IdUtilizator`),
  ADD KEY `Item_Id` (`Item_Id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`IdUtilizator`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD UNIQUE KEY `Nume` (`Nume`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `factiuni`
--
ALTER TABLE `factiuni`
  MODIFY `IdFactiune` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mesaje`
--
ALTER TABLE `mesaje`
  MODIFY `Id_mesaj` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shop`
--
ALTER TABLE `shop`
  MODIFY `Item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tranzactii`
--
ALTER TABLE `tranzactii`
  MODIFY `Id_Tranzactie` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `IdUtilizator` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `factiuni`
--
ALTER TABLE `factiuni`
  ADD CONSTRAINT `factiuni_ibfk_1` FOREIGN KEY (`OWNER`) REFERENCES `users` (`IdUtilizator`) ON DELETE CASCADE;

--
-- Constraints for table `membrii_factiunii`
--
ALTER TABLE `membrii_factiunii`
  ADD CONSTRAINT `membrii_factiunii_ibfk_1` FOREIGN KEY (`IdUtilizator`) REFERENCES `users` (`IdUtilizator`) ON DELETE CASCADE,
  ADD CONSTRAINT `membrii_factiunii_ibfk_2` FOREIGN KEY (`IdFactiune`) REFERENCES `factiuni` (`IdFactiune`) ON DELETE CASCADE;

--
-- Constraints for table `mesaje`
--
ALTER TABLE `mesaje`
  ADD CONSTRAINT `mesaje_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`IdUtilizator`) ON DELETE CASCADE,
  ADD CONSTRAINT `mesaje_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`IdUtilizator`) ON DELETE CASCADE;

--
-- Constraints for table `players_stats`
--
ALTER TABLE `players_stats`
  ADD CONSTRAINT `players_stats_ibfk_1` FOREIGN KEY (`IdUtilizator`) REFERENCES `users` (`IdUtilizator`) ON DELETE CASCADE;

--
-- Constraints for table `prietenii`
--
ALTER TABLE `prietenii`
  ADD CONSTRAINT `prietenii_ibfk_1` FOREIGN KEY (`IdUtilizator`) REFERENCES `users` (`IdUtilizator`) ON DELETE CASCADE,
  ADD CONSTRAINT `prietenii_ibfk_2` FOREIGN KEY (`friend_id`) REFERENCES `users` (`IdUtilizator`) ON DELETE CASCADE;

--
-- Constraints for table `tranzactii`
--
ALTER TABLE `tranzactii`
  ADD CONSTRAINT `tranzactii_ibfk_1` FOREIGN KEY (`IdUtilizator`) REFERENCES `users` (`IdUtilizator`) ON DELETE CASCADE,
  ADD CONSTRAINT `tranzactii_ibfk_2` FOREIGN KEY (`Item_Id`) REFERENCES `shop` (`Item_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
