# Host: localhost  (Version: 5.5.53)
# Date: 2019-01-16 16:16:07
# Generator: MySQL-Front 5.3  (Build 4.234)

/*!40101 SET NAMES utf8 */;

#
# Structure for table "device"
#

DROP TABLE IF EXISTS `device`;
CREATE TABLE `device` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `device_name` varchar(255) DEFAULT NULL COMMENT '设备名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 COMMENT='设备';

#
# Data for table "device"
#

INSERT INTO `device` VALUES (3,'134DH'),(4,'WGB'),(5,'K40THS'),(6,'72THS'),(7,'BN2640'),(8,'114XH'),(9,'KK36S'),(10,'J36TH'),(11,'J80TY'),(12,'K20TH'),(13,'DB'),(14,'117DH'),(15,'110DH'),(16,'BW72TH'),(17,'DB20'),(18,'K72TY'),(19,'36THS'),(20,'J72TY'),(21,'SL'),(22,'133XH'),(23,'K30TH');
