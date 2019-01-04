# Host: localhost  (Version: 5.5.53)
# Date: 2018-12-28 17:14:07
# Generator: MySQL-Front 5.3  (Build 4.234)

/*!40101 SET NAMES utf8 */;

#
# Structure for table "auth_group"
#

DROP TABLE IF EXISTS `auth_group`;
CREATE TABLE `auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `rules` varchar(200) NOT NULL DEFAULT '',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

#
# Data for table "auth_group"
#

INSERT INTO `auth_group` VALUES (1,'总经理',1,'16,17,18,19,22,23,24,28,29','2017-03-27 17:22:47'),(2,'销售',1,'8,10,11,12,13,14,31,32,33','2017-03-27 17:22:50'),(3,'财务',1,'8,10,11,19,20','2017-03-27 17:22:52'),(4,'仓管',1,'8,10,11,15,16,17,18','2017-03-27 17:22:54'),(5,'人事',1,'8,10,11,22,23,24','2017-03-27 17:22:57'),(10,'测试',1,'28,29','2017-04-27 18:53:36');

#
# Structure for table "auth_group_access"
#

DROP TABLE IF EXISTS `auth_group_access`;
CREATE TABLE `auth_group_access` (
  `uid` mediumint(8) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Data for table "auth_group_access"
#

/*!40000 ALTER TABLE `auth_group_access` DISABLE KEYS */;
INSERT INTO `auth_group_access` VALUES (1,1),(1,10);
/*!40000 ALTER TABLE `auth_group_access` ENABLE KEYS */;

#
# Structure for table "auth_rule"
#

DROP TABLE IF EXISTS `auth_rule`;
CREATE TABLE `auth_rule` (
  `id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL DEFAULT '',
  `title` varchar(20) NOT NULL DEFAULT '',
  `iconCls` varchar(20) NOT NULL DEFAULT '',
  `pid` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0',
  `type` tinyint(1) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `condition` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `name` (`name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;

#
# Data for table "auth_rule"
#

INSERT INTO `auth_rule` VALUES (3,'','生产记录管理','icon-system',0,1,1,''),(5,'','人事管理','icon-system',0,1,1,''),(7,'','系统管理','icon-system',0,1,1,''),(16,'record/index','生产记录','icon-book',3,1,1,''),(17,'Outlib','出库记录','icon-book',3,1,1,''),(18,'Alarm','库存警报','icon-book',3,1,1,''),(19,'Procure','采购记录','icon-book',3,1,1,''),(22,'User','登录帐号','icon-book',5,1,1,''),(23,'Staff','员工档案','icon-book',5,1,1,''),(24,'Group','职位部门','icon-book',5,1,1,''),(28,'Auth','权限管理','icon-book',7,1,1,''),(29,'Log','操作日志','icon-book',7,1,1,'');

#
# Structure for table "sequence"
#

DROP TABLE IF EXISTS `sequence`;
CREATE TABLE `sequence` (
  `name` varchar(32) NOT NULL,
  `value` int(6) DEFAULT NULL,
  `next` int(6) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Data for table "sequence"
#

INSERT INTO `sequence` VALUES ('trans_no',11,1);

#
# Structure for table "staff"
#

DROP TABLE IF EXISTS `staff`;
CREATE TABLE `staff` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '自动编号',
  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '关联登录帐号模块的ID',
  `number` char(10) NOT NULL DEFAULT '' COMMENT '员工编号',
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '员工姓名',
  `gender` char(1) NOT NULL COMMENT '性别',
  `id_card` char(18) DEFAULT '' COMMENT '身份证',
  `post` varchar(20) NOT NULL DEFAULT '' COMMENT '职位名称',
  `group_id` mediumint(8) unsigned NOT NULL COMMENT '权限ID',
  `tel` char(11) NOT NULL DEFAULT '' COMMENT '员工的手机号码',
  `type` char(4) NOT NULL DEFAULT '' COMMENT '员工类型',
  `nation` char(5) DEFAULT '' COMMENT '名族',
  `marital_status` char(2) DEFAULT '' COMMENT '婚姻状况',
  `entry_status` char(2) NOT NULL DEFAULT '' COMMENT '在职状况',
  `entry_date` date DEFAULT NULL COMMENT '入职时间',
  `dimission_date` date DEFAULT NULL COMMENT '离职时间',
  `politics_status` char(2) NOT NULL DEFAULT '' COMMENT '政治面貌',
  `education` char(2) NOT NULL DEFAULT '' COMMENT '学历',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `number` (`number`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

#
# Data for table "staff"
#

INSERT INTO `staff` VALUES (1,0,'1001','王大锤','男','320902349845734923','总经理',1,'13749239384','合同工','汉族','未婚','在职','2016-06-09',NULL,'团员','大专','2016-06-09 15:35:53'),(2,0,'1002','赵晓丽','女','320902128349326548','销售',2,'13838437493','正式员工','回族','已婚','调休','2016-06-09',NULL,'党员','本科','2016-06-09 15:36:11'),(3,0,'1003','钱德明','男','320902582372342343','财务',3,'13634381002','临时工','满族','离异','辞职','2016-06-11',NULL,'群众','硕士','2016-06-11 13:32:07'),(4,0,'1004','柯洁明','女','320902349348343754','销售',2,'','','','','',NULL,NULL,'','','2016-06-13 20:36:41'),(5,0,'1005','胡一八','男','320902832474394572','销售',2,'','','','','',NULL,NULL,'','','2016-06-18 21:38:44'),(6,0,'1006','白居易','男','320902192893873434','销售',2,'13439458455','正式员工','汉族','','','2016-06-20','2016-06-20','','','2016-06-20 02:12:11'),(7,0,'1007','李白','男','320902458572193442','财务',3,'13874785945','合同工','汉族','离异','调休','2016-06-20','2016-06-20','党员','本科','2016-06-20 02:15:08'),(8,0,'1008','杜甫','男','320902937845678455','销售',2,'','','','','',NULL,NULL,'','','2016-06-20 02:21:46'),(9,28,'1009','王羲之','女','320903843873489347','销售',2,'14856945854','正式员工','汉族','未婚','在职','2016-06-01',NULL,'团员','硕士','2016-06-23 00:12:40'),(12,27,'1010','测试员工','男','320394394932783474','测试',10,'','','','','',NULL,NULL,'','','2017-04-27 18:54:05');

#
# Structure for table "staff_extend"
#

DROP TABLE IF EXISTS `staff_extend`;
CREATE TABLE `staff_extend` (
  `staff_id` mediumint(8) unsigned NOT NULL,
  `health` varchar(30) NOT NULL DEFAULT '' COMMENT '健康状况',
  `specialty` varchar(20) DEFAULT '' COMMENT '学历',
  `registered` varchar(10) DEFAULT '' COMMENT '户口类型',
  `registered_address` varchar(50) DEFAULT '' COMMENT '户口所在地',
  `graduate_date` date DEFAULT NULL,
  `graduate_colleges` varchar(20) DEFAULT '' COMMENT '毕业院校',
  `intro` varchar(255) DEFAULT '' COMMENT '个人简介',
  `details` text COMMENT '详情'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Data for table "staff_extend"
#

INSERT INTO `staff_extend` VALUES (7,'良好','诗人','本地农村户口','北京','2016-06-13','北京大学','暂无！','1');

#
# Structure for table "user"
#

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '自动编号',
  `accounts` varchar(20) NOT NULL DEFAULT '' COMMENT '帐号名称',
  `password` varchar(40) NOT NULL DEFAULT '' COMMENT '帐号密码',
  `staff_id` mediumint(8) unsigned NOT NULL COMMENT '员工档案的关联ID',
  `last_login_time` datetime DEFAULT NULL COMMENT '最后登录时间',
  `last_login_ip` char(15) NOT NULL DEFAULT '' COMMENT '最后登录的IP',
  `login_count` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '登录次数',
  `state` char(2) NOT NULL COMMENT '帐号状态',
  `create_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

#
# Data for table "user"
#

INSERT INTO `user` VALUES (1,'admin','e10adc3949ba59abbe56e057f20f883e',0,'2017-04-27 18:47:54','127.0.0.1',187,'正常','2016-05-18 16:56:47'),(14,'白客','7c4a8d09ca3762af61e59520943dc26494f8941b',0,'2017-03-17 17:26:14','127.0.0.1',2,'正常','2016-07-08 14:29:46'),(16,'黑客','7c4a8d09ca3762af61e59520943dc26494f8941b',0,NULL,'',0,'正常','2016-07-08 14:36:35'),(17,'红客','7c4a8d09ca3762af61e59520943dc26494f8941b',0,NULL,'',0,'正常','2016-07-08 14:38:17'),(18,'蜡笔小新','7c4a8d09ca3762af61e59520943dc26494f8941b',0,'2017-04-27 18:44:24','127.0.0.1',8,'正常','2017-01-12 13:01:02'),(19,'樱桃小丸子','7c4a8d09ca3762af61e59520943dc26494f8941b',0,'2017-03-17 17:25:44','127.0.0.1',6,'正常','2017-01-15 22:38:06'),(20,'路飞','7c4a8d09ca3762af61e59520943dc26494f8941b',0,'2017-03-17 17:25:59','127.0.0.1',2,'正常','2017-03-09 11:29:29'),(21,'佐罗','7c4a8d09ca3762af61e59520943dc26494f8941b',0,'2017-03-17 17:26:48','127.0.0.1',1,'正常','2017-03-10 18:04:41'),(24,'测试','7c4a8d09ca3762af61e59520943dc26494f8941b',0,'2017-04-27 18:54:24','127.0.0.1',1,'正常','2017-04-27 18:54:16'),(27,'随便','7c4a8d09ca3762af61e59520943dc26494f8941b',12,NULL,'',0,'正常','2017-04-27 18:58:33'),(28,'test','123456',9,NULL,'',0,'正常','2018-09-28 14:11:12'),(29,'test1','e10adc3949ba59abbe56e057f20f883e',0,NULL,'',0,'正常','0000-00-00 00:00:00'),(30,'test2','e10adc3949ba59abbe56e057f20f883e',0,NULL,'',0,'正常','0000-00-00 00:00:00'),(31,'test3','e10adc3949ba59abbe56e057f20f883e',0,NULL,'',0,'正常','0000-00-00 00:00:00'),(32,'test4','e10adc3949ba59abbe56e057f20f883e',0,NULL,'',0,'正常','0000-00-00 00:00:00'),(33,'test5','e10adc3949ba59abbe56e057f20f883e',0,NULL,'',0,'正常','0000-00-00 00:00:00'),(34,'test6','e10adc3949ba59abbe56e057f20f883e',0,NULL,'',0,'正常','0000-00-00 00:00:00'),(35,'test7','e10adc3949ba59abbe56e057f20f883e',0,NULL,'',0,'正常','0000-00-00 00:00:00'),(36,'test8','e10adc3949ba59abbe56e057f20f883e',0,NULL,'',0,'正常','0000-00-00 00:00:00'),(37,'test9','e10adc3949ba59abbe56e057f20f883e',0,NULL,'',0,'正常','0000-00-00 00:00:00'),(38,'test10','e10adc3949ba59abbe56e057f20f883e',0,NULL,'',0,'正常','0000-00-00 00:00:00'),(39,'test11','e10adc3949ba59abbe56e057f20f883e',0,NULL,'',0,'正常','0000-00-00 00:00:00'),(40,'test12','e10adc3949ba59abbe56e057f20f883e',0,NULL,'',0,'正常','0000-00-00 00:00:00'),(41,'test13','e10adc3949ba59abbe56e057f20f883e',0,NULL,'',0,'正常','0000-00-00 00:00:00'),(42,'test14','e10adc3949ba59abbe56e057f20f883e',0,NULL,'',0,'正常','0000-00-00 00:00:00'),(43,'test15','e10adc3949ba59abbe56e057f20f883e',0,NULL,'',0,'正常','0000-00-00 00:00:00'),(44,'test16','e10adc3949ba59abbe56e057f20f883e',0,NULL,'',0,'正常','0000-00-00 00:00:00'),(45,'test17','e10adc3949ba59abbe56e057f20f883e',0,NULL,'',0,'正常','0000-00-00 00:00:00'),(46,'test18','e10adc3949ba59abbe56e057f20f883e',0,NULL,'',0,'正常','0000-00-00 00:00:00'),(47,'test19','e10adc3949ba59abbe56e057f20f883e',0,NULL,'',0,'正常','0000-00-00 00:00:00'),(48,'test20','e10adc3949ba59abbe56e057f20f883e',0,NULL,'',0,'正常','0000-00-00 00:00:00'),(49,'test21','e10adc3949ba59abbe56e057f20f883e',0,NULL,'',0,'正常','0000-00-00 00:00:00'),(50,'test22','e10adc3949ba59abbe56e057f20f883e',0,NULL,'',0,'正常','0000-00-00 00:00:00'),(51,'test23','e10adc3949ba59abbe56e057f20f883e',0,NULL,'',0,'正常','0000-00-00 00:00:00'),(52,'test24','e10adc3949ba59abbe56e057f20f883e',0,NULL,'',0,'正常','0000-00-00 00:00:00'),(53,'test25','e10adc3949ba59abbe56e057f20f883e',0,NULL,'',0,'正常','0000-00-00 00:00:00'),(54,'test26','e10adc3949ba59abbe56e057f20f883e',0,NULL,'',0,'正常','0000-00-00 00:00:00'),(55,'test27','e10adc3949ba59abbe56e057f20f883e',0,NULL,'',0,'正常','0000-00-00 00:00:00'),(56,'test28','e10adc3949ba59abbe56e057f20f883e',0,NULL,'',0,'正常','0000-00-00 00:00:00'),(57,'test29','e10adc3949ba59abbe56e057f20f883e',0,NULL,'',0,'正常','0000-00-00 00:00:00'),(58,'test30','e10adc3949ba59abbe56e057f20f883e',0,NULL,'',0,'正常','0000-00-00 00:00:00');

#
# Function "get_trans_num"
#

DROP FUNCTION IF EXISTS `get_trans_num`;
CREATE FUNCTION `get_trans_num`() RETURNS varchar(20) CHARSET utf8
BEGIN
DECLARE getval VARCHAR(24); 
    SET getval = (SELECT CONCAT(DATE_FORMAT(NOW(), '%Y%m'),  LPAD((SELECT next_trans_num('trans_no')), 4, '0')));
    RETURN getval;
END;

#
# Function "next_trans_num"
#

DROP FUNCTION IF EXISTS `next_trans_num`;
CREATE FUNCTION `next_trans_num`(`seq_name` varchar(20)) RETURNS int(11)
BEGIN
    UPDATE sequence SET value=IF(last_insert_id(value+next)>= 999998,0,last_insert_id(value+next)) WHERE name=seq_name; 
    RETURN last_insert_id();
END;
