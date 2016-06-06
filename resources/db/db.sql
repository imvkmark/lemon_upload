/*
Navicat MySQL Data Transfer

Source Server         : 192.168.1.111
Source Server Version : 50712
Source Host           : 192.168.1.111:3306
Source Database       : lar_upload

Target Server Type    : MYSQL
Target Server Version : 50712
File Encoding         : 65001

Date: 2016-06-06 18:06:31
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for mk_account_desktop
-- ----------------------------
DROP TABLE IF EXISTS `mk_account_desktop`;
CREATE TABLE `mk_account_desktop` (
  `account_id` int(10) NOT NULL DEFAULT '0' COMMENT '账户ID',
  `mobile` varchar(20) DEFAULT '' COMMENT '手机号码',
  `realname` varchar(50) DEFAULT '' COMMENT '真实姓名',
  `qq` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`account_id`),
  UNIQUE KEY `account_id` (`account_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of mk_account_desktop
-- ----------------------------
INSERT INTO `mk_account_desktop` VALUES ('1', '', '', '');
INSERT INTO `mk_account_desktop` VALUES ('2', '', '', '');
INSERT INTO `mk_account_desktop` VALUES ('262', '', '', '');
INSERT INTO `mk_account_desktop` VALUES ('263', '', '', '408128151');

-- ----------------------------
-- Table structure for mk_account_develop
-- ----------------------------
DROP TABLE IF EXISTS `mk_account_develop`;
CREATE TABLE `mk_account_develop` (
  `account_id` int(11) NOT NULL DEFAULT '0' COMMENT '账户id',
  `truename` varchar(50) DEFAULT NULL COMMENT '联系人姓名',
  `nickname` varchar(50) DEFAULT NULL COMMENT '昵称',
  `email` varchar(100) DEFAULT NULL COMMENT '邮箱',
  PRIMARY KEY (`account_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of mk_account_develop
-- ----------------------------
INSERT INTO `mk_account_develop` VALUES ('264', '赵殿有', '', 'zhaody901@126.com');

-- ----------------------------
-- Table structure for mk_account_front
-- ----------------------------
DROP TABLE IF EXISTS `mk_account_front`;
CREATE TABLE `mk_account_front` (
  `account_id` int(11) NOT NULL DEFAULT '0' COMMENT '账户id',
  `qq` varchar(15) DEFAULT NULL COMMENT 'qq 号码',
  `mobile` varchar(50) DEFAULT NULL COMMENT '手机号',
  `truename` varchar(50) DEFAULT NULL COMMENT '联系人姓名',
  `nickname` varchar(50) DEFAULT NULL COMMENT '昵称',
  `address` varchar(255) DEFAULT NULL COMMENT '地址',
  `head_pic` varchar(100) DEFAULT NULL COMMENT '头像',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '资金',
  `lock` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '锁定资金',
  `email` varchar(100) DEFAULT NULL COMMENT '邮箱',
  `chid` varchar(100) DEFAULT NULL COMMENT '身份证',
  `chid_pic` varchar(300) DEFAULT NULL COMMENT '身份证扫描件',
  `area_name` varchar(100) DEFAULT NULL COMMENT '所在地区',
  `ali` varchar(30) DEFAULT NULL COMMENT '旺旺',
  `order_prefix` varchar(30) DEFAULT NULL COMMENT '订单默认前缀',
  `v_mobile` enum('Y','N') DEFAULT 'N' COMMENT '验证手机真实性',
  `v_question` enum('Y','N') DEFAULT 'N' COMMENT '是否设置密保问题',
  `v_truename` enum('Y','N') DEFAULT 'N' COMMENT '验证身份证真实性',
  `truename_status` enum('none','wait','passed','failed') DEFAULT 'none' COMMENT '真实姓名认证状态',
  `truename_note` varchar(255) DEFAULT '' COMMENT '真实姓名验证原因',
  `v_email` enum('Y','N') DEFAULT 'N' COMMENT '验证邮箱真实性',
  `v_code` char(6) DEFAULT '' COMMENT '生成的验证码 6位',
  `v_type` varchar(50) DEFAULT '' COMMENT '验证类型',
  `v_valid_time` datetime DEFAULT NULL COMMENT '验证到期有效期',
  `question_title_1` varchar(255) DEFAULT NULL COMMENT '密保问题',
  `question_title_2` varchar(255) DEFAULT NULL,
  `question_title_3` varchar(255) DEFAULT NULL,
  `question_answer_1` varchar(255) DEFAULT NULL COMMENT '密保答案',
  `question_answer_2` varchar(255) DEFAULT NULL,
  `question_answer_3` varchar(255) DEFAULT NULL,
  `signature` varchar(255) DEFAULT NULL COMMENT '个性签名',
  `permission` text COMMENT '用户权限控制',
  `payword` varchar(50) DEFAULT NULL COMMENT '支付密码',
  `payword_key` char(6) DEFAULT NULL COMMENT '支付密码 key',
  PRIMARY KEY (`account_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of mk_account_front
-- ----------------------------
INSERT INTO `mk_account_front` VALUES ('265', '408128151', '15254109156', null, null, '', 'http://www.lar_dailian.com/uploads/avatar/265.png', '876204.88', '340.10', 'zhaody901@126.com', null, null, null, null, null, 'N', 'N', 'N', 'none', '', 'Y', '', '', null, null, null, null, null, null, null, null, null, '7eb44b5f418fb2aa0ee90ccd0ca5e590', 'KR5wxi');
INSERT INTO `mk_account_front` VALUES ('266', null, '13218333984', null, null, null, null, '0.00', '0.00', null, null, null, null, null, null, 'Y', 'N', 'N', 'none', '', 'N', '', '', null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `mk_account_front` VALUES ('267', null, '13151247958', null, null, null, 'http://www.lar_dailian.com/uploads/avatar/267.png', '0.00', '0.00', null, null, null, null, null, null, 'Y', 'N', 'N', 'none', '', 'N', '', '', null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `mk_account_front` VALUES ('268', null, '14444444444', null, null, null, null, '499239.00', '761.00', null, null, null, null, null, null, 'Y', 'N', 'N', 'none', '', 'N', '', '', null, null, null, null, null, null, null, null, null, '28ac3d1fd06daf9e91a7ff7ac1a54995', 'XrU9N5');
INSERT INTO `mk_account_front` VALUES ('269', null, '15555555555', null, null, null, null, '0.00', '0.00', null, null, null, null, null, null, 'Y', 'N', 'N', 'none', '', 'N', '', '', null, null, null, null, null, null, null, null, null, null, null);

-- ----------------------------
-- Table structure for mk_account_validate
-- ----------------------------
DROP TABLE IF EXISTS `mk_account_validate`;
CREATE TABLE `mk_account_validate` (
  `valid_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '验证ID',
  `valid_type` enum('email','mobile') NOT NULL DEFAULT 'mobile' COMMENT '验证类型',
  `valid_ip` char(16) NOT NULL DEFAULT '' COMMENT '发送验证的IP',
  `valid_subject` varchar(100) NOT NULL DEFAULT '' COMMENT '邮箱或者手机号',
  `valid_auth` varchar(100) NOT NULL DEFAULT '' COMMENT '保存的验证码值或者auth值',
  `account_id` int(11) unsigned DEFAULT '0',
  `expired_at` datetime DEFAULT NULL COMMENT '在什么时间过期',
  `created_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`valid_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of mk_account_validate
-- ----------------------------

-- ----------------------------
-- Table structure for mk_base_config
-- ----------------------------
DROP TABLE IF EXISTS `mk_base_config`;
CREATE TABLE `mk_base_config` (
  `conf_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '配置id',
  `conf_group` varchar(50) NOT NULL DEFAULT '' COMMENT '配置分组',
  `conf_name` varchar(50) NOT NULL DEFAULT '' COMMENT '配置名称',
  `conf_value` text NOT NULL COMMENT '配置值',
  `conf_desc` varchar(255) NOT NULL DEFAULT '' COMMENT '配置介绍',
  `is_enable` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '是否起作用',
  PRIMARY KEY (`conf_id`),
  KEY `conf_name` (`conf_name`) USING BTREE,
  KEY `conf_group` (`conf_group`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=289 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of mk_base_config
-- ----------------------------
INSERT INTO `mk_base_config` VALUES ('272', 'site', 'site_name', '易代练游戏交易平台', '', '1');
INSERT INTO `mk_base_config` VALUES ('273', 'site', 'is_open', 'Y', '', '1');
INSERT INTO `mk_base_config` VALUES ('274', 'site', 'close_reason', '站点关闭', '', '1');
INSERT INTO `mk_base_config` VALUES ('275', 'site', 'copyright', '版权所有, 翻版必究 @ 2015 Mark Zhao ', '', '1');
INSERT INTO `mk_base_config` VALUES ('280', 'site', 'order_over_hour', '72', '', '1');
INSERT INTO `mk_base_config` VALUES ('285', 'site', 'open_transfer', 'Y', '', '1');
INSERT INTO `mk_base_config` VALUES ('286', 'site', 'transfer_alipay_account', 'chongzhi@1dailian.com', '', '1');
INSERT INTO `mk_base_config` VALUES ('287', 'site', 'cash_bank_type', '支付宝\r\n中国工商银行\r\n中国建设银行', '', '1');
INSERT INTO `mk_base_config` VALUES ('288', 'site', 'cash_rate', '0', '', '1');

-- ----------------------------
-- Table structure for mk_l5_job
-- ----------------------------
DROP TABLE IF EXISTS `mk_l5_job`;
CREATE TABLE `mk_l5_job` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `l5_job_queue_reserved_reserved_at_index` (`queue`,`reserved`,`reserved_at`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of mk_l5_job
-- ----------------------------

-- ----------------------------
-- Table structure for mk_l5_migration
-- ----------------------------
DROP TABLE IF EXISTS `mk_l5_migration`;
CREATE TABLE `mk_l5_migration` (
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of mk_l5_migration
-- ----------------------------
INSERT INTO `mk_l5_migration` VALUES ('2016_05_13_112651_create_mk_account_desktop_table', '1');
INSERT INTO `mk_l5_migration` VALUES ('2016_05_13_112652_create_mk_account_develop_table', '1');
INSERT INTO `mk_l5_migration` VALUES ('2016_05_13_112652_create_mk_account_front_table', '1');
INSERT INTO `mk_l5_migration` VALUES ('2016_05_13_112652_create_mk_account_validate_table', '1');
INSERT INTO `mk_l5_migration` VALUES ('2016_05_13_112652_create_mk_base_config_table', '1');
INSERT INTO `mk_l5_migration` VALUES ('2016_05_13_112653_create_mk_l5_job_table', '1');
INSERT INTO `mk_l5_migration` VALUES ('2016_05_13_112653_create_mk_l5_migration_table', '1');
INSERT INTO `mk_l5_migration` VALUES ('2016_05_13_112653_create_mk_pam_account_table', '1');
INSERT INTO `mk_l5_migration` VALUES ('2016_05_13_112654_create_mk_pam_log_table', '1');
INSERT INTO `mk_l5_migration` VALUES ('2016_05_13_112654_create_mk_pam_online_table', '1');
INSERT INTO `mk_l5_migration` VALUES ('2016_05_13_112655_create_mk_pam_permission_role_table', '1');
INSERT INTO `mk_l5_migration` VALUES ('2016_05_13_112655_create_mk_pam_permission_table', '1');
INSERT INTO `mk_l5_migration` VALUES ('2016_05_13_112655_create_mk_pam_role_table', '1');
INSERT INTO `mk_l5_migration` VALUES ('2016_05_13_112656_create_mk_pam_role_account_table', '1');
INSERT INTO `mk_l5_migration` VALUES ('2016_05_13_112656_create_mk_plugin_image_key_table', '1');
INSERT INTO `mk_l5_migration` VALUES ('2016_05_13_112656_create_mk_plugin_image_upload_table', '1');

-- ----------------------------
-- Table structure for mk_pam_account
-- ----------------------------
DROP TABLE IF EXISTS `mk_pam_account`;
CREATE TABLE `mk_pam_account` (
  `account_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `account_name` varchar(100) DEFAULT '' COMMENT '账号名称， 支持中文',
  `account_pwd` char(32) DEFAULT '' COMMENT '账号密码',
  `account_key` char(6) DEFAULT '' COMMENT '账号注册时候随机生成的6位key',
  `account_type` char(20) DEFAULT '' COMMENT '账户类型',
  `login_times` mediumint(8) unsigned DEFAULT '0' COMMENT '登陆次数',
  `reg_ip` varchar(20) DEFAULT '' COMMENT '注册IP',
  `is_enable` enum('Y','N') DEFAULT 'Y',
  `created_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `logined_at` datetime DEFAULT NULL COMMENT '上次登录时间',
  `updated_at` datetime DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT '',
  PRIMARY KEY (`account_id`),
  UNIQUE KEY `u_account_name` (`account_name`) USING BTREE,
  KEY `k_account_name` (`account_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=269 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of mk_pam_account
-- ----------------------------
INSERT INTO `mk_pam_account` VALUES ('262', '呼思鹏', '80bf5532db4513a78b122396bdb497e9', 'DTVv3v', 'desktop', '365', '223.96.146.162', 'Y', '2015-11-18 16:14:32', null, '2016-04-17 16:22:51', '2016-04-17 16:22:52', 'AIB0JzKPMitAdui7VnYp8dsHYhQbTlgorT19vAjsC0HNT2lMULSBqGWVmQXa');
INSERT INTO `mk_pam_account` VALUES ('263', 'admin', '0a2ab2a2de774f739e5da2d8dfc2c551', 'gH9lKP', 'desktop', '43', '127.0.0.1', 'Y', '2016-04-17 16:17:07', null, '2016-06-01 16:06:11', '2016-06-06 15:51:59', 'MLjuqPt4t2tP66xQfOEEr1Q0DkRLhK8MNsLAF68vjgubVxYTXjYoSJfOYXC0');
INSERT INTO `mk_pam_account` VALUES ('265', 'fadan001', '9c1b92b8f25722a09249f1e42be1929c', 'vH4OIY', 'front', '18', '127.0.0.1', 'Y', '2016-04-17 16:18:06', null, '2016-04-20 00:34:26', '2016-04-23 22:43:14', 'bqU8L58uaOpehFcIDtNa6qtDeW2nnnS5GQKBAVajyFQH5vxX0p6Enb1K2OPk');
INSERT INTO `mk_pam_account` VALUES ('266', 'Tatyana', 'eb5ea3b0fe72de0bb00dfebc9231d479', 'T3uNvE', 'front', '1', '127.0.0.1', 'Y', '2016-04-19 22:29:05', null, '2016-04-19 23:15:35', '2016-04-19 23:15:36', 'W9ka4KF8n5A1PRgnY8mT2wkdqjv3NNaEFajsivcoAChTzwf3CEwSOUG4aP5s');
INSERT INTO `mk_pam_account` VALUES ('267', 'Cailin', 'b2ba03411c0a19b68c7eb22e9de02d47', 'Jy3sIj', 'front', '1', '127.0.0.1', 'Y', '2016-04-19 23:33:23', null, '2016-04-20 00:00:00', '2016-04-20 00:00:00', 'ZuL2E4ZzrvsTQutvI0CgexXel388JroXL6wyuDSS8P6kkWcQUL3eef7WlN7c');
INSERT INTO `mk_pam_account` VALUES ('268', 'jiedan001', '248f79da29be3057eda03988a7adff7d', 'feLfSf', 'front', '2', '127.0.0.1', 'Y', '2016-04-20 00:12:23', null, '2016-04-20 00:32:01', '2016-05-24 12:44:48', '3cff8hBfcya3hYqyYJtmSxynzcG9F0jjDQ1RjCcNUOdkgnZxEiVtat6jiSYv');

-- ----------------------------
-- Table structure for mk_pam_log
-- ----------------------------
DROP TABLE IF EXISTS `mk_pam_log`;
CREATE TABLE `mk_pam_log` (
  `log_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `account_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `parent_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '父账号ID',
  `account_name` varchar(50) NOT NULL DEFAULT '' COMMENT '账户名',
  `account_type` enum('subuser','front','desktop','develop') NOT NULL DEFAULT 'front' COMMENT '账户类型',
  `log_content` varchar(200) NOT NULL DEFAULT '',
  `log_type` enum('error','success','warning') NOT NULL DEFAULT 'success' COMMENT '登陆日志类型, success, error, warning',
  `log_ip` char(50) NOT NULL DEFAULT '' COMMENT 'IP ',
  `log_area_text` char(100) NOT NULL DEFAULT '',
  `log_area_name` char(100) NOT NULL DEFAULT '',
  `log_area_id` int(11) NOT NULL DEFAULT '0' COMMENT '地区ID, 以此判定跨区登陆',
  `created_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of mk_pam_log
-- ----------------------------
INSERT INTO `mk_pam_log` VALUES ('1', '263', '0', 'admin', 'desktop', '登陆成功', 'success', '127.0.0.1', 'LAN', '', '0', '2016-05-13 10:15:23', null, '2016-05-13 10:15:23');

-- ----------------------------
-- Table structure for mk_pam_online
-- ----------------------------
DROP TABLE IF EXISTS `mk_pam_online`;
CREATE TABLE `mk_pam_online` (
  `account_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `login_ip` char(50) NOT NULL DEFAULT '' COMMENT 'IP ',
  `logined_at` datetime DEFAULT NULL,
  PRIMARY KEY (`account_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of mk_pam_online
-- ----------------------------

-- ----------------------------
-- Table structure for mk_pam_permission
-- ----------------------------
DROP TABLE IF EXISTS `mk_pam_permission`;
CREATE TABLE `mk_pam_permission` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `permission_name` varchar(255) NOT NULL,
  `permission_title` varchar(255) DEFAULT NULL,
  `permission_description` varchar(255) DEFAULT NULL,
  `permission_group` varchar(255) DEFAULT NULL,
  `is_menu` int(11) DEFAULT '0',
  `account_type` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `l5_permission_name_unique` (`permission_name`)
) ENGINE=InnoDB AUTO_INCREMENT=245 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of mk_pam_permission
-- ----------------------------
INSERT INTO `mk_pam_permission` VALUES ('1', 'dev_api.auto', '自动', '', '接口测试', '1', 'develop', '2016-04-24 14:36:07', '2016-04-24 16:20:11');
INSERT INTO `mk_pam_permission` VALUES ('2', 'dev_l5_log.index', '首页', '', 'L5日志', '1', 'develop', '2016-04-24 14:36:07', '2016-04-24 16:20:11');
INSERT INTO `mk_pam_permission` VALUES ('74', 'dsk_image_key.index', '平台key列表', '', '图片平台', '1', 'desktop', '2016-04-24 14:49:07', '2016-04-24 16:19:59');
INSERT INTO `mk_pam_permission` VALUES ('108', 'dsk_validate.index', '资料认证', '', '资料认证', '1', 'desktop', '2016-04-24 14:49:07', '2016-04-24 16:19:59');
INSERT INTO `mk_pam_permission` VALUES ('109', 'front_finance.charge_confirm', '充值确认', '', '资金管理', '0', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:18');
INSERT INTO `mk_pam_permission` VALUES ('110', 'front_finance.charge', '充值', '', '资金管理', '1', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:18');
INSERT INTO `mk_pam_permission` VALUES ('111', 'front_finance.cash', '提现', '', '资金管理', '0', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:18');
INSERT INTO `mk_pam_permission` VALUES ('112', 'front_finance.charge_list', '充值记录', '', '资金管理', '1', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:18');
INSERT INTO `mk_pam_permission` VALUES ('113', 'front_finance.money_list', '资金流水', '', '资金管理', '1', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:18');
INSERT INTO `mk_pam_permission` VALUES ('114', 'front_finance.lock_list', '资金冻结', '', '资金管理', '1', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:18');
INSERT INTO `mk_pam_permission` VALUES ('115', 'front_finance.cash_list', '提现记录', '', '资金管理', '1', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:18');
INSERT INTO `mk_pam_permission` VALUES ('116', 'front_finance.bank', '银行卡管理', '', '资金管理', '0', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:18');
INSERT INTO `mk_pam_permission` VALUES ('117', 'front_finance.bank_delete', '删除银行卡', '', '资金管理', '0', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:18');
INSERT INTO `mk_pam_permission` VALUES ('118', 'front_game.order', '订单列表', '', '游戏管理', '0', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:18');
INSERT INTO `mk_pam_permission` VALUES ('119', 'front_help.feedback', '意见反馈', '', '帮助中心', '1', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:18');
INSERT INTO `mk_pam_permission` VALUES ('120', 'front_help.show', '文章页面', '', '帮助中心', '0', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:18');
INSERT INTO `mk_pam_permission` VALUES ('121', 'front_help.index', '文章列表', '', '帮助中心', '1', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:18');
INSERT INTO `mk_pam_permission` VALUES ('122', 'front_home.cp', '主控制台', '', '主面板', '1', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:18');
INSERT INTO `mk_pam_permission` VALUES ('123', 'front_home.env', '环境检测', '', '主面板', '0', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:18');
INSERT INTO `mk_pam_permission` VALUES ('124', 'front_home.welcome', '欢迎界面', '', '主面板', '0', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:18');
INSERT INTO `mk_pam_permission` VALUES ('125', 'front_order.index', '我要接单', '', '订单管理', '1', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:18');
INSERT INTO `mk_pam_permission` VALUES ('126', 'front_order.create', '我要发单', '', '订单管理', '1', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:18');
INSERT INTO `mk_pam_permission` VALUES ('127', 'front_order.store', '保存订单', '', '订单管理', '0', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:18');
INSERT INTO `mk_pam_permission` VALUES ('128', 'front_order.my_create', '发单管理', '', '订单管理', '1', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:18');
INSERT INTO `mk_pam_permission` VALUES ('129', 'front_order.my', '接单管理', '', '订单管理', '1', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:18');
INSERT INTO `mk_pam_permission` VALUES ('130', 'front_order.type_selection', '选择类别', '', '订单管理', '0', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:18');
INSERT INTO `mk_pam_permission` VALUES ('131', 'front_order.money_enough', '检测余额是否充足', '', '订单管理', '0', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:18');
INSERT INTO `mk_pam_permission` VALUES ('132', 'front_order.handle', '接手订单', '', '订单管理', '0', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:18');
INSERT INTO `mk_pam_permission` VALUES ('133', 'front_order.update_progress', '更新进度', '', '订单管理', '0', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:18');
INSERT INTO `mk_pam_permission` VALUES ('134', 'front_order.talk', '订单留言', '', '订单管理', '0', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:18');
INSERT INTO `mk_pam_permission` VALUES ('135', 'front_order.submit_over', '提交订单完成', '', '订单管理', '0', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:18');
INSERT INTO `mk_pam_permission` VALUES ('136', 'front_order.over', '审核订单至完成', '', '订单管理', '0', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:18');
INSERT INTO `mk_pam_permission` VALUES ('137', 'front_order.exception', '订单异常', '', '订单管理', '0', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:18');
INSERT INTO `mk_pam_permission` VALUES ('138', 'front_order.cancel_exception', '取消订单异常', '', '订单管理', '0', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:18');
INSERT INTO `mk_pam_permission` VALUES ('139', 'front_order.change_game_account', '修改游戏账号资料', '', '订单管理', '0', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:18');
INSERT INTO `mk_pam_permission` VALUES ('140', 'front_order.pub_cancel', '申请撤单', '', '订单管理', '0', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:19');
INSERT INTO `mk_pam_permission` VALUES ('141', 'front_order.sd_cancel', '申请退单', '', '订单管理', '0', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:19');
INSERT INTO `mk_pam_permission` VALUES ('142', 'front_order.add_time', '补时', '', '订单管理', '0', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:19');
INSERT INTO `mk_pam_permission` VALUES ('143', 'front_order.add_money', '补分', '', '订单管理', '0', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:19');
INSERT INTO `mk_pam_permission` VALUES ('144', 'front_order.handle_pub_cancel', '处理发单者撤销', '', '订单管理', '0', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:19');
INSERT INTO `mk_pam_permission` VALUES ('145', 'front_order.handle_sd_cancel', '处理发单者撤销', '', '订单管理', '0', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:19');
INSERT INTO `mk_pam_permission` VALUES ('146', 'front_order.lock', '锁定订单', '', '订单管理', '0', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:19');
INSERT INTO `mk_pam_permission` VALUES ('147', 'front_order.unlock', '解锁订单', '', '订单管理', '0', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:19');
INSERT INTO `mk_pam_permission` VALUES ('148', 'front_order.quash', '撤销订单', '', '订单管理', '0', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:19');
INSERT INTO `mk_pam_permission` VALUES ('149', 'front_order.delete', '删除订单', '', '订单管理', '0', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:19');
INSERT INTO `mk_pam_permission` VALUES ('150', 'front_order.kf', '申请客服介入', '', '订单管理', '0', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:19');
INSERT INTO `mk_pam_permission` VALUES ('151', 'front_order.edit', '编辑订单', '', '订单管理', '0', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:19');
INSERT INTO `mk_pam_permission` VALUES ('152', 'front_order.star', '评价上家', '', '订单管理', '0', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:19');
INSERT INTO `mk_pam_permission` VALUES ('153', 'front_order.cancel_pub_cancel', '取消发单者退单', '', '订单管理', '0', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:19');
INSERT INTO `mk_pam_permission` VALUES ('154', 'front_order.cancel_sd_cancel', '取消接单者退单', '', '订单管理', '0', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:19');
INSERT INTO `mk_pam_permission` VALUES ('155', 'front_soldier.index', '下家列表', '', '下家管理', '0', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:19');
INSERT INTO `mk_pam_permission` VALUES ('156', 'front_soldier.show', '下家详情', '', '下家管理', '0', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:19');
INSERT INTO `mk_pam_permission` VALUES ('157', 'front_soldier.group', '分组管理', '', '下家管理', '0', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:19');
INSERT INTO `mk_pam_permission` VALUES ('158', 'front_soldier.my', '我的打手', '', '下家管理', '1', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:19');
INSERT INTO `mk_pam_permission` VALUES ('159', 'front_sub_user.index', '子账号列表', '', '子账号操作', '0', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:19');
INSERT INTO `mk_pam_permission` VALUES ('160', 'front_sub_user.create', '创建/新增', '', '子账号操作', '0', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:19');
INSERT INTO `mk_pam_permission` VALUES ('161', 'front_sub_user.edit', '编辑/修改', '', '子账号操作', '0', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:19');
INSERT INTO `mk_pam_permission` VALUES ('162', 'front_tpl.selection', '选择模板', '', '用户操作', '0', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:19');
INSERT INTO `mk_pam_permission` VALUES ('163', 'front_tpl.create', '创建模板', '', '用户操作', '0', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:19');
INSERT INTO `mk_pam_permission` VALUES ('164', 'front_tpl.destroy', '删除模板', '', '用户操作', '0', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:19');
INSERT INTO `mk_pam_permission` VALUES ('165', 'front_tpl.edit', '编辑模板', '', '用户操作', '0', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:19');
INSERT INTO `mk_pam_permission` VALUES ('166', 'front_tpl.default', '设置默认模版', '', '用户操作', '0', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:19');
INSERT INTO `mk_pam_permission` VALUES ('167', 'front_user.socialite_bind', '绑定账号', '', '用户操作', '0', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:19');
INSERT INTO `mk_pam_permission` VALUES ('168', 'front_user.login', '用户登陆', '', '用户操作', '0', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:19');
INSERT INTO `mk_pam_permission` VALUES ('169', 'front_user.register', '用户注册', '', '用户操作', '0', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:19');
INSERT INTO `mk_pam_permission` VALUES ('170', 'front_user.basic', '基本资料', '', '用户操作', '1', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:19');
INSERT INTO `mk_pam_permission` VALUES ('171', 'front_user.avatar', '头像管理', '', '用户操作', '0', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:19');
INSERT INTO `mk_pam_permission` VALUES ('172', 'front_user.password', '修改密码', '', '用户操作', '1', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:19');
INSERT INTO `mk_pam_permission` VALUES ('173', 'front_user.question', '密保问题', '', '用户操作', '0', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:19');
INSERT INTO `mk_pam_permission` VALUES ('174', 'front_user.payword', '修改支付密码', '', '用户操作', '0', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:19');
INSERT INTO `mk_pam_permission` VALUES ('175', 'front_user.validate_truename', '实名认证', '', '用户操作', '0', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:19');
INSERT INTO `mk_pam_permission` VALUES ('176', 'front_user.bind_mobile', '手机绑定', '', '用户操作', '0', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:19');
INSERT INTO `mk_pam_permission` VALUES ('177', 'front_user.send_captcha', '发送验证码', '', '用户操作', '0', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:19');
INSERT INTO `mk_pam_permission` VALUES ('178', 'front_user.login_log', '登陆日志', '', '用户操作', '0', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:19');
INSERT INTO `mk_pam_permission` VALUES ('179', 'front_user.logout', '退出登陆', '', '用户操作', '0', 'front', '2016-04-24 14:49:28', '2016-04-24 16:19:19');
INSERT INTO `mk_pam_permission` VALUES ('187', 'dsk_pam_role.index', '用户角色', '', '角色管理', '1', 'desktop', '2016-04-25 07:04:00', '2016-04-25 07:04:00');
INSERT INTO `mk_pam_permission` VALUES ('188', 'dsk_pam_role.create', '创建角色', '', '角色管理', '0', 'desktop', '2016-04-25 07:04:00', '2016-04-25 07:04:00');
INSERT INTO `mk_pam_permission` VALUES ('189', 'dsk_pam_role.edit', '编辑角色', '', '角色管理', '0', 'desktop', '2016-04-25 07:04:00', '2016-04-25 07:04:00');
INSERT INTO `mk_pam_permission` VALUES ('190', 'dsk_pam_role.destroy', '删除角色', '', '角色管理', '0', 'desktop', '2016-04-25 07:04:00', '2016-04-25 07:04:00');
INSERT INTO `mk_pam_permission` VALUES ('191', 'dsk_pam_role.menu', '授权登录', '', '角色管理', '0', 'desktop', '2016-04-25 07:04:00', '2016-04-25 07:04:00');
INSERT INTO `mk_pam_permission` VALUES ('233', 'dsk_image_key.create', '创建key', '', '图片平台', '1', 'desktop', '2016-05-13 10:44:15', '2016-05-13 10:44:15');
INSERT INTO `mk_pam_permission` VALUES ('234', 'dsk_lemon_home.welcome', '欢迎页面', '', '主页管理', '1', 'desktop', '2016-05-24 12:02:47', '2016-05-24 12:02:47');
INSERT INTO `mk_pam_permission` VALUES ('235', 'dsk_lemon_home.cp', '控制面板', '', '主页管理', '0', 'desktop', '2016-05-24 12:02:47', '2016-05-24 12:02:47');
INSERT INTO `mk_pam_permission` VALUES ('236', 'dsk_lemon_home.tip', '弹出提示', '', '主页管理', '0', 'desktop', '2016-05-24 12:02:47', '2016-05-24 12:02:47');
INSERT INTO `mk_pam_permission` VALUES ('237', 'dsk_pam_account.index', '用户列表', '', '账号管理', '1', 'desktop', '2016-05-24 12:03:01', '2016-05-24 12:03:01');
INSERT INTO `mk_pam_permission` VALUES ('238', 'dsk_pam_account.log', '登陆日志', '', '账号管理', '1', 'desktop', '2016-05-24 12:03:01', '2016-05-24 12:03:01');
INSERT INTO `mk_pam_permission` VALUES ('239', 'dsk_pam_account.status', '启用/禁用账号', '', '账号管理', '0', 'desktop', '2016-05-24 12:03:01', '2016-05-24 12:03:01');
INSERT INTO `mk_pam_permission` VALUES ('240', 'dsk_pam_account.create', '创建账号', '', '账号管理', '0', 'desktop', '2016-05-24 12:03:01', '2016-05-24 12:03:01');
INSERT INTO `mk_pam_permission` VALUES ('241', 'dsk_pam_account.edit', '更改用户资料', '', '账号管理', '0', 'desktop', '2016-05-24 12:03:01', '2016-05-24 12:03:01');
INSERT INTO `mk_pam_permission` VALUES ('242', 'dsk_pam_account.destroy', '删除用户', '', '账号管理', '0', 'desktop', '2016-05-24 12:03:01', '2016-05-24 12:03:01');
INSERT INTO `mk_pam_permission` VALUES ('243', 'dsk_image_upload.index', '图片列表', '', '图片上传', '1', 'desktop', '2016-06-01 15:59:34', '2016-06-01 15:59:34');
INSERT INTO `mk_pam_permission` VALUES ('244', 'dsk_image_upload.destroy', '删除图片', '', '图片上传', '0', 'desktop', '2016-06-01 15:59:34', '2016-06-01 15:59:34');

-- ----------------------------
-- Table structure for mk_pam_permission_role
-- ----------------------------
DROP TABLE IF EXISTS `mk_pam_permission_role`;
CREATE TABLE `mk_pam_permission_role` (
  `permission_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of mk_pam_permission_role
-- ----------------------------
INSERT INTO `mk_pam_permission_role` VALUES ('43', '3');
INSERT INTO `mk_pam_permission_role` VALUES ('74', '1');
INSERT INTO `mk_pam_permission_role` VALUES ('97', '11');
INSERT INTO `mk_pam_permission_role` VALUES ('101', '11');
INSERT INTO `mk_pam_permission_role` VALUES ('102', '11');
INSERT INTO `mk_pam_permission_role` VALUES ('103', '11');
INSERT INTO `mk_pam_permission_role` VALUES ('104', '11');
INSERT INTO `mk_pam_permission_role` VALUES ('105', '11');
INSERT INTO `mk_pam_permission_role` VALUES ('108', '1');
INSERT INTO `mk_pam_permission_role` VALUES ('187', '1');
INSERT INTO `mk_pam_permission_role` VALUES ('188', '1');
INSERT INTO `mk_pam_permission_role` VALUES ('189', '1');
INSERT INTO `mk_pam_permission_role` VALUES ('190', '1');
INSERT INTO `mk_pam_permission_role` VALUES ('191', '1');
INSERT INTO `mk_pam_permission_role` VALUES ('233', '1');
INSERT INTO `mk_pam_permission_role` VALUES ('234', '1');
INSERT INTO `mk_pam_permission_role` VALUES ('235', '1');
INSERT INTO `mk_pam_permission_role` VALUES ('236', '1');
INSERT INTO `mk_pam_permission_role` VALUES ('237', '1');
INSERT INTO `mk_pam_permission_role` VALUES ('238', '1');
INSERT INTO `mk_pam_permission_role` VALUES ('239', '1');
INSERT INTO `mk_pam_permission_role` VALUES ('240', '1');
INSERT INTO `mk_pam_permission_role` VALUES ('241', '1');
INSERT INTO `mk_pam_permission_role` VALUES ('242', '1');
INSERT INTO `mk_pam_permission_role` VALUES ('243', '1');
INSERT INTO `mk_pam_permission_role` VALUES ('244', '1');

-- ----------------------------
-- Table structure for mk_pam_role
-- ----------------------------
DROP TABLE IF EXISTS `mk_pam_role`;
CREATE TABLE `mk_pam_role` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `role_name` varchar(100) NOT NULL DEFAULT '' COMMENT '角色组名称',
  `role_title` varchar(100) NOT NULL DEFAULT '' COMMENT '中文名称',
  `role_type` varchar(100) NOT NULL DEFAULT '' COMMENT '角色的标识',
  `role_description` varchar(100) NOT NULL DEFAULT '',
  `account_type` varchar(20) NOT NULL DEFAULT '' COMMENT '账户类型',
  `is_enable` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否可用',
  `is_system` tinyint(4) NOT NULL DEFAULT '0',
  `note` varchar(150) NOT NULL DEFAULT '' COMMENT '备注',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `role_name` (`role_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of mk_pam_role
-- ----------------------------
INSERT INTO `mk_pam_role` VALUES ('1', 'root', '超级管理员', '', '', 'desktop', '1', '1', '', null, null);
INSERT INTO `mk_pam_role` VALUES ('2', 'develop', '开发者', '', '', 'develop', '1', '2', '', null, null);
INSERT INTO `mk_pam_role` VALUES ('3', 'front', '用户', '', '', 'front', '1', '1', '', null, null);

-- ----------------------------
-- Table structure for mk_pam_role_account
-- ----------------------------
DROP TABLE IF EXISTS `mk_pam_role_account`;
CREATE TABLE `mk_pam_role_account` (
  `account_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '账户id',
  `role_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '角色id',
  PRIMARY KEY (`account_id`),
  KEY `role_id` (`role_id`) USING BTREE,
  KEY `account_id` (`account_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of mk_pam_role_account
-- ----------------------------
INSERT INTO `mk_pam_role_account` VALUES ('262', '1');
INSERT INTO `mk_pam_role_account` VALUES ('263', '1');
INSERT INTO `mk_pam_role_account` VALUES ('265', '100');
INSERT INTO `mk_pam_role_account` VALUES ('266', '100');
INSERT INTO `mk_pam_role_account` VALUES ('267', '100');
INSERT INTO `mk_pam_role_account` VALUES ('268', '100');
INSERT INTO `mk_pam_role_account` VALUES ('269', '100');
INSERT INTO `mk_pam_role_account` VALUES ('264', '102');

-- ----------------------------
-- Table structure for mk_plugin_image_key
-- ----------------------------
DROP TABLE IF EXISTS `mk_plugin_image_key`;
CREATE TABLE `mk_plugin_image_key` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `account_id` int(10) unsigned NOT NULL DEFAULT '0',
  `key_public` varchar(255) DEFAULT '',
  `key_type` varchar(50) DEFAULT '',
  `key_secret` varchar(255) DEFAULT '',
  `key_note` varchar(255) NOT NULL DEFAULT '',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `plugin_image_key_account_id_index` (`account_id`),
  KEY `plugin_image_key_key_public_index` (`key_public`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of mk_plugin_image_key
-- ----------------------------
INSERT INTO `mk_plugin_image_key` VALUES ('1', '265', '3302300000263176', 'product', '12345678901234567890', '', '2016-04-17 16:56:41', '2016-05-24 13:38:28');

-- ----------------------------
-- Table structure for mk_plugin_image_upload
-- ----------------------------
DROP TABLE IF EXISTS `mk_plugin_image_upload`;
CREATE TABLE `mk_plugin_image_upload` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `account_id` int(10) unsigned NOT NULL,
  `md5` char(32) NOT NULL,
  `upload_type` varchar(50) NOT NULL DEFAULT '',
  `upload_path` varchar(255) DEFAULT '',
  `upload_extension` varchar(255) DEFAULT '',
  `upload_filesize` int(10) unsigned NOT NULL,
  `upload_mime` varchar(50) NOT NULL DEFAULT '',
  `upload_field` varchar(50) NOT NULL DEFAULT '',
  `image_type` varchar(50) NOT NULL DEFAULT '',
  `image_width` int(10) unsigned NOT NULL,
  `image_height` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `plugin_image_upload_account_id_index` (`account_id`),
  KEY `md5` (`md5`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of mk_plugin_image_upload
-- ----------------------------
INSERT INTO `mk_plugin_image_upload` VALUES ('25', '265', '73373d05ba4ffa6d260feb5ca8f405a3', 'image', '201606/04/19/2626XcOprV6n.jpg', 'jpg', '898880', 'image/jpeg', '', 'jpeg', '1440', '900', '2016-06-04 19:26:26', '2016-06-04 19:26:26');
INSERT INTO `mk_plugin_image_upload` VALUES ('26', '265', '224164937c942732d36ba8eedb28dcb1', 'image', '201606/04/19/2711tcrI1J0X.jpg', 'jpg', '5912147', 'image/jpeg', '', 'jpeg', '1440', '1440', '2016-06-04 19:27:13', '2016-06-04 19:27:13');
INSERT INTO `mk_plugin_image_upload` VALUES ('27', '265', 'ba45f00d7063a77d377e1612f605e0bb', 'image', '201606/04/19/2753YRLU01kA.jpg', 'jpg', '7673696', 'image/jpeg', '', 'jpeg', '1440', '1440', '2016-06-04 19:27:54', '2016-06-04 19:27:54');
INSERT INTO `mk_plugin_image_upload` VALUES ('28', '265', '5842e4c476a6460440fad7cc824fbf52', 'image', '201606/06/15/5936hJI2gOSR.png', '', '74009', 'image/png', '', 'png', '200', '200', '2016-06-06 15:59:36', '2016-06-06 15:59:36');
INSERT INTO `mk_plugin_image_upload` VALUES ('29', '265', 'da1ffc848f36d5a44e5dd59510040c1e', 'image', '201606/06/17/2037moW7eGOX.png', 'png', '221892', 'image/png', '', 'png', '685', '1380', '2016-06-06 17:20:37', '2016-06-06 17:20:37');
INSERT INTO `mk_plugin_image_upload` VALUES ('30', '265', '9655928e0d27d9eb9158f632523b435e', 'image', '201606/06/17/2303cetw1GNh.png', 'png', '134072', 'image/png', '', 'png', '695', '962', '2016-06-06 17:23:03', '2016-06-06 17:23:03');
