-- --------------------------------------------------------
-- Hôte:                         127.0.0.1
-- Version du serveur:           8.4.3 - MySQL Community Server - GPL
-- SE du serveur:                Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Listage de la structure de la base pour agenda_pvvih
CREATE DATABASE IF NOT EXISTS `agenda_pvvih` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `agenda_pvvih`;


-- Listage de la structure de table agenda_pvvih. patients
CREATE TABLE IF NOT EXISTS `patients` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `prenoms` varchar(100) DEFAULT NULL,
  `sexe` char(1) DEFAULT NULL,
  `date_naissance` date DEFAULT NULL,
  `age` int DEFAULT NULL,
  `quartier` varchar(100) DEFAULT NULL,
  `telephone1` varchar(20) DEFAULT NULL,
  `telephone2` varchar(20) DEFAULT NULL,
  `categorie` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Listage des données de la table agenda_pvvih.patients : ~20 rows (environ)
INSERT INTO `patients` (`id`, `code`, `nom`, `prenoms`, `sexe`, `date_naissance`, `age`, `quartier`, `telephone1`, `telephone2`, `categorie`) VALUES
	(1, 'CHRA1/01/26/00001', 'KOUASSI', 'JEAN MARC', 'M', '1985-05-12', 40, 'COCODY', '0102030405', '0504030201', 'POPULATION GÉNÉRALE'),
	(2, 'CHRA1/01/26/00002', 'BAMBA', 'MARIAM', 'F', '1998-08-20', 27, 'YOPOUGON', '0707070808', '', 'FEMME ENCEINTE'),
	(3, 'CHRA1/02/26/00003', 'TRAORE', 'MOUSSA', 'M', '2010-02-15', 15, 'ABOBO', '0140506070', '0708091011', 'POPULATION CLÉ'),
	(4, 'CHRA1/01/26/00004', 'N\'GUESSAN', 'AMÉNAN', 'F', '1995-11-30', 30, 'BINGERVILLE', '0505060708', '', 'FEMME ALLAITANTE'),
	(5, 'CHRA1/01/26/00005', 'YOBOUET', 'KOFFI LUC', 'M', '2023-01-10', 2, 'TREICHVILLE', '0101020304', '', 'ENFANT EXPOSÉ'),
	(6, 'CHRA1/03/26/00006', 'DIALLO', 'AWA', 'F', '1992-04-05', 33, 'ADJAME', '0701020304', '0101010202', 'POPULATION GÉNÉRALE'),
	(7, 'CHRA1/01/26/00007', 'KOFFI', 'AFFOUE ROSE', 'F', '2000-12-25', 25, 'PLATEAU', '0505445566', '', 'FEMME ENCEINTE'),
	(8, 'CHRA1/02/26/00008', 'KONAN', 'YVES', 'M', '1980-07-14', 45, 'MARCORY', '0708090706', '', 'POPULATION CLÉ'),
	(9, 'CHRA1/01/26/00009', 'TOURE', 'ABDOUL', 'M', '2022-06-18', 3, 'KOUMASSI', '0102036677', '', 'ENFANT EXPOSÉ'),
	(10, 'CHRA1/01/26/00010', 'GOHOU', 'MICHEL', 'M', '1975-03-03', 50, 'YOPOUGON', '0707889900', '0505050505', 'POPULATION GÉNÉRALE'),
	(11, 'CHRA1/01/26/00011', 'SIDIBE', 'FATOUMATA', 'F', '1999-09-09', 26, 'ABOBO', '0102030405', '', 'FEMME ALLAITANTE'),
	(12, 'CHRA1/04/26/00012', 'OUATTARA', 'ALASSANE', 'M', '1988-10-10', 37, 'COCODY', '0700112233', '', 'POPULATION CLÉ'),
	(13, 'CHRA1/01/26/00013', 'ZOKORA', 'DIDIER MAESTRO 1', 'M', '1982-11-11', 43, 'BINGERVILLE', '0505050505', '', 'POPULATION GÉNÉRALE'),
	(14, 'CHRA1/01/26/00014', 'AKISSI', 'DELTA', 'F', '1970-01-01', 56, 'MARCORY', '0101010101', '', 'POPULATION GÉNÉRALE'),
	(15, 'CHRA1/01/26/00015', 'YAO', 'BROU', 'M', '2024-02-02', 1, 'KOUMASSI', '0707070707', '', 'ENFANT EXPOSÉ'),
	(16, 'CHRA1/02/26/00016', 'KONE', 'BAKARY', 'M', '1990-05-05', 35, 'ADJAME', '0505060607', '', 'POPULATION CLÉ'),
	(17, 'CHRA1/01/26/00017', 'OUEDRAOGO', 'SALI', 'F', '1996-06-06', 29, 'TREICHVILLE', '0102030406', '', 'FEMME ENCEINTE'),
	(18, 'CHRA1/01/26/00018', 'COULIBALY', 'IBRAHIM', 'M', '1987-07-07', 38, 'YOPOUGON', '0708090102', '', 'POPULATION GÉNÉRALE'),
	(19, 'CHRA1/01/26/00019', 'SYLLA', 'TENIN', 'F', '2001-08-08', 24, 'ABOBO', '0505050606', '', 'FEMME ALLAITANTE'),
	(20, 'CHRA1/01/26/00020', 'BLE', 'GOUDE CHARLES', 'M', '1978-09-09', 47, 'COCODY', '0101020203', '0565343234', 'POPULATION GÉNÉRALE');

