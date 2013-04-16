-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Mar 16 Avril 2013 à 08:14
-- Version du serveur: 5.5.24-log
-- Version de PHP: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `book`
--

-- --------------------------------------------------------

--
-- Structure de la table `auteur`
--

CREATE TABLE IF NOT EXISTS `auteur` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `image` varchar(200) NOT NULL DEFAULT 'images/auteurs/default.jpg',
  `date_ajout` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `auteur`
--

INSERT INTO `auteur` (`id`, `nom`, `image`, `date_ajout`) VALUES
(1, 'Victor Hugo', 'images/auteurs/default.jpg', '2013-04-04 22:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `ecritpar`
--

CREATE TABLE IF NOT EXISTS `ecritpar` (
  `idLivre` int(11) NOT NULL,
  `idAuteur` int(11) NOT NULL,
  PRIMARY KEY (`idLivre`,`idAuteur`),
  KEY `idAuteur` (`idAuteur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `ecritpar`
--

INSERT INTO `ecritpar` (`idLivre`, `idAuteur`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `genre`
--

CREATE TABLE IF NOT EXISTS `genre` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `genre`
--

INSERT INTO `genre` (`id`, `libelle`) VALUES
(1, 'histoire'),
(2, 'Roman'),
(3, 'Fiction');

-- --------------------------------------------------------

--
-- Structure de la table `lecture`
--

CREATE TABLE IF NOT EXISTS `lecture` (
  `idLivre` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `appreciation` varchar(1000) DEFAULT NULL,
  `dateLEcture` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`idLivre`,`idUser`),
  KEY `idUser` (`idUser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `livre`
--

CREATE TABLE IF NOT EXISTS `livre` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(50) DEFAULT NULL,
  `editeur` varchar(100) NOT NULL,
  `nbPages` int(11) DEFAULT NULL,
  `resume` varchar(1000) DEFAULT NULL,
  `datePublication` varchar(50) NOT NULL,
  `image` varchar(200) NOT NULL DEFAULT 'images/livres/default.png',
  `dateAjout` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `livre`
--

INSERT INTO `livre` (`id`, `titre`, `editeur`, `nbPages`, `resume`, `datePublication`, `image`, `dateAjout`) VALUES
(1, 'Les misérables', '', 318, 'Le drame de Jean Valjean, l''ex-forçat contraint au mal par l''injustice sociale, c''est " le vaste miroir reflétant le genre humain de son siècle ". Sous le nom de Monsieur Madeleine, puis sous celui de Monsieur Fauchelevent, il devient propriétaire d''une maison et connaît les joies de l''amour paternel auprès de Cosette, arrachées à l''affreux couple Thénardier. Mais ces moments de bonheur seront de courte durée Javert le traque avec l''acharnement d''un fanatique, inaccessible à la pitié. Roman policier, roman social, chef-d''œuvre du XIXe siècle, Les Misérables n''ont pas encore pris fin. Les protagonistes ont encore bien des choses à vivre avant le bouquet final.', '1995-10-15', 'images/livres/default.png', '2013-04-14 00:44:32');

-- --------------------------------------------------------

--
-- Structure de la table `livregenre`
--

CREATE TABLE IF NOT EXISTS `livregenre` (
  `idLivre` int(11) NOT NULL,
  `idGenre` int(11) NOT NULL,
  PRIMARY KEY (`idLivre`,`idGenre`),
  KEY `idGenre` (`idGenre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `livregenre`
--

INSERT INTO `livregenre` (`idLivre`, `idGenre`) VALUES
(1, 1),
(1, 2),
(1, 3);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(50) NOT NULL,
  `nom` varchar(50) DEFAULT NULL,
  `prenom` varchar(50) DEFAULT NULL,
  `pass` varchar(50) NOT NULL,
  `role` varchar(50) NOT NULL,
  `image` varchar(200) NOT NULL DEFAULT 'images/users/default.jpg',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Contenu de la table `users`
--

INSERT INTO `users` (`id`, `login`, `nom`, `prenom`, `pass`, `role`, `image`) VALUES
(1, 'admin', 'Administrateur', '', '21232f297a57a5a743894a0e4a801fc3', 'admin', 'images/users/admin.png'),
(2, 'test2', 'test2', 'test', '972a3a0ebe07920fd5a13a9f7633272b', 'user', 'images/users/default.jpg'),
(3, 'test1', 'test1', 'test', '098f6bcd4621d373cade4e832627b4f6', 'user', 'images/users/default.jpg'),
(4, 'test3', 'test3', 'test', '098f6bcd4621d373cade4e832627b4f6', 'user', 'images/users/default.jpg'),
(5, 'test4', 'test4', 'test', '098f6bcd4621d373cade4e832627b4f6', 'user', 'images/users/default.jpg'),
(6, 'test5', 'TEST5', 'test', '098f6bcd4621d373cade4e832627b4f6', 'user', 'images/users/default.jpg'),
(7, 'test6', 'TEST6', 'test', '098f6bcd4621d373cade4e832627b4f6', 'user', 'images/users/default.jpg'),
(8, 'khalil', 'KHALIL', 'eimk', '44536ccadaa639127cf1c317ef0652e5', 'user', 'images/users/default.jpg'),
(9, 'test9', 'TEST9', 'test', '098f6bcd4621d373cade4e832627b4f6', 'user', 'images/users/default.jpg'),
(10, 'test10', 'TEST10', 'test', '098f6bcd4621d373cade4e832627b4f6', 'user', 'images/users/default.jpg'),
(11, 'test11', 'TEST11', 'test', '098f6bcd4621d373cade4e832627b4f6', 'user', 'images/users/default.jpg');

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `ecritpar`
--
ALTER TABLE `ecritpar`
  ADD CONSTRAINT `ecritpar_ibfk_1` FOREIGN KEY (`idLivre`) REFERENCES `livre` (`id`),
  ADD CONSTRAINT `ecritpar_ibfk_2` FOREIGN KEY (`idAuteur`) REFERENCES `auteur` (`id`);

--
-- Contraintes pour la table `lecture`
--
ALTER TABLE `lecture`
  ADD CONSTRAINT `lecture_ibfk_1` FOREIGN KEY (`idLivre`) REFERENCES `livre` (`id`),
  ADD CONSTRAINT `lecture_ibfk_2` FOREIGN KEY (`idUser`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `livregenre`
--
ALTER TABLE `livregenre`
  ADD CONSTRAINT `livregenre_ibfk_1` FOREIGN KEY (`idLivre`) REFERENCES `livre` (`id`),
  ADD CONSTRAINT `livregenre_ibfk_2` FOREIGN KEY (`idGenre`) REFERENCES `genre` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
