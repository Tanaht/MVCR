-- phpMyAdmin SQL Dump
-- version 4.3.11
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Mer 15 Février 2017 à 20:33
-- Version du serveur :  5.6.24
-- Version de PHP :  5.6.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `21302782_dev`
--

-- --------------------------------------------------------

--
-- Structure de la table `attributs`
--

CREATE TABLE IF NOT EXISTS `attributs` (
  `id_attribut` int(2) NOT NULL,
  `nom` varchar(15) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `attributs`
--

INSERT INTO `attributs` (`id_attribut`, `nom`) VALUES
(1, 'Ténèbres'),
(2, 'Terre'),
(3, 'Feu'),
(4, 'Lumière'),
(5, 'Eau'),
(6, 'Vent'),
(7, 'Divin'),
(8, 'Magie'),
(9, 'Piège'),
(10, 'Piège et Magie'),
(11, 'Virus');

-- --------------------------------------------------------

--
-- Structure de la table `cartes`
--

CREATE TABLE IF NOT EXISTS `cartes` (
  `id_carte` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `id_attribut` int(1) NOT NULL,
  `id_categorie` int(1) DEFAULT NULL,
  `id_effet` int(1) DEFAULT NULL,
  `dateCreation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nom` varchar(40) NOT NULL,
  `niveau` int(2) DEFAULT NULL,
  `attaque` varchar(6) DEFAULT NULL,
  `defense` varchar(6) DEFAULT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `cartes`
--

INSERT INTO `cartes` (`id_carte`, `id_utilisateur`, `id_attribut`, `id_categorie`, `id_effet`, `dateCreation`, `nom`, `niveau`, `attaque`, `defense`, `description`) VALUES
(33, 1, 1, 2, 10, '2016-04-04 18:45:02', 'Exodia l''Interdit', 3, '1000', '1000', 'Lorsque vous avez "Jambe Droite de l''Interdit", "Jambe Gauche de l''Interdit", "Bras Droit de l''Interdit", "Bras Gauche de l''Interdit" en plus de cette carte dans votre Main vous gagnez le Duel.');

-- --------------------------------------------------------

--
-- Structure de la table `carte_types`
--

CREATE TABLE IF NOT EXISTS `carte_types` (
  `id_carte` int(11) NOT NULL,
  `id_type` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `carte_types`
--

INSERT INTO `carte_types` (`id_carte`, `id_type`) VALUES
(33, 14);

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id_categorie` int(11) NOT NULL,
  `nom` varchar(20) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `categories`
--

INSERT INTO `categories` (`id_categorie`, `nom`) VALUES
(1, 'Normale'),
(2, 'Effet'),
(3, 'Fusion'),
(4, 'Synchro'),
(5, 'XYZ'),
(6, 'Pendullum'),
(7, 'Magie'),
(8, 'Piège');

-- --------------------------------------------------------

--
-- Structure de la table `effets`
--

CREATE TABLE IF NOT EXISTS `effets` (
  `id_effet` int(11) NOT NULL,
  `nom` varchar(20) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `effets`
--

INSERT INTO `effets` (`id_effet`, `nom`) VALUES
(1, 'Flip'),
(2, 'Continu'),
(3, 'Ignition'),
(4, 'Déclencheur'),
(5, 'Rapide'),
(6, 'Jeu-Rapide'),
(7, 'Rituelle'),
(8, 'Terrain'),
(9, 'Equipement'),
(10, 'Aucun');

-- --------------------------------------------------------

--
-- Structure de la table `types`
--

CREATE TABLE IF NOT EXISTS `types` (
  `id_type` int(11) NOT NULL,
  `nom` varchar(20) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `types`
--

INSERT INTO `types` (`id_type`, `nom`) VALUES
(1, 'Aqua'),
(2, 'Bête'),
(3, 'Bête Ailée'),
(4, 'Bête Divine'),
(5, 'Bête-Guerrier'),
(6, 'Démon'),
(7, 'Dinosaure'),
(8, 'Dragon'),
(9, 'Elfe'),
(10, 'Gémeau'),
(11, 'Guerrier'),
(12, 'Insecte'),
(13, 'Machine'),
(14, 'Magicien'),
(15, 'Plante'),
(16, 'Poisson'),
(17, 'Psychique'),
(18, 'Pyro'),
(19, 'Reptile'),
(20, 'Rocher'),
(21, 'Serpent des Mers'),
(22, 'Spirit'),
(23, 'Tonnerre'),
(24, 'Toon et Zombie'),
(25, 'Aucun');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id_utilisateur` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(70) NOT NULL,
  `sudo` varchar(15) NOT NULL DEFAULT 'MEMBER'
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id_utilisateur`, `email`, `username`, `password`, `sudo`) VALUES
(1, 'anonymous@gmail.com', 'Anonymous', '$2y$10$u2u.GaKATME1sjf./9nP.eLvOUaj6JrNgF71dzGGc878c9xcVogAC', 'ADMIN'),
(10, 'anonyme@an.nym', 'Utilisateur', '$2y$10$t.7pjWM2fcKYYne6YlI2Mu7GJg3/kqF92N4k/dsV2rcq83sRVS/Wi', 'MEMBER'),
(12, 'charp.antoine@gmail.com', 'g', '$2y$10$NNVDB6jiFhOxhMENbuKG5.fqfrQsJ8GkXAltuBujHB7JPsSnXXlhC', 'MEMBER'),
(13, 'truc@mail.com', 'Truc', '$2y$10$cebPCrr1ziZzRsQOgJ.WD.E3u72Hlu3yPCVwvftwdtiTs1GwEdV5m', 'MEMBER');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `attributs`
--
ALTER TABLE `attributs`
  ADD PRIMARY KEY (`id_attribut`);

--
-- Index pour la table `cartes`
--
ALTER TABLE `cartes`
  ADD PRIMARY KEY (`id_carte`), ADD KEY `id_utilisateur` (`id_utilisateur`,`id_attribut`,`id_categorie`,`id_effet`), ADD KEY `FK_CARTE_ATTRIBUT` (`id_attribut`), ADD KEY `FK_CARTE_EFFET` (`id_effet`), ADD KEY `FK_CARTE_CATEGORIE` (`id_categorie`);

--
-- Index pour la table `carte_types`
--
ALTER TABLE `carte_types`
  ADD PRIMARY KEY (`id_carte`,`id_type`), ADD KEY `REL_TYPES` (`id_type`);

--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id_categorie`);

--
-- Index pour la table `effets`
--
ALTER TABLE `effets`
  ADD PRIMARY KEY (`id_effet`);

--
-- Index pour la table `types`
--
ALTER TABLE `types`
  ADD PRIMARY KEY (`id_type`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id_utilisateur`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `attributs`
--
ALTER TABLE `attributs`
  MODIFY `id_attribut` int(2) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT pour la table `cartes`
--
ALTER TABLE `cartes`
  MODIFY `id_carte` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=34;
--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `id_categorie` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT pour la table `effets`
--
ALTER TABLE `effets`
  MODIFY `id_effet` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT pour la table `types`
--
ALTER TABLE `types`
  MODIFY `id_type` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id_utilisateur` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `cartes`
--
ALTER TABLE `cartes`
ADD CONSTRAINT `FK_CARTE_ATTRIBUT` FOREIGN KEY (`id_attribut`) REFERENCES `attributs` (`id_attribut`),
ADD CONSTRAINT `FK_CARTE_CATEGORIE` FOREIGN KEY (`id_categorie`) REFERENCES `categories` (`id_categorie`),
ADD CONSTRAINT `FK_CARTE_EFFET` FOREIGN KEY (`id_effet`) REFERENCES `effets` (`id_effet`),
ADD CONSTRAINT `FK_CARTE_UTILISATEUR` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`);

--
-- Contraintes pour la table `carte_types`
--
ALTER TABLE `carte_types`
ADD CONSTRAINT `REL_CARTE` FOREIGN KEY (`id_carte`) REFERENCES `cartes` (`id_carte`) ON DELETE CASCADE,
ADD CONSTRAINT `REL_TYPES` FOREIGN KEY (`id_type`) REFERENCES `types` (`id_type`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
