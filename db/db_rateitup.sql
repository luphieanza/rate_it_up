-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 06 Jul 2025 pada 17.09
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_rateitup`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `check_ins`
--

CREATE TABLE `check_ins` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `place_name` varchar(255) NOT NULL,
  `check_in_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `check_ins`
--

INSERT INTO `check_ins` (`id`, `user_id`, `place_name`, `check_in_time`) VALUES
(1, 2, 'RM Makan Sederhana', '2025-07-06 11:46:18'),
(2, 2, 'Bakso President Malang', '2025-07-06 11:46:22'),
(3, 2, 'Sate Khas Senayan', '2025-07-06 11:46:23'),
(4, 2, 'RM Makan Sederhana', '2025-07-06 11:46:38'),
(5, 2, 'RM Makan Sederhana', '2025-07-06 11:46:38'),
(6, 2, 'RM Makan Sederhana', '2025-07-06 11:47:08'),
(7, 2, 'RM Makan Sederhana', '2025-07-06 11:50:37'),
(8, 2, 'Sate Khas Senayan', '2025-07-06 11:50:40'),
(9, 2, 'Bakso President Malang', '2025-07-06 11:50:41'),
(10, 2, 'RM Makan Sederhana', '2025-07-06 11:50:45'),
(11, 2, 'Sate Khas Senayan', '2025-07-06 11:50:46'),
(12, 2, 'Bakso President Malang', '2025-07-06 11:50:47'),
(13, 2, 'RM Makan Sederhana', '2025-07-06 12:39:19'),
(14, 2, 'Sate Khas Senayan', '2025-07-06 12:39:20'),
(15, 2, 'Bakso President Malang', '2025-07-06 12:39:20'),
(16, 2, 'RM Makan Sederhana', '2025-07-06 14:55:31');

-- --------------------------------------------------------

--
-- Struktur dari tabel `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nama_tempat` varchar(255) NOT NULL,
  `review_text` text NOT NULL,
  `rating` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `nama_tempat`, `review_text`, `rating`, `created_at`) VALUES
(4, 2, 'RM Makan Sederhana', 'Rumah makan nya bersih dan nyaman, makanan nya juga enak sayangnya harganya cukup mahal', 4, '2025-07-05 06:57:36');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama_lengkap` varchar(255) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','administrator') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `nama_lengkap`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'Admin', 'admin', '$2y$10$PL1LRy93vL/vmurgZnTAuueYSl6KUsTyS3EcCD6axZjiZHNPktpyy', 'administrator', '2025-07-06 12:58:54'),
(2, 'Muhammad Rafly Andrianza', 'luphieanza', '$2y$10$ZW91C6L0qz/gSADC1ZFQT.JDMJ2i8kIN7ueN1rGZ4XaLVJumw2sSi', 'user', '2025-07-04 04:53:24');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `check_ins`
--
ALTER TABLE `check_ins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `check_ins`
--
ALTER TABLE `check_ins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `check_ins`
--
ALTER TABLE `check_ins`
  ADD CONSTRAINT `check_ins_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
