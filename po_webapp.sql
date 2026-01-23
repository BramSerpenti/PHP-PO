-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 23 jan 2026 om 10:35
-- Serverversie: 10.4.32-MariaDB
-- PHP-versie: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `po_webapp`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `friends`
--

CREATE TABLE `friends` (
  `friendid` int(11) NOT NULL,
  `person1` int(11) NOT NULL,
  `person2` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `friends`
--

INSERT INTO `friends` (`friendid`, `person1`, `person2`) VALUES
(0, 37, 36),
(0, 37, 33),
(0, 38, 37),
(0, 39, 30),
(0, 39, 37),
(0, 39, 33);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `groups`
--

CREATE TABLE `groups` (
  `id` int(11) NOT NULL,
  `titel` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `groups`
--

INSERT INTO `groups` (`id`, `titel`) VALUES
(1, 'hjlkjl'),
(2, 'ak'),
(3, 'ak'),
(4, ''),
(5, 'kja'),
(6, 'kja'),
(7, 'jllalksjadsk'),
(8, 'jllalksjadsk'),
(22, 'wiskunde'),
(23, ''),
(24, ''),
(25, ''),
(26, ''),
(27, 'algebra'),
(28, ''),
(29, ''),
(30, 'wiskunde'),
(31, 'wiskunde'),
(32, 'wiskunde'),
(33, 'wiskunde'),
(34, 'wiskunde'),
(35, ''),
(36, 'wiskunde'),
(37, 'bio'),
(38, ''),
(39, ''),
(40, 'wiskunde');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `group_members`
--

CREATE TABLE `group_members` (
  `id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `group_members`
--

INSERT INTO `group_members` (`id`, `group_id`, `user_id`) VALUES
(1, 1, 2),
(2, 2, 1),
(3, 2, 2),
(4, 3, 1),
(5, 3, 2),
(6, 5, 1),
(7, 6, 1),
(8, 7, 1),
(9, 8, 1),
(10, 9, 1),
(11, 10, 1),
(12, 11, 1),
(13, 12, 1),
(14, 13, 30),
(15, 13, 1),
(16, 14, 30),
(17, 14, 2),
(18, 15, 30),
(19, 15, 2),
(20, 16, 30),
(21, 16, 2),
(22, 17, 30),
(23, 17, 2),
(24, 18, 30),
(25, 18, 2),
(26, 19, 30),
(27, 19, 2),
(28, 20, 30),
(29, 20, 2),
(30, 21, 30),
(31, 21, 2),
(33, 22, 1),
(34, 23, 30),
(35, 24, 30),
(36, 25, 30),
(37, 25, 3),
(38, 26, 30),
(39, 26, 1),
(40, 27, 31),
(41, 27, 1),
(42, 28, 31),
(43, 28, 4),
(44, 29, 31),
(45, 30, 32),
(46, 30, 1),
(47, 31, 32),
(48, 31, 1),
(49, 32, 32),
(50, 32, 2),
(52, 33, 1),
(54, 34, 1),
(55, 35, 33),
(56, 36, 37),
(57, 36, 36),
(58, 36, 33),
(59, 37, 38),
(60, 37, 37),
(61, 38, 37),
(62, 39, 39),
(63, 40, 39),
(64, 40, 30),
(65, 40, 37),
(66, 40, 33);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `group_teachers`
--

CREATE TABLE `group_teachers` (
  `id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `group_teachers`
--

INSERT INTO `group_teachers` (`id`, `group_id`, `user_id`) VALUES
(1, 2, 1),
(2, 2, 2),
(3, 3, 1),
(4, 3, 2),
(5, 5, 3),
(6, 6, 3),
(7, 7, 1),
(8, 8, 1),
(9, 9, 1),
(10, 10, 1),
(11, 11, 1),
(12, 12, 1),
(13, 13, 1),
(14, 14, 3),
(15, 15, 3),
(16, 16, 3),
(17, 17, 3),
(18, 18, 3),
(19, 19, 1),
(20, 20, 1),
(21, 21, 1),
(22, 22, 2),
(23, 27, 1),
(24, 30, 1),
(25, 30, 2),
(26, 31, 1),
(27, 32, 3),
(28, 33, 1),
(29, 34, 2);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `todo`
--

CREATE TABLE `todo` (
  `taakid` int(11) NOT NULL,
  `datum` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `info` varchar(255) DEFAULT NULL,
  `titel` varchar(255) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL,
  `person` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `todo`
--

INSERT INTO `todo` (`taakid`, `datum`, `info`, `titel`, `group_id`, `person`) VALUES
(32, '2026-01-22 23:00:00', 'lkajafklj', 'maak....af', 36, '36'),
(34, '2026-01-21 23:00:00', 'reql+jlqkewrj', 'elkqwrjlqewrjeqwr', 37, '37'),
(35, '2026-01-24 23:00:00', 'fkjala;jsd', 'maak....af', 40, '30'),
(36, '2026-01-24 23:00:00', 'fkjala;jsd', 'maak....af', 40, '37'),
(37, '2026-01-24 23:00:00', 'fkjala;jsd', 'maak....af', 40, '33');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `userinfo`
--

CREATE TABLE `userinfo` (
  `userinfoid` int(11) NOT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `userinfo`
--

INSERT INTO `userinfo` (`userinfoid`, `firstname`, `lastname`, `email`, `user_id`) VALUES
(1, 'Alice', 'Admin', 'alice.admin@example.com', 1),
(2, 'Tom', 'Teacher', 'tom.teacher@example.com', 2),
(3, 'Sara', 'Student', 'sara.student@example.com', 3),
(4, 'Liam', 'Student', 'liam.student@example.com', 4),
(10, 'Bram', 'Serpenti', '111@gmail.com', 30),
(11, 'Bert', 'H', '666@gmail.com', 31),
(12, '222@gmail.com', '000', '222@gmail.com', 32),
(13, 'Ti', '5', '67@gmail', 33),
(14, '6@6', '2', '6@6', 35),
(15, 'Henk', 'Willem', '11@gmail.com', 36),
(16, 'Bram', 'Serpenti', '123@123', 37),
(17, 'Bram', 'Serpenti', '56@56', 38),
(18, 'Bram', 'Serpenti', 't@t', 39);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `wachtwoord` varchar(255) DEFAULT NULL,
  `role` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `users`
--

INSERT INTO `users` (`id`, `username`, `wachtwoord`, `role`) VALUES
(1, 'admin01', 'adminpass', 'admin'),
(2, 'teacher01', 'teachpass', 'teacher'),
(3, 'student01', 'studpass', 'student'),
(4, 'student02', 'studpass2', 'student'),
(27, 'b.serpenti@gmail.com', '$2y$10$Sl/4v.L7omKc3ZcUwmhcpuoiF4TqzSnZMDOUKC.7omofIUTo66Gqm', '1'),
(28, 'pietje@gmail.com', '$2y$10$rHNUIx8ZOK.RIb2ppKK9COi2HN3/JerCG8bIM9gjYt/rOqiIkdc1C', '2'),
(29, 'aldkjfadljskl@klfajlkdafjlajgmail.com', '$2y$10$h8ZOuyPyWzRgh5lrczOMSeKGibuXtz7LEp1q63l.UPF0MBAibei06', '2'),
(30, '111@gmail.com', '$2y$10$2oFiJyeI0LqBJQGBABjlcOkZqRBeTUVtxpRshKsT4FubYxWkLZDmK', '1'),
(31, '666@gmail.com', '$2y$10$vY28RGxWBmTYYUttxvpituqi9ymSniuNCk5h2VHgOOPJ.zcdtgYVW', '1'),
(32, '222@gmail.com', '$2y$10$/vWKFZpODP9VplgpZJbSIeQ4E.xzjhwbnYUrBezBzKXzvl5TVrZ2a', '1'),
(33, '67@gmail', '$2y$10$Iooax.Dp9kwaZzhbu5pVluuESEnOoRBe6K9HVo5lm.5FKCKLG1sSe', '1'),
(34, '77@77', '$2y$10$JPwzfs9ldvl.L7RwNX0n6egV5DjkLKA9nEs3lm2ll4HAurIBJhAiK', '1'),
(35, '6@6', '$2y$10$tn0Oi/g6csE2P7Bh1/bie.wca7H82/oOYa6Epa76PhH4tpw6AHiDe', '1'),
(36, '11@gmail.com', '$2y$10$AB0VuNmS/ePEU/AYEwnwiuL8jWEPKpOFVgVhynSvruaefLTh2fBAi', '2'),
(37, '123@123', '$2y$10$Mv.whpkKDUb1yx4DGCESd./KTSsoYLBtSUXAO.8oomHUc7wg.CLoO', '1'),
(38, '56@56', '$2y$10$4XVR31Bv/fXECiXVwmrOR.HdISjhpFKfnd3ton0.hdqwi9c0bUsOm', '1'),
(39, 't@t', '$2y$10$cWzIyTxSimIaFGxhUEfQvuWIRp57cRr8RTQ/nOrug5M42GAFot4su', '2');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `userstory`
--

CREATE TABLE `userstory` (
  `statusid` int(11) NOT NULL,
  `admin` varchar(255) DEFAULT NULL,
  `teacher` varchar(255) DEFAULT NULL,
  `student` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `userstory`
--

INSERT INTO `userstory` (`statusid`, `admin`, `teacher`, `student`) VALUES
(1, 'Kan gebruikers beheren', 'Kan taken aanmaken', 'Kan taken bekijken');

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `group_members`
--
ALTER TABLE `group_members`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `group_teachers`
--
ALTER TABLE `group_teachers`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `todo`
--
ALTER TABLE `todo`
  ADD PRIMARY KEY (`taakid`),
  ADD KEY `group_id` (`group_id`);

--
-- Indexen voor tabel `userinfo`
--
ALTER TABLE `userinfo`
  ADD PRIMARY KEY (`userinfoid`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexen voor tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexen voor tabel `userstory`
--
ALTER TABLE `userstory`
  ADD PRIMARY KEY (`statusid`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT voor een tabel `group_members`
--
ALTER TABLE `group_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT voor een tabel `group_teachers`
--
ALTER TABLE `group_teachers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT voor een tabel `todo`
--
ALTER TABLE `todo`
  MODIFY `taakid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT voor een tabel `userinfo`
--
ALTER TABLE `userinfo`
  MODIFY `userinfoid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT voor een tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT voor een tabel `userstory`
--
ALTER TABLE `userstory`
  MODIFY `statusid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `userinfo`
--
ALTER TABLE `userinfo`
  ADD CONSTRAINT `userinfo_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
