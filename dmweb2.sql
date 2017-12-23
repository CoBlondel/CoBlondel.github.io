-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  jeu. 21 déc. 2017 à 17:23
-- Version du serveur :  5.7.19
-- Version de PHP :  5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `dmweb2`
--

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id_User` int(11) NOT NULL AUTO_INCREMENT,
  `ndc_User` varchar(40) NOT NULL,
  `pseudo_User` varchar(40) DEFAULT NULL,
  `password_User` varchar(255) DEFAULT NULL,
  `mail_User` varchar(200) DEFAULT NULL,
  `dateSignIn_User` date DEFAULT NULL,
  `VIP` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_User`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id_User`, `ndc_User`, `pseudo_User`, `password_User`, `mail_User`, `dateSignIn_User`, `VIP`) VALUES
(1, 'vctListo', 'Firstourm', '$2y$09$UAB1ugWW.kfSuzgtUpHmDON7T7T7NhQDpibi6MepJUAfORnfeH7fi', 'mail@test.com', '2017-10-03', 1),
(2, 'bbbb', 'aaaa', '$2y$09$5DV.0zUkHWYEPcc0x2PrYumXLzc9AkgBMxrm0DB9uAQBVD7Q89G3m', 'bbbijoijo@gmail.com', '2017-10-24', 1),
(3, 'cccc', 'bbbb', '$2y$09$CwM0PodvR9MvUYmnqbbsNOnxejewnn6aYl9FADIr.Juh8TbqEZWS6', 'cccc@gmail.com', '2017-10-24', 1),
(4, 'fejrjept', 'aabc', '$2y$09$H6970Fa6inKn6tizuHi56e6zcV5moO5vPkowNAH02jwAyi.uzan3a', 'fep@gmail.com', '2017-11-06', 1),
(5, 'truc', 'SltTlmd', '$2y$09$yLzl3aXJ1NT4iEASdt0crue.170IPug249LAjSXfZEdxDeJOFAwfa', 'test@gmail.com', '2017-12-05', 1),
(6, 'test2', 'aaa', '$2y$09$zW0AXWBHFYe6JX2w3D/aVul1XG0vdMP0c//O9c0bSW3bzbdd9RlmC', 'aaa@gmail.com', '2017-12-21', 1);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
