 
CREATE TABLE `klienti` (
 `id_klient` int(11) NOT NULL DEFAULT '0',
 `nazev` varchar(255) COLLATE utf8_czech_ci NOT NULL,
 `kontakt` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
 `email` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
 `telefon` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
 `id_stav` varchar(255) COLLATE utf8_czech_ci NOT NULL,
 `poznamka` text COLLATE utf8_czech_ci,
 `datum_vl` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY (`id_klient`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci

CREATE TABLE `stavy` (
 `id_stav` int(11) NOT NULL AUTO_INCREMENT,
 `stav` varchar(255) COLLATE utf8_czech_ci NOT NULL,
 PRIMARY KEY (`id_stav`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci