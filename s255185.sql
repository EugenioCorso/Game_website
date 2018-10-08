-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Lug 12, 2018 alle 16:18
-- Versione del server: 10.1.32-MariaDB
-- Versione PHP: 7.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mydb`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `positions`
--

DROP TABLE IF EXISTS `positions`;
CREATE TABLE `positions` (
  `user` varchar(64) NOT NULL,
  `rowstart` int(64) NOT NULL,
  `colstart` int(64) NOT NULL,
  `rowend` int(64) NOT NULL,
  `colend` int(64) NOT NULL,
  `n` int(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `positions`
--

INSERT INTO `positions` (`user`, `rowstart`, `colstart`, `rowend`, `colend`, `n`) VALUES
('u2@p.it', 1, 3, 1, 6, 1),
('u1@p.it', 3, 0, 6, 0, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `email` varchar(64) NOT NULL,
  `password` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `users`
--

INSERT INTO `users` (`email`, `password`) VALUES
('u1@p.it', '$2y$10$1dv0Ae0nWV9cEuAjEzD.dO.Ul4LcmQ/dicchc4aS6lNTTJX6wfpVC'),
('u2@p.it', '$2y$10$mrrmPSTAAvjJezy8NkIgPeNobyfQjI4e0bo7qZmqsO1HAoyE1pn3C');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `positions`
--
ALTER TABLE `positions`
  ADD PRIMARY KEY (`rowstart`,`colstart`);

--
-- Indici per le tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`email`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
