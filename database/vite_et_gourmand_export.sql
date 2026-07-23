-- MySQL dump 10.13  Distrib 8.0.46, for Win64 (x86_64)
--
-- Host: localhost    Database: vite_et_gourmand
-- ------------------------------------------------------
-- Server version	8.0.46

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `allergene`
--

DROP TABLE IF EXISTS `allergene`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `allergene` (
  `id` int NOT NULL AUTO_INCREMENT,
  `libelle` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `allergene`
--

LOCK TABLES `allergene` WRITE;
/*!40000 ALTER TABLE `allergene` DISABLE KEYS */;
INSERT INTO `allergene` VALUES (31,'Gluten'),(32,'Oeufs'),(33,'Lait'),(34,'Fruits à coque'),(35,'Poisson');
/*!40000 ALTER TABLE `allergene` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `avis`
--

DROP TABLE IF EXISTS `avis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `avis` (
  `id` int NOT NULL AUTO_INCREMENT,
  `note` int NOT NULL,
  `commentaire` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `valide` tinyint NOT NULL,
  `date_creation` datetime NOT NULL,
  `commande_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8F91ABF082EA2E54` (`commande_id`),
  CONSTRAINT `FK_8F91ABF082EA2E54` FOREIGN KEY (`commande_id`) REFERENCES `commande` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `avis`
--

LOCK TABLES `avis` WRITE;
/*!40000 ALTER TABLE `avis` DISABLE KEYS */;
INSERT INTO `avis` VALUES (11,5,'Un traiteur de qualité supérieure. Nous nous sommes régalés !',1,'2026-07-17 21:06:50',27),(12,5,'Une équipe aux petits soins, des plats de qualité. Nous nous sommes régalés ! Merci !',1,'2026-07-20 15:13:21',29),(13,4,'Commande faite pour un repas d\'anniversaire et nous n\'avons pas été déçus. Traiteur au top !',1,'2026-07-20 15:22:52',31),(14,5,'Une belle découverte ! Tous les participants à notre séminaire d\'entreprise ont grandement apprécié le repas livré par Vite & Gourmand. Merci à vous pour ce délicieux repas.',1,'2026-07-20 15:30:30',32),(15,5,'Très bien ! Je recommande !',1,'2026-07-20 15:34:05',33),(16,4,'Très bonne expérience. Nous ferons de nouveau appel à ce traiteur dont la qualité des plats est remarquable. Service impeccable et équipe attentive à nos demandes.',1,'2026-07-20 15:40:00',34),(17,5,'Rien à redire, tout était parfait !',1,'2026-07-20 15:45:01',35);
/*!40000 ALTER TABLE `avis` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `commande`
--

DROP TABLE IF EXISTS `commande`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `commande` (
  `id` int NOT NULL AUTO_INCREMENT,
  `numero_commande` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_commande` datetime NOT NULL,
  `date_prestation` date NOT NULL,
  `heure_livraison` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `adresse_livraison` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ville_livraison` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nb_personnes` int NOT NULL,
  `prix_menu` decimal(10,2) NOT NULL,
  `prix_livraison` decimal(10,2) DEFAULT NULL,
  `prix_total` decimal(10,2) NOT NULL,
  `pret_materiel` tinyint NOT NULL,
  `materiel_rendu` tinyint DEFAULT NULL,
  `motif_annulation` longtext COLLATE utf8mb4_unicode_ci,
  `mode_contact_annulation` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `utilisateur_id` int NOT NULL,
  `menu_id` int NOT NULL,
  `materiel_accorde` tinyint DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6EEAA67DFB88E14F` (`utilisateur_id`),
  KEY `IDX_6EEAA67DCCD7E912` (`menu_id`),
  CONSTRAINT `FK_6EEAA67DCCD7E912` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`),
  CONSTRAINT `FK_6EEAA67DFB88E14F` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `commande`
--

LOCK TABLES `commande` WRITE;
/*!40000 ALTER TABLE `commande` DISABLE KEYS */;
INSERT INTO `commande` VALUES (27,'CMD-TEST-001','2026-07-09 21:06:50','2026-07-16','19:00','49 rue des Festivités','Bordeaux',6,270.00,0.00,270.00,0,NULL,NULL,NULL,32,16,NULL),(28,'CMD-6a5e1c9e9d801','2026-07-20 15:03:26','2026-08-28','12','2 rue du lieu','orélans',12,388.80,5.00,393.80,0,NULL,NULL,NULL,33,19,NULL),(29,'CMD-6a5e1dca68f7f','2026-07-20 15:08:26','2026-08-29','12h','2 rue de la joie','bordeaux',10,270.00,0.00,270.00,0,NULL,NULL,NULL,34,21,NULL),(30,'CMD-6a5e1f8e08d5b','2026-07-20 15:15:58','2026-09-16','18h','3 rue des cigales','Nyons',7,266.00,5.00,271.00,0,NULL,NULL,NULL,35,17,NULL),(31,'CMD-6a5e20ab540d0','2026-07-20 15:20:43','2026-09-16','18','3 rue des essais','Nyons',4,128.00,5.00,133.00,0,NULL,NULL,NULL,36,18,NULL),(32,'CMD-6a5e21d57bdb0','2026-07-20 15:25:41','2026-09-22','10','3 impasse de l\'industrie','Caen',27,1093.50,5.00,1098.50,0,NULL,NULL,NULL,37,20,NULL),(33,'CMD-6a5e23895d005','2026-07-20 15:32:57','2026-07-30','12','4 rue des grands oiseaux','Lille',5,190.00,5.00,195.00,0,NULL,NULL,NULL,38,17,NULL),(34,'CMD-6a5e247542fd2','2026-07-20 15:36:53','2026-08-29','12h','12 avenue des pins','Chambéry',8,288.00,5.00,293.00,0,NULL,NULL,NULL,39,19,NULL),(35,'CMD-6a5e2612c4b9a','2026-07-20 15:43:46','2026-08-07','12h','3 avenue du tram','Montélimar',6,216.00,5.00,221.00,0,NULL,NULL,NULL,40,19,NULL),(36,'CMD-6a5e4619c3577','2026-07-20 18:00:25','2026-08-27','12h','1 rue du mail','bayonne',11,495.00,5.00,500.00,0,NULL,NULL,NULL,32,20,NULL);
/*!40000 ALTER TABLE `commande` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctrine_migration_versions`
--

LOCK TABLES `doctrine_migration_versions` WRITE;
/*!40000 ALTER TABLE `doctrine_migration_versions` DISABLE KEYS */;
INSERT INTO `doctrine_migration_versions` VALUES ('DoctrineMigrations\\Version20260708182617','2026-07-08 18:29:00',779),('DoctrineMigrations\\Version20260709185606','2026-07-09 18:56:13',66),('DoctrineMigrations\\Version20260711145141','2026-07-11 14:54:14',66),('DoctrineMigrations\\Version20260714162252','2026-07-14 18:25:01',147),('DoctrineMigrations\\Version20260716125513','2026-07-16 14:56:55',50),('DoctrineMigrations\\Version20260716151131','2026-07-16 17:13:40',18);
/*!40000 ALTER TABLE `doctrine_migration_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `horaire`
--

DROP TABLE IF EXISTS `horaire`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `horaire` (
  `id` int NOT NULL AUTO_INCREMENT,
  `jour` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `heure_ouverture` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `heure_fermeture` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `horaire`
--

LOCK TABLES `horaire` WRITE;
/*!40000 ALTER TABLE `horaire` DISABLE KEYS */;
INSERT INTO `horaire` VALUES (43,'Lundi','09:00','19:00'),(44,'Mardi','09:00','19:00'),(45,'Mercredi','09:00','19:00'),(46,'Jeudi','09:00','19:00'),(47,'Vendredi','09:00','20:00'),(48,'Samedi','10:00','20:00'),(49,'Dimanche','10:00','14:00');
/*!40000 ALTER TABLE `horaire` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `image`
--

DROP TABLE IF EXISTS `image`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `image` (
  `id` int NOT NULL AUTO_INCREMENT,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ordre` int DEFAULT NULL,
  `menu_id` int NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_C53D045FCCD7E912` (`menu_id`),
  CONSTRAINT `FK_C53D045FCCD7E912` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `image`
--

LOCK TABLES `image` WRITE;
/*!40000 ALTER TABLE `image` DISABLE KEYS */;
INSERT INTO `image` VALUES (40,'12-chapon-farci-aux-marrons-et-foie-gras-1-6a6091a2a48c3645716099.webp',NULL,16,NULL),(41,'03-foie-gras-maison-et-chutney-de-figues-1-6a609268a9914523913769.webp',NULL,16,NULL),(42,'19-buche-de-noel-chocolat-noisette-1-6a609274dd496304956116.webp',NULL,16,NULL),(43,'07-terrine-de-campagne-et-pickles-1-6a60929db12bf243672490.webp',NULL,17,NULL),(44,'11-gigot-d-agneau-confit-aux-herbes-gratin-dauphinois-1-6a6092b0da1a9801739608.webp',NULL,17,NULL),(45,'21-fraisier-1-6a6092c134d4e798361602.webp',NULL,17,NULL),(46,'15-supreme-de-pintade-sauce-morilles-1-6a6092fd9b9f8276980340.webp',NULL,18,NULL),(47,'03-foie-gras-maison-et-chutney-de-figues-1-6a609324a5a44810595706.webp',NULL,18,NULL),(48,'20-tiramisu-1-6a609337ebafb575475459.webp',NULL,18,NULL),(49,'26-terrine-de-legumes-grilles-et-tofu-marine-1x-6a609376737d5637969078.webp',NULL,19,NULL),(50,'27-ragout-de-lentilles-et-legumes-racines-1-6a609382cec04015505402.webp',NULL,19,NULL),(51,'25-panna-cotta-coulis-de-fruits-rouges-1-1x-6a60938fb1cd8528642977.webp',NULL,19,NULL),(52,'feuillete-6a6093cf01887921698615.jpg',NULL,20,NULL),(53,'10-pave-de-saumon-beurre-blanc-1-6a6093e57e9c9264272317.webp',NULL,20,NULL),(54,'22-entremet-trois-chocolats-1-6a6093f813335261315318.webp',NULL,20,NULL),(55,'14-curry-de-legumes-et-lait-de-coco-1-6a6094436f2ae961401513.webp',NULL,21,NULL),(56,'13-risotto-aux-champignons-et-parmesan-1-6a609463529fd453861636.webp',NULL,21,NULL),(57,'23-salade-de-fruits-1-6a6094715419c027499623.webp',NULL,21,NULL);
/*!40000 ALTER TABLE `image` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu`
--

DROP TABLE IF EXISTS `menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menu` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `conditions` longtext COLLATE utf8mb4_unicode_ci,
  `nb_personnes_min` int NOT NULL,
  `prix_base` decimal(10,2) NOT NULL,
  `stock_disponible` int NOT NULL,
  `actif` tinyint NOT NULL,
  `theme_id` int NOT NULL,
  `regime_id` int NOT NULL,
  `delai_minimum_jours` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_7D053A9359027487` (`theme_id`),
  KEY `IDX_7D053A9335E7D534` (`regime_id`),
  CONSTRAINT `FK_7D053A9335E7D534` FOREIGN KEY (`regime_id`) REFERENCES `regime` (`id`),
  CONSTRAINT `FK_7D053A9359027487` FOREIGN KEY (`theme_id`) REFERENCES `theme` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu`
--

LOCK TABLES `menu` WRITE;
/*!40000 ALTER TABLE `menu` DISABLE KEYS */;
INSERT INTO `menu` VALUES (16,'Menu Noël Tradition','Un menu chaleureux et familial pour célébrer Noël autour de plats traditionnels français.','Commande à effectuer 5 jours avant la prestation.',6,30.00,11,1,25,19,5),(17,'Menu Pâques Agneau','Menu printanier autour de l\'agneau, plat traditionnel de Pâques.','Commande à effectuer 3 jours avant la prestation.',4,38.00,6,1,26,19,3),(18,'Menu Table élégante','Un menu raffiné pour un repas assis, disponible toute l\'année.','Commande à effectuer 2 jours avant la prestation.',2,32.00,14,1,27,19,2),(19,'Menu Vegan','Un menu Un menu que tout le monde pourra manger.','Commande à effectuer 3 jours avant la prestation.',6,36.00,7,1,27,21,3),(20,'Menu Cocktail Business','Formule cocktail dînatoire idéale pour vos réceptions d\'entreprise.','Commande à effectuer 7 jours avant la prestation.',10,45.00,6,1,28,19,7),(21,'Menu Végétarien','Pour les végés qui veulent se régaler.','Commande à effectuer 2 jours avant la prestation.',4,30.00,9,1,27,20,2);
/*!40000 ALTER TABLE `menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu_plat`
--

DROP TABLE IF EXISTS `menu_plat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menu_plat` (
  `menu_id` int NOT NULL,
  `plat_id` int NOT NULL,
  PRIMARY KEY (`menu_id`,`plat_id`),
  KEY `IDX_E8775249CCD7E912` (`menu_id`),
  KEY `IDX_E8775249D73DB560` (`plat_id`),
  CONSTRAINT `FK_E8775249CCD7E912` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_E8775249D73DB560` FOREIGN KEY (`plat_id`) REFERENCES `plat` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu_plat`
--

LOCK TABLES `menu_plat` WRITE;
/*!40000 ALTER TABLE `menu_plat` DISABLE KEYS */;
INSERT INTO `menu_plat` VALUES (16,16),(16,17),(16,18),(17,19),(17,20),(17,21),(18,16),(18,23),(18,24),(19,25),(19,26),(19,27),(20,28),(20,29),(20,30),(21,31),(21,32),(21,33);
/*!40000 ALTER TABLE `menu_plat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messenger_messages`
--

DROP TABLE IF EXISTS `messenger_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `messenger_messages` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `available_at` datetime NOT NULL,
  `delivered_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750` (`queue_name`,`available_at`,`delivered_at`,`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messenger_messages`
--

LOCK TABLES `messenger_messages` WRITE;
/*!40000 ALTER TABLE `messenger_messages` DISABLE KEYS */;
INSERT INTO `messenger_messages` VALUES (1,'O:36:\\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\\":2:{s:44:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\\";a:1:{s:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\";a:1:{i:0;O:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\":1:{s:55:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\\";s:21:\\\"messenger.bus.default\\\";}}}s:45:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\\";O:51:\\\"Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\\":2:{s:60:\\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0message\\\";O:39:\\\"Symfony\\\\Bridge\\\\Twig\\\\Mime\\\\TemplatedEmail\\\":5:{i:0;s:30:\\\"reset_password/email.html.twig\\\";i:1;N;i:2;a:1:{s:10:\\\"resetToken\\\";O:58:\\\"SymfonyCasts\\\\Bundle\\\\ResetPassword\\\\Model\\\\ResetPasswordToken\\\":4:{s:65:\\\"\\0SymfonyCasts\\\\Bundle\\\\ResetPassword\\\\Model\\\\ResetPasswordToken\\0token\\\";s:40:\\\"mQyMWLBXJgxKqD5HPYQTlmZ173K4XP83sd1uB82q\\\";s:69:\\\"\\0SymfonyCasts\\\\Bundle\\\\ResetPassword\\\\Model\\\\ResetPasswordToken\\0expiresAt\\\";O:33:\\\"Symfony\\\\Component\\\\Clock\\\\DatePoint\\\":3:{s:4:\\\"date\\\";s:26:\\\"2026-07-09 20:14:08.915843\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:3:\\\"UTC\\\";}s:71:\\\"\\0SymfonyCasts\\\\Bundle\\\\ResetPassword\\\\Model\\\\ResetPasswordToken\\0generatedAt\\\";i:1783624448;s:73:\\\"\\0SymfonyCasts\\\\Bundle\\\\ResetPassword\\\\Model\\\\ResetPasswordToken\\0transInterval\\\";i:1;}}i:3;a:6:{i:0;N;i:1;N;i:2;N;i:3;N;i:4;a:0:{}i:5;a:2:{i:0;O:37:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\\":2:{s:46:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\\";a:3:{s:4:\\\"from\\\";a:1:{i:0;O:47:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:4:\\\"From\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:58:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\\";a:1:{i:0;O:30:\\\"Symfony\\\\Component\\\\Mime\\\\Address\\\":2:{s:39:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\\";s:27:\\\"contact@vite-et-gourmand.fr\\\";s:36:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\\";s:15:\\\"Vite & Gourmand\\\";}}}}s:2:\\\"to\\\";a:1:{i:0;O:47:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:2:\\\"To\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:58:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\\";a:1:{i:0;O:30:\\\"Symfony\\\\Component\\\\Mime\\\\Address\\\":2:{s:39:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\\";s:26:\\\"admin@vite-et-gourmand.com\\\";s:36:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\\";s:0:\\\"\\\";}}}}s:7:\\\"subject\\\";a:1:{i:0;O:48:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:7:\\\"Subject\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:55:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\0value\\\";s:27:\\\"Your password reset request\\\";}}}s:49:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\\";i:76;}i:1;N;}}i:4;N;}s:61:\\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0envelope\\\";N;}}','[]','default','2026-07-09 19:14:09','2026-07-09 19:14:09',NULL);
/*!40000 ALTER TABLE `messenger_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plat`
--

DROP TABLE IF EXISTS `plat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `plat` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plat`
--

LOCK TABLES `plat` WRITE;
/*!40000 ALTER TABLE `plat` DISABLE KEYS */;
INSERT INTO `plat` VALUES (16,'Délice de foie gras','entrée','Foie gras mi-cuit accompagné de chutney de figues.'),(17,'Chapon farci','plat','Volaille fermière rôtie, farce aux marrons et champignons.'),(18,'Bûche de Noël','dessert','Bûche pâtissière chocolat noisette.'),(19,'Terrine de campagne','entrée','Belle terrine accompagnée de pickles et de pain toasté.'),(20,'Gigot d\'agneau confit aux herbes et gratin dauphinois','plat','Pièce de viande fondante, avec herbes de Provence, et gratin onctueux.'),(21,'Fraisier','dessert','Fraisier traditionnel.'),(22,'Foie gras maison','entrée','Foie gras mi-cuit accompagné de chutney de figues.'),(23,'Suprême de pintade','plat','Volaille fermière et sa sauce aux morilles.'),(24,'Tiramisu','dessert','Tiramisu au café et poudre de cacao.'),(25,'Terrine de légumes','entrée','Terrine de légumes grillés et tofu mariné.'),(26,'Ragoût de lentilles','plat','Lentilles vertes et légumes racines.'),(27,'Panna cotta','dessert','Panna cotta de tradition avec coulis de fruits rouges.'),(28,'Feuilletés de la mer','entrée','Feuilletés croustillants garnis de crevettes.'),(29,'Délicieux saumon','plat','Pavé de saumon frais, beurre blanc.'),(30,'Entremet gourmand','dessert','Entremet trois chocolats.'),(31,'Curry de légume','entrée','Curry de légumes et lait de coco.'),(32,'Risotto','plat','Risotto aux champignons et parmesan.'),(33,'Salade de fruits','dessert','Salade fraîcheur et ses fruits du marché.');
/*!40000 ALTER TABLE `plat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plat_allergene`
--

DROP TABLE IF EXISTS `plat_allergene`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `plat_allergene` (
  `plat_id` int NOT NULL,
  `allergene_id` int NOT NULL,
  PRIMARY KEY (`plat_id`,`allergene_id`),
  KEY `IDX_6FA44BBFD73DB560` (`plat_id`),
  KEY `IDX_6FA44BBF4646AB2` (`allergene_id`),
  CONSTRAINT `FK_6FA44BBF4646AB2` FOREIGN KEY (`allergene_id`) REFERENCES `allergene` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_6FA44BBFD73DB560` FOREIGN KEY (`plat_id`) REFERENCES `plat` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plat_allergene`
--

LOCK TABLES `plat_allergene` WRITE;
/*!40000 ALTER TABLE `plat_allergene` DISABLE KEYS */;
/*!40000 ALTER TABLE `plat_allergene` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `regime`
--

DROP TABLE IF EXISTS `regime`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `regime` (
  `id` int NOT NULL AUTO_INCREMENT,
  `libelle` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `regime`
--

LOCK TABLES `regime` WRITE;
/*!40000 ALTER TABLE `regime` DISABLE KEYS */;
INSERT INTO `regime` VALUES (19,'Classique'),(20,'Végétarien'),(21,'Vegan');
/*!40000 ALTER TABLE `regime` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reset_password_request`
--

DROP TABLE IF EXISTS `reset_password_request`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reset_password_request` (
  `id` int NOT NULL AUTO_INCREMENT,
  `selector` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hashed_token` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `requested_at` datetime NOT NULL,
  `expires_at` datetime NOT NULL,
  `user_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_7CE748AA76ED395` (`user_id`),
  CONSTRAINT `FK_7CE748AA76ED395` FOREIGN KEY (`user_id`) REFERENCES `utilisateur` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reset_password_request`
--

LOCK TABLES `reset_password_request` WRITE;
/*!40000 ALTER TABLE `reset_password_request` DISABLE KEYS */;
/*!40000 ALTER TABLE `reset_password_request` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role`
--

DROP TABLE IF EXISTS `role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role` (
  `id` int NOT NULL AUTO_INCREMENT,
  `libelle` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role`
--

LOCK TABLES `role` WRITE;
/*!40000 ALTER TABLE `role` DISABLE KEYS */;
INSERT INTO `role` VALUES (19,'ROLE_ADMIN'),(20,'ROLE_EMPLOYE'),(21,'ROLE_USER');
/*!40000 ALTER TABLE `role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `statut_historique`
--

DROP TABLE IF EXISTS `statut_historique`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `statut_historique` (
  `id` int NOT NULL AUTO_INCREMENT,
  `statut` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_modification` datetime NOT NULL,
  `commentaire` longtext COLLATE utf8mb4_unicode_ci,
  `commande_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_9874D8E582EA2E54` (`commande_id`),
  CONSTRAINT `FK_9874D8E582EA2E54` FOREIGN KEY (`commande_id`) REFERENCES `commande` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `statut_historique`
--

LOCK TABLES `statut_historique` WRITE;
/*!40000 ALTER TABLE `statut_historique` DISABLE KEYS */;
INSERT INTO `statut_historique` VALUES (38,'reçue','2026-07-20 15:03:26',NULL,28),(39,'terminée','2026-07-20 15:04:24',NULL,28),(40,'reçue','2026-07-20 15:08:26',NULL,29),(41,'terminée','2026-07-20 15:09:20',NULL,29),(42,'reçue','2026-07-20 15:15:58',NULL,30),(43,'terminée','2026-07-20 15:16:30',NULL,30),(44,'reçue','2026-07-20 15:20:43',NULL,31),(45,'terminée','2026-07-20 15:21:19',NULL,31),(46,'reçue','2026-07-20 15:25:41',NULL,32),(47,'terminée','2026-07-20 15:26:26',NULL,32),(48,'reçue','2026-07-20 15:32:57',NULL,33),(49,'terminée','2026-07-20 15:33:28',NULL,33),(50,'reçue','2026-07-20 15:36:53',NULL,34),(51,'terminée','2026-07-20 15:37:23',NULL,34),(52,'reçue','2026-07-20 15:43:46',NULL,35),(53,'terminée','2026-07-20 15:44:20',NULL,35),(54,'reçue','2026-07-20 18:00:25',NULL,36),(55,'terminée','2026-07-20 18:07:00',NULL,36),(56,'annulée','2026-07-20 18:08:02','motif : plus de stock (Contact : mail)',27);
/*!40000 ALTER TABLE `statut_historique` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `theme`
--

DROP TABLE IF EXISTS `theme`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `theme` (
  `id` int NOT NULL AUTO_INCREMENT,
  `libelle` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `theme`
--

LOCK TABLES `theme` WRITE;
/*!40000 ALTER TABLE `theme` DISABLE KEYS */;
INSERT INTO `theme` VALUES (25,'Noël'),(26,'Pâques'),(27,'Classique'),(28,'Événement');
/*!40000 ALTER TABLE `theme` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `utilisateur` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adresse` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ville` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_postal` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pays` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `actif` tinyint NOT NULL,
  `role_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_IDENTIFIER_EMAIL` (`email`),
  KEY `IDX_1D1C63B3D60322AC` (`role_id`),
  CONSTRAINT `FK_1D1C63B3D60322AC` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `utilisateur`
--

LOCK TABLES `utilisateur` WRITE;
/*!40000 ALTER TABLE `utilisateur` DISABLE KEYS */;
INSERT INTO `utilisateur` VALUES (30,'admin@vite-et-gourmand.com','[\"ROLE_ADMIN\"]','$2y$13$yjKLUwMogfGlFOL1T4NNs.liV8lAQ3QWa/myEwkKq8MLjTDJk/5Ya','Dupont','José',NULL,NULL,NULL,NULL,NULL,1,19),(31,'employe@vite-et-gourmand.com','[\"ROLE_EMPLOYE\"]','$2y$13$1WcjTNGSXEKXy512I2UQ4.RlM7z34n0uuK.oyVfLrKBdaSsvHV5ma','Durand','Julie',NULL,NULL,NULL,NULL,NULL,1,20),(32,'user@example.com','[\"ROLE_USER\"]','$2y$13$pELVY7s7NVNtIXZ2SuCGjOUNZ.u5Z8V9Pbn8cCvFXA04t9oS3W95G','S','John',NULL,NULL,NULL,NULL,NULL,1,21),(33,'arthur@mail.com','[\"ROLE_USER\"]','$2y$13$M0oIX.8G3618RvQ75TMEvuKP/ZCu22gFnc3ILiMOnDrThH/iX.dTO','T','Arthur',NULL,NULL,NULL,NULL,NULL,1,21),(34,'olivia@mail.com','[\"ROLE_USER\"]','$2y$13$nmkF8wsb8h8xICxMmeKwJufctD.xHxMQQcsTSconRQonAa8/8TDS2','G','Olivia',NULL,NULL,NULL,NULL,NULL,1,21),(35,'theo@mail.com','[\"ROLE_USER\"]','$2y$13$/rSb8hkCWaambYBNWwTvR.3RiciLPc3Y1IUPljb.TqmWVWoLYdXGi','M','Théo',NULL,NULL,NULL,NULL,NULL,1,21),(36,'julien@mail.com','[\"ROLE_USER\"]','$2y$13$FV8uJ2fLJTXmdtyNUuK/kOR/3v0YaITC60UGDh.sQAxuijQK5kRpu','M','Julien',NULL,NULL,NULL,NULL,NULL,1,21),(37,'mel@mail.com','[\"ROLE_USER\"]','$2y$13$4yCy98hvddThr78a2.Sahe45BxoiGG9SX3oHioKNF2fizKBhiYZLu','O','Mélanie',NULL,NULL,NULL,NULL,NULL,1,21),(38,'Lilian@mail.com','[\"ROLE_USER\"]','$2y$13$OEsTyIPCOo2PXYa5QY6WAeZxG.y1Cd4r3A7VlGIP6Ze6e4e33G3FS','L','Lilian',NULL,NULL,NULL,NULL,NULL,1,21),(39,'martial@mail.com','[\"ROLE_USER\"]','$2y$13$qHkICWuWXQYIXAoVxE62euhy2vNmRYml4l5.j2ufGTfvJaAtw2FsS','V','Martial',NULL,NULL,NULL,NULL,NULL,1,21),(40,'brigitte@mail.com','[\"ROLE_USER\"]','$2y$13$G5Zp865lM21kYpU50wmfFO98WQ1Rj3fbxOgw2wBE/SM93G/EGecoO','S','Brigitte',NULL,NULL,NULL,NULL,NULL,1,21),(41,'soso@mail.com','[\"ROLE_USER\"]','$2y$13$ms.X3RfqxYoYIVLUqXkt/.Md0vrDjQm4M6KXZrZBS2aeoZT7T6FVu','Martin','Sophie','07 00 00 00 00','10 rue du jardin','Bordeaux','33000','France',1,21);
/*!40000 ALTER TABLE `utilisateur` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-07-22 18:45:21
