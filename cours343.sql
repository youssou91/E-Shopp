-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 23 juil. 2024 à 00:49
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `cours343`
--

-- --------------------------------------------------------

--
-- Structure de la table `adresse`
--

CREATE TABLE `adresse` (
  `id_adresse` int(11) NOT NULL,
  `rue` varchar(100) NOT NULL,
  `ville` varchar(50) NOT NULL,
  `code_postal` varchar(10) NOT NULL,
  `pays` varchar(50) DEFAULT 'Canada',
  `numero` varchar(50) DEFAULT NULL,
  `province` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

CREATE TABLE `categorie` (
  `id_categorie` int(11) NOT NULL,
  `nom_categorie` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `categorie`
--

INSERT INTO `categorie` (`id_categorie`, `nom_categorie`) VALUES
(1, 'Boubou'),
(2, 'Pantalon'),
(3, 'T-shirt'),
(4, 'Chapeau'),
(5, 'Babouches');

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `id_commande` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `date_commande` date NOT NULL,
  `quantite` int(11) NOT NULL,
  `prix_total` varchar(10) NOT NULL,
  `id_statut` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`id_commande`, `id_utilisateur`, `date_commande`, `quantite`, `prix_total`, `id_statut`) VALUES
(1, 1, '2024-07-22', 0, '131', 1),
(3, 1, '2024-07-21', 0, '51', 2),
(4, 1, '2024-07-21', 0, '51', 3),
(5, 1, '2024-07-21', 0, '107', 1),
(6, 3, '2024-07-21', 0, '136', 1),
(7, 1, '2024-07-22', 0, '36', 1),
(8, 1, '2024-07-22', 0, '204', 1),
(9, 3, '2024-07-22', 0, '1060', 1),
(10, 3, '2024-07-22', 0, '649', 1),
(11, 1, '2024-07-22', 0, '330', 1),
(12, 1, '2024-07-22', 0, '191', 1);

-- --------------------------------------------------------

--
-- Structure de la table `image`
--

CREATE TABLE `image` (
  `id_image` int(11) NOT NULL,
  `id_produit` int(11) NOT NULL,
  `chemin_image` text DEFAULT NULL,
  `nom_image` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `image`
--

INSERT INTO `image` (`id_image`, `id_produit`, `chemin_image`, `nom_image`) VALUES
(11, 53, 'images/Image 006.jpeg', ''),
(14, 56, 'images/Image 010.jpeg', ''),
(15, 57, 'images/images (6).jpg', ''),
(16, 58, 'images/images (5).jpg', ''),
(17, 59, 'images/49.jpg', ''),
(18, 60, 'images/bf7e75f8488443a5a6c3b96452b3fc69.jpg', ''),
(19, 61, 'images/Blog-Atelier-Invitation-Instagram-publication-85.png', '');

-- --------------------------------------------------------

--
-- Structure de la table `produits`
--

CREATE TABLE `produits` (
  `id_produit` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prix_unitaire` float NOT NULL,
  `description` text DEFAULT NULL,
  `courte_description` varchar(250) NOT NULL,
  `quantite` int(11) DEFAULT 0,
  `id_categorie` int(11) NOT NULL,
  `taille_produit` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `produits`
--

INSERT INTO `produits` (`id_produit`, `nom`, `prix_unitaire`, `description`, `courte_description`, `quantite`, `id_categorie`, `taille_produit`) VALUES
(53, 'Polo ', 10, '                                                            courte description courte description courte description courte description                                         ', '                                                            courte description                                         ', 494, 3, 'Medium'),
(56, 't-shirt', 13, 'ererrerer', 'courte description edit', 56, 3, 'Large'),
(57, 'Babouches L', 55, 'babouches locales fabriques au pays par des artisants locaux', 'babouches locales', 8, 5, 'Medium'),
(58, 'Dallou-Ngaye', 60, 'sandale noirs  de très bonne qualité ', 'sandale noirs ', 19, 5, 'Medium'),
(59, 'Babouches A', 57, 'babouches locales', 'babouches locales', 54, 5, 'Large'),
(60, 'Thiarakh', 45, 'babouches localesbabouches localesbabouches localesbabouches localesbabouches locales', 'babouches locales', 100, 5, 'Large'),
(61, 'Babouches Y', 40, '            courte description courte description courte description courte description courte description         ', '            courte description         ', 80, 5, 'XL');

-- --------------------------------------------------------

--
-- Structure de la table `produit_commande`
--

CREATE TABLE `produit_commande` (
  `id_commande` int(11) NOT NULL,
  `id_produit` int(11) NOT NULL,
  `quantite` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `produit_commande`
--

INSERT INTO `produit_commande` (`id_commande`, `id_produit`, `quantite`) VALUES
(1, 56, 4),
(5, 53, 4),
(5, 56, 3),
(6, 56, 4),
(7, 53, 1),
(7, 56, 2),
(8, 56, 3),
(8, 57, 3),
(9, 57, 16),
(9, 58, 3),
(10, 53, 5),
(10, 56, 8),
(10, 57, 9),
(11, 57, 6),
(12, 56, 2),
(12, 57, 3);

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

CREATE TABLE `role` (
  `id_role` int(11) NOT NULL,
  `description` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `role`
--

INSERT INTO `role` (`id_role`, `description`) VALUES
(1, 'admin'),
(2, 'client');

-- --------------------------------------------------------

--
-- Structure de la table `role_utilisateur`
--

CREATE TABLE `role_utilisateur` (
  `id_role` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `role_utilisateur`
--

INSERT INTO `role_utilisateur` (`id_role`, `id_utilisateur`) VALUES
(2, 1),
(2, 3);

-- --------------------------------------------------------

--
-- Structure de la table `statuts_commande`
--

CREATE TABLE `statuts_commande` (
  `id_statut` int(11) NOT NULL,
  `description` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `statuts_commande`
--

INSERT INTO `statuts_commande` (`id_statut`, `description`) VALUES
(1, 'En attente'),
(2, 'En traitement'),
(3, 'Expédiée'),
(4, 'Livrée'),
(5, 'Annulée');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id_utilisateur` int(11) NOT NULL,
  `nom_utilisateur` varchar(100) NOT NULL,
  `prenom` varchar(100) DEFAULT NULL,
  `date_naissance` date DEFAULT NULL,
  `couriel` varchar(250) NOT NULL,
  `mot_de_pass` text NOT NULL,
  `telephone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id_utilisateur`, `nom_utilisateur`, `prenom`, `date_naissance`, `couriel`, `mot_de_pass`, `telephone`) VALUES
(1, 'NDONG', 'Lamine Sene', '2007-04-03', 'ndong.lamine@gmail.com', '$2y$10$6eH5cQ.u/pAs4P3/czC0hONEReUvvZQ7C4n0.QZgC8JE4t/BrM6IK', '5147483630'),
(3, 'GNING', 'Youssouf', '1997-07-07', 'gningyussuf@gmail.com', '$2y$10$fEh1MwjukD9GNXn7lH24aOhwNEyKtVd4ynUPTh3jTyY8I5Z7boF2S', '987654321');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur_adresse`
--

CREATE TABLE `utilisateur_adresse` (
  `id_utilisateur` int(11) NOT NULL,
  `id_adresse` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `adresse`
--
ALTER TABLE `adresse`
  ADD PRIMARY KEY (`id_adresse`);

--
-- Index pour la table `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`id_categorie`);

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`id_commande`),
  ADD KEY `id_utilisateur` (`id_utilisateur`),
  ADD KEY `fk_statut` (`id_statut`);

