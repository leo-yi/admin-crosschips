
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `admin_extension_histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_extension_histories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` tinyint NOT NULL DEFAULT '1',
  `version` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `detail` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `admin_extension_histories_name_index` (`name`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `admin_extension_histories` WRITE;
/*!40000 ALTER TABLE `admin_extension_histories` DISABLE KEYS */;
/*!40000 ALTER TABLE `admin_extension_histories` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `admin_extensions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_extensions` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `version` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `is_enabled` tinyint NOT NULL DEFAULT '0',
  `options` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `admin_extensions_name_unique` (`name`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `admin_extensions` WRITE;
/*!40000 ALTER TABLE `admin_extensions` DISABLE KEYS */;
/*!40000 ALTER TABLE `admin_extensions` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `admin_help_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_help_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'feather icon-help-circle',
  `sort` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `admin_help_categories` WRITE;
/*!40000 ALTER TABLE `admin_help_categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `admin_help_categories` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `admin_helps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_helps` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link_target` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '_self',
  `sort` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `admin_helps_category_id_index` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `admin_helps` WRITE;
/*!40000 ALTER TABLE `admin_helps` DISABLE KEYS */;
/*!40000 ALTER TABLE `admin_helps` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `admin_menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_menu` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `parent_id` bigint NOT NULL DEFAULT '0',
  `order` int NOT NULL DEFAULT '0',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uri` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `extension` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `show` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `admin_menu` WRITE;
/*!40000 ALTER TABLE `admin_menu` DISABLE KEYS */;
INSERT INTO `admin_menu` VALUES (1,0,1,'Index','feather icon-bar-chart-2','/','',1,'2022-07-16 13:56:27',NULL),(2,0,10,'admin','feather icon-settings','','',1,'2022-12-04 02:52:50','2026-07-28 02:47:48'),(3,2,11,'Users','','auth/users','',1,'2022-12-04 02:52:50','2024-09-09 04:39:02'),(4,2,12,'Roles','','auth/roles','',1,'2022-12-04 02:52:50','2024-09-09 04:39:02'),(5,2,13,'Permission','','auth/permissions','',1,'2022-12-04 02:52:50','2024-09-09 04:39:02'),(6,2,14,'Menu','','auth/menu','',1,'2022-12-04 02:52:50','2024-09-09 04:39:02'),(7,2,15,'Extensions','','auth/extensions','',1,'2022-12-04 02:52:50','2024-09-09 04:39:02'),(11,0,2,'Web Admin','fa-align-justify','/web','',1,'2022-12-12 06:41:25','2024-09-09 04:39:02'),(12,15,8,'chip-manufacturer','fa-circle-o','chip-manufacturer','',1,'2022-12-12 06:42:14','2026-07-28 02:47:48'),(13,11,3,'index-banner','fa-circle-o','/index-banner','',1,'2022-12-13 06:56:58','2026-07-28 02:47:48'),(14,15,7,'chip-product','fa-circle-o','chip-product','',1,'2022-12-26 05:07:19','2026-07-28 02:47:48'),(15,0,6,'Basic Data','fa-align-justify','/basic-data','',1,'2022-12-31 06:15:33','2024-09-09 04:39:02'),(18,11,4,'chip-rfq','fa-circle-o','chip-rfq','',1,'2023-01-03 05:14:54','2026-07-28 02:47:48'),(20,15,9,'chip-category','fa-circle-o','chip-category','',1,'2023-02-18 17:54:19','2026-07-28 02:47:48'),(22,11,5,'task','fa-circle-o','task','',1,'2023-02-25 13:51:56','2026-07-28 02:47:48'),(28,11,16,'chip-manufacturer-relation','fa-circle-o','chip-manufacturer-relation','',1,'2024-09-23 01:19:10','2026-07-28 02:47:48'),(29,11,17,'chip-product-stock','fa-circle-o','chip-product-stock','',1,'2024-09-23 01:19:41','2026-07-28 02:47:48'),(32,11,18,'chip-station','fa-circle-o','chip-station','',1,'2026-07-28 02:36:12','2026-07-28 02:47:48'),(33,2,6,'operation_log','feather icon-file-text','auth/operation-logs','',1,'2026-07-28 03:05:54','2026-07-28 03:05:54'),(34,0,7,'help_center','feather icon-help-circle','','',1,'2026-07-28 03:05:54','2026-07-28 03:05:54'),(35,34,1,'help_categories','feather icon-layers','help-categories','',1,'2026-07-28 03:05:54','2026-07-28 03:05:54'),(36,34,2,'helps','feather icon-book-open','helps','',1,'2026-07-28 03:05:54','2026-07-28 03:05:54'),(37,0,8,'notifications','feather icon-bell','notifications','',1,'2026-07-28 03:05:54','2026-07-28 03:05:54');
/*!40000 ALTER TABLE `admin_menu` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `admin_notification_reads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_notification_reads` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `notification_id` bigint unsigned NOT NULL,
  `admin_user_id` bigint unsigned NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admin_notification_reads_notification_id_admin_user_id_unique` (`notification_id`,`admin_user_id`),
  KEY `admin_notification_reads_notification_id_index` (`notification_id`),
  KEY `admin_notification_reads_admin_user_id_index` (`admin_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `admin_notification_reads` WRITE;
/*!40000 ALTER TABLE `admin_notification_reads` DISABLE KEYS */;
/*!40000 ALTER TABLE `admin_notification_reads` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `admin_notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_notifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'info',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `admin_notifications` WRITE;
/*!40000 ALTER TABLE `admin_notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `admin_notifications` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `admin_operation_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_operation_log` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `panel_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_id` bigint NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `ip` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `input` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `admin_operation_log` WRITE;
/*!40000 ALTER TABLE `admin_operation_log` DISABLE KEYS */;
INSERT INTO `admin_operation_log` VALUES (1,'admin',0,'admin/auth/login','GET','127.0.0.1','[]','2026-07-28 06:13:03','2026-07-28 06:13:03'),(2,'admin',0,'admin/auth/login','POST','127.0.0.1','{\"_token\":\"KhbR0sw5ANTSMHUrkUP1gkUF62P6tV5j2f9vVJGM\",\"username\":\"admin\",\"password\":\"adm******\"}','2026-07-28 06:13:06','2026-07-28 06:13:06'),(3,'admin',1,'admin','GET','127.0.0.1','[]','2026-07-28 06:13:07','2026-07-28 06:13:07'),(4,'admin',1,'admin/auth/users','GET','127.0.0.1','[]','2026-07-28 06:23:52','2026-07-28 06:23:52'),(5,'admin',1,'admin','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2026-07-28 06:23:56','2026-07-28 06:23:56'),(6,'admin',1,'admin/chip-product','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2026-07-28 06:23:58','2026-07-28 06:23:58'),(7,'admin',1,'admin/auth/roles','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2026-07-28 06:24:02','2026-07-28 06:24:02'),(8,'admin',1,'admin/auth/permissions','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2026-07-28 06:24:03','2026-07-28 06:24:03'),(9,'admin',1,'admin/auth/menu','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2026-07-28 06:24:12','2026-07-28 06:24:12'),(10,'admin',1,'admin/auth/extensions','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2026-07-28 06:24:15','2026-07-28 06:24:15'),(11,'admin',1,'admin/auth/users','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2026-07-28 06:24:16','2026-07-28 06:24:16'),(12,'admin',1,'admin/auth/roles','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2026-07-28 06:24:18','2026-07-28 06:24:18'),(13,'admin',1,'admin/auth/roles/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2026-07-28 06:24:20','2026-07-28 06:24:20'),(14,'admin',1,'admin/auth/roles/create','GET','127.0.0.1','[]','2026-07-28 06:27:38','2026-07-28 06:27:38'),(15,'admin',1,'admin/auth/roles','POST','127.0.0.1','{\"_token\":\"VFwEsn6NOyDHoBIgve3jGV6Qg7hzuI6QHH7IyBfN\",\"slug\":\"user-admin\",\"name\":\"普通管理员\",\"role_authorization_present\":\"1\",\"route_permissions\":[\"route:04e3de7341fcd813c8aa8b0dc16c1e8d27365e83\",\"route:4f56d9802c3f53f03beaad29713a6d634d86832e\",\"route:a16d94acb091c07b5dbccf93f453a689068eaf4e\",\"route:ebed79448941a44690cb946f01e997c5b44bc53d\",\"route:76ba5a10f0c34cca234b068e834dc17a83056551\",\"route:8ad5470008efe611f29b6bb1835b749ab048c782\",\"route:179ec1e76b8f5de1f9b3f4bfe6be973a204bdcb0\",\"route:62fc72d571d54e7e87bf51299098d1be15acf729\",\"route:64042df64056a217f4f7b099f40a4f65eea42da8\",\"route:43ece7f59391b9254f7bbe952e728500b13c9fb2\",\"route:c065c7d87c0b9d666bfc9e4049fe9cd4cb197fe2\",\"route:e3478b18823266141aeb0bc23a3cd7debe43f0c1\",\"route:e24cbfd4f1d26a241e37022b3f054836fc43a33c\",\"route:39b9b668a2450e7befeab3698ab34184fc78264d\",\"route:826ee7af06e1f21c2c5047869c4b8e9b6e3c1214\",\"route:4ffccbb44dde29dd0e977d674ffea17a33a38e5c\",\"route:ae227b71c4d7dac9ee5a0c4b6051463110db3f5b\",\"route:f07b39b5510b888316846ea8c2db4f2af2239e4f\",\"route:0a04e726dded794f16017e008da53e3cbbeba19e\",\"route:35256c6e8bf677d340a207834a1f1bb873a44a6b\",\"route:983b366673082e9013df7cd9ec68d10379a263c0\",\"route:3ef71c38043e20aaabd625093e002623dc99921f\",\"route:1c6a6d426ef5ff8a1e5ce54397a136e5b54bc316\",\"route:443f30535c19626e54ab49cf00d268a60c4dfcb5\",\"route:ae028bda316879c39246290b0030ebf7b3d6b588\",\"route:7d502e12714d97b0eec432e2200cd5e981a0a92f\",\"route:e34365c3faf37625d115fded9f2f90ec8eaf88bd\",\"route:4df1096490e29c1b97f42d9ddcbdf826c5c645d5\",\"route:3b1151e7167d3f962971ab2394b849bda2aaa881\",\"route:83ade535f2b77c418eff52971dc257468374b04f\",\"route:c9d1995e4cf56c6faf2f4351844f0899b6b05d73\",\"route:def26da3821d32565aa84b6fff0d290bb3fa6ec0\",\"route:02f33823035410ee21a0c6ecb152d20c2f84eae6\",\"route:9d4c9110210b53d85535cc26d668ee4e4734fe81\",\"route:b599895dcacda449dc83454ac7050d0af217c10d\",\"route:3438cc13000c83d4eab7632496962dd5ca292043\",\"route:49d4ef1c6ce2813270bc5c6d7c29296b0d7bfa4d\",\"route:3a2163ecf970675b8fa254493d337e60077b62cf\",\"route:4ed671d6a0a8782f30c868afe131f2f990027689\",\"route:4b95de9ea5a9ef7cbf1f85c0ac7e5cd0d4b12e3f\",\"route:6f6fb67e6e0e226748cfc3d9af4344d57ae6500b\",\"route:8c8ffc1be47ac42460277a8832213a28c923cc23\",\"route:3ad11d4f97b08b4df19f94da81ea23be527f904d\",\"route:032fb241f7803386f71d00bc6bd2336b280d0623\",\"route:3b5473ae2f9a753775cff36b7d26a5a6e005d3fd\",\"route:ce8b4b49b41e0ada0c65f30b2fff1a392287e233\",\"route:5448a075fa3671b966bf1a97f148bd9bffbc15f3\",\"route:27f04591c74c606bc8d549eafc7b253c8b7e7432\",\"route:b026888c8fef2407896d2233c2f8a6898a44cded\",\"route:df7d9bb5a6a8b8b101501c670ee4e37041c3db82\",\"route:dbb7dbb1ae9e13971bfbd2cb242bcc1c9239f0a3\",\"route:ba5bf85e947b75230c93bc400f85a196464cbd82\",\"route:5047c279d7adedde199f93e8460e6741e216bc57\",\"route:f2aebdb5a7a66ae00654c39b22f2a4b2e9ddd0d6\",\"route:2ac3ab24f44fe9783816fcb48edc6f55efe36d2f\",\"route:45b9b3d4e24816985b3dcf063e0e4d4584904187\",\"route:5ed7df579e4f56346088e0f112252f03389400cb\",\"route:029b3d5a5779453715563dae86256083d348f6cd\",\"route:853143e218835b08f1e6a33d7637cfbcfcb8d74d\",\"route:c797995c677853f68f2b9ff5272a26e0ea2d17a2\",\"route:7a14de897267926b4f6d210ac0efeab50a45b1c8\",\"route:723f0d2a79e020d52615267872e85fb9f0b073a7\",\"route:497aa70af4412ab568459992ab6614540943a444\",\"route:8e338f3aab176113383909e0979c9d909344eb52\",\"route:7ae5a4516314cd35faddf0295e37e122654e4f01\",\"route:52fccee8e84ff424e271ef19e3850bd20a7ddcbf\",\"route:3784bdd7f4857e4c731fae1e01f73d957cdaf004\",\"route:3bb89984e4ad86ae3dcb82371bef88e6305907d1\",\"route:cd8ac1b2677fd442bb8d47576cc2c8b7788ed3ca\",\"route:818a48d10d11d9b82a418fb83f460a5f2c430e2d\",\"route:8cd8872eb8b7d6c6fa4c683c8f8a2b5e09b909e5\",\"route:726b48585687ab57d4ef5a8d39503a7c10771463\",\"route:eca8e8640149a3cae76dff58f5b168368bcfa22f\",\"route:a7595b175ea3aeb0aef21b63bf8c12b45652fe0d\",\"route:da643b95ad00f63755dd805e44b051c750e0d005\",\"route:27399beb60877741574224f3268301b9d7eca3c8\",\"route:2c4dbfa0364e7467910ee59c5b88291a133a3866\",\"route:963b3b256659e8bce4e0aad8e6fc2e835063b425\",\"route:9739ef2e3ad5804ccc52fed23e326d342153920a\",\"route:cc0a4523a3740d9d0a5d51b185ddde0c22b3a49f\",\"route:c4deb8e15786ffa28c4a3fd04221bb80cc2e596e\",\"route:07462eec8108afee1a0636c795b2dc60303c38ad\",\"route:f9545875eca0a6d481995671f0d1aca83ff75ee7\",\"route:ed7965ddd1db67136a21b024ecd43343d2d8602a\",\"route:7d6e11291fc748396c13f8f615668a82a81aa38d\",\"route:f3889b3667de54b14eb89e7bafe31eae56a36e19\",\"route:b9cb9187c2d74f4d242966a2bc0e600973eb6d6b\",\"route:0a47eb3c044767d485522f6463111c5b9a2df5fb\",\"route:331396de798dfe61c6b0b0ee757ab2cf894c9643\",\"route:a6fed02dc2dc7bcba4b62a93ea1a7d3325f26943\",\"route:45daf69ec7a40485a71b92d2e363a78d39b8c26a\",\"route:c7f725ba47274866a067a7925fbd5a2e403a7f0f\",\"route:aa17e630a781fe8247657c4ac218e7c55a3990d6\",\"route:3a7676d412c8f24c8f8a2a0c305f106fd66205e8\",\"route:908edc68bc6e66a5062c215db793765d497889dd\",\"route:5d0a2e9b0d0482a914eb116fb1e9348978d133f7\",\"route:2e6f4965399e153a764cfe9e238b1ab70b80e0b6\",\"route:1fed19d78ec0bf49a025c410a2152d222e48e82d\",\"route:735e66c301d2fc0d46ec268b4610dff4a81145ab\",\"route:837fee41ebb5102be7249504625d5be75efce1a5\",\"route:a1b538672fe19225940af6c0b63b5604f92513ea\",\"route:02e383189f5b3244e706a8039893eeb1a6113d83\",\"route:11d51976543db324f7ac1a43eac83dd78acc0078\"],\"role_menus\":\"1,11,13,18,22,15,33,14,12,37,20,3,4,5,6,28,29,32\"}','2026-07-28 06:29:02','2026-07-28 06:29:02'),(16,'admin',1,'admin/auth/roles','GET','127.0.0.1','[]','2026-07-28 06:29:03','2026-07-28 06:29:03'),(17,'admin',1,'admin/auth/users','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2026-07-28 06:29:05','2026-07-28 06:29:05'),(18,'admin',1,'admin/auth/users/2/edit','GET','127.0.0.1','{\"_dialog_form_\":\"1\"}','2026-07-28 06:29:12','2026-07-28 06:29:12'),(19,'admin',1,'admin/auth/users/2','PUT','127.0.0.1','{\"username\":\"alan\",\"name\":\"Alan\",\"avatar\":null,\"_file_\":null,\"password\":null,\"password_confirmation\":null,\"roles\":[\"2\",null],\"_method\":\"PUT\",\"_previous_\":\"https:\\/\\/admin.hk.localdev.com\\/admin\\/auth\\/users\",\"_token\":\"VFwEsn6NOyDHoBIgve3jGV6Qg7hzuI6QHH7IyBfN\"}','2026-07-28 06:29:16','2026-07-28 06:29:16'),(20,'admin',1,'admin/auth/users','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2026-07-28 06:29:16','2026-07-28 06:29:16'),(21,'admin',1,'admin/auth/users/2/edit','GET','127.0.0.1','{\"_dialog_form_\":\"1\"}','2026-07-28 06:29:19','2026-07-28 06:29:19'),(22,'admin',1,'admin/auth/users/2','PUT','127.0.0.1','{\"username\":\"alan\",\"name\":\"Alan\",\"avatar\":null,\"_file_\":null,\"password\":\"adm******\",\"password_confirmation\":\"adm******\",\"roles\":[\"2\",null],\"_method\":\"PUT\",\"_previous_\":\"https:\\/\\/admin.hk.localdev.com\\/admin\\/auth\\/users\",\"_token\":\"VFwEsn6NOyDHoBIgve3jGV6Qg7hzuI6QHH7IyBfN\"}','2026-07-28 06:29:31','2026-07-28 06:29:31'),(23,'admin',1,'admin/auth/users','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2026-07-28 06:29:31','2026-07-28 06:29:31'),(24,'admin',1,'admin/auth/logout','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2026-07-28 06:29:38','2026-07-28 06:29:38'),(25,'admin',0,'admin/auth/login','GET','127.0.0.1','[]','2026-07-28 06:29:38','2026-07-28 06:29:38'),(26,'admin',0,'admin/auth/login','POST','127.0.0.1','{\"_token\":\"3Rv11O3naAPH0pS6aR083zb1FYmPyHdUUnw3oz7D\",\"username\":\"alan\",\"password\":\"adm******\"}','2026-07-28 06:29:45','2026-07-28 06:29:45'),(27,'admin',2,'admin','GET','127.0.0.1','[]','2026-07-28 06:29:47','2026-07-28 06:29:47'),(28,'admin',2,'admin/index-banner','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2026-07-28 06:29:49','2026-07-28 06:29:49'),(29,'admin',2,'admin/chip-rfq','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2026-07-28 06:29:49','2026-07-28 06:29:49'),(30,'admin',2,'admin/task','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2026-07-28 06:29:50','2026-07-28 06:29:50'),(31,'admin',2,'admin/chip-product','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2026-07-28 06:29:53','2026-07-28 06:29:53'),(32,'admin',2,'admin/chip-manufacturer','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2026-07-28 06:29:53','2026-07-28 06:29:53'),(33,'admin',2,'admin/notifications','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2026-07-28 06:29:54','2026-07-28 06:29:54'),(34,'admin',2,'admin/auth/users','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2026-07-28 06:29:56','2026-07-28 06:29:56'),(35,'admin',2,'admin/auth/roles','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2026-07-28 06:29:57','2026-07-28 06:29:57'),(36,'admin',2,'admin/auth/menu','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2026-07-28 06:29:58','2026-07-28 06:29:58'),(37,'admin',1,'admin/auth/users/2/edit','GET','127.0.0.1','{\"_dialog_form_\":\"1\"}','2026-07-28 06:30:07','2026-07-28 06:30:07'),(38,'admin',1,'admin/auth/roles','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2026-07-28 06:30:12','2026-07-28 06:30:12'),(39,'admin',1,'admin/auth/roles/2/edit','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2026-07-28 06:30:14','2026-07-28 06:30:14'),(40,'admin',2,'admin/chip-product','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2026-07-28 06:30:50','2026-07-28 06:30:50'),(41,'admin',2,'admin/chip-product','GET','127.0.0.1','[]','2026-07-28 06:30:50','2026-07-28 06:30:50'),(42,'admin',2,'admin/chip-product','GET','127.0.0.1','[]','2026-07-28 06:30:54','2026-07-28 06:30:54'),(43,'admin',2,'admin/chip-product','GET','127.0.0.1','[]','2026-07-28 06:31:11','2026-07-28 06:31:11'),(44,'admin',2,'admin/chip-product','GET','127.0.0.1','[]','2026-07-28 06:31:13','2026-07-28 06:31:13'),(45,'admin',1,'admin/auth/users','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2026-07-28 06:31:33','2026-07-28 06:31:33'),(46,'admin',1,'admin/auth/users','GET','127.0.0.1','[]','2026-07-28 06:51:24','2026-07-28 06:51:24'),(47,'admin',1,'admin/auth/users','GET','127.0.0.1','[]','2026-07-28 06:51:27','2026-07-28 06:51:27'),(48,'admin',1,'admin/auth/users','GET','127.0.0.1','[]','2026-07-28 06:51:46','2026-07-28 06:51:46'),(49,'admin',2,'admin','GET','127.0.0.1','[]','2026-07-28 06:51:56','2026-07-28 06:51:56'),(50,'admin',2,'admin/notifications','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2026-07-28 06:51:58','2026-07-28 06:51:58'),(51,'admin',2,'admin/auth/operation-logs','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2026-07-28 06:52:00','2026-07-28 06:52:00'),(52,'admin',2,'admin/notifications','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2026-07-28 06:52:02','2026-07-28 06:52:02'),(53,'admin',2,'admin','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2026-07-28 06:52:03','2026-07-28 06:52:03'),(54,'admin',2,'admin/notifications','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2026-07-28 06:52:05','2026-07-28 06:52:05'),(55,'admin',2,'admin','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2026-07-28 06:52:33','2026-07-28 06:52:33'),(56,'admin',2,'admin/notifications','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2026-07-28 06:52:35','2026-07-28 06:52:35'),(57,'admin',2,'admin/auth/operation-logs','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2026-07-28 06:52:35','2026-07-28 06:52:35'),(58,'admin',2,'admin/notifications','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2026-07-28 06:52:36','2026-07-28 06:52:36'),(59,'admin',2,'admin','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2026-07-28 06:52:37','2026-07-28 06:52:37'),(60,'admin',2,'admin','GET','127.0.0.1','[]','2026-07-28 06:55:59','2026-07-28 06:55:59'),(61,'admin',2,'admin/notifications','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2026-07-28 06:56:01','2026-07-28 06:56:01'),(62,'admin',2,'admin/auth/menu','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2026-07-28 06:56:08','2026-07-28 06:56:08'),(63,'admin',2,'admin/auth/menu','GET','127.0.0.1','[]','2026-07-28 06:56:11','2026-07-28 06:56:11'),(64,'admin',2,'admin/auth/menu','GET','127.0.0.1','[]','2026-07-28 06:59:19','2026-07-28 06:59:19'),(65,'admin',2,'admin/auth/menu','GET','127.0.0.1','[]','2026-07-28 06:59:20','2026-07-28 06:59:20');
/*!40000 ALTER TABLE `admin_operation_log` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `admin_permission_menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_permission_menu` (
  `permission_id` bigint NOT NULL,
  `menu_id` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  UNIQUE KEY `admin_permission_menu_permission_id_menu_id_unique` (`permission_id`,`menu_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `admin_permission_menu` WRITE;
/*!40000 ALTER TABLE `admin_permission_menu` DISABLE KEYS */;
INSERT INTO `admin_permission_menu` VALUES (2,3,'2026-07-28 06:21:42','2026-07-28 06:21:42'),(3,4,'2026-07-28 06:21:42','2026-07-28 06:21:42'),(4,5,'2026-07-28 06:21:42','2026-07-28 06:21:42'),(5,6,'2026-07-28 06:21:42','2026-07-28 06:21:42'),(6,7,'2026-07-28 06:21:42','2026-07-28 06:21:42'),(7,33,'2026-07-28 06:21:42','2026-07-28 06:21:42'),(9,35,'2026-07-28 06:21:42','2026-07-28 06:21:42'),(10,36,'2026-07-28 06:21:42','2026-07-28 06:21:42'),(12,13,'2026-07-28 06:21:42','2026-07-28 06:21:42'),(13,18,'2026-07-28 06:21:42','2026-07-28 06:21:42'),(14,22,'2026-07-28 06:21:42','2026-07-28 06:21:42'),(15,28,'2026-07-28 06:21:42','2026-07-28 06:21:42'),(16,29,'2026-07-28 06:21:42','2026-07-28 06:21:42'),(17,32,'2026-07-28 06:21:42','2026-07-28 06:21:42'),(19,14,'2026-07-28 06:21:42','2026-07-28 06:21:42'),(20,12,'2026-07-28 06:21:42','2026-07-28 06:21:42'),(21,20,'2026-07-28 06:21:42','2026-07-28 06:21:42'),(22,1,'2026-07-28 06:21:42','2026-07-28 06:21:42'),(23,37,'2026-07-28 06:21:42','2026-07-28 06:21:42');
/*!40000 ALTER TABLE `admin_permission_menu` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `admin_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `http_method` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `http_path` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `order` int NOT NULL DEFAULT '0',
  `parent_id` bigint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `admin_permissions_slug_unique` (`slug`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=127 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `admin_permissions` WRITE;
/*!40000 ALTER TABLE `admin_permissions` DISABLE KEYS */;
INSERT INTO `admin_permissions` VALUES (1,'group-admin','group-admin','','',1,0,'2026-07-28 06:21:42','2026-07-28 06:21:42'),(2,'auth-users','auth-users','','/auth/users*',0,1,'2026-07-28 06:21:42','2026-07-28 06:21:42'),(3,'auth-roles','auth-roles','','/auth/roles*',0,1,'2026-07-28 06:21:42','2026-07-28 06:21:42'),(4,'auth-permissions','auth-permissions','','/auth/permissions*',0,1,'2026-07-28 06:21:42','2026-07-28 06:21:42'),(5,'auth-menu','auth-menu','','/auth/menu*',0,1,'2026-07-28 06:21:42','2026-07-28 06:21:42'),(6,'auth-extensions','auth-extensions','','/auth/extensions*',0,1,'2026-07-28 06:21:42','2026-07-28 06:21:42'),(7,'auth-operation-logs','auth-operation-logs','','/auth/operation-logs*',0,1,'2026-07-28 06:21:42','2026-07-28 06:21:42'),(8,'group-help-center','group-help-center','','',2,0,'2026-07-28 06:21:42','2026-07-28 06:21:42'),(9,'help-categories','help-categories','','/help-categories*',0,8,'2026-07-28 06:21:42','2026-07-28 06:21:42'),(10,'helps','helps','','/helps*',0,8,'2026-07-28 06:21:42','2026-07-28 06:21:42'),(11,'group-web-admin','group-web-admin','','',3,0,'2026-07-28 06:21:42','2026-07-28 06:21:42'),(12,'index-banner','index-banner','','/index-banner*',0,11,'2026-07-28 06:21:42','2026-07-28 06:21:42'),(13,'chip-rfq','chip-rfq','','/chip-rfq*',0,11,'2026-07-28 06:21:42','2026-07-28 06:21:42'),(14,'task','task','','/task*',0,11,'2026-07-28 06:21:42','2026-07-28 06:21:42'),(15,'chip-manufacturer-relation','chip-manufacturer-relation','','/chip-manufacturer-relation*',0,11,'2026-07-28 06:21:42','2026-07-28 06:21:42'),(16,'chip-product-stock','chip-product-stock','','/chip-product-stock*',0,11,'2026-07-28 06:21:42','2026-07-28 06:21:42'),(17,'chip-station','chip-station','','/chip-station*',0,11,'2026-07-28 06:21:42','2026-07-28 06:21:42'),(18,'group-basic-data','group-basic-data','','',4,0,'2026-07-28 06:21:42','2026-07-28 06:21:42'),(19,'chip-product','chip-product','','/chip-product*',0,18,'2026-07-28 06:21:42','2026-07-28 06:21:42'),(20,'chip-manufacturer','chip-manufacturer','','/chip-manufacturer*',0,18,'2026-07-28 06:21:42','2026-07-28 06:21:42'),(21,'chip-category','chip-category','','/chip-category*',0,18,'2026-07-28 06:21:42','2026-07-28 06:21:42'),(22,'home','home','','/',0,0,'2026-07-28 06:21:42','2026-07-28 06:21:42'),(23,'notifications','notifications','','/notifications*',0,0,'2026-07-28 06:21:42','2026-07-28 06:21:42'),(24,'index','chip-rfq.index','GET','/chip-rfq',5,0,'2026-07-28 06:29:02','2026-07-28 07:00:42'),(25,'show','chip-rfq.show','GET','/chip-rfq/*',6,0,'2026-07-28 06:29:02','2026-07-28 07:00:42'),(26,'create','chip-rfq.create','GET','/chip-rfq/create',7,0,'2026-07-28 06:29:02','2026-07-28 07:00:42'),(27,'store','chip-rfq.store','POST','/chip-rfq',8,0,'2026-07-28 06:29:02','2026-07-28 07:00:42'),(28,'edit','chip-rfq.edit','GET','/chip-rfq/*/edit',9,0,'2026-07-28 06:29:02','2026-07-28 07:00:42'),(29,'update','chip-rfq.update','PATCH,PUT','/chip-rfq/*',10,0,'2026-07-28 06:29:02','2026-07-28 07:00:42'),(30,'destroy','chip-rfq.destroy','DELETE','/chip-rfq/*',11,0,'2026-07-28 06:29:02','2026-07-28 07:00:42'),(31,'index','chip-product-stock.index','GET','/chip-product-stock',12,0,'2026-07-28 06:29:02','2026-07-28 07:00:42'),(32,'show','chip-product-stock.show','GET','/chip-product-stock/*',13,0,'2026-07-28 06:29:02','2026-07-28 07:00:42'),(33,'create','chip-product-stock.create','GET','/chip-product-stock/create',14,0,'2026-07-28 06:29:02','2026-07-28 07:00:42'),(34,'store','chip-product-stock.store','POST','/chip-product-stock',15,0,'2026-07-28 06:29:02','2026-07-28 07:00:42'),(35,'edit','chip-product-stock.edit','GET','/chip-product-stock/*/edit',16,0,'2026-07-28 06:29:02','2026-07-28 07:00:42'),(36,'update','chip-product-stock.update','PATCH,PUT','/chip-product-stock/*',17,0,'2026-07-28 06:29:02','2026-07-28 07:00:42'),(37,'destroy','chip-product-stock.destroy','DELETE','/chip-product-stock/*',18,0,'2026-07-28 06:29:02','2026-07-28 07:00:42'),(38,'index','chip-manufacturer-relation.index','GET','/chip-manufacturer-relation',19,0,'2026-07-28 06:29:02','2026-07-28 07:00:42'),(39,'show','chip-manufacturer-relation.show','GET','/chip-manufacturer-relation/*',20,0,'2026-07-28 06:29:02','2026-07-28 07:00:42'),(40,'create','chip-manufacturer-relation.create','GET','/chip-manufacturer-relation/create',21,0,'2026-07-28 06:29:02','2026-07-28 07:00:42'),(41,'store','chip-manufacturer-relation.store','POST','/chip-manufacturer-relation',22,0,'2026-07-28 06:29:02','2026-07-28 07:00:42'),(42,'edit','chip-manufacturer-relation.edit','GET','/chip-manufacturer-relation/*/edit',23,0,'2026-07-28 06:29:02','2026-07-28 07:00:42'),(43,'update','chip-manufacturer-relation.update','PATCH,PUT','/chip-manufacturer-relation/*',24,0,'2026-07-28 06:29:02','2026-07-28 07:00:42'),(44,'destroy','chip-manufacturer-relation.destroy','DELETE','/chip-manufacturer-relation/*',25,0,'2026-07-28 06:29:02','2026-07-28 07:00:42'),(45,'index','task.index','GET','/task',26,0,'2026-07-28 06:29:02','2026-07-28 07:00:42'),(46,'show','task.show','GET','/task/*',27,0,'2026-07-28 06:29:02','2026-07-28 07:00:42'),(47,'create','task.create','GET','/task/create',28,0,'2026-07-28 06:29:02','2026-07-28 07:00:42'),(48,'store','task.store','POST','/task',29,0,'2026-07-28 06:29:02','2026-07-28 07:00:42'),(49,'edit','task.edit','GET','/task/*/edit',30,0,'2026-07-28 06:29:02','2026-07-28 07:00:42'),(50,'update','task.update','PATCH,PUT','/task/*',31,0,'2026-07-28 06:29:02','2026-07-28 07:00:42'),(51,'destroy','task.destroy','DELETE','/task/*',32,0,'2026-07-28 06:29:02','2026-07-28 07:00:42'),(52,'index','chip-station.index','GET','/chip-station',33,0,'2026-07-28 06:29:02','2026-07-28 07:00:42'),(53,'show','chip-station.show','GET','/chip-station/*',34,0,'2026-07-28 06:29:02','2026-07-28 07:00:42'),(54,'create','chip-station.create','GET','/chip-station/create',35,0,'2026-07-28 06:29:02','2026-07-28 07:00:42'),(55,'store','chip-station.store','POST','/chip-station',36,0,'2026-07-28 06:29:02','2026-07-28 07:00:42'),(56,'edit','chip-station.edit','GET','/chip-station/*/edit',37,0,'2026-07-28 06:29:02','2026-07-28 07:00:42'),(57,'update','chip-station.update','PATCH,PUT','/chip-station/*',38,0,'2026-07-28 06:29:02','2026-07-28 07:00:42'),(58,'destroy','chip-station.destroy','DELETE','/chip-station/*',39,0,'2026-07-28 06:29:02','2026-07-28 07:00:42'),(59,'index','index-banner.index','GET','/index-banner',40,0,'2026-07-28 06:29:02','2026-07-28 07:00:42'),(60,'show','index-banner.show','GET','/index-banner/*',41,0,'2026-07-28 06:29:02','2026-07-28 07:00:42'),(61,'create','index-banner.create','GET','/index-banner/create',42,0,'2026-07-28 06:29:02','2026-07-28 07:00:42'),(62,'store','index-banner.store','POST','/index-banner',43,0,'2026-07-28 06:29:02','2026-07-28 07:00:42'),(63,'edit','index-banner.edit','GET','/index-banner/*/edit',44,0,'2026-07-28 06:29:02','2026-07-28 07:00:42'),(64,'update','index-banner.update','PATCH,PUT','/index-banner/*',45,0,'2026-07-28 06:29:03','2026-07-28 07:00:42'),(65,'destroy','index-banner.destroy','DELETE','/index-banner/*',46,0,'2026-07-28 06:29:03','2026-07-28 07:00:42'),(66,'index','chip-product.index','GET','/chip-product',47,0,'2026-07-28 06:29:03','2026-07-28 07:00:42'),(67,'show','chip-product.show','GET','/chip-product/*',48,0,'2026-07-28 06:29:03','2026-07-28 07:00:42'),(68,'create','chip-product.create','GET','/chip-product/create',49,0,'2026-07-28 06:29:03','2026-07-28 07:00:42'),(69,'store','chip-product.store','POST','/chip-product',50,0,'2026-07-28 06:29:03','2026-07-28 07:00:42'),(70,'edit','chip-product.edit','GET','/chip-product/*/edit',51,0,'2026-07-28 06:29:03','2026-07-28 07:00:42'),(71,'update','chip-product.update','PATCH,PUT','/chip-product/*',52,0,'2026-07-28 06:29:03','2026-07-28 07:00:42'),(72,'destroy','chip-product.destroy','DELETE','/chip-product/*',53,0,'2026-07-28 06:29:03','2026-07-28 07:00:42'),(73,'index','chip-category.index','GET','/chip-category',54,0,'2026-07-28 06:29:03','2026-07-28 07:00:42'),(74,'show','chip-category.show','GET','/chip-category/*',55,0,'2026-07-28 06:29:03','2026-07-28 07:00:42'),(75,'create','chip-category.create','GET','/chip-category/create',56,0,'2026-07-28 06:29:03','2026-07-28 07:00:42'),(76,'store','chip-category.store','POST','/chip-category',57,0,'2026-07-28 06:29:03','2026-07-28 07:00:42'),(77,'edit','chip-category.edit','GET','/chip-category/*/edit',58,0,'2026-07-28 06:29:03','2026-07-28 07:00:42'),(78,'update','chip-category.update','PATCH,PUT','/chip-category/*',59,0,'2026-07-28 06:29:03','2026-07-28 07:00:42'),(79,'destroy','chip-category.destroy','DELETE','/chip-category/*',60,0,'2026-07-28 06:29:03','2026-07-28 07:00:42'),(80,'index','chip-manufacturer.index','GET','/chip-manufacturer',61,0,'2026-07-28 06:29:03','2026-07-28 07:00:42'),(81,'show','chip-manufacturer.show','GET','/chip-manufacturer/*',62,0,'2026-07-28 06:29:03','2026-07-28 07:00:42'),(82,'create','chip-manufacturer.create','GET','/chip-manufacturer/create',63,0,'2026-07-28 06:29:03','2026-07-28 07:00:42'),(83,'store','chip-manufacturer.store','POST','/chip-manufacturer',64,0,'2026-07-28 06:29:03','2026-07-28 07:00:42'),(84,'edit','chip-manufacturer.edit','GET','/chip-manufacturer/*/edit',65,0,'2026-07-28 06:29:03','2026-07-28 07:00:42'),(85,'update','chip-manufacturer.update','PATCH,PUT','/chip-manufacturer/*',66,0,'2026-07-28 06:29:03','2026-07-28 07:00:42'),(86,'destroy','chip-manufacturer.destroy','DELETE','/chip-manufacturer/*',67,0,'2026-07-28 06:29:03','2026-07-28 07:00:42'),(87,'operation-log.index','dcat-admin.operation-log.index','GET','/auth/operation-logs',68,0,'2026-07-28 06:29:03','2026-07-28 07:00:42'),(88,'operation-log.destroy','dcat-admin.operation-log.destroy','DELETE','/auth/operation-logs/*',69,0,'2026-07-28 06:29:03','2026-07-28 07:00:42'),(89,'notifications.index','notifications.index','GET','/notifications',70,0,'2026-07-28 06:29:03','2026-07-28 07:00:42'),(90,'notifications.show','notifications.show','GET','/notifications/*',71,0,'2026-07-28 06:29:03','2026-07-28 07:00:42'),(91,'notifications.create','notifications.create','GET','/notifications/create',72,0,'2026-07-28 06:29:03','2026-07-28 07:00:42'),(92,'notifications.store','notifications.store','POST','/notifications',73,0,'2026-07-28 06:29:03','2026-07-28 07:00:42'),(93,'notifications.edit','notifications.edit','GET','/notifications/*/edit',74,0,'2026-07-28 06:29:03','2026-07-28 07:00:42'),(94,'notifications.update','notifications.update','PATCH,PUT','/notifications/*',75,0,'2026-07-28 06:29:03','2026-07-28 07:00:42'),(95,'notifications.destroy','notifications.destroy','DELETE','/notifications/*',76,0,'2026-07-28 06:29:03','2026-07-28 07:00:42'),(96,'permissions.index','permissions.index','GET','/auth/permissions',77,0,'2026-07-28 06:29:03','2026-07-28 07:00:42'),(97,'permissions.show','permissions.show','GET','/auth/permissions/*',78,0,'2026-07-28 06:29:03','2026-07-28 07:00:42'),(98,'permissions.create','permissions.create','GET','/auth/permissions/create',79,0,'2026-07-28 06:29:03','2026-07-28 07:00:42'),(99,'permissions.store','permissions.store','POST','/auth/permissions',80,0,'2026-07-28 06:29:03','2026-07-28 07:00:42'),(100,'permissions.edit','permissions.edit','GET','/auth/permissions/*/edit',81,0,'2026-07-28 06:29:03','2026-07-28 07:00:42'),(101,'permissions.update','permissions.update','PATCH,PUT','/auth/permissions/*',82,0,'2026-07-28 06:29:03','2026-07-28 07:00:42'),(102,'permissions.destroy','permissions.destroy','DELETE','/auth/permissions/*',83,0,'2026-07-28 06:29:03','2026-07-28 07:00:42'),(103,'index','users.index','GET','/auth/users',84,0,'2026-07-28 06:29:03','2026-07-28 07:00:42'),(104,'show','users.show','GET','/auth/users/*',85,0,'2026-07-28 06:29:03','2026-07-28 07:00:42'),(105,'create','users.create','GET','/auth/users/create',86,0,'2026-07-28 06:29:03','2026-07-28 07:00:42'),(106,'store','users.store','POST','/auth/users',87,0,'2026-07-28 06:29:03','2026-07-28 07:00:42'),(107,'edit','users.edit','GET','/auth/users/*/edit',88,0,'2026-07-28 06:29:03','2026-07-28 07:00:42'),(108,'update','users.update','PATCH,PUT','/auth/users/*',89,0,'2026-07-28 06:29:03','2026-07-28 07:00:42'),(109,'destroy','users.destroy','DELETE','/auth/users/*',90,0,'2026-07-28 06:29:03','2026-07-28 07:00:42'),(110,'menu.index','menu.index','GET','/auth/menu',91,0,'2026-07-28 06:29:03','2026-07-28 07:00:42'),(111,'menu.store','menu.store','POST','/auth/menu',92,0,'2026-07-28 06:29:03','2026-07-28 07:00:42'),(112,'menu.edit','menu.edit','GET','/auth/menu/*/edit',93,0,'2026-07-28 06:29:03','2026-07-28 07:00:42'),(113,'menu.update','menu.update','PATCH,PUT','/auth/menu/*',94,0,'2026-07-28 06:29:03','2026-07-28 07:00:42'),(114,'menu.destroy','menu.destroy','DELETE','/auth/menu/*',95,0,'2026-07-28 06:29:03','2026-07-28 07:00:42'),(115,'roles.index','roles.index','GET','/auth/roles',96,0,'2026-07-28 06:29:03','2026-07-28 07:00:42'),(116,'roles.show','roles.show','GET','/auth/roles/*',97,0,'2026-07-28 06:29:03','2026-07-28 07:00:42'),(117,'roles.create','roles.create','GET','/auth/roles/create',98,0,'2026-07-28 06:29:03','2026-07-28 07:00:42'),(118,'roles.store','roles.store','POST','/auth/roles',99,0,'2026-07-28 06:29:03','2026-07-28 07:00:42'),(119,'roles.edit','roles.edit','GET','/auth/roles/*/edit',100,0,'2026-07-28 06:29:03','2026-07-28 07:00:42'),(120,'roles.update','roles.update','PATCH,PUT','/auth/roles/*',101,0,'2026-07-28 06:29:03','2026-07-28 07:00:42'),(121,'roles.destroy','roles.destroy','DELETE','/auth/roles/*',102,0,'2026-07-28 06:29:03','2026-07-28 07:00:42'),(122,'log-viewer-file','log-viewer.log-viewer-file','GET','/auth/system-log-viewer/*',103,0,'2026-07-28 06:29:03','2026-07-28 07:00:42'),(123,'log-viewer','log-viewer','GET','/auth/system-log-viewer',104,0,'2026-07-28 06:29:03','2026-07-28 07:00:42'),(124,'log-viewer.download','log-viewer.download','GET','/auth/system-log-viewer/download',105,0,'2026-07-28 06:29:03','2026-07-28 07:00:42'),(125,'log-viewer.delete','log-viewer.delete','POST','/auth/system-log-viewer/delete',106,0,'2026-07-28 06:29:03','2026-07-28 07:00:42'),(126,'log-viewer.clear','log-viewer.clear','POST','/auth/system-log-viewer/clear',107,0,'2026-07-28 06:29:03','2026-07-28 07:00:42');
/*!40000 ALTER TABLE `admin_permissions` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `admin_role_menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_role_menu` (
  `role_id` bigint NOT NULL,
  `menu_id` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  UNIQUE KEY `admin_role_menu_role_id_menu_id_unique` (`role_id`,`menu_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `admin_role_menu` WRITE;
/*!40000 ALTER TABLE `admin_role_menu` DISABLE KEYS */;
INSERT INTO `admin_role_menu` VALUES (2,1,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,3,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,4,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,5,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,6,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,11,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,12,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,13,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,14,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,15,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,18,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,20,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,22,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,28,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,29,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,32,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,33,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,37,'2026-07-28 06:29:03','2026-07-28 06:29:03');
/*!40000 ALTER TABLE `admin_role_menu` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `admin_role_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_role_permissions` (
  `role_id` bigint NOT NULL,
  `permission_id` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  UNIQUE KEY `admin_role_permissions_role_id_permission_id_unique` (`role_id`,`permission_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `admin_role_permissions` WRITE;
/*!40000 ALTER TABLE `admin_role_permissions` DISABLE KEYS */;
INSERT INTO `admin_role_permissions` VALUES (2,24,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,25,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,26,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,27,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,28,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,29,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,30,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,31,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,32,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,33,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,34,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,35,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,36,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,37,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,38,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,39,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,40,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,41,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,42,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,43,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,44,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,45,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,46,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,47,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,48,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,49,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,50,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,51,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,52,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,53,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,54,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,55,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,56,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,57,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,58,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,59,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,60,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,61,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,62,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,63,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,64,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,65,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,66,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,67,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,68,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,69,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,70,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,71,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,72,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,73,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,74,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,75,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,76,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,77,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,78,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,79,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,80,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,81,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,82,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,83,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,84,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,85,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,86,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,87,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,88,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,89,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,90,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,91,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,92,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,93,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,94,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,95,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,96,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,97,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,98,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,99,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,100,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,101,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,102,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,103,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,104,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,105,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,106,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,107,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,108,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,109,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,110,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,111,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,112,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,113,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,114,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,115,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,116,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,117,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,118,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,119,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,120,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,121,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,122,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,123,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,124,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,125,'2026-07-28 06:29:03','2026-07-28 06:29:03'),(2,126,'2026-07-28 06:29:03','2026-07-28 06:29:03');
/*!40000 ALTER TABLE `admin_role_permissions` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `admin_role_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_role_users` (
  `role_id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  UNIQUE KEY `admin_role_users_role_id_user_id_unique` (`role_id`,`user_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `admin_role_users` WRITE;
/*!40000 ALTER TABLE `admin_role_users` DISABLE KEYS */;
INSERT INTO `admin_role_users` VALUES (1,1,'2026-07-28 06:21:42','2026-07-28 06:21:42'),(2,2,'2026-07-28 06:29:16','2026-07-28 06:29:16');
/*!40000 ALTER TABLE `admin_role_users` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `admin_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `admin_roles_slug_unique` (`slug`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `admin_roles` WRITE;
/*!40000 ALTER TABLE `admin_roles` DISABLE KEYS */;
INSERT INTO `admin_roles` VALUES (1,'Administrator','administrator','2026-07-28 06:21:42','2026-07-28 06:21:42'),(2,'普通管理员','user-admin','2026-07-28 06:29:02','2026-07-28 06:29:02');
/*!40000 ALTER TABLE `admin_roles` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `admin_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `group_name` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admin_settings_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `admin_settings` WRITE;
/*!40000 ALTER TABLE `admin_settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `admin_settings` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

