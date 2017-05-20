-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Počítač: localhost
-- Vytvořeno: Sob 20. kvě 2017, 10:22
-- Verze serveru: 5.7.18-0ubuntu0.16.04.1
-- Verze PHP: 7.0.15-0ubuntu0.16.04.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `evidence`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `klienti`
--

CREATE TABLE `klienti` (
  `id_klient` int(11) NOT NULL,
  `nazev` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `kontakt` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `telefon` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `id_stav` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `poznamka` text COLLATE utf8_czech_ci,
  `datum_vl` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `klienti`
--

INSERT INTO `klienti` (`id_klient`, `nazev`, `kontakt`, `email`, `telefon`, `id_stav`, `poznamka`, `datum_vl`) VALUES
(2, 'nereknu', 'taky nereknu', '', '123456789', '53', 'Takove klienty nechceme', '2017-05-14 14:06:16'),
(4, 'Super firma', 'Lucky Skrblik', 'skrblik@super-firma.cz', '123456789', '54', 'Tohodle chceme', '2017-05-14 19:44:12'),
(5, 'Websoft', 'Hauerland', 'hauerland@websoft.cz', '456789123', '62', 'Potencionální zákazník', '2017-05-14 20:02:31'),
(6, 'Internet expert', 'Juřík', 'jurik@internet-expert.cz', '456789123', '59', '', '2017-05-14 20:21:44'),
(7, 'WEBDav', 'David Petr', 'nereknu@centru.cz', '', '53', 'Zaslat výzvu', '2017-05-14 20:39:59'),
(8, 'SoftLand', 'Jan Rumcajs', 'rumcajs@centru.cz', '741258963', '54', '', '2017-05-15 13:37:30'),
(15, 'WiFi manželka', 'Jana Kamlerová', 'jana.kamlerova@seznam.cz', '123456789', '53', 'Zrušit podporu', '2017-05-15 15:32:58'),
(16, 'AutoCont', 'Chytrý Josef', 'chytracek@autocont.cz', '777777777', '59', '', '2017-05-15 18:54:36');

-- --------------------------------------------------------

--
-- Struktura tabulky `stavy`
--

CREATE TABLE `stavy` (
  `id_stav` int(11) NOT NULL,
  `stav` varchar(255) COLLATE utf8_czech_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `stavy`
--

INSERT INTO `stavy` (`id_stav`, `stav`) VALUES
(53, 'Neplatící'),
(54, 'Zřizování služby'),
(59, 'Platící'),
(62, 'Demo'),
(66, 'Stav nepřiřazený klientovi jde smazat'),
(68, 'dd');

--
-- Klíče pro exportované tabulky
--

--
-- Klíče pro tabulku `klienti`
--
ALTER TABLE `klienti`
  ADD PRIMARY KEY (`id_klient`);

--
-- Klíče pro tabulku `stavy`
--
ALTER TABLE `stavy`
  ADD PRIMARY KEY (`id_stav`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `klienti`
--
ALTER TABLE `klienti`
  MODIFY `id_klient` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT pro tabulku `stavy`
--
ALTER TABLE `stavy`
  MODIFY `id_stav` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