--
-- Index pour la table `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`id_image`),
  ADD KEY `id_produit` (`id_produit`);

--
-- Index pour la table `produits`
--
ALTER TABLE `produits`
  ADD PRIMARY KEY (`id_produit`),
  ADD KEY `fk_p_c` (`id_categorie`);

--
-- Index pour la table `produit_commande`
--
ALTER TABLE `produit_commande`
  ADD PRIMARY KEY (`id_commande`,`id_produit`),
  ADD KEY `id_produit` (`id_produit`);

--
-- Index pour la table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id_role`);

--
-- Index pour la table `role_utilisateur`
--
ALTER TABLE `role_utilisateur`
  ADD PRIMARY KEY (`id_role`,`id_utilisateur`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `statuts_commande`
--
ALTER TABLE `statuts_commande`
  ADD PRIMARY KEY (`id_statut`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id_utilisateur`),
  ADD UNIQUE KEY `couriel` (`couriel`);

--
-- Index pour la table `utilisateur_adresse`
--
ALTER TABLE `utilisateur_adresse`
  ADD PRIMARY KEY (`id_utilisateur`,`id_adresse`),
  ADD KEY `id_adresse` (`id_adresse`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `adresse`
--
ALTER TABLE `adresse`
  MODIFY `id_adresse` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `categorie`
--
ALTER TABLE `categorie`
  MODIFY `id_categorie` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `id_commande` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `image`
--
ALTER TABLE `image`
  MODIFY `id_image` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT pour la table `produits`
--
ALTER TABLE `produits`
  MODIFY `id_produit` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT pour la table `role`
--
ALTER TABLE `role`
  MODIFY `id_role` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `statuts_commande`
--
ALTER TABLE `statuts_commande`
  MODIFY `id_statut` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id_utilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `commande_ibfk_2` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`),
  ADD CONSTRAINT `fk_statut` FOREIGN KEY (`id_statut`) REFERENCES `statuts_commande` (`id_statut`);

--
-- Contraintes pour la table `image`
--
ALTER TABLE `image`
  ADD CONSTRAINT `image_ibfk_1` FOREIGN KEY (`id_produit`) REFERENCES `produits` (`id_produit`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `produits`
--
ALTER TABLE `produits`
  ADD CONSTRAINT `fk_p_c` FOREIGN KEY (`id_categorie`) REFERENCES `categorie` (`id_categorie`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `produit_commande`
--
ALTER TABLE `produit_commande`
  ADD CONSTRAINT `produit_commande_ibfk_1` FOREIGN KEY (`id_commande`) REFERENCES `commande` (`id_commande`),
  ADD CONSTRAINT `produit_commande_ibfk_2` FOREIGN KEY (`id_produit`) REFERENCES `produits` (`id_produit`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `role_utilisateur`
--
ALTER TABLE `role_utilisateur`
  ADD CONSTRAINT `role_utilisateur_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `role_utilisateur_ibfk_2` FOREIGN KEY (`id_role`) REFERENCES `role` (`id_role`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `utilisateur_adresse`
--
ALTER TABLE `utilisateur_adresse`
  ADD CONSTRAINT `utilisateur_adresse_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`),
  ADD CONSTRAINT `utilisateur_adresse_ibfk_2` FOREIGN KEY (`id_adresse`) REFERENCES `adresse` (`id_adresse`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