-- Listage de la structure de table agenda_pvvih. relances
CREATE TABLE IF NOT EXISTS `relances` (
  `id` int NOT NULL AUTO_INCREMENT,
  `patient_id` int DEFAULT NULL,
  `moyen` varchar(50) DEFAULT NULL,
  `resultat` text,
  `date_relance` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `patient_id` (`patient_id`),
  CONSTRAINT `relances_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- Listage de la structure de table agenda_pvvih. avals
CREATE TABLE IF NOT EXISTS `avals` (
  `id` int NOT NULL AUTO_INCREMENT,
  `patient_id` int DEFAULT NULL,
  `nom_complet` varchar(200) DEFAULT NULL,
  `lien_parente` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `confidentialite` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `patient_id` (`patient_id`),
  CONSTRAINT `avals_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Listage des données de la table agenda_pvvih.avals : ~20 rows (environ)
INSERT INTO `avals` (`id`, `patient_id`, `nom_complet`, `lien_parente`, `telephone`, `confidentialite`) VALUES
	(1, 1, 'KOUASSI AMÉLIE', 'CONJOINT(E)', '0101010101', 'OUI'),
	(2, 2, 'BAMBA SOULEYMANE', 'PARENT', '0707070707', 'NON'),
	(3, 3, 'TRAORE ADAMA', 'FRÈRE/SŒUR', '0102030405', 'OUI'),
	(4, 4, 'N\'GUESSAN KOFFI', 'CONJOINT(E)', '0505050505', 'NON'),
	(5, 5, 'YOBOUET MARIE', 'PARENT', '0101010101', 'NON'),
	(6, 6, 'DIALLO ISSA', 'CONJOINT(E)', '0701020304', 'OUI'),
	(7, 7, 'KOFFI HERMANN', 'CONJOINT(E)', '0505445566', 'NON'),
	(8, 8, 'KONAN ALICE', 'FRÈRE/SŒUR', '0708090706', 'OUI'),
	(9, 9, 'TOURE FATIM', 'PARENT', '0102036677', 'NON'),
	(10, 10, 'GOHOU THERESE', 'CONJOINT(E)', '0707889900', 'OUI'),
	(11, 11, 'SIDIBE MOUSSA', 'PARENT', '0102030405', 'NON'),
	(12, 12, 'OUATTARA FANTA', 'FRÈRE/SŒUR', '0700112233', 'OUI'),
	(14, 14, 'AKISSI PAUL', 'ENFANT', '0101010101', 'NON'),
	(15, 15, 'YAO AFFOUE', 'PARENT', '0707070707', 'NON'),
	(16, 16, 'KONE SEYDOU', 'FRÈRE/SŒUR', '0505060607', 'OUI'),
	(17, 17, 'OUEDRAOGO DRISSA', 'CONJOINT(E)', '0102030406', 'NON'),
	(18, 18, 'COULIBALY AWA', 'CONJOINT(E)', '0708090102', 'OUI'),
	(19, 19, 'SYLLA BAKARY', 'PARENT', '0505050606', 'NON'),
	(21, 20, 'BLE SIMONE', 'CONJOINT(E)', '0101020203', 'OUI'),
	(22, 13, 'ZOKORA NADEGE', 'CONJOINT(E)', '0505050505', 'NON');

-- Listage des données de la table agenda_pvvih.relances : ~0 rows (environ)

-- Listage de la structure de table agenda_pvvih. rendez_vous
CREATE TABLE IF NOT EXISTS `rendez_vous` (
  `id` int NOT NULL AUTO_INCREMENT,
  `patient_id` int DEFAULT NULL,
  `date_derniere_visite` date DEFAULT NULL,
  `date_rdv` date DEFAULT NULL,
  `motif_rdv` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `molecule` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `duree_traitement` int DEFAULT NULL,
  `assiduite` varchar(50) DEFAULT NULL,
  `date_prochain_rdv` date DEFAULT NULL,
  `motif_prochain_rdv` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `patient_id` (`patient_id`),
  CONSTRAINT `rendez_vous_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Listage des données de la table agenda_pvvih.rendez_vous : ~3 rows (environ)
INSERT INTO `rendez_vous` (`id`, `patient_id`, `date_derniere_visite`, `date_rdv`, `motif_rdv`, `molecule`, `duree_traitement`, `assiduite`, `date_prochain_rdv`, `motif_prochain_rdv`) VALUES
	(1, 13, NULL, '2025-11-05', 'BILAN DE SUIVI, RENOUVELLEMENT ORDONNANCE, VISITE DE SUIVI', 'TDF/3TC/DTG', 30, 'RDV RESPECTER', '2025-12-05', 'CHARGE VIRALE, RENOUVELLEMENT ORDONNANCE, VISITE DE SUIVI'),
	(2, 13, NULL, '2025-12-03', 'CHARGE VIRALE, RENOUVELLEMENT ORDONNANCE, VISITE DE SUIVI', 'TDF/3TC/DTG', 30, 'RDV ANTICIPE', '2026-01-02', 'RENOUVELLEMENT ORDONNANCE, VISITE DE SUIVI'),
	(3, 13, NULL, '2026-01-19', 'CHARGE VIRALE, RENOUVELLEMENT ORDONNANCE, VISITE DE SUIVI', 'TDF/3TC/DTG', 30, 'RDV RATTRAPER', '2026-02-18', 'RENOUVELLEMENT ORDONNANCE, VISITE DE SUIVI');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
