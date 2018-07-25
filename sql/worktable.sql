/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50710
Source Host           : localhost:3306
Source Database       : workorder

Target Server Type    : MYSQL
Target Server Version : 50710
File Encoding         : 65001

Date: 2018-07-25 23:28:30
*/

SET FOREIGN_KEY_CHECKS=0;

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
