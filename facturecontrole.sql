-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 01 déc. 2023 à 21:55
-- Version du serveur : 8.0.31
-- Version de PHP : 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `facturecontrole`
--

-- --------------------------------------------------------

--
-- Structure de la table `commentaire`
--

DROP TABLE IF EXISTS `commentaire`;
CREATE TABLE IF NOT EXISTS `commentaire` (
  `id` int NOT NULL AUTO_INCREMENT,
  `expediteur_id` int DEFAULT NULL,
  `destinataire_id` int DEFAULT NULL,
  `facture_id` int DEFAULT NULL,
  `objet` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` longtext COLLATE utf8mb4_unicode_ci,
  `est_lue` tinyint(1) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_67F068BC10335F61` (`expediteur_id`),
  KEY `IDX_67F068BCA4F84F6E` (`destinataire_id`),
  KEY `IDX_67F068BC7F2DEE08` (`facture_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `commentaire`
--

INSERT INTO `commentaire` (`id`, `expediteur_id`, `destinataire_id`, `facture_id`, `objet`, `message`, `est_lue`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, NULL, NULL, 6, 'Nouvelle Facture émise', 'Veuillez recevoir, Mme/Mr la personne chargée de l\'étude des factures, la facture suivante pour évaluation. Merci', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20231128134705', '2023-11-28 13:47:18', 677),
('DoctrineMigrations\\Version20231128140544', '2023-11-28 14:05:49', 337),
('DoctrineMigrations\\Version20231128140940', '2023-11-28 14:09:46', 251),
('DoctrineMigrations\\Version20231128164515', '2023-11-28 16:45:22', 216),
('DoctrineMigrations\\Version20231128183212', '2023-11-28 18:32:18', 140),
('DoctrineMigrations\\Version20231129195234', '2023-11-29 19:52:48', 151),
('DoctrineMigrations\\Version20231129214833', '2023-11-29 21:48:39', 62),
('DoctrineMigrations\\Version20231129215311', '2023-11-29 21:53:15', 196),
('DoctrineMigrations\\Version20231130151419', '2023-11-30 15:14:37', 80),
('DoctrineMigrations\\Version20231130151752', '2023-11-30 15:18:20', 438),
('DoctrineMigrations\\Version20231130181804', '2023-11-30 18:18:13', 1202),
('DoctrineMigrations\\Version20231130182529', '2023-11-30 18:25:36', 70),
('DoctrineMigrations\\Version20231130184619', '2023-11-30 18:46:30', 956),
('DoctrineMigrations\\Version20231130190722', '2023-11-30 19:07:29', 74),
('DoctrineMigrations\\Version20231130195240', '2023-11-30 19:52:46', 71),
('DoctrineMigrations\\Version20231130202132', '2023-11-30 20:21:39', 68),
('DoctrineMigrations\\Version20231130205118', '2023-11-30 20:51:25', 73),
('DoctrineMigrations\\Version20231130214043', '2023-11-30 21:40:50', 77),
('DoctrineMigrations\\Version20231201193348', '2023-12-01 19:34:02', 555),
('DoctrineMigrations\\Version20231201195449', '2023-12-01 19:54:56', 177),
('DoctrineMigrations\\Version20231201200148', '2023-12-01 20:01:55', 99),
('DoctrineMigrations\\Version20231201203142', '2023-12-01 20:49:04', 92),
('DoctrineMigrations\\Version20231201204934', '2023-12-01 20:49:43', 132),
('DoctrineMigrations\\Version20231201211022', '2023-12-01 21:10:31', 109),
('DoctrineMigrations\\Version20231201213321', '2023-12-01 21:33:28', 226);

-- --------------------------------------------------------

--
-- Structure de la table `element_facture`
--

DROP TABLE IF EXISTS `element_facture`;
CREATE TABLE IF NOT EXISTS `element_facture` (
  `id` int NOT NULL AUTO_INCREMENT,
  `designation` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `valeur` decimal(30,2) DEFAULT NULL,
  `est_sup` tinyint(1) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `facture_id` int NOT NULL,
  `qte` decimal(10,2) DEFAULT NULL,
  `mnt_total` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_96D24447F2DEE08` (`facture_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `element_facture`
--

INSERT INTO `element_facture` (`id`, `designation`, `valeur`, `est_sup`, `created_at`, `updated_at`, `deleted_at`, `facture_id`, `qte`, `mnt_total`) VALUES
(1, 'Déploiement', '70000.00', 0, NULL, NULL, NULL, 3, '1.00', '70000.00'),
(2, 'Déploiement', '70000.00', 0, NULL, NULL, NULL, 4, '1.00', '70000.00'),
(3, 'Déploiement', '70000.00', 0, NULL, NULL, NULL, 5, '1.00', '70000.00'),
(4, 'Déploiement', '70000.00', 0, NULL, NULL, NULL, 6, '1.00', '70000.00');

-- --------------------------------------------------------

--
-- Structure de la table `facture`
--

DROP TABLE IF EXISTS `facture`;
CREATE TABLE IF NOT EXISTS `facture` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ref_fact` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_fac` datetime NOT NULL,
  `montant_fac` decimal(30,2) NOT NULL,
  `montant_rest` decimal(30,2) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `statut` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL,
  `est_sup` tinyint(1) NOT NULL,
  `est_valide` tinyint(1) NOT NULL,
  `emetteur` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `facture`
--

INSERT INTO `facture` (`id`, `ref_fact`, `date_fac`, `montant_fac`, `montant_rest`, `created_at`, `updated_at`, `deleted_at`, `statut`, `est_sup`, `est_valide`, `emetteur`) VALUES
(3, 'FA00000123', '2023-11-29 00:00:00', '150000.00', NULL, NULL, NULL, NULL, 'En cours', 0, 0, 'KOMBIENI YANTEKOUA Paul'),
(4, 'FA00000223', '2023-11-29 00:00:00', '150000.00', NULL, NULL, NULL, NULL, 'En cours', 0, 0, 'KOMBIENI YANTEKOUA Paul'),
(5, 'FA00000323', '2023-11-29 00:00:00', '150000.00', NULL, NULL, NULL, NULL, 'En cours', 0, 0, 'KOMBIENI YANTEKOUA Paul'),
(6, 'FA00000423', '2023-11-29 00:00:00', '150000.00', NULL, NULL, NULL, NULL, 'En cours', 0, 0, 'KOMBIENI YANTEKOUA Paul');

-- --------------------------------------------------------

--
-- Structure de la table `media`
--

DROP TABLE IF EXISTS `media`;
CREATE TABLE IF NOT EXISTS `media` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom_media` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `chemin_media` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `num_media` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `extension` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `media`
--

INSERT INTO `media` (`id`, `nom_media`, `chemin_media`, `num_media`, `extension`) VALUES
(1, '28267842_7.jpg', NULL, '', NULL),
(2, '28267842_7.jpg', NULL, '', NULL),
(3, '28267842_7.jpg', NULL, '', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `menu`
--

DROP TABLE IF EXISTS `menu`;
CREATE TABLE IF NOT EXISTS `menu` (
  `id` int NOT NULL AUTO_INCREMENT,
  `menu_superieur_id` int DEFAULT NULL,
  `sous_titre` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `titre` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(4000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `icon` longtext COLLATE utf8mb4_unicode_ci,
  `type_menu` varchar(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `roles` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_7D053A93F9ADC1B4` (`menu_superieur_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `menu`
--

INSERT INTO `menu` (`id`, `menu_superieur_id`, `sous_titre`, `titre`, `url`, `active`, `icon`, `type_menu`, `roles`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, NULL, 'Configuration', 'Application', '', 0, '<span class=\"svg-icon svg-icon-2\">\r\n                                    <svg width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">\r\n                                        <path d=\"M11.2929 2.70711C11.6834 2.31658 12.3166 2.31658 12.7071 2.70711L15.2929 5.29289C15.6834 5.68342 15.6834 6.31658 15.2929 6.70711L12.7071 9.29289C12.3166 9.68342 11.6834 9.68342 11.2929 9.29289L8.70711 6.70711C8.31658 6.31658 8.31658 5.68342 8.70711 5.29289L11.2929 2.70711Z\" fill=\"currentColor\"></path>\r\n                                        <path d=\"M11.2929 14.7071C11.6834 14.3166 12.3166 14.3166 12.7071 14.7071L15.2929 17.2929C15.6834 17.6834 15.6834 18.3166 15.2929 18.7071L12.7071 21.2929C12.3166 21.6834 11.6834 21.6834 11.2929 21.2929L8.70711 18.7071C8.31658 18.3166 8.31658 17.6834 8.70711 17.2929L11.2929 14.7071Z\" fill=\"currentColor\"></path>\r\n                                        <path opacity=\"0.3\" d=\"M5.29289 8.70711C5.68342 8.31658 6.31658 8.31658 6.70711 8.70711L9.29289 11.2929C9.68342 11.6834 9.68342 12.3166 9.29289 12.7071L6.70711 15.2929C6.31658 15.6834 5.68342 15.6834 5.29289 15.2929L2.70711 12.7071C2.31658 12.3166 2.31658 11.6834 2.70711 11.2929L5.29289 8.70711Z\" fill=\"currentColor\"></path>\r\n                                        <path opacity=\"0.3\" d=\"M17.2929 8.70711C17.6834 8.31658 18.3166 8.31658 18.7071 8.70711L21.2929 11.2929C21.6834 11.6834 21.6834 12.3166 21.2929 12.7071L18.7071 15.2929C18.3166 15.6834 17.6834 15.6834 17.2929 15.2929L14.7071 12.7071C14.3166 12.3166 14.3166 11.6834 14.7071 11.2929L17.2929 8.70711Z\" fill=\"currentColor\"></path>\r\n                                    </svg>\r\n                                </span>', NULL, 'ROLE_CONFIGURATION', NULL, NULL, NULL),
(2, 1, NULL, 'Menu', '', 0, NULL, NULL, 'ROLE_MENU', NULL, NULL, NULL),
(3, NULL, 'ADMINISTRATION', 'Sécurité', '', 0, '<span class=\"svg-icon svg-icon-2\">\r\n                                    <svg width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">\r\n                                        <path d=\"M11.2929 2.70711C11.6834 2.31658 12.3166 2.31658 12.7071 2.70711L15.2929 5.29289C15.6834 5.68342 15.6834 6.31658 15.2929 6.70711L12.7071 9.29289C12.3166 9.68342 11.6834 9.68342 11.2929 9.29289L8.70711 6.70711C8.31658 6.31658 8.31658 5.68342 8.70711 5.29289L11.2929 2.70711Z\" fill=\"currentColor\"></path>\r\n                                        <path d=\"M11.2929 14.7071C11.6834 14.3166 12.3166 14.3166 12.7071 14.7071L15.2929 17.2929C15.6834 17.6834 15.6834 18.3166 15.2929 18.7071L12.7071 21.2929C12.3166 21.6834 11.6834 21.6834 11.2929 21.2929L8.70711 18.7071C8.31658 18.3166 8.31658 17.6834 8.70711 17.2929L11.2929 14.7071Z\" fill=\"currentColor\"></path>\r\n                                        <path opacity=\"0.3\" d=\"M5.29289 8.70711C5.68342 8.31658 6.31658 8.31658 6.70711 8.70711L9.29289 11.2929C9.68342 11.6834 9.68342 12.3166 9.29289 12.7071L6.70711 15.2929C6.31658 15.6834 5.68342 15.6834 5.29289 15.2929L2.70711 12.7071C2.31658 12.3166 2.31658 11.6834 2.70711 11.2929L5.29289 8.70711Z\" fill=\"currentColor\"></path>\r\n                                        <path opacity=\"0.3\" d=\"M17.2929 8.70711C17.6834 8.31658 18.3166 8.31658 18.7071 8.70711L21.2929 11.2929C21.6834 11.6834 21.6834 12.3166 21.2929 12.7071L18.7071 15.2929C18.3166 15.6834 17.6834 15.6834 17.2929 15.2929L14.7071 12.7071C14.3166 12.3166 14.3166 11.6834 14.7071 11.2929L17.2929 8.70711Z\" fill=\"currentColor\"></path>\r\n                                    </svg>\r\n                                </span>', NULL, 'ROLE_ADMINISTRATION', NULL, NULL, NULL),
(4, 3, NULL, 'Profil', 'profil', 0, NULL, NULL, 'ROLE_PROFIL', NULL, NULL, NULL),
(5, 3, NULL, 'Utilisateur', 'collaborateur', 0, NULL, NULL, 'ROLE_UTILISATEUR', NULL, NULL, NULL),
(6, 3, NULL, 'Configuration', '', 0, NULL, NULL, 'ROLE_CONFIGURATION', NULL, NULL, NULL),
(7, NULL, 'Configuration', 'Paramétrage', '', 0, '<span class=\"svg-icon svg-icon-2\">\r\n                                    <svg width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">\r\n                                        <path d=\"M11.2929 2.70711C11.6834 2.31658 12.3166 2.31658 12.7071 2.70711L15.2929 5.29289C15.6834 5.68342 15.6834 6.31658 15.2929 6.70711L12.7071 9.29289C12.3166 9.68342 11.6834 9.68342 11.2929 9.29289L8.70711 6.70711C8.31658 6.31658 8.31658 5.68342 8.70711 5.29289L11.2929 2.70711Z\" fill=\"currentColor\"></path>\r\n                                        <path d=\"M11.2929 14.7071C11.6834 14.3166 12.3166 14.3166 12.7071 14.7071L15.2929 17.2929C15.6834 17.6834 15.6834 18.3166 15.2929 18.7071L12.7071 21.2929C12.3166 21.6834 11.6834 21.6834 11.2929 21.2929L8.70711 18.7071C8.31658 18.3166 8.31658 17.6834 8.70711 17.2929L11.2929 14.7071Z\" fill=\"currentColor\"></path>\r\n                                        <path opacity=\"0.3\" d=\"M5.29289 8.70711C5.68342 8.31658 6.31658 8.31658 6.70711 8.70711L9.29289 11.2929C9.68342 11.6834 9.68342 12.3166 9.29289 12.7071L6.70711 15.2929C6.31658 15.6834 5.68342 15.6834 5.29289 15.2929L2.70711 12.7071C2.31658 12.3166 2.31658 11.6834 2.70711 11.2929L5.29289 8.70711Z\" fill=\"currentColor\"></path>\r\n                                        <path opacity=\"0.3\" d=\"M17.2929 8.70711C17.6834 8.31658 18.3166 8.31658 18.7071 8.70711L21.2929 11.2929C21.6834 11.6834 21.6834 12.3166 21.2929 12.7071L18.7071 15.2929C18.3166 15.6834 17.6834 15.6834 17.2929 15.2929L14.7071 12.7071C14.3166 12.3166 14.3166 11.6834 14.7071 11.2929L17.2929 8.70711Z\" fill=\"currentColor\"></path>\r\n                                    </svg>\r\n                                </span>', NULL, 'ROLE_CONFIGURATION', NULL, NULL, NULL),
(8, 7, NULL, 'Société', 'societe_index', 0, NULL, NULL, 'ROLE_SOCIETE', NULL, NULL, NULL),
(9, 7, NULL, 'Collaborateur', 'collaborateur', 0, NULL, NULL, 'ROLE_COLLABORATEUR', NULL, NULL, NULL),
(10, 7, NULL, 'Mode de Paiement', 'mode_paiement_index', 0, NULL, NULL, 'ROLE_MODE_PAIEMENT', NULL, NULL, NULL),
(11, NULL, 'Facture', 'Facturation', '', 0, '<span class=\"svg-icon svg-icon-2\">\r\n                                    <svg width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">\r\n                                        <path d=\"M11.2929 2.70711C11.6834 2.31658 12.3166 2.31658 12.7071 2.70711L15.2929 5.29289C15.6834 5.68342 15.6834 6.31658 15.2929 6.70711L12.7071 9.29289C12.3166 9.68342 11.6834 9.68342 11.2929 9.29289L8.70711 6.70711C8.31658 6.31658 8.31658 5.68342 8.70711 5.29289L11.2929 2.70711Z\" fill=\"currentColor\"></path>\r\n                                        <path d=\"M11.2929 14.7071C11.6834 14.3166 12.3166 14.3166 12.7071 14.7071L15.2929 17.2929C15.6834 17.6834 15.6834 18.3166 15.2929 18.7071L12.7071 21.2929C12.3166 21.6834 11.6834 21.6834 11.2929 21.2929L8.70711 18.7071C8.31658 18.3166 8.31658 17.6834 8.70711 17.2929L11.2929 14.7071Z\" fill=\"currentColor\"></path>\r\n                                        <path opacity=\"0.3\" d=\"M5.29289 8.70711C5.68342 8.31658 6.31658 8.31658 6.70711 8.70711L9.29289 11.2929C9.68342 11.6834 9.68342 12.3166 9.29289 12.7071L6.70711 15.2929C6.31658 15.6834 5.68342 15.6834 5.29289 15.2929L2.70711 12.7071C2.31658 12.3166 2.31658 11.6834 2.70711 11.2929L5.29289 8.70711Z\" fill=\"currentColor\"></path>\r\n                                        <path opacity=\"0.3\" d=\"M17.2929 8.70711C17.6834 8.31658 18.3166 8.31658 18.7071 8.70711L21.2929 11.2929C21.6834 11.6834 21.6834 12.3166 21.2929 12.7071L18.7071 15.2929C18.3166 15.6834 17.6834 15.6834 17.2929 15.2929L14.7071 12.7071C14.3166 12.3166 14.3166 11.6834 14.7071 11.2929L17.2929 8.70711Z\" fill=\"currentColor\"></path>\r\n                                    </svg>\r\n                                </span>', NULL, 'ROLE_CONFIGURATION', NULL, NULL, NULL),
(12, 11, NULL, 'Devis', '', 0, NULL, NULL, '', NULL, NULL, NULL),
(13, NULL, 'Paiements', 'Paiement', '', 0, '<span class=\"svg-icon svg-icon-2\">\r\n                                    <svg width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">\r\n                                        <path d=\"M11.2929 2.70711C11.6834 2.31658 12.3166 2.31658 12.7071 2.70711L15.2929 5.29289C15.6834 5.68342 15.6834 6.31658 15.2929 6.70711L12.7071 9.29289C12.3166 9.68342 11.6834 9.68342 11.2929 9.29289L8.70711 6.70711C8.31658 6.31658 8.31658 5.68342 8.70711 5.29289L11.2929 2.70711Z\" fill=\"currentColor\"></path>\r\n                                        <path d=\"M11.2929 14.7071C11.6834 14.3166 12.3166 14.3166 12.7071 14.7071L15.2929 17.2929C15.6834 17.6834 15.6834 18.3166 15.2929 18.7071L12.7071 21.2929C12.3166 21.6834 11.6834 21.6834 11.2929 21.2929L8.70711 18.7071C8.31658 18.3166 8.31658 17.6834 8.70711 17.2929L11.2929 14.7071Z\" fill=\"currentColor\"></path>\r\n                                        <path opacity=\"0.3\" d=\"M5.29289 8.70711C5.68342 8.31658 6.31658 8.31658 6.70711 8.70711L9.29289 11.2929C9.68342 11.6834 9.68342 12.3166 9.29289 12.7071L6.70711 15.2929C6.31658 15.6834 5.68342 15.6834 5.29289 15.2929L2.70711 12.7071C2.31658 12.3166 2.31658 11.6834 2.70711 11.2929L5.29289 8.70711Z\" fill=\"currentColor\"></path>\r\n                                        <path opacity=\"0.3\" d=\"M17.2929 8.70711C17.6834 8.31658 18.3166 8.31658 18.7071 8.70711L21.2929 11.2929C21.6834 11.6834 21.6834 12.3166 21.2929 12.7071L18.7071 15.2929C18.3166 15.6834 17.6834 15.6834 17.2929 15.2929L14.7071 12.7071C14.3166 12.3166 14.3166 11.6834 14.7071 11.2929L17.2929 8.70711Z\" fill=\"currentColor\"></path>\r\n                                    </svg>\r\n                                </span>', NULL, 'ROLE_CONFIGURATION', NULL, NULL, NULL),
(14, 15, NULL, 'Suivi', '', 0, NULL, NULL, '', NULL, NULL, NULL),
(15, NULL, 'Suivi', 'Suivi et Messagerie', '', 0, '<span class=\"svg-icon svg-icon-2\">\r\n                                    <svg width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">\r\n                                        <path d=\"M11.2929 2.70711C11.6834 2.31658 12.3166 2.31658 12.7071 2.70711L15.2929 5.29289C15.6834 5.68342 15.6834 6.31658 15.2929 6.70711L12.7071 9.29289C12.3166 9.68342 11.6834 9.68342 11.2929 9.29289L8.70711 6.70711C8.31658 6.31658 8.31658 5.68342 8.70711 5.29289L11.2929 2.70711Z\" fill=\"currentColor\"></path>\r\n                                        <path d=\"M11.2929 14.7071C11.6834 14.3166 12.3166 14.3166 12.7071 14.7071L15.2929 17.2929C15.6834 17.6834 15.6834 18.3166 15.2929 18.7071L12.7071 21.2929C12.3166 21.6834 11.6834 21.6834 11.2929 21.2929L8.70711 18.7071C8.31658 18.3166 8.31658 17.6834 8.70711 17.2929L11.2929 14.7071Z\" fill=\"currentColor\"></path>\r\n                                        <path opacity=\"0.3\" d=\"M5.29289 8.70711C5.68342 8.31658 6.31658 8.31658 6.70711 8.70711L9.29289 11.2929C9.68342 11.6834 9.68342 12.3166 9.29289 12.7071L6.70711 15.2929C6.31658 15.6834 5.68342 15.6834 5.29289 15.2929L2.70711 12.7071C2.31658 12.3166 2.31658 11.6834 2.70711 11.2929L5.29289 8.70711Z\" fill=\"currentColor\"></path>\r\n                                        <path opacity=\"0.3\" d=\"M17.2929 8.70711C17.6834 8.31658 18.3166 8.31658 18.7071 8.70711L21.2929 11.2929C21.6834 11.6834 21.6834 12.3166 21.2929 12.7071L18.7071 15.2929C18.3166 15.6834 17.6834 15.6834 17.2929 15.2929L14.7071 12.7071C14.3166 12.3166 14.3166 11.6834 14.7071 11.2929L17.2929 8.70711Z\" fill=\"currentColor\"></path>\r\n                                    </svg>\r\n                                </span>', NULL, 'ROLE_CONFIGURATION', NULL, NULL, NULL),
(16, NULL, 'ETATS', 'ETATS & Statistique', '', 0, '<span class=\"svg-icon svg-icon-2\">\r\n                                    <svg width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">\r\n                                        <path d=\"M11.2929 2.70711C11.6834 2.31658 12.3166 2.31658 12.7071 2.70711L15.2929 5.29289C15.6834 5.68342 15.6834 6.31658 15.2929 6.70711L12.7071 9.29289C12.3166 9.68342 11.6834 9.68342 11.2929 9.29289L8.70711 6.70711C8.31658 6.31658 8.31658 5.68342 8.70711 5.29289L11.2929 2.70711Z\" fill=\"currentColor\"></path>\r\n                                        <path d=\"M11.2929 14.7071C11.6834 14.3166 12.3166 14.3166 12.7071 14.7071L15.2929 17.2929C15.6834 17.6834 15.6834 18.3166 15.2929 18.7071L12.7071 21.2929C12.3166 21.6834 11.6834 21.6834 11.2929 21.2929L8.70711 18.7071C8.31658 18.3166 8.31658 17.6834 8.70711 17.2929L11.2929 14.7071Z\" fill=\"currentColor\"></path>\r\n                                        <path opacity=\"0.3\" d=\"M5.29289 8.70711C5.68342 8.31658 6.31658 8.31658 6.70711 8.70711L9.29289 11.2929C9.68342 11.6834 9.68342 12.3166 9.29289 12.7071L6.70711 15.2929C6.31658 15.6834 5.68342 15.6834 5.29289 15.2929L2.70711 12.7071C2.31658 12.3166 2.31658 11.6834 2.70711 11.2929L5.29289 8.70711Z\" fill=\"currentColor\"></path>\r\n                                        <path opacity=\"0.3\" d=\"M17.2929 8.70711C17.6834 8.31658 18.3166 8.31658 18.7071 8.70711L21.2929 11.2929C21.6834 11.6834 21.6834 12.3166 21.2929 12.7071L18.7071 15.2929C18.3166 15.6834 17.6834 15.6834 17.2929 15.2929L14.7071 12.7071C14.3166 12.3166 14.3166 11.6834 14.7071 11.2929L17.2929 8.70711Z\" fill=\"currentColor\"></path>\r\n                                    </svg>\r\n                                </span>', NULL, 'ROLE_CONFIGURATION', NULL, NULL, NULL),
(17, 16, NULL, 'Liste des factures émises par collaborateur', '', 0, NULL, NULL, '', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `messenger_messages`
--

DROP TABLE IF EXISTS `messenger_messages`;
CREATE TABLE IF NOT EXISTS `messenger_messages` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `mode_paiement`
--

DROP TABLE IF EXISTS `mode_paiement`;
CREATE TABLE IF NOT EXISTS `mode_paiement` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `libelle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `paiement`
--

DROP TABLE IF EXISTS `paiement`;
CREATE TABLE IF NOT EXISTS `paiement` (
  `id` int NOT NULL AUTO_INCREMENT,
  `facture_id` int NOT NULL,
  `ref_pai` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_paie` datetime NOT NULL,
  `montant_paie` decimal(30,2) NOT NULL,
  `rest_apayer` decimal(30,2) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `mode_paiement_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_B1DC7A1E7F2DEE08` (`facture_id`),
  KEY `IDX_B1DC7A1E438F5B63` (`mode_paiement_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `profil`
--

DROP TABLE IF EXISTS `profil`;
CREATE TABLE IF NOT EXISTS `profil` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json DEFAULT NULL,
  `est_sup` tinyint(1) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `profil`
--

INSERT INTO `profil` (`id`, `titre`, `roles`, `est_sup`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'PROFIL', '[\"ROLE_PROFIL\", \"ROLE_UTILISATEUR\", \"ROLE_CONFIGURATION\"]', 1, NULL, NULL, '2023-11-30 16:01:23'),
(2, 'Sortant', '[\"ROLE_MENU\", \"ROLE_PROFIL\", \"ROLE_UTILISATEUR\", \"ROLE_CONFIGURATION\"]', 0, NULL, NULL, NULL),
(3, 'SUPER_ADMIN', '[\"ROLE_MENU\", \"ROLE_PROFIL\", \"ROLE_UTILISATEUR\", \"ROLE_CONFIGURATION\", \"ROLE_SOCIETE\", \"ROLE_COLLABORATEUR\", \"ROLE_MODE_PAIEMENT\", \"\", \"\", \"\"]', 0, NULL, NULL, NULL),
(4, 'CALLABORATEUR', '[\"\", \"\"]', 0, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

DROP TABLE IF EXISTS `role`;
CREATE TABLE IF NOT EXISTS `role` (
  `id` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `societe`
--

DROP TABLE IF EXISTS `societe`;
CREATE TABLE IF NOT EXISTS `societe` (
  `id` int NOT NULL AUTO_INCREMENT,
  `logo_id` int DEFAULT NULL,
  `entete_id` int DEFAULT NULL,
  `pied_de_page_id` int DEFAULT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sigle` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `raison_social` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `statut` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ifu` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rccm` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adresse` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `est_sup` tinyint(1) DEFAULT NULL,
  `api_token` longtext COLLATE utf8mb4_unicode_ci,
  `api_nim` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message_commercial` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `est_mode_mecef` tinyint(1) DEFAULT NULL,
  `est_regime_tps` tinyint(1) DEFAULT NULL,
  `api_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `est_admin` tinyint(1) DEFAULT NULL,
  `est_mode_de_connexion` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_19653DBDF98F144A` (`logo_id`),
  UNIQUE KEY `UNIQ_19653DBDEC285501` (`entete_id`),
  UNIQUE KEY `UNIQ_19653DBDF31E83EB` (`pied_de_page_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `societe`
--

INSERT INTO `societe` (`id`, `logo_id`, `entete_id`, `pied_de_page_id`, `nom`, `sigle`, `raison_social`, `statut`, `ifu`, `rccm`, `adresse`, `est_sup`, `api_token`, `api_nim`, `message_commercial`, `est_mode_mecef`, `est_regime_tps`, `api_link`, `est_admin`, `est_mode_de_connexion`) VALUES
(1, 1, 2, 3, 'CABOOOOO', 'CABO', 'CABOOOOO', NULL, '2030154006851', 'RCCM0023TY', 'Cotonou', NULL, NULL, NULL, 'Un seul mot', NULL, NULL, NULL, 0, 1);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `adresse` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `actif` tinyint(1) NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sexe` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `civilite` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_nais` datetime DEFAULT NULL,
  `est_collaborateur` tinyint(1) DEFAULT NULL,
  `est_admin` tinyint(1) DEFAULT NULL,
  `profession` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profil` json DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_1D1C63B3F85E0677` (`username`),
  UNIQUE KEY `UNIQ_1D1C63B3E7927C74` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id`, `username`, `roles`, `password`, `nom`, `prenom`, `tel`, `adresse`, `actif`, `email`, `sexe`, `civilite`, `date_nais`, `est_collaborateur`, `est_admin`, `profession`, `profil`) VALUES
(1, 'Paul', '[\"ROLE_MENU\", \"ROLE_PROFIL\", \"ROLE_UTILISATEUR\", \"ROLE_CONFIGURATION\", \"ROLE_SOCIETE\", \"ROLE_COLLABORATEUR\", \"ROLE_MODE_PAIEMENT\", \"\", \"\", \"\"]', '$2y$13$yeF.RCZEMX2Vop5NWzdPcujOXE3Mhx8z5khvvmOicY0dN8HgGt4yq', 'KOMBIENI YANTEKOUA', 'Paul', '98959293', 'Bonjour', 1, 'ayaba22@gmail.com', 'Masculin', 'Monsieur', '2023-11-01 00:00:00', NULL, 0, 'Commerciale', '[3]'),
(4, 'Franck', '[\"\", \"\"]', '$2y$13$iJb1Ha2niXkDUl4vi38pKOpNFeXcoBcPqIEa99FWI3rbtbHh1.0o2', 'ATGAN', 'Franck', '9895969291', 'Super Collaborateur', 1, 'franckatagan@gmail.com', 'Masculin', 'Monsieur', NULL, NULL, 0, 'Informaticien', '[4]');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commentaire`
--
ALTER TABLE `commentaire`
  ADD CONSTRAINT `FK_67F068BC10335F61` FOREIGN KEY (`expediteur_id`) REFERENCES `utilisateur` (`id`),
  ADD CONSTRAINT `FK_67F068BC7F2DEE08` FOREIGN KEY (`facture_id`) REFERENCES `facture` (`id`),
  ADD CONSTRAINT `FK_67F068BCA4F84F6E` FOREIGN KEY (`destinataire_id`) REFERENCES `utilisateur` (`id`);

--
-- Contraintes pour la table `element_facture`
--
ALTER TABLE `element_facture`
  ADD CONSTRAINT `FK_96D24447F2DEE08` FOREIGN KEY (`facture_id`) REFERENCES `facture` (`id`);

--
-- Contraintes pour la table `menu`
--
ALTER TABLE `menu`
  ADD CONSTRAINT `FK_7D053A93F9ADC1B4` FOREIGN KEY (`menu_superieur_id`) REFERENCES `menu` (`id`);

--
-- Contraintes pour la table `paiement`
--
ALTER TABLE `paiement`
  ADD CONSTRAINT `FK_B1DC7A1E438F5B63` FOREIGN KEY (`mode_paiement_id`) REFERENCES `mode_paiement` (`id`),
  ADD CONSTRAINT `FK_B1DC7A1E7F2DEE08` FOREIGN KEY (`facture_id`) REFERENCES `facture` (`id`);

--
-- Contraintes pour la table `societe`
--
ALTER TABLE `societe`
  ADD CONSTRAINT `FK_19653DBDEC285501` FOREIGN KEY (`entete_id`) REFERENCES `media` (`id`),
  ADD CONSTRAINT `FK_19653DBDF31E83EB` FOREIGN KEY (`pied_de_page_id`) REFERENCES `media` (`id`),
  ADD CONSTRAINT `FK_19653DBDF98F144A` FOREIGN KEY (`logo_id`) REFERENCES `media` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
