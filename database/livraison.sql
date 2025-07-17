-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : jeu. 17 juil. 2025 à 07:08
-- Version du serveur : 5.7.24
-- Version de PHP : 8.1.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `ecodeli`
--

-- --------------------------------------------------------

--
-- Structure de la table `livraison`
--

CREATE TABLE `livraison` (
  `id_livraison` bigint(20) UNSIGNED NOT NULL,
  `id_annonce` bigint(20) UNSIGNED DEFAULT NULL,
  `id_livreur` bigint(20) UNSIGNED DEFAULT NULL,
  `id_utilisateur` bigint(20) UNSIGNED NOT NULL,
  `id_adresse_depart` bigint(20) UNSIGNED NOT NULL,
  `id_adresse_arrivee` bigint(20) UNSIGNED NOT NULL,
  `date_livraison` date DEFAULT NULL,
  `code_validation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `poids` double DEFAULT NULL,
  `fragile` tinyint(1) NOT NULL DEFAULT '0',
  `statut` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en_attente',
  `contenu` text COLLATE utf8mb4_unicode_ci,
  `date` datetime DEFAULT NULL,
  `modalite` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `livraison`
--

INSERT INTO `livraison` (`id_livraison`, `id_annonce`, `id_livreur`, `id_utilisateur`, `id_adresse_depart`, `id_adresse_arrivee`, `date_livraison`, `code_validation`, `poids`, `fragile`, `statut`, `contenu`, `date`, `modalite`, `type`, `created_at`, `updated_at`) VALUES
(2, 14, NULL, 3, 1, 3, '2025-07-15', '4ESXPXVW', 3.5, 1, 'livrée', 'vaisselle', '2025-07-14 16:05:39', 'Deluxe', NULL, NULL, NULL),
(3, 13, NULL, 3, 1, 3, '2025-07-16', 'OY7BVOSY', 3.5, 1, 'livrée', 'Vaisselle', '2025-07-15 08:55:06', 'Deluxe', NULL, NULL, NULL),
(4, 15, NULL, 3, 1, 3, '2025-07-17', '1MLXYWWK', 3.5, 1, 'livrée', 'Vaisselle', '2025-07-16 13:30:51', 'Deluxe', NULL, NULL, NULL),
(5, 19, NULL, 3, 1, 3, '2025-07-17', 'RAYSEJJW', 3, 0, 'en_attente', 'Café', '2025-07-16 14:21:08', 'Deluxe', NULL, NULL, NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `livraison`
--
ALTER TABLE `livraison`
  ADD PRIMARY KEY (`id_livraison`),
  ADD KEY `livraison_id_annonce_foreign` (`id_annonce`),
  ADD KEY `livraison_id_livreur_foreign` (`id_livreur`),
  ADD KEY `livraison_id_utilisateur_foreign` (`id_utilisateur`),
  ADD KEY `livraison_id_adresse_depart_foreign` (`id_adresse_depart`),
  ADD KEY `livraison_id_adresse_arrivee_foreign` (`id_adresse_arrivee`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `livraison`
--
ALTER TABLE `livraison`
  MODIFY `id_livraison` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `livraison`
--
ALTER TABLE `livraison`
  ADD CONSTRAINT `livraison_id_adresse_arrivee_foreign` FOREIGN KEY (`id_adresse_arrivee`) REFERENCES `addresse` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `livraison_id_adresse_depart_foreign` FOREIGN KEY (`id_adresse_depart`) REFERENCES `addresse` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `livraison_id_annonce_foreign` FOREIGN KEY (`id_annonce`) REFERENCES `annonce` (`id_annonce`) ON DELETE CASCADE,
  ADD CONSTRAINT `livraison_id_livreur_foreign` FOREIGN KEY (`id_livreur`) REFERENCES `livreur` (`id_livreur`) ON DELETE SET NULL,
  ADD CONSTRAINT `livraison_id_utilisateur_foreign` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
