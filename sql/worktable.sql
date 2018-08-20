/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50722
Source Host           : localhost:3306
Source Database       : workorder

Target Server Type    : MYSQL
Target Server Version : 50722
File Encoding         : 65001

Date: 2018-07-29 17:40:31
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for work_failed_jobs
-- ----------------------------
DROP TABLE IF EXISTS `work_failed_jobs`;
CREATE TABLE `work_failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of work_failed_jobs
-- ----------------------------

-- ----------------------------
-- Table structure for work_jobs
-- ----------------------------
DROP TABLE IF EXISTS `work_jobs`;
CREATE TABLE `work_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of work_jobs
-- ----------------------------

-- ----------------------------
-- Table structure for work_migrations
-- ----------------------------
DROP TABLE IF EXISTS `work_migrations`;
CREATE TABLE `work_migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of work_migrations
-- ----------------------------
INSERT INTO `work_migrations` VALUES ('1', '2014_10_12_000000_create_users_table', '1');
INSERT INTO `work_migrations` VALUES ('2', '2014_10_12_100000_create_password_resets_table', '1');
INSERT INTO `work_migrations` VALUES ('3', '2018_07_29_085747_create_jobs_table', '2');
INSERT INTO `work_migrations` VALUES ('4', '2018_07_29_091146_create_failed_jobs_table', '3');

-- ----------------------------
-- Table structure for work_password_resets
-- ----------------------------
DROP TABLE IF EXISTS `work_password_resets`;
CREATE TABLE `work_password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of work_password_resets
-- ----------------------------

-- ----------------------------
-- Table structure for work_service_details
-- ----------------------------
DROP TABLE IF EXISTS `work_service_details`;
CREATE TABLE `work_service_details` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `order_id` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '订单自编号',
  `sku` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'sku',
  `sku_name_cn` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '产品中文名',
  `sku_name_en` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '产品英文名',
  `sku_location` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '产品储位',
  `cost` decimal(15,5) NOT NULL COMMENT '产品成本',
  `attr` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '商品属性',
  `hs_code` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '海关商品编码',
  `net_length` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '净长',
  `net_width` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '净宽',
  `net_height` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '净高',
  `net_weight` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '净重',
  `pack_length` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '包长',
  `pack_width` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '包宽',
  `pack_height` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '包高',
  `pack_weight` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '包重',
  `qty` int(11) NOT NULL COMMENT '数量',
  `price` decimal(15,5) NOT NULL COMMENT '单价',
  `declaration_name_cn` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '申报中文名',
  `declaration_name_en` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '申报英文名',
  `ext_field` longtext COLLATE utf8mb4_unicode_ci COMMENT '拓展字段',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of work_service_details
-- ----------------------------

-- ----------------------------
-- Table structure for work_service_orders
-- ----------------------------
DROP TABLE IF EXISTS `work_service_orders`;
CREATE TABLE `work_service_orders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `order_id` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '自编号',
  `platform_num` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '平台订单号',
  `platform_id` int(11) NOT NULL COMMENT '所属平台',
  `account_id` int(11) NOT NULL COMMENT '销售账号',
  `buyer_id` int(11) NOT NULL COMMENT '买家信息',
  `order_total` decimal(15,5) NOT NULL COMMENT '订单金额',
  `currency_code` char(5) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '币种',
  `freight_amount` decimal(15,5) NOT NULL COMMENT '运费',
  `freight_currency_code` char(5) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '运费币种',
  `service_charge` decimal(15,5) NOT NULL COMMENT '手续费',
  `rate` decimal(15,5) NOT NULL COMMENT '兑美金汇率',
  `channel_id` int(11) DEFAULT NULL COMMENT '物流信息',
  `shipping_id` int(11) DEFAULT NULL COMMENT '发货信息',
  `country` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '国家',
  `buyer_country_code` char(5) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '买家国家简码',
  `orders_type` int(11) NOT NULL COMMENT '订单类型',
  `is_unpack` int(11) NOT NULL DEFAULT '1' COMMENT '可拆单',
  `status` int(11) NOT NULL COMMENT '状态',
  `opt_status` int(11) NOT NULL COMMENT '操作状态',
  `dock_status` int(11) NOT NULL COMMENT '流程id',
  `ext_field` longtext COLLATE utf8mb4_unicode_ci COMMENT '拓展字段',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of work_service_orders
-- ----------------------------

-- ----------------------------
-- Table structure for work_users
-- ----------------------------
DROP TABLE IF EXISTS `work_users`;
CREATE TABLE `work_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of work_users
-- ----------------------------
