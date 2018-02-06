-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Ven 26 Mai 2017 à 17:29
-- Version du serveur :  5.7.17-0ubuntu0.16.04.1
-- Version de PHP :  7.0.15-0ubuntu0.16.04.4

--
-- Base de données :  `monitoring_activite`
--

-- --------------------------------------------------------

--
-- Structure de la table `ACTIVITE`
--

CREATE TABLE `ACTIVITE` (
  `id_activite` int(11) NOT NULL,
  `date_debut` datetime NOT NULL,
  `date_fin` datetime NOT NULL,
  `type_activite` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Structure de la table `ANOMALIE_SONORE`
--

CREATE TABLE `ANOMALIE_SONORE` (
  `id_as` int(11) NOT NULL,
  `niveau` float NOT NULL,
  `date_debut` datetime NOT NULL,
  `date_fin` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `CONF_ANOMALIE_SONORE`
--

CREATE TABLE `CONF_ANOMALIE_SONORE` (
  `id_conf_as` int(11) NOT NULL,
  `heure_debut` time NOT NULL,
  `heure_fin` time NOT NULL,
  `seuil` int(11) NOT NULL,
  `duree` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `CONF_ANOMALIE_SONORE`
--

INSERT INTO `CONF_ANOMALIE_SONORE` (`id_conf_as`, `heure_debut`, `heure_fin`, `seuil`, `duree`) VALUES
(3, '00:00:00', '08:00:00', 20, 2),
(4, '08:00:00', '00:00:00', 60, 4);

-- --------------------------------------------------------

--
-- Structure de la table `TYPE_ACTIVITE`
--

CREATE TABLE `TYPE_ACTIVITE` (
  `type_activite` varchar(50) NOT NULL,
  `label` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `TYPE_ACTIVITE`
--

INSERT INTO `TYPE_ACTIVITE` (`type_activite`, `label`) VALUES
('chambre', 'Localisation : chambre'),
('cuisine', 'Localisation : cuisine'),
('salon', 'Localisation : salon'),
('tv_on', 'Visionnage TV');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `ACTIVITE`
--
ALTER TABLE `ACTIVITE`
  ADD PRIMARY KEY (`id_activite`),
  ADD KEY `type_activite` (`type_activite`);

--
-- Index pour la table `ANOMALIE_SONORE`
--
ALTER TABLE `ANOMALIE_SONORE`
  ADD PRIMARY KEY (`id_as`);

--
-- Index pour la table `CONF_ANOMALIE_SONORE`
--
ALTER TABLE `CONF_ANOMALIE_SONORE`
  ADD PRIMARY KEY (`id_conf_as`);

--
-- Index pour la table `TYPE_ACTIVITE`
--
ALTER TABLE `TYPE_ACTIVITE`
  ADD PRIMARY KEY (`type_activite`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `ACTIVITE`
--
ALTER TABLE `ACTIVITE`
  MODIFY `id_activite` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=621;
--
-- AUTO_INCREMENT pour la table `ANOMALIE_SONORE`
--
ALTER TABLE `ANOMALIE_SONORE`
  MODIFY `id_as` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;
--
-- AUTO_INCREMENT pour la table `CONF_ANOMALIE_SONORE`
--
ALTER TABLE `CONF_ANOMALIE_SONORE`
  MODIFY `id_conf_as` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `ACTIVITE`
--
ALTER TABLE `ACTIVITE`
  ADD CONSTRAINT `ACTIVITE_ibfk_1` FOREIGN KEY (`type_activite`) REFERENCES `TYPE_ACTIVITE` (`type_activite`);

