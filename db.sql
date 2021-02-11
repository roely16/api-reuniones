/*
SQLyog Community v13.1.1 (64 bit)
MySQL - 10.3.15-MariaDB : Database - reuniones
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`reuniones` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `reuniones`;

/*Table structure for table `menu` */

DROP TABLE IF EXISTS `menu`;

CREATE TABLE `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `url` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `menu` */

insert  into `menu`(`id`,`nombre`,`icon`,`url`) values 
(1,'Reuniones','mdi-account-group','reuniones'),
(2,'Calendario','mdi-calendar','calendario'),
(3,'Participantes','mdi-account-multiple-plus','participantes');

/*Table structure for table `persona` */

DROP TABLE IF EXISTS `persona`;

CREATE TABLE `persona` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombres` varchar(100) DEFAULT NULL,
  `apellidos` varchar(100) DEFAULT NULL,
  `telefono` varchar(100) DEFAULT NULL,
  `cargo` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `avatar` varchar(1000) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=latin1;

/*Data for the table `persona` */

insert  into `persona`(`id`,`nombres`,`apellidos`,`telefono`,`cargo`,`email`,`avatar`,`created_at`,`updated_at`,`deleted_at`) values 
(12,'Herson','Chur','44522476','Senior Developer','gerson.roely@gmail.com','avatar/man.png','2021-02-04 18:35:03','2021-02-10 18:21:25',NULL),
(23,'Juan','Gomez','44522477','Coordinador de Informática','juan.gomez@gmail.com',NULL,'2021-02-04 20:20:44','2021-02-10 18:24:02',NULL),
(47,'Miguel','Gonzales','44522475','Encargado de Compras','miguel.gonzales@gmail.com',NULL,'2021-02-10 16:59:23','2021-02-10 18:22:35',NULL),
(48,'Oscar','Perez','44522474','Encargado de Ventas','oscar.perez@gmail.com',NULL,'2021-02-10 17:01:37','2021-02-10 18:42:12','2021-02-10 18:42:12'),
(49,'Ana','Torres','44522478','Supervisora','ana.torres@gmail.com',NULL,'2021-02-10 17:05:18','2021-02-10 18:41:53','2021-02-10 18:41:53'),
(50,'Otto','Hernandez','44778899','Supervisor','otto.he@gmail.com',NULL,'2021-02-10 18:40:17','2021-02-10 18:40:51','2021-02-10 18:40:51');

/*Table structure for table `reunion` */

DROP TABLE IF EXISTS `reunion`;

CREATE TABLE `reunion` (
  `ind` int(11) NOT NULL,
  PRIMARY KEY (`ind`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `reunion` */

/*Table structure for table `usuario` */

DROP TABLE IF EXISTS `usuario`;

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(100) DEFAULT NULL,
  `password` varchar(500) DEFAULT NULL,
  `id_persona` int(10) unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuario` (`usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=latin1;

/*Data for the table `usuario` */

insert  into `usuario`(`id`,`usuario`,`password`,`id_persona`,`created_at`,`updated_at`,`deleted_at`) values 
(1,'44522476','eyJpdiI6InQraVd3NGZHb09oTWl5VzlVZWl2Unc9PSIsInZhbHVlIjoibkQxN2ZiMktQaGdXQnlxWDRPOWVuZz09IiwibWFjIjoiYzdiYzhlNzg0ODgzZmU1ZWJhOTgzMzJhZGJjMzJiNjk0ZTU3MzNhYTI4MDEyMjUwYmRjNTZhMmIzOTM5YTZjZCJ9',12,'2021-02-04 18:35:03','2021-02-04 18:35:03',NULL),
(12,'44522477','eyJpdiI6IlFERmVSUE04NDV5aE45S1IyRWxwQlE9PSIsInZhbHVlIjoieThZVTFnV2ZOMllMSU9DR3FGNUVjQT09IiwibWFjIjoiODJjYTg5NWJiYmQ5MzkwYzhmMWQ5OTM3ZGRiMGE5ZmY5ZjRhMmE5NjMxM2EzMjk3M2E4NjRjYTUzNDgwNmJmOCJ9',23,'2021-02-04 20:20:44','2021-02-04 20:20:44',NULL),
(35,'44522475','eyJpdiI6InBMUHMrRXhkZ3hDNDJYY1k0NU02UHc9PSIsInZhbHVlIjoieWVSbEdkNDlGSEdtRDRLK1hGVDlSUT09IiwibWFjIjoiZTJlZmNmY2U5YTA5Yjg4OTgzYjgxNWY5MzQxNzk2Y2U0MzI5MjE4OTg4ZWU1Mzk4M2QzMzNmYzBiZjIxN2JkZiJ9',47,'2021-02-10 16:59:24','2021-02-10 16:59:24',NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
