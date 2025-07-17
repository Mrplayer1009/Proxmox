-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : jeu. 17 juil. 2025 à 06:01
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
-- Structure de la table `abonnement`
--

CREATE TABLE `abonnement` (
  `id_abonnement` bigint(20) UNSIGNED NOT NULL,
  `id_utilisateur` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_debut` date DEFAULT NULL,
  `date_fin` date DEFAULT NULL,
  `statut` enum('actif','inactif') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'actif',
  `prix` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `abonnement`
--

INSERT INTO `abonnement` (`id_abonnement`, `id_utilisateur`, `nom`, `date_debut`, `date_fin`, `statut`, `prix`, `created_at`, `updated_at`) VALUES
(3, 3, 'Premium', '2025-07-14', '2025-08-14', 'actif', '19.98', NULL, NULL),
(4, 3, 'Basic', '2025-07-14', '2026-07-14', 'actif', '9.99', '2025-07-14 07:29:27', '2025-07-14 07:29:27'),
(5, 3, 'Premium', '2025-07-14', '2026-07-14', 'actif', '19.99', '2025-07-14 07:35:27', '2025-07-14 07:35:27'),
(6, 3, 'Deluxe', '2025-07-14', '2026-07-14', 'actif', '29.99', '2025-07-14 07:41:03', '2025-07-14 07:41:03');

-- --------------------------------------------------------

--
-- Structure de la table `addresse`
--

CREATE TABLE `addresse` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `rue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ville` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_postal` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `addresse`
--

INSERT INTO `addresse` (`id`, `rue`, `ville`, `code_postal`, `created_at`, `updated_at`) VALUES
(1, 'les fromagers', 'Paris', '75001', '2025-07-14 12:25:07', '2025-07-17 03:38:07'),
(2, 'Les marseillais', 'Marseille', '13055', '2025-07-14 12:26:51', '2025-07-14 12:26:51'),
(3, '8 boulevard Haussmann', 'Paris', '75005', NULL, '2025-07-17 03:29:19'),
(4, '23 rue Sainte-Catherine', 'Bordeaux', '33000', NULL, NULL),
(5, '17 rue Nationale', 'Lille', '59000', NULL, NULL),
(6, '10 chemin des Acacias', 'Toulouse', '31000', NULL, NULL),
(7, '5 place Bellecour', 'Lyon', '69002', NULL, NULL),
(8, '66 rue de Rome', 'Marseille', '13006', NULL, NULL),
(9, '3 rue Alsace Lorraine', 'Nantes', '44000', NULL, NULL),
(10, '99 avenue Victor Hugo', 'Nice', '06000', NULL, NULL),
(11, 'AAAA', 'Sainte-Geneviève-Lès-Gasny', '27620', '2025-07-16 14:09:51', '2025-07-16 14:09:51'),
(12, '12 rue madrid', 'Madrid', '88008', '2025-07-16 17:43:44', '2025-07-16 17:43:44');

-- --------------------------------------------------------

--
-- Structure de la table `annonce`
--

CREATE TABLE `annonce` (
  `id_annonce` bigint(20) UNSIGNED NOT NULL,
  `id_utilisateur` bigint(20) UNSIGNED NOT NULL,
  `titre` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_addresse` int(11) NOT NULL,
  `nombre` int(11) DEFAULT '1',
  `poids` decimal(5,2) DEFAULT NULL,
  `fragile` tinyint(1) DEFAULT '0',
  `description` text COLLATE utf8mb4_unicode_ci,
  `prix` decimal(10,2) NOT NULL,
  `statut` enum('active','en_cours','terminée','annulée') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `date_limite` date DEFAULT NULL,
  `type_colis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `annonce`
--

INSERT INTO `annonce` (`id_annonce`, `id_utilisateur`, `titre`, `id_addresse`, `nombre`, `poids`, `fragile`, `description`, `prix`, `statut`, `date_limite`, `type_colis`, `created_at`, `updated_at`) VALUES
(1, 3, 'Carotte', 1, 1, '0.00', 0, 'Fruit', '10.00', 'en_cours', NULL, 'Alimentaire', NULL, NULL),
(13, 1, 'Vaisselle', 1, 1, '3.50', 1, 'Colis fragile contenant de la vaisselle', '25.00', 'terminée', '2025-07-05', 'fragile', '2025-06-27 07:06:27', '2025-06-27 07:06:27'),
(14, 2, 'Vaisselle', 1, 1, '3.50', 1, 'Colis fragile contenant de la vaisselle', '25.00', 'terminée', '2025-07-05', 'fragile', '2025-06-27 07:06:27', '2025-06-27 07:06:27'),
(15, 3, 'Vaisselle', 1, 0, '3.50', 1, 'Colis fragile contenant de la vaisselle', '25.00', 'terminée', '2025-07-05', 'fragile', '2025-06-27 07:06:27', '2025-06-27 07:06:27'),
(16, 4, 'Vaisselle', 1, 1, '3.50', 1, 'Colis fragile contenant de la vaisselle', '25.00', 'en_cours', '2025-07-05', 'fragile', '2025-06-27 07:06:27', '2025-06-27 07:06:27'),
(17, 5, 'Vaisselle', 1, 1, '3.50', 1, 'Colis fragile contenant de la vaisselle', '25.00', 'en_cours', '2025-07-05', 'fragile', '2025-06-27 07:06:27', '2025-06-27 07:06:27'),
(19, 3, 'Café', 1, 3, '3.00', 0, 'Du café', '5.00', 'terminée', NULL, 'Alimentaire', NULL, NULL),
(20, 3, 'Croquette', 1, 1, '1.00', 1, 'aa', '10.00', 'en_cours', NULL, 'Alimentaire', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `annonce_prestation`
--

CREATE TABLE `annonce_prestation` (
  `id_annonce_prestation` bigint(20) UNSIGNED NOT NULL,
  `id_prestataire` bigint(20) UNSIGNED NOT NULL,
  `titre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `prix` decimal(8,2) DEFAULT NULL,
  `statut` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en_cours',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `annonce_prestation`
--

INSERT INTO `annonce_prestation` (`id_annonce_prestation`, `id_prestataire`, `titre`, `description`, `prix`, `statut`, `created_at`, `updated_at`) VALUES
(1, 1, 'Jardinier', 'aaa', '10.00', 'en_cours', '2025-07-16 06:28:55', '2025-07-16 06:28:55'),
(2, 1, 'Plombier', 'aaa', '10.00', 'en_cours', '2025-07-16 06:29:16', '2025-07-16 06:29:16');

-- --------------------------------------------------------

--
-- Structure de la table `batiment`
--

CREATE TABLE `batiment` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_addresse` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `batiment`
--

INSERT INTO `batiment` (`id`, `nom`, `id_addresse`, `created_at`, `updated_at`) VALUES
(1, 'Entrepot Paris', 1, '2025-07-14 12:25:07', '2025-07-14 12:25:07'),
(2, 'Entrepot Marseille', 2, '2025-07-14 12:26:51', '2025-07-14 12:26:51'),
(3, 'Entrepot Madrid', 12, '2025-07-16 17:43:44', '2025-07-16 17:43:44');

-- --------------------------------------------------------

--
-- Structure de la table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `commercants`
--

CREATE TABLE `commercants` (
  `id_commercant` bigint(20) UNSIGNED NOT NULL,
  `id_utilisateur` int(11) DEFAULT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adresse` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `commercants`
--

INSERT INTO `commercants` (`id_commercant`, `id_utilisateur`, `nom`, `email`, `telephone`, `adresse`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Ecoco', 'loicraoult1009@gmail.com', '109210', 10, '2025-07-09 14:52:17', '2025-07-09 14:52:17');

-- --------------------------------------------------------

--
-- Structure de la table `contrats`
--

CREATE TABLE `contrats` (
  `id_contrat` bigint(20) UNSIGNED NOT NULL,
  `id_commercant` bigint(20) UNSIGNED NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date DEFAULT NULL,
  `statut` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'actif',
  `fichier_pdf` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `contrats`
--

INSERT INTO `contrats` (`id_contrat`, `id_commercant`, `date_debut`, `date_fin`, `statut`, `fichier_pdf`, `created_at`, `updated_at`) VALUES
(1, 1, '2025-07-01', '2025-11-09', 'actif', 'contrats/JhXWvy6pMFNXGL8XoVNLqYu83CqdSLlUZlBnbo5P.pdf', '2025-07-09 14:52:38', '2025-07-16 18:30:47'),
(4, 1, '2025-07-18', '2025-07-31', 'actif', 'contrats/JhXWvy6pMFNXGL8XoVNLqYu83CqdSLlUZlBnbo5P.pdf', '2025-07-16 18:30:06', '2025-07-16 18:30:49');

-- --------------------------------------------------------

--
-- Structure de la table `evaluations`
--

CREATE TABLE `evaluations` (
  `id_evaluation` bigint(20) UNSIGNED NOT NULL,
  `id_prestataire` bigint(20) UNSIGNED NOT NULL,
  `id_client` bigint(20) UNSIGNED NOT NULL,
  `note` tinyint(3) UNSIGNED NOT NULL,
  `commentaire` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `factures`
--

CREATE TABLE `factures` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_prestataire` bigint(20) UNSIGNED NOT NULL,
  `mois` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_generation` date NOT NULL,
  `montant` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `factures`
--

INSERT INTO `factures` (`id`, `id_prestataire`, `mois`, `date_generation`, `montant`) VALUES
(1, 1, '2025-07', '2025-07-05', '120.50'),
(2, 1, '2025-07', '2025-07-10', '200.00'),
(3, 1, '2025-07', '2025-07-15', '89.99'),
(4, 1, '2025-07', '2025-07-20', '175.75'),
(5, 1, '2025-07', '2025-07-28', '300.25');

-- --------------------------------------------------------

--
-- Structure de la table `langue`
--

CREATE TABLE `langue` (
  `id_langue` bigint(20) UNSIGNED NOT NULL,
  `code_langue` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom_langue` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `activee` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `langue`
--

INSERT INTO `langue` (`id_langue`, `code_langue`, `nom_langue`, `activee`) VALUES
(1, 'fr', 'Français', 1),
(2, 'en', 'English', 1);

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

-- --------------------------------------------------------

--
-- Structure de la table `livreur`
--

CREATE TABLE `livreur` (
  `id_livreur` bigint(20) UNSIGNED NOT NULL,
  `id_utilisateur` bigint(20) UNSIGNED NOT NULL,
  `pieces_justificatives` text COLLATE utf8mb4_unicode_ci,
  `note_moyenne` decimal(3,2) NOT NULL DEFAULT '0.00',
  `solde_portefeuille` decimal(10,2) NOT NULL DEFAULT '0.00',
  `statut_validation` enum('en_attente','validé','refusé') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en_attente',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `livreur`
--

INSERT INTO `livreur` (`id_livreur`, `id_utilisateur`, `pieces_justificatives`, `note_moyenne`, `solde_portefeuille`, `statut_validation`, `created_at`, `updated_at`) VALUES
(1, 3, 'pieces_livreur/mZViK42hm6qxgKGgooCOoskyUF5kdUPYcs5YBe6Z.pdf', '0.00', '0.00', 'validé', '2025-07-07 14:22:26', '2025-07-07 14:39:24'),
(2, 4, 'pieces_livreur/GmzKxFO2iI9JfghHra6rxcQrMruugDGhEskVtWEa.png', '0.00', '0.00', 'validé', '2025-07-13 12:20:22', '2025-07-13 12:21:06');

-- --------------------------------------------------------

--
-- Structure de la table `localisations`
--

CREATE TABLE `localisations` (
  `id_localisation` bigint(20) UNSIGNED NOT NULL,
  `livraison_id` bigint(20) UNSIGNED NOT NULL,
  `id_adresse` bigint(20) UNSIGNED DEFAULT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ordre` int(11) NOT NULL DEFAULT '0',
  `cree_le` datetime DEFAULT NULL,
  `modifie_le` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `localisations`
--

INSERT INTO `localisations` (`id_localisation`, `livraison_id`, `id_adresse`, `nom`, `ordre`, `cree_le`, `modifie_le`) VALUES
(1, 2, NULL, 'Entrepot Marseille', 0, '2025-07-15 10:16:03', '2025-07-15 10:16:03'),
(2, 2, NULL, 'Entrepot Paris', 1, '2025-07-15 10:16:15', '2025-07-15 10:16:15'),
(3, 2, NULL, 'Entrepot Paris', 2, '2025-07-15 10:18:21', '2025-07-15 10:18:21'),
(4, 2, NULL, 'Entrepot Paris', 3, '2025-07-15 11:46:16', '2025-07-15 11:46:16'),
(5, 4, NULL, 'Entrepot Marseille', 0, '2025-07-16 13:33:31', '2025-07-16 13:33:31'),
(6, 5, NULL, 'Entrepot Paris', 0, '2025-07-17 05:21:58', '2025-07-17 05:21:58'),
(7, 5, NULL, 'Entrepot Paris', 1, '2025-07-17 05:24:42', '2025-07-17 05:24:42'),
(8, 5, NULL, 'Entrepot Paris', 2, '2025-07-17 05:26:13', '2025-07-17 05:26:13');

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2025_06_25_152732_create_sessions_table', 1),
(2, '2025_06_25_202917_create_cache_table', 1),
(3, '2025_06_26_090800_create_abonnement_table', 1),
(4, '2025_06_26_090850_create_langue_table', 1),
(5, '2025_06_26_090901_create_livraison_table', 1),
(6, '2025_06_26_090907_create_livreur_table', 1),
(7, '2025_06_26_090931_create_notification_table', 1),
(8, '2025_06_26_090940_create_paiement_table', 1),
(9, '2025_06_26_090951_create_planning_table', 1),
(10, '2025_06_26_091000_create_prestataire_table', 1),
(11, '2025_06_26_091011_create_annonce_table', 1),
(12, '2025_06_26_092341_create_users_table', 1),
(13, '2025_06_26_094408_create_utilisateur_table', 1),
(14, '2024_06_01_000001_create_delivery_locations_table', 2),
(15, '2024_07_01_000004_create_reservations_table', 3),
(16, '2024_07_01_000005_create_factures_table', 4),
(17, '2024_07_01_000010_create_abonnements_table', 5),
(18, '2025_07_09_154058_create_commercants_table', 6),
(19, '2025_07_09_154112_create_contrats_table', 6),
(20, '2025_07_09_154200_create_planning_table', 7),
(29, '2025_07_09_154300_create_addresse_table', 8),
(30, '2025_07_09_154410_create_livraison_table', 8),
(31, '2025_07_09_154420_create_localisations_table', 8);

-- --------------------------------------------------------

--
-- Structure de la table `notification`
--

CREATE TABLE `notification` (
  `id_notification` bigint(20) UNSIGNED NOT NULL,
  `id_utilisateur` bigint(20) UNSIGNED NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lue` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `paiement`
--

CREATE TABLE `paiement` (
  `id_paiement` bigint(20) UNSIGNED NOT NULL,
  `id_utilisateur` bigint(20) UNSIGNED NOT NULL,
  `montant` decimal(10,2) NOT NULL,
  `info` text COLLATE utf8mb4_unicode_ci,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `methode` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `statut` enum('en_attente','validé','refusé','remboursé') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en_attente',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `paiement`
--

INSERT INTO `paiement` (`id_paiement`, `id_utilisateur`, `montant`, `info`, `date`, `methode`, `statut`, `created_at`, `updated_at`) VALUES
(1, 1, '49.99', NULL, '2025-06-20 00:00:00', 'carte', 'validé', '2025-06-27 05:54:47', '2025-06-27 05:54:47'),
(2, 2, '120.00', NULL, '2025-06-21 00:00:00', 'paypal', 'en_attente', '2025-06-27 05:54:47', '2025-06-27 05:54:47'),
(3, 3, '75.50', NULL, '2025-06-22 00:00:00', 'virement', 'validé', '2025-06-27 05:54:47', '2025-06-27 05:54:47'),
(4, 1, '15.00', NULL, '2025-06-23 00:00:00', 'carte', 'refusé', '2025-06-27 05:54:47', '2025-06-27 05:54:47'),
(5, 4, '200.00', NULL, '2025-06-24 00:00:00', 'espèces', 'validé', '2025-06-27 05:54:47', '2025-06-27 05:54:47'),
(9, 2, '120.00', NULL, '2025-06-21 00:00:00', 'paypal', 'en_attente', '2025-06-27 05:56:00', '2025-06-27 05:56:00'),
(10, 2, '15.00', NULL, '2025-06-23 00:00:00', 'carte', 'refusé', '2025-06-27 05:56:00', '2025-06-27 05:56:00'),
(11, 3, '39.98', NULL, '2025-07-09 17:50:58', 'stripe', 'validé', NULL, NULL),
(12, 3, '9.99', NULL, '2025-07-14 09:29:27', 'stripe', 'validé', NULL, NULL),
(13, 3, '19.99', NULL, '2025-07-14 09:35:27', 'stripe', 'validé', NULL, NULL),
(14, 3, '29.99', 'abonnement Deluxe', '2025-07-14 09:41:03', 'stripe', 'validé', NULL, NULL),
(15, 3, '25.00', 'annonce Vaisselle', '2025-07-14 11:55:14', 'stripe', 'validé', NULL, NULL),
(16, 3, '25.00', 'annonce Vaisselle', '2025-07-14 11:55:54', 'stripe', 'validé', NULL, NULL),
(17, 3, '25.00', 'annonce Vaisselle', '2025-07-14 11:58:33', 'stripe', 'validé', NULL, NULL),
(18, 3, '10.00', 'annonce Carotte', '2025-07-14 12:02:50', 'stripe', 'validé', NULL, NULL),
(19, 3, '10.00', 'annonce Carotte', '2025-07-14 12:03:12', 'stripe', 'validé', NULL, NULL),
(20, 3, '5.00', 'annonce Café', '2025-07-14 12:07:55', 'stripe', 'validé', NULL, NULL),
(21, 3, '25.00', 'annonce Vaisselle', '2025-07-14 14:33:55', 'stripe', 'validé', NULL, NULL),
(22, 3, '25.00', 'annonce Vaisselle', '2025-07-14 14:38:29', 'stripe', 'validé', NULL, NULL),
(23, 3, '25.00', 'annonce Vaisselle', '2025-07-14 14:44:29', 'stripe', 'validé', NULL, NULL),
(24, 3, '25.00', 'annonce Vaisselle', '2025-07-14 14:47:16', 'stripe', 'validé', NULL, NULL),
(25, 3, '25.00', 'annonce Vaisselle', '2025-07-14 14:47:46', 'stripe', 'validé', NULL, NULL),
(26, 3, '25.00', 'annonce Vaisselle', '2025-07-14 14:48:56', 'stripe', 'validé', NULL, NULL),
(27, 3, '25.00', 'annonce Vaisselle', '2025-07-14 14:59:41', 'stripe', 'validé', NULL, NULL),
(28, 3, '25.00', 'annonce Vaisselle', '2025-07-14 16:05:39', 'stripe', 'validé', NULL, NULL),
(29, 3, '25.00', 'annonce Vaisselle', '2025-07-15 08:55:06', 'stripe', 'validé', NULL, NULL),
(30, 3, '2.00', NULL, '2025-07-15 13:49:59', 'stripe', 'validé', NULL, NULL),
(31, 3, '1.99', NULL, '2025-07-15 14:04:16', 'stripe', 'validé', NULL, NULL),
(32, 3, '8.00', NULL, '2025-07-15 14:14:32', 'stripe', 'validé', NULL, NULL),
(33, 3, '44.89', NULL, '2025-07-15 14:27:23', 'stripe', 'validé', NULL, NULL),
(34, 3, '44.89', NULL, '2025-07-15 14:46:20', 'stripe', 'validé', NULL, NULL),
(35, 3, '25.00', 'annonce Vaisselle', '2025-07-16 13:30:51', 'stripe', 'validé', NULL, NULL),
(36, 3, '5.00', 'annonce Café', '2025-07-16 14:21:08', 'stripe', 'validé', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `paiement_livreur`
--

CREATE TABLE `paiement_livreur` (
  `id_paiement` bigint(20) UNSIGNED NOT NULL,
  `id_livreur` bigint(20) UNSIGNED NOT NULL,
  `montant` decimal(10,2) NOT NULL,
  `date_paiement` date NOT NULL,
  `methode_paiement` varchar(100) NOT NULL,
  `statut_paiement` varchar(50) NOT NULL,
  `cree_le` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modifie_le` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `paiement_livreur`
--

INSERT INTO `paiement_livreur` (`id_paiement`, `id_livreur`, `montant`, `date_paiement`, `methode_paiement`, `statut_paiement`, `cree_le`, `modifie_le`) VALUES
(6, 1, '5.00', '2025-07-01', 'Virement bancaire', 'effectué', '2025-07-09 13:12:57', '2025-07-13 11:45:46'),
(7, 1, '8.00', '2025-07-03', 'Espèces', 'en attente', '2025-07-09 13:12:57', '2025-07-13 11:45:52'),
(8, 1, '15.00', '2025-07-05', 'Chèque', 'effectué', '2025-07-09 13:12:57', '2025-07-13 11:45:55'),
(9, 1, '8.00', '2025-07-08', 'Virement bancaire', 'effectué', '2025-07-09 13:12:57', '2025-07-13 11:45:58'),
(10, 1, '6.25', '2025-07-08', 'Espèces', 'effectué', '2025-07-09 13:12:57', '2025-07-13 11:46:01');

-- --------------------------------------------------------

--
-- Structure de la table `planning`
--

CREATE TABLE `planning` (
  `id_planning` bigint(20) UNSIGNED NOT NULL,
  `id_livreur` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `lieu_arrivee` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `planning`
--

INSERT INTO `planning` (`id_planning`, `id_livreur`, `date`, `lieu_arrivee`, `description`, `created_at`, `updated_at`) VALUES
(2, 1, '2025-07-14', '1', NULL, '2025-07-13 12:15:28', '2025-07-13 12:15:28'),
(3, 2, '2025-07-15', '2', NULL, '2025-07-13 12:23:03', '2025-07-13 12:33:49');

-- --------------------------------------------------------

--
-- Structure de la table `prestataire`
--

CREATE TABLE `prestataire` (
  `id_prestataire` bigint(20) UNSIGNED NOT NULL,
  `id_utilisateur` bigint(20) UNSIGNED NOT NULL,
  `piece_justificative` text COLLATE utf8mb4_unicode_ci,
  `nom_entreprise` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `siret` varchar(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `adresse` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `statut_validation` enum('en_attente','validé','refusé') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en_attente',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `prestataire`
--

INSERT INTO `prestataire` (`id_prestataire`, `id_utilisateur`, `piece_justificative`, `nom_entreprise`, `siret`, `adresse`, `telephone`, `statut_validation`, `created_at`, `updated_at`) VALUES
(1, 3, 'pieces_prestataire/Pd7rqeBF8qphR4qw2qDYr94KmwHVB3h4fMMdhd78.pdf', 'Ecodeli', '1009001', 'Paris', '0770256895', 'validé', '2025-06-29 14:17:52', '2025-07-09 09:00:58'),
(2, 7, 'pieces_prestataire/gw9TKNercJIvrdAXuWgs6fFcRIiqiwr7rYKGwlyZ.jpg', 'Poufpouf', '12121', '30 rue bourgon, Paris', '09212342', 'en_attente', '2025-07-09 09:06:56', '2025-07-09 09:07:11');

-- --------------------------------------------------------

--
-- Structure de la table `prestations`
--

CREATE TABLE `prestations` (
  `id_prestation` bigint(20) UNSIGNED NOT NULL,
  `id_prestataire` bigint(20) UNSIGNED NOT NULL,
  `id_utilisateur` int(11) DEFAULT NULL,
  `nom` varchar(255) NOT NULL,
  `habilitation` varchar(255) DEFAULT NULL,
  `tarif` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `prestations`
--

INSERT INTO `prestations` (`id_prestation`, `id_prestataire`, `id_utilisateur`, `nom`, `habilitation`, `tarif`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'Entretien de jardin', 'Certificat Espaces Verts', '75.00', '2025-07-01 08:00:00', '2025-07-01 08:00:00'),
(2, 1, NULL, 'Nettoyage intérieur', NULL, '50.00', '2025-07-02 12:00:00', '2025-07-02 12:00:00'),
(3, 1, NULL, 'Réparation électroménager', 'Habilitation Électrique', '90.00', '2025-07-03 07:00:00', '2025-07-03 07:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `produits`
--

CREATE TABLE `produits` (
  `id_produits` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `description` text,
  `prix` decimal(10,2) DEFAULT NULL,
  `quantite` int(11) DEFAULT '0',
  `affiche` tinyint(1) NOT NULL DEFAULT '1',
  `image_url` varchar(500) DEFAULT NULL,
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  `date_mise_a_jour` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `id_commercant` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `produits`
--

INSERT INTO `produits` (`id_produits`, `nom`, `description`, `prix`, `quantite`, `affiche`, `image_url`, `date_creation`, `date_mise_a_jour`, `id_commercant`) VALUES
(1, 'T-shirt en coton bio', 'T-shirt unisexe 100% coton biologique, taille M', '19.99', 44, 1, 'https://example.com/images/tshirt.jpg', '2025-06-27 09:10:39', '2025-07-16 22:31:10', 1),
(2, 'Gourde inox 750ml', 'Gourde isotherme en acier inoxydable, garde le froid 24h', '24.90', 22, 0, 'https://example.com/images/gourde.jpg', '2025-06-27 09:10:39', '2025-07-15 16:46:20', 1),
(3, 'Sac à dos éco-responsable', 'Sac à dos fabriqué à partir de matériaux recyclés, 20L', '59.00', 15, 0, 'https://example.com/images/sacados.jpg', '2025-06-27 09:10:39', '2025-07-09 18:57:30', 1),
(4, 'Carotte', '2carotte', '10.00', 10, 1, NULL, '2025-06-29 18:04:03', '2025-07-15 15:34:59', 1),
(5, 'Carotte', '2carotte', '10.00', 10, 1, NULL, '2025-06-29 18:06:21', '2025-07-09 18:57:35', 1),
(6, 'Carotte', 'botte de carotte', '2.00', 1, 1, '/storage/produits/9rplei7k7x7RcdB0vLr51BVL9ql6rtHHsAkkDsng.png', '2025-07-09 19:02:46', '2025-07-15 16:14:32', 1),
(7, 'Carotte', 'Botte carotte', '2.00', 11, 1, '/storage/produits/Ll7RCXyMz0nlTaj2yZZYuHYLHmqMbupmSDNuvuWD.png', '2025-07-09 19:05:10', '2025-07-15 15:49:59', 1),
(8, 'Coucou', 'canarie', '1.00', 10, 1, NULL, '2025-07-09 19:06:48', '2025-07-09 19:06:48', NULL),
(9, 'Peta', 'aa', '1.00', 1, 1, NULL, '2025-07-09 19:07:25', '2025-07-09 19:07:25', NULL),
(10, 'Peta', 'aaa', '1.99', 0, 1, '/storage/produits/436SrgpN7FcRd1d8HGeemCxn8SOjwMlK9N7YKV7A.png', '2025-07-09 19:09:53', '2025-07-15 16:04:16', 1),
(11, 'Coco', 'test', '10.00', 5, 1, NULL, '2025-07-16 20:49:55', '2025-07-16 20:49:55', 1);

-- --------------------------------------------------------

--
-- Structure de la table `reservations`
--

CREATE TABLE `reservations` (
  `id_reservation` bigint(20) UNSIGNED NOT NULL,
  `id_prestataire` bigint(20) UNSIGNED NOT NULL,
  `id_client` bigint(20) UNSIGNED NOT NULL,
  `titre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `heure_debut` time NOT NULL,
  `heure_fin` time NOT NULL,
  `id_addresse` int(11) NOT NULL,
  `note` int(11) DEFAULT NULL,
  `commentaire` text COLLATE utf8mb4_unicode_ci,
  `statut` enum('en_attente','validée','refusée','annulée') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en_attente',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `reservations`
--

INSERT INTO `reservations` (`id_reservation`, `id_prestataire`, `id_client`, `titre`, `date`, `heure_debut`, `heure_fin`, `id_addresse`, `note`, `commentaire`, `statut`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Jardin', '2025-07-05', '09:00:00', '11:00:00', 3, NULL, '', 'annulée', '2025-07-01 10:00:00', '2025-07-04 06:25:21'),
(2, 1, 2, 'Jardin', '2025-07-10', '14:00:00', '16:00:00', 3, NULL, '', 'en_attente', '2025-07-02 14:00:00', '2025-07-02 14:00:00'),
(4, 1, 3, 'Jardin', '2025-07-19', '09:00:00', '12:00:00', 3, NULL, '', 'en_attente', '2025-07-16 07:06:31', '2025-07-16 07:06:31'),
(5, 1, 3, 'Jardin', '2025-07-19', '15:00:00', '17:00:00', 3, 4, NULL, 'validée', '2025-07-16 13:29:55', '2025-07-16 16:46:17'),
(6, 1, 3, 'Jardin', '2025-07-19', '13:00:00', '14:00:00', 3, 2, 'pas bien', 'validée', '2025-07-16 13:30:41', '2025-07-16 13:52:03'),
(7, 1, 3, 'Jardin', '2025-07-22', '09:00:00', '12:00:00', 3, NULL, NULL, 'en_attente', '2025-07-16 18:37:15', '2025-07-16 18:37:15'),
(8, 1, 3, 'Plombier', '2025-07-21', '09:00:00', '11:00:00', 3, NULL, NULL, 'en_attente', '2025-07-16 19:02:14', '2025-07-16 19:02:14');

-- --------------------------------------------------------

--
-- Structure de la table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('P0QSAC7faoQ3wIn098jbW9KcPK2kGJ5NVcbLsyk7', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36 OPR/119.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiOVRKRjhmSkk4M0p0ZFBVN1BOUE5zVnR6YXp5TXdOUDhRN2xoSmJ4biI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9wcmVzdGF0YWlyZS9pbnRlcnZlbnRpb25zIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mzt9', 1752699744),
('QG7cxWAJmPvJEfDudCsG93939iyzQKQl7faFIgX0', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36 OPR/119.0.0.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiN1VnRGxaZXp1cU1Qam0yVTJWY2kwNktaQThCUHZ5a0VNNkNOSEhEMyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9wYW5pZXIvc3RyaXBlIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MztzOjY6InBhbmllciI7YToyOntpOjY7YTo0OntzOjI6ImlkIjtpOjY7czozOiJub20iO3M6NzoiQ2Fyb3R0ZSI7czo0OiJwcml4IjtzOjQ6IjIuMDAiO3M6ODoicXVhbnRpdGUiO2k6MTt9aToyO2E6NDp7czoyOiJpZCI7aToyO3M6Mzoibm9tIjtzOjE3OiJHb3VyZGUgaW5veCA3NTBtbCI7czo0OiJwcml4IjtzOjU6IjI0LjkwIjtzOjg6InF1YW50aXRlIjtpOjE7fX19', 1752731790);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id_utilisateur` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adresse` int(11) DEFAULT NULL,
  `type_utilisateur` enum('client','livreur','commercant','admin','prestataire') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'client',
  `statut_compte` enum('actif','inactif','suspendu') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'actif',
  `date_inscription` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id_utilisateur`, `nom`, `prenom`, `email`, `password`, `telephone`, `adresse`, `type_utilisateur`, `statut_compte`, `date_inscription`, `remember_token`) VALUES
(1, 'Admin', 'System', 'admin@ecodeli.com', '$2y$12$I.D4wey.oO99i9iwcn6/feBpJxv8acaUMJuALEFSaJLFTlfMqCKHC', NULL, 5, 'commercant', 'actif', '2025-05-15 05:58:45', NULL),
(2, 'Raoult', 'Loïc', 'loicraoult31@gmail.com', '$2y$12$z6ovCbXpy049O/7ltAl7AOT9RIPyzVmSbeC5mT3CUAIdn33yO7ydG', NULL, 4, 'client', 'actif', '2025-05-15 06:57:19', NULL),
(3, 'Silva-Raoult', 'Loïc', 'loicraoult1009@gmail.com', '$2y$12$yCxKjacn7FaHqRSdjbEuYe4wIefRw.OI01h6i5/hdATiMpYrub5ue', '0770256896', 3, 'admin', 'actif', '2025-06-26 08:00:27', NULL),
(4, 'Silva-Raoult', 'Loïc', 'test@gmail.com', '$2y$12$TyWp4KzUmLMmxTe2.VxD2ecGPgajbZY79xVeYhnqAou54F/QqLXL2', '0770256895', 4, 'admin', 'actif', NULL, NULL),
(5, 'Silva-Raoult', 'Loïc', 'nimp@mal.com', '$2y$12$LoH1b3NOBYnakAZT0kxTuuc81.V70/.qP6qHnGumFre3F6VpkBbZK', '0770256895', 5, 'client', 'actif', NULL, NULL),
(6, 'livreur', 'll', 'liv@liv.liv', '$2y$12$meRsPg9rIkHFcBGrdlfw9.fLG2QlY21fb8tvHBRceU.jx/lPgJT56', '0770256895', 6, 'livreur', 'actif', NULL, NULL),
(7, 'Silva-Raoult', 'Loïc', 'presta@gmail.com', '$2y$12$9OydaVDU7ExMipYbNtMO2egAYyoLj0ozlP.x.sdwi0J4KCDpfPYyO', '0770256895', NULL, 'prestataire', 'actif', NULL, NULL),
(8, 'Silva-Raoult', 'Loïc', 'testete@gmail.com', '$2y$12$A8qJHodAra/hQFw7J.4UTeC9TFoBNROKDRVFW3stOfLTFF18HlGZ6', '0770256895', NULL, 'client', 'actif', NULL, NULL),
(9, 'Silva-Raoult', 'Loïc', 'papapapa@gmail.com', '$2y$12$8H/xPH4X19rrBKG9GKOsdOhbvVT3m8w1t9d1KDLMdaramEnxjmuGW', '0770256895', NULL, 'client', 'actif', NULL, NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `abonnement`
--
ALTER TABLE `abonnement`
  ADD PRIMARY KEY (`id_abonnement`);

--
-- Index pour la table `addresse`
--
ALTER TABLE `addresse`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `annonce`
--
ALTER TABLE `annonce`
  ADD PRIMARY KEY (`id_annonce`);

--
-- Index pour la table `annonce_prestation`
--
ALTER TABLE `annonce_prestation`
  ADD PRIMARY KEY (`id_annonce_prestation`),
  ADD KEY `annonce_prestation_id_prestation_foreign` (`id_prestataire`);

--
-- Index pour la table `batiment`
--
ALTER TABLE `batiment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `batiment_id_addresse_foreign` (`id_addresse`);

--
-- Index pour la table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Index pour la table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Index pour la table `commercants`
--
ALTER TABLE `commercants`
  ADD PRIMARY KEY (`id_commercant`),
  ADD UNIQUE KEY `commercants_email_unique` (`email`);

--
-- Index pour la table `contrats`
--
ALTER TABLE `contrats`
  ADD PRIMARY KEY (`id_contrat`),
  ADD KEY `contrats_id_commercant_foreign` (`id_commercant`);

--
-- Index pour la table `evaluations`
--
ALTER TABLE `evaluations`
  ADD PRIMARY KEY (`id_evaluation`),
  ADD KEY `fk_evaluations_prestataire` (`id_prestataire`),
  ADD KEY `fk_evaluations_client` (`id_client`);

--
-- Index pour la table `factures`
--
ALTER TABLE `factures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `factures_id_prestataire_foreign` (`id_prestataire`);

--
-- Index pour la table `langue`
--
ALTER TABLE `langue`
  ADD PRIMARY KEY (`id_langue`);

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
-- Index pour la table `livreur`
--
ALTER TABLE `livreur`
  ADD PRIMARY KEY (`id_livreur`);

--
-- Index pour la table `localisations`
--
ALTER TABLE `localisations`
  ADD PRIMARY KEY (`id_localisation`),
  ADD KEY `localisations_livraison_id_foreign` (`livraison_id`),
  ADD KEY `localisations_id_adresse_foreign` (`id_adresse`);

--
-- Index pour la table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id_notification`);

--
-- Index pour la table `paiement`
--
ALTER TABLE `paiement`
  ADD PRIMARY KEY (`id_paiement`);

--
-- Index pour la table `paiement_livreur`
--
ALTER TABLE `paiement_livreur`
  ADD PRIMARY KEY (`id_paiement`),
  ADD KEY `id_livreur` (`id_livreur`);

--
-- Index pour la table `planning`
--
ALTER TABLE `planning`
  ADD PRIMARY KEY (`id_planning`),
  ADD KEY `planning_id_livreur_foreign` (`id_livreur`);

--
-- Index pour la table `prestataire`
--
ALTER TABLE `prestataire`
  ADD PRIMARY KEY (`id_prestataire`);

--
-- Index pour la table `prestations`
--
ALTER TABLE `prestations`
  ADD PRIMARY KEY (`id_prestation`),
  ADD KEY `fk_prestations_prestataire` (`id_prestataire`);

--
-- Index pour la table `produits`
--
ALTER TABLE `produits`
  ADD PRIMARY KEY (`id_produits`),
  ADD KEY `produits_id_commercant_foreign` (`id_commercant`);

--
-- Index pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id_reservation`),
  ADD KEY `reservations_id_prestation_foreign` (`id_prestataire`),
  ADD KEY `reservations_id_client_foreign` (`id_client`);

--
-- Index pour la table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id_utilisateur`),
  ADD UNIQUE KEY `utilisateur_email_unique` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `abonnement`
--
ALTER TABLE `abonnement`
  MODIFY `id_abonnement` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `addresse`
--
ALTER TABLE `addresse`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `annonce`
--
ALTER TABLE `annonce`
  MODIFY `id_annonce` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `annonce_prestation`
--
ALTER TABLE `annonce_prestation`
  MODIFY `id_annonce_prestation` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `batiment`
--
ALTER TABLE `batiment`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `commercants`
--
ALTER TABLE `commercants`
  MODIFY `id_commercant` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `contrats`
--
ALTER TABLE `contrats`
  MODIFY `id_contrat` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `evaluations`
--
ALTER TABLE `evaluations`
  MODIFY `id_evaluation` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `factures`
--
ALTER TABLE `factures`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `langue`
--
ALTER TABLE `langue`
  MODIFY `id_langue` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `livraison`
--
ALTER TABLE `livraison`
  MODIFY `id_livraison` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `livreur`
--
ALTER TABLE `livreur`
  MODIFY `id_livreur` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `localisations`
--
ALTER TABLE `localisations`
  MODIFY `id_localisation` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT pour la table `notification`
--
ALTER TABLE `notification`
  MODIFY `id_notification` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `paiement`
--
ALTER TABLE `paiement`
  MODIFY `id_paiement` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT pour la table `paiement_livreur`
--
ALTER TABLE `paiement_livreur`
  MODIFY `id_paiement` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `planning`
--
ALTER TABLE `planning`
  MODIFY `id_planning` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `prestataire`
--
ALTER TABLE `prestataire`
  MODIFY `id_prestataire` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `prestations`
--
ALTER TABLE `prestations`
  MODIFY `id_prestation` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `produits`
--
ALTER TABLE `produits`
  MODIFY `id_produits` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id_reservation` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id_utilisateur` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `annonce_prestation`
--
ALTER TABLE `annonce_prestation`
  ADD CONSTRAINT `annonce_prestation_id_prestation_foreign` FOREIGN KEY (`id_prestataire`) REFERENCES `prestations` (`id_prestation`) ON DELETE CASCADE;

--
-- Contraintes pour la table `batiment`
--
ALTER TABLE `batiment`
  ADD CONSTRAINT `batiment_id_addresse_foreign` FOREIGN KEY (`id_addresse`) REFERENCES `addresse` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `contrats`
--
ALTER TABLE `contrats`
  ADD CONSTRAINT `contrats_id_commercant_foreign` FOREIGN KEY (`id_commercant`) REFERENCES `commercants` (`id_commercant`) ON DELETE CASCADE;

--
-- Contraintes pour la table `evaluations`
--
ALTER TABLE `evaluations`
  ADD CONSTRAINT `fk_evaluations_client` FOREIGN KEY (`id_client`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_evaluations_prestataire` FOREIGN KEY (`id_prestataire`) REFERENCES `prestataire` (`id_prestataire`) ON DELETE CASCADE;

--
-- Contraintes pour la table `factures`
--
ALTER TABLE `factures`
  ADD CONSTRAINT `factures_id_prestataire_foreign` FOREIGN KEY (`id_prestataire`) REFERENCES `prestataire` (`id_prestataire`) ON DELETE CASCADE;

--
-- Contraintes pour la table `livraison`
--
ALTER TABLE `livraison`
  ADD CONSTRAINT `livraison_id_adresse_arrivee_foreign` FOREIGN KEY (`id_adresse_arrivee`) REFERENCES `addresse` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `livraison_id_adresse_depart_foreign` FOREIGN KEY (`id_adresse_depart`) REFERENCES `addresse` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `livraison_id_annonce_foreign` FOREIGN KEY (`id_annonce`) REFERENCES `annonce` (`id_annonce`) ON DELETE CASCADE,
  ADD CONSTRAINT `livraison_id_livreur_foreign` FOREIGN KEY (`id_livreur`) REFERENCES `livreur` (`id_livreur`) ON DELETE SET NULL,
  ADD CONSTRAINT `livraison_id_utilisateur_foreign` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE;

--
-- Contraintes pour la table `localisations`
--
ALTER TABLE `localisations`
  ADD CONSTRAINT `localisations_id_adresse_foreign` FOREIGN KEY (`id_adresse`) REFERENCES `addresse` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `localisations_livraison_id_foreign` FOREIGN KEY (`livraison_id`) REFERENCES `livraison` (`id_livraison`) ON DELETE CASCADE;

--
-- Contraintes pour la table `paiement_livreur`
--
ALTER TABLE `paiement_livreur`
  ADD CONSTRAINT `paiement_livreur_ibfk_1` FOREIGN KEY (`id_livreur`) REFERENCES `livreur` (`id_livreur`) ON DELETE CASCADE;

--
-- Contraintes pour la table `planning`
--
ALTER TABLE `planning`
  ADD CONSTRAINT `planning_id_livreur_foreign` FOREIGN KEY (`id_livreur`) REFERENCES `livreur` (`id_livreur`) ON DELETE CASCADE;

--
-- Contraintes pour la table `prestations`
--
ALTER TABLE `prestations`
  ADD CONSTRAINT `fk_prestations_prestataire` FOREIGN KEY (`id_prestataire`) REFERENCES `prestataire` (`id_prestataire`) ON DELETE CASCADE;

--
-- Contraintes pour la table `produits`
--
ALTER TABLE `produits`
  ADD CONSTRAINT `produits_id_commercant_foreign` FOREIGN KEY (`id_commercant`) REFERENCES `commercants` (`id_commercant`) ON DELETE SET NULL;

--
-- Contraintes pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_id_client_foreign` FOREIGN KEY (`id_client`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservations_id_prestation_foreign` FOREIGN KEY (`id_prestataire`) REFERENCES `prestations` (`id_prestation`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
