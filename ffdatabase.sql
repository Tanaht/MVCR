-- phpMyAdmin SQL Dump
-- version 4.4.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Ven 18 Mars 2016 à 00:48
-- Version du serveur :  5.6.26
-- Version de PHP :  5.6.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `ffdatabase`
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
  `id_type` int(1) DEFAULT NULL,
  `id_categorie` int(1) DEFAULT NULL,
  `id_effet` int(11) DEFAULT NULL,
  `nom` varchar(40) NOT NULL,
  `niveau` int(2) DEFAULT NULL,
  `attaque` varchar(6) DEFAULT NULL,
  `défense` varchar(6) DEFAULT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `cartes`
--

INSERT INTO `cartes` (`id_carte`, `id_utilisateur`, `id_attribut`, `id_type`, `id_categorie`, `id_effet`, `nom`, `niveau`, `attaque`, `défense`, `description`) VALUES
(1, 1, 1, 14, 1, NULL, 'Magicien Sombre', 7, '200', '2100', 'Mage Suprême en termes d''attaque et de défense.');

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id_categorie` int(11) NOT NULL,
  `nom` varchar(20) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `categories`
--

INSERT INTO `categories` (`id_categorie`, `nom`) VALUES
(1, 'Normale'),
(2, 'Effet'),
(3, 'Fusion'),
(4, 'Synchro'),
(5, 'XYZ'),
(6, 'Pendullum');

-- --------------------------------------------------------

--
-- Structure de la table `effets`
--

CREATE TABLE IF NOT EXISTS `effets` (
  `id_effet` int(11) NOT NULL,
  `nom` varchar(20) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

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
(9, 'Equipement');

-- --------------------------------------------------------

--
-- Structure de la table `types`
--

CREATE TABLE IF NOT EXISTS `types` (
  `id_type` int(11) NOT NULL,
  `nom` varchar(20) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

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
(24, 'Toon et Zombie');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id_utilisateur` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(30) NOT NULL,
  `sudo` varchar(15) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id_utilisateur`, `email`, `username`, `password`, `sudo`) VALUES
(1, 'anonymous@gmail.com', 'Anonymous', '', 'admin');

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
  ADD PRIMARY KEY (`id_carte`),
  ADD KEY `id_utilisateur` (`id_utilisateur`,`id_attribut`,`id_type`,`id_categorie`,`id_effet`),
  ADD KEY `FK_CARTE_ATTRIBUT` (`id_attribut`),
  ADD KEY `FK_CARTE_TYPE` (`id_type`),
  ADD KEY `FK_CARTE_EFFET` (`id_effet`),
  ADD KEY `FK_CARTE_CATEGORIE` (`id_categorie`);

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
  MODIFY `id_carte` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `id_categorie` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT pour la table `effets`
--
ALTER TABLE `effets`
  MODIFY `id_effet` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT pour la table `types`
--
ALTER TABLE `types`
  MODIFY `id_type` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id_utilisateur` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
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
  ADD CONSTRAINT `FK_CARTE_TYPE` FOREIGN KEY (`id_type`) REFERENCES `types` (`id_type`),
  ADD CONSTRAINT `FK_CARTE_UTILISATEUR` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
