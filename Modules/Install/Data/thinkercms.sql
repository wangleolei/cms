/*
Navicat MySQL Data Transfer

Source Server         : localhost-本地
Source Server Version : 50540
Source Host           : localhost:3306
Source Database       : thinkercms1.0

Target Server Type    : MYSQL
Target Server Version : 50540
File Encoding         : 65001

Date: 2016-04-06 22:01:05
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for fc_admin
-- ----------------------------
DROP TABLE IF EXISTS `fc_admin`;
CREATE TABLE `fc_admin` (
  `userid` mediumint(6) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` varchar(32) NOT NULL,
  `encrypt` varchar(6) NOT NULL,
  `email` varchar(40) NOT NULL,
  `realname` varchar(50) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `ishead` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`userid`),
  UNIQUE KEY `username_unique` (`username`) USING BTREE,
  KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理员数据表';

-- ----------------------------
-- Records of fc_admin
-- ----------------------------

-- ----------------------------
-- Table structure for fc_admin_behavior
-- ----------------------------
DROP TABLE IF EXISTS `fc_admin_behavior`;
CREATE TABLE `fc_admin_behavior` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `uid` mediumint(6) DEFAULT NULL,
  `timelong` float NOT NULL,
  `memory` int(11) NOT NULL,
  `time` varchar(50) NOT NULL,
  `date1` varchar(20) NOT NULL,
  `date2` varchar(20) NOT NULL,
  `m` varchar(50) NOT NULL,
  `c` varchar(50) NOT NULL,
  `a` varchar(50) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `data` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='管理员操作记录';

-- ----------------------------
-- Records of fc_admin_behavior
-- ----------------------------

-- ----------------------------
-- Table structure for fc_admin_cache
-- ----------------------------
DROP TABLE IF EXISTS `fc_admin_cache`;
CREATE TABLE `fc_admin_cache` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `title` varchar(50) NOT NULL,
  `namespace` varchar(50) NOT NULL,
  `function` varchar(255) NOT NULL,
  `args` varchar(255) NOT NULL,
  `creattime` int(10) NOT NULL,
  `long` varchar(50) NOT NULL,
  `clear` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`,`name`),
  UNIQUE KEY `缓存名称唯一` (`name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='缓存记录';

-- ----------------------------
-- Records of fc_admin_cache
-- ----------------------------
INSERT INTO `fc_admin_cache` VALUES ('1', '0c326503ba389299af39f94bddd908f1', '系统设置缓存', 'Admin\\Model\\SettingModel', 'getSetting', 'a:1:{i:0;i:1;}', '1459950790', '0', '0');
INSERT INTO `fc_admin_cache` VALUES ('2', 'b9a430006575f0e99a46432ac7fee877', '网站基本信息缓存', 'Admin\\Model\\SettingModel', 'getSetting', 'a:1:{i:0;i:5;}', '1459950939', '0', '0');
INSERT INTO `fc_admin_cache` VALUES ('3', '7af53c4f36d8f780a502a5e5c482a865', '网站首页SEO缓存', 'Admin\\Model\\SettingModel', 'getSetting', 'a:1:{i:0;i:4;}', '1459950939', '0', '0');
INSERT INTO `fc_admin_cache` VALUES ('4', '38a4587bcffacda6b1ba16de95f57518', '微信帐号缓存', 'Admin\\Model\\SettingModel', 'getSetting', 'a:1:{i:0;i:2;}', '1459337914', '0', '0');
INSERT INTO `fc_admin_cache` VALUES ('5', 'bfa226da317a2ec6552f24bcfdd197dd', '路由规则缓存', 'Admin\\Model\\SettingModel', 'getSetting', 'a:1:{i:0;i:3;}', '1459950790', '0', '0');

-- ----------------------------
-- Table structure for fc_admin_constraint
-- ----------------------------
DROP TABLE IF EXISTS `fc_admin_constraint`;
CREATE TABLE `fc_admin_constraint` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `info` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='权限约束';

-- ----------------------------
-- Records of fc_admin_constraint
-- ----------------------------
INSERT INTO `fc_admin_constraint` VALUES ('1', '管理自己发表的文章', '1', '用户只能修改和删除和查看自己发表的文章', '');

-- ----------------------------
-- Table structure for fc_admin_login
-- ----------------------------
DROP TABLE IF EXISTS `fc_admin_login`;
CREATE TABLE `fc_admin_login` (
  `id` mediumint(6) unsigned NOT NULL AUTO_INCREMENT,
  `uid` mediumint(6) unsigned NOT NULL,
  `time` int(10) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `area` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `外键索引` (`uid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理员登录记录';

-- ----------------------------
-- Records of fc_admin_login
-- ----------------------------

-- ----------------------------
-- Table structure for fc_admin_menu
-- ----------------------------
DROP TABLE IF EXISTS `fc_admin_menu`;
CREATE TABLE `fc_admin_menu` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(40) NOT NULL DEFAULT '',
  `parentid` smallint(6) NOT NULL DEFAULT '0',
  `authid` smallint(6) unsigned NOT NULL,
  `m` char(20) NOT NULL DEFAULT '',
  `c` char(20) NOT NULL DEFAULT '',
  `a` char(20) NOT NULL DEFAULT '',
  `data` char(100) NOT NULL DEFAULT '',
  `path` varchar(50) NOT NULL DEFAULT '0',
  `listorder` smallint(6) unsigned NOT NULL DEFAULT '0',
  `display` enum('1','0') NOT NULL DEFAULT '1' COMMENT '是否显示菜单',
  `project1` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否在副菜单中展示',
  `project2` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否为弹出窗口',
  `project3` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否突出显示',
  `project4` varchar(10) NOT NULL DEFAULT '0' COMMENT '弹出窗口高',
  `project5` varchar(10) NOT NULL DEFAULT '0' COMMENT '弹出窗口宽',
  `project6` tinyint(1) NOT NULL COMMENT '是否显示副菜单',
  `target` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `listorder` (`listorder`),
  KEY `parentid` (`parentid`),
  KEY `module` (`m`,`c`,`a`)
) ENGINE=InnoDB AUTO_INCREMENT=572 DEFAULT CHARSET=utf8 COMMENT='后台菜单';

-- ----------------------------
-- Records of fc_admin_menu
-- ----------------------------
INSERT INTO `fc_admin_menu` VALUES ('1', '系统', '0', '1', 'Admin', 'System', 'index', '', '-0-', '19', '1', '0', '1', '0', '1', '1', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('2', '系统管理', '1', '9', 'System_settings', 'System_settings', 'System_settings', '', '-0-1-', '89', '1', '0', '0', '1', '1', '1', '1', '0');
INSERT INTO `fc_admin_menu` VALUES ('3', '菜单管理', '2', '10', 'Admin', 'Menu', 'menuList', '', '-0-1-2-', '99', '1', '1', '0', '0', '1', '1', '1', '0');
INSERT INTO `fc_admin_menu` VALUES ('4', '删除菜单', '3', '11', 'Admin', 'Menu', 'delete', '', '-0-1-2-3-', '3', '0', '0', '0', '0', '1', '1', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('5', '系统用户', '1', '2', 'system_user', 'system_user', 'system_user', '', '-0-1-', '99', '1', '0', '0', '0', '1', '1', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('6', '用户列表', '5', '3', 'Admin', 'Admin', 'AdminList', '', '-0-1-5-', '99', '1', '1', '0', '0', '1', '1', '1', '0');
INSERT INTO `fc_admin_menu` VALUES ('7', '权限分组', '5', '7', 'Admin', 'Group', 'groupList', '', '-0-1-5-', '89', '1', '1', '0', '0', '1', '1', '1', '0');
INSERT INTO `fc_admin_menu` VALUES ('8', '添加菜单', '3', '23', 'Admin', 'Menu', 'add', '', '-0-1-2-3-', '4', '1', '1', '0', '1', '400', '400', '1', '0');
INSERT INTO `fc_admin_menu` VALUES ('9', '修改菜单', '3', '13', 'Admin', 'Menu', 'edit', '', '-0-1-2-3-', '2', '1', '0', '0', '0', '300', '200', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('16', '修改分租', '7', '8', 'Admin', 'Group', 'groupEdit', '', '-0-1-5-7-', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('19', '添加分组', '7', '24', 'Admin', 'Group', 'groupAdd', '', '-0-1-5-7-', '0', '1', '1', '1', '1', '80', '500', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('35', '添加用户', '6', '6', 'Admin', 'Admin', 'adminAdd', '', '-0-1-5-6-', '0', '1', '1', '1', '1', '270', '550', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('37', '修改用户', '6', '5', 'Admin', 'Admin', 'adminEdit', '', '-0-1-5-6-', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('38', '删除用户', '6', '4', 'Admin', 'Admin', 'adminDelete', '', '-0-1-5-6-', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('50', '删除权限组', '7', '27', 'Admin', 'Group', 'groupDelete', '', '-0-1-5-7-', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('51', '修改用户组权限', '7', '28', 'Admin', 'Group', 'groupPrev', '', '-0-1-5-7-', '0', '1', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('61', '系统设置', '2', '38', 'Admin', 'Config', 'configList', '', '-0-1-2-', '89', '1', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('62', '内容', '0', '39', 'Content', 'Index', 'index', '', '-0-', '89', '1', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('63', '内容设置', '62', '40', 'article_manage', 'article_manage', 'article_manage', '', '-0-62-', '89', '1', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('73', '网站设置', '62', '50', 'site_setting', 'site_setting', 'site_setting', '', '-0-62-', '69', '1', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('74', '栏目管理', '63', '51', 'Content', 'Category', 'lists', '', '-0-62-63-', '2', '1', '1', '0', '0', '0', '0', '1', '0');
INSERT INTO `fc_admin_menu` VALUES ('75', '添加栏目', '74', '52', 'Content', 'Category', 'add', '', '-0-62-63-74-', '0', '0', '1', '1', '1', '500', '700', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('78', '排序', '74', '55', 'Content', 'Category', 'listorder', '', '-0-62-63-74-', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('79', '修改', '74', '56', 'Content', 'Category', 'edit', '', '-0-62-63-74-', '0', '0', '0', '0', '0', '100', '100', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('80', '删除', '74', '57', 'Content', 'Category', 'delete', '', '-0-62-63-74-', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('81', '更新缓存', '74', '58', 'Content', 'Category', 'recache', '', '-0-62-63-74-', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('88', '修改系统设置', '61', '65', 'Admin', 'Config', 'edit', '', '-0-1-2-61-', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('90', '文件资源', '467', '67', 'Plug', 'Uploads', 'lists', '', '-0-62-467-', '89', '1', '0', '0', '0', '0', '0', '1', '0');
INSERT INTO `fc_admin_menu` VALUES ('91', '上传文件', '90', '68', 'Plug', 'Uploads', 'add', '', '-0-62-467-90-', '0', '1', '1', '1', '1', '450', '600', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('92', '删除一个文件', '90', '69', 'Plug', 'Uploads', 'delete', '', '-0-62-467-90-', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('93', '删除选中文件', '90', '70', 'Plug', 'Uploads', 'deleteAll', '', '-0-62-467-90-', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('94', '文件重命名', '90', '71', 'Plug', 'Uploads', 'edit', '', '-0-62-467-90-', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('99', '推荐位', '63', '75', 'Content', 'Position', 'lists', '', '-0-62-63-', '0', '1', '1', '0', '0', '0', '0', '1', '0');
INSERT INTO `fc_admin_menu` VALUES ('100', '添加推荐位', '99', '76', 'Content', 'Position', 'add', '', '-0-62-63-99-', '0', '1', '1', '1', '1', '120', '500', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('111', '留言板', '142', '87', 'Plug', 'Message', 'lists', '', '-0-62-142-', '0', '1', '1', '0', '0', '0', '0', '1', '0');
INSERT INTO `fc_admin_menu` VALUES ('112', '修复栏目结构', '74', '88', 'Content', 'Category', 'repair', '', '-0-62-63-74-', '0', '0', '1', '0', '0', '0', '0', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('121', '登陆日志', '5', '97', 'Admin', 'Landlog', 'lists', '', '-0-1-5-', '69', '1', '1', '0', '0', '0', '0', '1', '0');
INSERT INTO `fc_admin_menu` VALUES ('122', '修复菜单结构', '3', '98', 'Admin', 'Menu', 'repair', '', '-0-1-2-3-', '0', '0', '1', '0', '0', '0', '0', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('125', '清除三个月前的记录', '121', '101', 'Admin', 'Landlog', 'clear', '', '-0-1-5-121-', '0', '0', '1', '0', '0', '0', '0', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('142', '独立插件', '62', '118', 'Plug_module', 'Plug_content', 'Plug_content', '', '-0-62-', '79', '1', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('160', '修改推荐位', '99', '136', 'Content', 'Position', 'edit', '', '-0-62-63-99-', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('161', '删除推荐位', '99', '137', 'Content', 'Position', 'delete', '', '-0-62-63-99-', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('162', '排序', '99', '138', 'Content', 'Position', 'listorder', '', '-0-62-63-99-', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('167', '删除留言', '111', '143', 'Plug', 'Message', 'delete', '', '-0-62-142-111-', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('168', '查看留言', '111', '144', 'Plug', 'Message', 'view', '', '-0-62-142-111-', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('169', '删除选中留言', '111', '145', 'Plug', 'Message', 'deleteSelect', '', '-0-62-142-111-', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('214', '广告块', '142', '190', 'Plug', 'Block', 'lists', '', '-0-62-142-', '0', '1', '1', '0', '0', '0', '0', '1', '0');
INSERT INTO `fc_admin_menu` VALUES ('215', '添加广告块', '214', '191', 'Plug', 'Block', 'add', '', '-0-62-142-214-', '0', '1', '1', '1', '1', '100', '500', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('216', '友情链接', '142', '192', 'Plug', 'Link', 'index', '', '-0-62-142-', '0', '1', '1', '0', '0', '0', '0', '1', '0');
INSERT INTO `fc_admin_menu` VALUES ('217', '添加友情链接', '216', '193', 'Plug', 'Link', 'add', '', '-0-62-142-216-', '0', '1', '1', '1', '1', '150', '550', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('218', '修改友情链接', '216', '194', 'Plug', 'Link', 'edit', '', '-0-62-142-216-', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('219', '删除友情链接', '216', '195', 'Plug', 'Link', 'delete', '', '-0-62-142-216-', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('220', '推荐友情链接', '216', '196', 'Plug', 'Link', 'recommend', '', '-0-62-142-216-', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('221', '排序', '216', '197', 'Plug', 'Link', 'listorder', '', '-0-62-142-216-', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('222', '修改', '214', '198', 'Plug', 'Block', 'edit', '', '-0-62-142-214-', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('223', '编辑广告块', '214', '199', 'Plug', 'Block', 'editBlock', '', '-0-62-142-214-', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('224', '添加区块', '214', '200', 'Plug', 'Block', 'addBlock', '', '-0-62-142-214-', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('225', '删除区块', '214', '201', 'Plug', 'Block', 'delBlock', '', '-0-62-142-214-', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('226', '删除广告块', '214', '202', 'Plug', 'Block', 'delete', '', '-0-62-142-214-', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('227', '广告块排序', '214', '203', 'Plug', 'Block', 'listorder', '', '-0-62-142-214-', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('288', '站点设置', '73', '269', 'Content', 'Settings', 'index', '', '-0-62-73-', '0', '1', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('307', '导航链接', '73', '288', 'Content', 'Nav', 'index', '', '-0-62-73-', '0', '1', '1', '0', '0', '0', '0', '1', '0');
INSERT INTO `fc_admin_menu` VALUES ('308', '添加导航链接', '307', '289', 'Content', 'Nav', 'add', '', '-0-62-73-307-', '0', '1', '1', '1', '1', '90', '600', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('309', '修改导航链接', '307', '290', 'Content', 'Nav', 'edit', '', '-0-62-73-307-', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('310', '删除', '307', '291', 'Content', 'Nav', 'delete', '', '-0-62-73-307-', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('311', '修改状态', '307', '292', 'Content', 'Nav', 'status', '', '-0-62-73-307-', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('312', '排序', '307', '293', 'Content', 'Nav', 'listorder', '', '-0-62-73-307-', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('333', '邮件发送测试', '61', '314', 'Admin', 'Config', 'test_mail', '', '-0-1-2-61-', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('350', '商品交易', '153', '331', 'Member', 'trading', 'trading_module', '', '-0-153-', '79', '1', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('366', '操作日志', '2', '349', 'Admin', 'Log', 'index', '', '-0-1-2-', '0', '1', '1', '0', '0', '0', '0', '1', '0');
INSERT INTO `fc_admin_menu` VALUES ('367', '清除三个余月前的日志', '366', '350', 'Admin', 'Log', 'clear', '', '-0-1-2-366-', '0', '1', '1', '0', '0', '0', '0', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('387', '确认结款', '386', '369', 'Member', 'Seller', 'confirmSettlement', '', '-0-386-', '0', '0', '0', '0', '0', '', '', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('451', '菜单排序', '3', '434', 'Admin', 'Menu', 'listorder', '', '-0-1-2-3-', '0', '0', '0', '0', '0', '', '', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('457', '创建缩略图', '90', '440', 'Plug', 'Uploads', 'creatTrumb', '', '-0-62-467-90-', '0', '0', '0', '0', '0', '', '', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('460', '栏目SEO设置', '74', '443', 'Content', 'Category', 'seo', '', '-0-62-63-74-', '0', '0', '0', '0', '0', '', '', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('462', '内容模型', '63', '445', 'Content', 'Sitemodel', 'index', '', '-0-62-63-', '0', '1', '1', '0', '0', '', '', '1', '0');
INSERT INTO `fc_admin_menu` VALUES ('463', '添加模型', '462', '446', 'Content', 'Sitemodel', 'add', '', '-0-62-63-462-', '0', '0', '1', '1', '1', '170', '580', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('464', '内容模型字段', '63', '447', 'Content', 'Sitemodelfield', 'index', '', '-0-62-63-', '0', '0', '1', '0', '0', '', '', '1', '0');
INSERT INTO `fc_admin_menu` VALUES ('465', '添加字段', '464', '448', 'Content', 'Sitemodelfield', 'add', 'modelid/get_modelid', '-0-62-63-464-', '0', '0', '1', '0', '1', '', '', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('466', '预览模型', '464', '449', 'Content', 'Sitemodelfield', 'public_priview', 'modelid/get_modelid', '-0-62-63-464-', '0', '0', '1', '0', '0', '', '', '0', '1');
INSERT INTO `fc_admin_menu` VALUES ('467', '内容发布', '62', '450', 'Content', 'Index', 'publish', '', '-0-62-', '99', '1', '0', '0', '0', '', '', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('468', '管理内容', '467', '451', 'Content', 'Content', 'index', '', '-0-62-467-', '99', '1', '0', '0', '0', '', '', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('469', '内容列表', '467', '452', 'Content', 'Content', 'lists', 'modelid/get_modelid/menuid/469', '-0-62-467-', '0', '0', '1', '0', '0', '', '', '1', '0');
INSERT INTO `fc_admin_menu` VALUES ('470', '添加内容', '469', '453', 'Content', 'Content', 'add', 'modelid/get_modelid/catid/get_catid/', '-0-62-467-469-', '0', '1', '1', '0', '1', '', '', '0', '1');
INSERT INTO `fc_admin_menu` VALUES ('471', '导入模型', '462', '454', 'Content', 'Sitemodel', 'import', '', '-0-62-63-462-', '0', '0', '1', '0', '0', '', '', '1', '0');
INSERT INTO `fc_admin_menu` VALUES ('472', '编辑模型', '462', '455', 'Content', 'Sitemodel', 'edit', '', '-0-62-63-462-', '0', '0', '0', '0', '0', '', '', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('473', '删除模型', '462', '456', 'Content', 'Sitemodel', 'delete', '', '-0-62-63-462-', '0', '0', '0', '0', '0', '', '', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('474', '修改模型状态', '462', '457', 'Content', 'Sitemodel', 'disabled', '', '-0-62-63-462-', '0', '0', '0', '0', '0', '', '', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('475', '导出模型', '462', '458', 'Content', 'Sitemodel', 'export', '', '-0-62-63-462-', '0', '0', '0', '0', '0', '', '', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('476', '编辑字段', '464', '459', 'Content', 'Sitemodelfield', 'edit', '', '-0-62-63-464-', '0', '0', '0', '0', '0', '', '', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('477', '禁用字段', '464', '460', 'Content', 'Sitemodelfield', 'disabled', '', '-0-62-63-464-', '0', '0', '0', '0', '0', '', '', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('478', '删除字段', '464', '461', 'Content', 'Sitemodelfield', 'delete', '', '-0-62-63-464-', '0', '0', '0', '0', '0', '', '', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('479', '字段排序', '464', '462', 'Content', 'Sitemodelfield', 'listorder', '', '-0-62-63-464-', '0', '0', '0', '0', '0', '', '', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('480', '编辑内容', '469', '463', 'Content', 'Content', 'edit', '', '-0-62-467-469-', '0', '0', '0', '0', '0', '', '', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('505', '栏目权限', '7', '488', 'Admin', 'Group', 'categoryPrev', '', '-0-1-5-7-', '0', '0', '0', '0', '0', '', '', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('506', '权限约束', '7', '489', 'Admin', 'Group', 'constraint', '', '-0-1-5-7-', '0', '0', '0', '0', '0', '', '', '0', '0');
INSERT INTO `fc_admin_menu` VALUES ('570', '数据库管理', '2', '553', 'Admin', 'Database', 'index', '', '-0-1-2-', '0', '1', '1', '0', '0', '', '', '1', '0');
INSERT INTO `fc_admin_menu` VALUES ('571', '备份还原', '570', '554', 'Admin', 'Database', 'restore', '', '-0-1-2-570-', '0', '0', '1', '0', '0', '', '', '1', '0');

-- ----------------------------
-- Table structure for fc_admin_panel
-- ----------------------------
DROP TABLE IF EXISTS `fc_admin_panel`;
CREATE TABLE `fc_admin_panel` (
  `menuid` mediumint(8) unsigned NOT NULL,
  `userid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  UNIQUE KEY `userid` (`menuid`,`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='后台快捷栏';

-- ----------------------------
-- Records of fc_admin_panel
-- ----------------------------

-- ----------------------------
-- Table structure for fc_admin_setting
-- ----------------------------
DROP TABLE IF EXISTS `fc_admin_setting`;
CREATE TABLE `fc_admin_setting` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `名称是唯一的` (`name`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='设置项';

-- ----------------------------
-- Records of fc_admin_setting
-- ----------------------------
INSERT INTO `fc_admin_setting` VALUES ('1', '系统设置', 'a:23:{s:11:\"system_name\";s:10:\"ThinkerCMS\";s:11:\"system_logo\";s:33:\"/Public/images/admin_img/logo.png\";s:7:\"js_path\";s:11:\"/Public/js/\";s:8:\"css_path\";s:12:\"/Public/css/\";s:8:\"img_path\";s:15:\"/Public/images/\";s:9:\"site_host\";s:0:\"\";s:9:\"cachetime\";s:1:\"1\";s:9:\"admin_log\";s:1:\"1\";s:10:\"LOG_RECORD\";s:1:\"1\";s:13:\"errorlog_size\";s:2:\"20\";s:19:\"maxloginfailedtimes\";s:1:\"8\";s:14:\"minrefreshtime\";s:1:\"2\";s:9:\"admin_url\";s:0:\"\";s:11:\"mail_server\";s:18:\"smtp.exmail.qq.com\";s:9:\"mail_port\";s:3:\"465\";s:9:\"mail_from\";s:0:\"\";s:9:\"mail_auth\";s:1:\"1\";s:9:\"mail_user\";s:0:\"\";s:13:\"mail_password\";s:0:\"\";s:16:\"FILE_UPLOAD_TYPE\";s:5:\"Local\";s:11:\"upload_host\";s:0:\"\";s:20:\"UPLOAD_FILE_MAX_SIZE\";s:5:\"20240\";s:10:\"THUMB_SPEC\";s:0:\"\";}');
INSERT INTO `fc_admin_setting` VALUES ('4', '网站首页SEO', 'a:3:{s:9:\"seo_title\";s:28:\"ThinkerCMS内容管理系统\";s:12:\"seo_keywords\";s:66:\"ThinkerCMS,ThinkerCMS内容管理系统,thinkphp内容管理系统\";s:15:\"seo_description\";s:146:\"ThinkerCMS是ThinkerPHP旗下的一款专门针对小型网站的内容管理系统，其特点在于小巧精致，可快速进行二次开发。\";}');
INSERT INTO `fc_admin_setting` VALUES ('5', '网站基本信息', 'a:9:{s:5:\"phone\";s:0:\"\";s:6:\"phone1\";s:0:\"\";s:6:\"phone2\";s:0:\"\";s:3:\"fax\";s:0:\"\";s:5:\"email\";s:0:\"\";s:2:\"qq\";s:0:\"\";s:5:\"weibo\";s:0:\"\";s:7:\"address\";s:0:\"\";s:3:\"icp\";s:0:\"\";}');
INSERT INTO `fc_admin_setting` VALUES ('3', '路由规则', 'a:5:{s:4:\"open\";s:1:\"1\";s:8:\"pc_lists\";s:12:\"lists/:catid\";s:9:\"pc_detail\";s:16:\"lists/:catid/:id\";s:8:\"mb_lists\";s:13:\"mlists/:catid\";s:9:\"mb_detail\";s:17:\"mlists/:catid/:id\";}');

-- ----------------------------
-- Table structure for fc_auth_group
-- ----------------------------
DROP TABLE IF EXISTS `fc_auth_group`;
CREATE TABLE `fc_auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `listorder` tinyint(4) NOT NULL DEFAULT '0',
  `title` char(100) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `rules` text NOT NULL,
  `constraint` text NOT NULL,
  `usertype` varchar(50) DEFAULT NULL COMMENT '用户类别',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='后台权限分组';

-- ----------------------------
-- Records of fc_auth_group
-- ----------------------------
INSERT INTO `fc_auth_group` VALUES ('1', '0', '超级管理员', '1', '', '', null);

-- ----------------------------
-- Table structure for fc_auth_group_access
-- ----------------------------
DROP TABLE IF EXISTS `fc_auth_group_access`;
CREATE TABLE `fc_auth_group_access` (
  `uid` mediumint(8) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理员所属权限组';

-- ----------------------------
-- Records of fc_auth_group_access
-- ----------------------------
INSERT INTO `fc_auth_group_access` VALUES ('1', '1');

-- ----------------------------
-- Table structure for fc_auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `fc_auth_rule`;
CREATE TABLE `fc_auth_rule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(80) NOT NULL DEFAULT '',
  `title` char(20) NOT NULL DEFAULT '',
  `type` tinyint(1) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `condition` char(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `快速查询` (`id`,`name`,`title`,`type`,`status`,`condition`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=559 DEFAULT CHARSET=utf8 COMMENT='权限规则';

-- ----------------------------
-- Records of fc_auth_rule
-- ----------------------------
INSERT INTO `fc_auth_rule` VALUES ('1', 'Admin/System/index/', '系统', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('2', 'system_user/system_user/system_user/', '系统用户', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('3', 'Admin/Admin/AdminList/', '用户列表', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('4', 'Admin/Admin/adminDelete/', '删除用户', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('5', 'Admin/Admin/adminEdit/', '修改用户', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('6', 'Admin/Admin/adminAdd/', '添加用户', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('7', 'Admin/Group/groupList/', '权限分组', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('8', 'Admin/Group/groupEdit/', '修改分租', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('9', 'System_settings/System_settings/System_settings/', '系统设置', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('10', 'Admin/Menu/menuList/', '菜单管理', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('11', 'Admin/Menu/delete/', '删除菜单', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('13', 'Admin/Menu/edit/', '修改菜单', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('23', 'Admin/Menu/add/', '添加菜单', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('24', 'Admin/Group/groupAdd/', '添加分组', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('27', 'Admin/Group/groupDelete/', '删除权限组', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('28', 'Admin/Group/groupPrev/', '修改用户组权限', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('38', 'Admin/Config/configList/', '系统设置', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('39', 'Content/Index/index/', '内容', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('40', 'article_manage/article_manage/article_manage/', '内容设置', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('50', 'site_setting/site_setting/site_setting/', '网站设置', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('51', 'Content/Category/lists/', '栏目管理', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('52', 'Content/Category/add/', '添加栏目', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('55', 'Content/Category/listorder/', '排序', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('56', 'Content/Category/edit/', '修改', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('57', 'Content/Category/delete/', '删除', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('58', 'Content/Category/delcache/', '更新缓存', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('65', 'Admin/Config/edit/', '修改系统设置', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('67', 'Plug/Uploads/lists/', '文件资源', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('68', 'Plug/Uploads/add/', '上传文件', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('69', 'Plug/Uploads/delete/', '删除一个文件', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('70', 'Plug/Uploads/deleteAll/', '删除选中文件', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('71', 'Plug/Uploads/edit/', '文件重命名', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('75', 'Content/Position/lists/', '推荐位', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('76', 'Content/Position/add/', '添加推荐位', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('88', 'Content/Category/repair/', '修复栏目结构', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('97', 'Admin/Landlog/lists/', '登陆日志', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('98', 'Admin/Menu/repair/', '修复菜单结构', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('101', 'Admin/Landlog/clear/', '清除三个月前的记录', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('118', 'Plug_module/Plug_content/Plug_content/', '独立插件', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('136', 'Content/Position/edit/', '修改推荐位', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('137', 'Content/Position/delete/', '删除推荐位', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('138', 'Content/Position/listorder/', '排序', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('190', 'Plug/Block/lists/', '广告块', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('191', 'Plug/Block/add/', '添加广告块', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('192', 'Plug/Link/index/', '友情链接', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('193', 'Plug/Link/add/', '添加友情链接', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('194', 'Plug/Link/edit/', '修改友情链接', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('195', 'Plug/Link/delete/', '删除友情链接', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('196', 'Plug/Link/recommend/', '推荐友情链接', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('197', 'Plug/Link/listorder/', '排序', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('198', 'Plug/Block/edit/', '修改', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('199', 'Plug/Block/editBlock/', '编辑广告块', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('200', 'Plug/Block/addBlock/', '添加区块', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('201', 'Plug/Block/delBlock/', '删除区块', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('202', 'Plug/Block/delete/', '删除广告块', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('203', 'Plug/Block/listorder/', '广告块排序', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('269', 'Content/Settings/index/', '站点设置', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('288', 'Content/Nav/index/', '导航链接', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('289', 'Content/Nav/add/', '添加导航链接', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('290', 'Content/Nav/edit/', '修改导航链接', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('291', 'Content/Nav/delete/', '删除', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('292', 'Content/Nav/status/', '修改状态', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('293', 'Content/Nav/listorder/', '排序', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('314', 'Admin/Config/test_mail/', '邮件发送测试', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('331', 'Member/trading/trading_module/', '商品交易', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('349', 'Admin/Log/index/', '操作日志', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('350', 'Admin/Log/clear/', '清除三个余月前的日志', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('369', 'Member/Seller/confirmSettlement/', '确认结款', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('434', 'Admin/Menu/listorder/', '菜单排序', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('440', 'Plug/Uploads/creatTrumb/', '创建缩略图', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('443', 'Content/Category/seo/', '栏目SEO设置', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('445', 'Content/Sitemodel/index/', '内容模型', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('446', 'Content/Sitemodel/add/', '添加模型', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('447', 'Content/Sitemodelfield/index/', '内容模型字段', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('448', 'Content/Sitemodelfield/add/', '添加字段', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('449', 'Content/Sitemodelfield/public_priview/', '预览模型', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('450', 'Content/Index/publish/', '内容发布', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('451', 'Content/Content/index/', '管理内容', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('452', 'Content/Content/lists/', '内容列表', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('453', 'Content/Content/add/', '添加内容', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('454', 'Content/Sitemodel/import/', '导入模型', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('455', 'Content/Sitemodel/edit/', '编辑模型', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('456', 'Content/Sitemodel/delete/', '删除模型', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('457', 'Content/Sitemodel/disabled/', '修改模型状态', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('458', 'Content/Sitemodel/export/', '导出模型', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('459', 'Content/Sitemodelfield/edit/', '编辑字段', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('460', 'Content/Sitemodelfield/disabled/', '禁用字段', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('461', 'Content/Sitemodelfield/delete/', '删除字段', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('462', 'Content/Sitemodelfield/listorder/', '字段排序', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('463', 'Content/Content/edit/', '编辑内容', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('488', 'Admin/Group/categoryPrev/', '栏目权限', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('489', 'Admin/Group/constraint/', '权限约束', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('553', 'Admin/Database/index/', '数据库管理', '1', '1', '');
INSERT INTO `fc_auth_rule` VALUES ('554', 'Admin/Database/restore/', '备份还原', '1', '1', '');

-- ----------------------------
-- Table structure for fc_content_case
-- ----------------------------
DROP TABLE IF EXISTS `fc_content_case`;
CREATE TABLE `fc_content_case` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `catid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `title` char(80) NOT NULL DEFAULT '',
  `style` char(24) NOT NULL DEFAULT '',
  `thumb` char(100) NOT NULL DEFAULT '',
  `keywords` char(40) NOT NULL DEFAULT '',
  `description` char(255) NOT NULL DEFAULT '',
  `posids` varchar(30) NOT NULL DEFAULT '',
  `url` char(100) NOT NULL,
  `listorder` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1',
  `sysadd` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `islink` int(10) unsigned NOT NULL DEFAULT '0',
  `username` char(20) NOT NULL,
  `inputtime` int(10) unsigned NOT NULL DEFAULT '0',
  `updatetime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `status` (`status`,`listorder`,`id`),
  KEY `listorder` (`catid`,`status`,`listorder`,`id`),
  KEY `catid` (`catid`,`status`,`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of fc_content_case
-- ----------------------------

-- ----------------------------
-- Table structure for fc_content_case_data
-- ----------------------------
DROP TABLE IF EXISTS `fc_content_case_data`;
CREATE TABLE `fc_content_case_data` (
  `id` mediumint(8) unsigned DEFAULT '0',
  `content` text NOT NULL,
  `readpoint` smallint(5) unsigned NOT NULL DEFAULT '0',
  `groupids_view` varchar(100) NOT NULL,
  `paginationtype` tinyint(1) NOT NULL,
  `maxcharperpage` mediumint(6) NOT NULL,
  `template` varchar(30) NOT NULL,
  `paytype` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `allow_comment` tinyint(1) unsigned NOT NULL DEFAULT '1',
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of fc_content_case_data
-- ----------------------------

-- ----------------------------
-- Table structure for fc_content_category
-- ----------------------------
DROP TABLE IF EXISTS `fc_content_category`;
CREATE TABLE `fc_content_category` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `modelids` varchar(255) NOT NULL,
  `parentid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `name` varchar(30) NOT NULL,
  `style` varchar(35) NOT NULL,
  `child` tinyint(1) NOT NULL,
  `path` varchar(200) NOT NULL,
  `listorder` smallint(5) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL,
  `url` varchar(150) NOT NULL DEFAULT '',
  `type` varchar(100) NOT NULL,
  `display` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `template1` varchar(30) NOT NULL DEFAULT '' COMMENT '列表页模版',
  `template2` varchar(30) NOT NULL DEFAULT '' COMMENT '详细页模版',
  `template3` varchar(30) NOT NULL DEFAULT '',
  `template4` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parentid` (`parentid`,`listorder`),
  KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=35 DEFAULT CHARSET=utf8 COMMENT='内容栏目';

-- ----------------------------
-- Records of fc_content_category
-- ----------------------------
INSERT INTO `fc_content_category` VALUES ('1', ',1,', '0', '关于我们', '0', '0', '-0-', '93', '1', '/Home/Index/detail/catid/1/id/1.html', ',', '1', '', 'detail3', '', '');
INSERT INTO `fc_content_category` VALUES ('2', ',1,', '0', '新闻咨询', '0', '0', '-0-', '96', '1', '', 'seo,', '1', 'news_category', '', '', '');
INSERT INTO `fc_content_category` VALUES ('3', ',1,', '2', '公司新闻', '0', '0', '-0-2-', '99', '1', '', 'seo,', '1', 'news_list1', 'detail1', '', '');
INSERT INTO `fc_content_category` VALUES ('4', ',1,', '2', '行业新闻', '0', '0', '-0-2-', '98', '1', '', 'seo,', '1', 'news_list2', 'detail1', '', '');
INSERT INTO `fc_content_category` VALUES ('5', ',1,', '2', '媒体报道', '0', '0', '-0-2-', '97', '1', '', 'seo,', '1', 'news_list3', 'detail1', '', '');
INSERT INTO `fc_content_category` VALUES ('6', ',10,', '0', '公司产品', '0', '0', '-0-', '98', '1', '', 'seo,', '1', 'product_category', '', '', '');
INSERT INTO `fc_content_category` VALUES ('7', ',10,', '6', '商城系统', '0', '0', '-0-6-', '0', '1', '', 'seo,', '1', 'product_list3', 'detail2', '', '');
INSERT INTO `fc_content_category` VALUES ('8', ',10,', '6', '管理系统', '0', '0', '-0-6-', '0', '1', '', 'seo,', '1', 'product_list2', 'detail2', '', '');
INSERT INTO `fc_content_category` VALUES ('9', ',10,', '6', '公司行网', '0', '0', '-0-6-', '0', '1', '', 'seo,', '1', 'product_list1', 'detail2', '', '');
INSERT INTO `fc_content_category` VALUES ('10', ',11,', '0', '项目案例', '0', '0', '-0-', '97', '1', '', 'seo,', '1', 'case_category', '', '', '');
INSERT INTO `fc_content_category` VALUES ('11', ',11,', '10', '商城系统', '0', '0', '-0-10-', '0', '1', '', 'seo,', '1', 'case_list1', 'detail1', '', '');
INSERT INTO `fc_content_category` VALUES ('12', ',11,', '10', '管理系统', '0', '0', '-0-10-', '0', '1', '', 'seo,', '1', 'case_list2', 'detail1', '', '');
INSERT INTO `fc_content_category` VALUES ('13', ',11,', '10', '公司行网', '0', '0', '-0-10-', '0', '1', '', 'seo,', '1', 'case_list1', 'detail1', '', '');
INSERT INTO `fc_content_category` VALUES ('14', ',1,', '0', '服务中心', '0', '0', '-0-', '95', '1', '', 'seo,', '1', 'service_category', '', '', '');
INSERT INTO `fc_content_category` VALUES ('15', ',1,', '14', '常见问题', '0', '0', '-0-14-', '0', '1', '', 'seo,', '1', 'service_list1', '', '', '');
INSERT INTO `fc_content_category` VALUES ('16', ',,', '0', '社区', '0', '0', '-0-', '94', '1', 'http://www.thinkerphp.com/forum.php?gid=1', ',', '1', '', '', '', '');

-- ----------------------------
-- Table structure for fc_content_category_priv
-- ----------------------------
DROP TABLE IF EXISTS `fc_content_category_priv`;
CREATE TABLE `fc_content_category_priv` (
  `grpid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `view` mediumtext,
  `add` mediumtext COMMENT '添加',
  `edit` mediumtext COMMENT '编辑',
  `delete` mediumtext COMMENT '删除',
  `examine` mediumtext COMMENT '审核',
  PRIMARY KEY (`grpid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='栏目权限';

-- ----------------------------
-- Records of fc_content_category_priv
-- ----------------------------

-- ----------------------------
-- Table structure for fc_content_category_seo
-- ----------------------------
DROP TABLE IF EXISTS `fc_content_category_seo`;
CREATE TABLE `fc_content_category_seo` (
  `catid` smallint(5) unsigned NOT NULL,
  `seo_title` varchar(100) NOT NULL,
  `seo_keywords` varchar(50) NOT NULL,
  `seo_description` varchar(250) NOT NULL,
  PRIMARY KEY (`catid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='栏目seo属性';

-- ----------------------------
-- Records of fc_content_category_seo
-- ----------------------------
INSERT INTO `fc_content_category_seo` VALUES ('2', '', '', '');

-- ----------------------------
-- Table structure for fc_content_model
-- ----------------------------
DROP TABLE IF EXISTS `fc_content_model`;
CREATE TABLE `fc_content_model` (
  `modelid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(30) NOT NULL,
  `description` char(100) NOT NULL,
  `tablename` char(20) NOT NULL,
  `setting` text NOT NULL,
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `items` smallint(5) unsigned NOT NULL DEFAULT '0',
  `enablesearch` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `disabled` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `sort` tinyint(3) NOT NULL,
  `type` tinyint(1) NOT NULL,
  PRIMARY KEY (`modelid`),
  KEY `type` (`type`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='内容模型';

-- ----------------------------
-- Records of fc_content_model
-- ----------------------------
INSERT INTO `fc_content_model` VALUES ('1', '文章', '普通文章、新闻', 'news', '', '0', '0', '1', '0', '0', '0');
INSERT INTO `fc_content_model` VALUES ('10', '产品', '公司软件产品以及解决方案', 'product', '', '0', '0', '1', '0', '0', '0');
INSERT INTO `fc_content_model` VALUES ('11', '案例', '公司开发案例', 'case', '', '0', '0', '1', '0', '0', '0');

-- ----------------------------
-- Table structure for fc_content_model_field
-- ----------------------------
DROP TABLE IF EXISTS `fc_content_model_field`;
CREATE TABLE `fc_content_model_field` (
  `fieldid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `modelid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `field` varchar(20) NOT NULL,
  `name` varchar(30) NOT NULL,
  `tips` text NOT NULL,
  `css` varchar(30) NOT NULL,
  `minlength` int(10) unsigned NOT NULL DEFAULT '0',
  `maxlength` int(10) unsigned NOT NULL DEFAULT '0',
  `pattern` varchar(255) NOT NULL,
  `errortips` varchar(255) NOT NULL,
  `formtype` varchar(20) NOT NULL,
  `setting` mediumtext NOT NULL,
  `formattribute` varchar(255) NOT NULL,
  `unsetgroupids` varchar(255) NOT NULL,
  `unsetroleids` varchar(255) NOT NULL,
  `iscore` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `issystem` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `isunique` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `isbase` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `issearch` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `isadd` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `isfulltext` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `isposition` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `listorder` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `disabled` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `isomnipotent` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`fieldid`),
  KEY `modelid` (`modelid`,`disabled`),
  KEY `field` (`field`,`modelid`)
) ENGINE=MyISAM AUTO_INCREMENT=230 DEFAULT CHARSET=utf8 COMMENT='内容模型字段';

-- ----------------------------
-- Records of fc_content_model_field
-- ----------------------------
INSERT INTO `fc_content_model_field` VALUES ('1', '1', 'catid', '栏目', '', '', '1', '6', '/^[0-9]{1,6}$/', '请选择栏目', 'catid', 'array (\n  \'defaultvalue\' => \'\',\n)', '', '-99', '-99', '0', '1', '0', '1', '1', '1', '0', '0', '1', '0', '0');
INSERT INTO `fc_content_model_field` VALUES ('2', '1', 'title', '标题', '', 'inputtitle', '1', '150', '', '请输入标题', 'title', '', '', '', '', '0', '1', '0', '1', '1', '1', '1', '1', '4', '0', '0');
INSERT INTO `fc_content_model_field` VALUES ('3', '1', 'keywords', '关键词', '多关键词之间用空格或者“,”隔开', '', '0', '40', '', '', 'keyword', 'array (\r\n  \'size\' => \'100\',\r\n  \'defaultvalue\' => \'\',\r\n)', '', '-99', '-99', '0', '1', '0', '1', '1', '1', '1', '0', '7', '0', '0');
INSERT INTO `fc_content_model_field` VALUES ('4', '1', 'description', '摘要', '', '', '0', '255', '', '', 'textarea', 'array (\r\n  \'width\' => \'98\',\r\n  \'height\' => \'46\',\r\n  \'defaultvalue\' => \'\',\r\n  \'enablehtml\' => \'0\',\r\n)', '', '', '', '0', '1', '0', '1', '0', '1', '1', '1', '10', '0', '0');
INSERT INTO `fc_content_model_field` VALUES ('5', '1', 'updatetime', '更新时间', '', '', '0', '0', '', '', 'datetime', 'array (\r\n  \'dateformat\' => \'int\',\r\n  \'format\' => \'Y-m-d H:i:s\',\r\n  \'defaulttype\' => \'1\',\r\n  \'defaultvalue\' => \'\',\r\n)', '', '', '', '1', '1', '0', '1', '0', '0', '0', '0', '12', '0', '0');
INSERT INTO `fc_content_model_field` VALUES ('6', '1', 'content', '内容', '<div class=\"content_attr\"><label><input name=\"add_introduce\" type=\"checkbox\"  value=\"1\" checked>是否截取内容</label><input type=\"text\" name=\"introcude_length\" value=\"200\" size=\"3\">字符至内容摘要</div>', '', '1', '999999', '', '内容不能为空', 'editor', 'array (\n  \'toolbar\' => \'full\',\n  \'defaultvalue\' => \'\',\n  \'enablekeylink\' => \'1\',\n  \'replacenum\' => \'2\',\n  \'link_mode\' => \'0\',\n  \'enablesaveimage\' => \'1\',\n)', '', '', '', '0', '0', '0', '1', '0', '1', '1', '0', '13', '0', '0');
INSERT INTO `fc_content_model_field` VALUES ('7', '1', 'thumb', '缩略图', '', '', '0', '100', '', '', 'image', 'array (\n  \'size\' => \'50\',\n  \'defaultvalue\' => \'\',\n  \'show_type\' => \'1\',\n  \'upload_maxsize\' => \'1024\',\n  \'upload_allowext\' => \'jpg|jpeg|gif|png|bmp\',\n  \'watermark\' => \'0\',\n  \'isselectimage\' => \'1\',\n  \'images_width\' => \'\',\n  \'images_height\' => \'\',\n)', '', '', '', '0', '1', '0', '0', '0', '1', '0', '1', '14', '0', '0');
INSERT INTO `fc_content_model_field` VALUES ('8', '1', 'pages', '分页方式', '', '', '0', '0', '', '', 'pages', '', '', '-99', '-99', '0', '0', '0', '1', '0', '0', '0', '0', '16', '1', '0');
INSERT INTO `fc_content_model_field` VALUES ('9', '1', 'inputtime', '发布时间', '', '', '0', '0', '', '', 'datetime', 'array (\n  \'fieldtype\' => \'int\',\n  \'format\' => \'Y-m-d H:i:s\',\n  \'defaulttype\' => \'0\',\n)', '', '', '', '0', '1', '0', '0', '0', '0', '0', '1', '17', '0', '0');
INSERT INTO `fc_content_model_field` VALUES ('10', '1', 'posids', '推荐位', '', '', '0', '0', '', '', 'posid', 'array (\n  \'cols\' => \'4\',\n  \'width\' => \'125\',\n)', '', '', '', '0', '1', '0', '1', '0', '0', '0', '0', '18', '0', '0');
INSERT INTO `fc_content_model_field` VALUES ('11', '1', 'url', 'URL', '', '', '0', '100', '', '', 'text', 'array (\n  \\\'size\\\' => \\\'\\\',\n  \\\'defaultvalue\\\' => \\\'\\\',\n  \\\'ispassword\\\' => \\\'0\\\',\n)', '', '', '', '1', '1', '0', '1', '0', '0', '0', '0', '50', '0', '0');
INSERT INTO `fc_content_model_field` VALUES ('12', '1', 'listorder', '排序', '', '', '0', '6', '', '', 'number', '', '', '', '', '1', '1', '0', '1', '0', '0', '0', '0', '51', '0', '0');
INSERT INTO `fc_content_model_field` VALUES ('13', '1', 'template', '内容页模板', '', '', '0', '30', '', '', 'template', 'array (\n  \'size\' => \'\',\n  \'defaultvalue\' => \'\',\n)', '', '-99', '-99', '0', '0', '0', '0', '0', '0', '0', '0', '53', '0', '0');
INSERT INTO `fc_content_model_field` VALUES ('14', '1', 'allow_comment', '允许评论', '', '', '0', '0', '', '', 'box', 'array (\n  \'options\' => \'允许评论|1\r\n不允许评论|0\',\n  \'boxtype\' => \'radio\',\n  \'fieldtype\' => \'tinyint\',\n  \'minnumber\' => \'1\',\n  \'width\' => \'88\',\n  \'size\' => \'1\',\n  \'defaultvalue\' => \'1\',\n  \'outputtype\' => \'0\',\n)', '', '', '', '0', '0', '0', '0', '0', '0', '0', '0', '54', '1', '0');
INSERT INTO `fc_content_model_field` VALUES ('15', '1', 'status', '状态', '', '', '0', '2', '', '', 'box', '', '', '', '', '1', '1', '0', '1', '0', '0', '0', '0', '55', '0', '0');
INSERT INTO `fc_content_model_field` VALUES ('16', '1', 'readpoint', '阅读收费', '', '', '0', '5', '', '', 'readpoint', 'array (\n  \'minnumber\' => \'1\',\n  \'maxnumber\' => \'99999\',\n  \'decimaldigits\' => \'0\',\n  \'defaultvalue\' => \'\',\n)', '', '-99', '-99', '0', '0', '0', '0', '0', '0', '0', '0', '55', '1', '0');
INSERT INTO `fc_content_model_field` VALUES ('17', '1', 'username', '用户名', '', '', '0', '20', '', '', 'text', '', '', '', '', '1', '1', '0', '1', '0', '0', '0', '0', '98', '0', '0');
INSERT INTO `fc_content_model_field` VALUES ('18', '1', 'islink', '转向链接', '', '', '0', '0', '', '', 'islink', 'array (\n  \'size\' => \'\',\n)', '', '', '', '0', '1', '0', '0', '0', '1', '0', '0', '20', '1', '0');
INSERT INTO `fc_content_model_field` VALUES ('194', '10', 'catid', '栏目', '', '', '1', '6', '/^[0-9]{1,6}$/', '请选择栏目', 'catid', 'array (\n  \'defaultvalue\' => \'\',\n)', '', '-99', '-99', '0', '1', '0', '1', '1', '1', '0', '0', '1', '0', '0');
INSERT INTO `fc_content_model_field` VALUES ('195', '10', 'title', '标题', '', 'inputtitle', '1', '80', '', '请输入标题', 'title', '', '', '', '', '0', '1', '0', '1', '1', '1', '1', '1', '4', '0', '0');
INSERT INTO `fc_content_model_field` VALUES ('196', '10', 'keywords', '关键词', '多关键词之间用空格或者“,”隔开', '', '0', '40', '', '', 'keyword', 'array (\r\n  \'size\' => \'100\',\r\n  \'defaultvalue\' => \'\',\r\n)', '', '-99', '-99', '0', '1', '0', '1', '1', '1', '1', '0', '7', '0', '0');
INSERT INTO `fc_content_model_field` VALUES ('197', '10', 'description', '摘要', '', '', '0', '255', '', '', 'textarea', 'array (\r\n  \'width\' => \'98\',\r\n  \'height\' => \'46\',\r\n  \'defaultvalue\' => \'\',\r\n  \'enablehtml\' => \'0\',\r\n)', '', '', '', '0', '1', '0', '1', '0', '1', '1', '1', '10', '0', '0');
INSERT INTO `fc_content_model_field` VALUES ('198', '10', 'updatetime', '更新时间', '', '', '0', '0', '', '', 'datetime', 'array (\r\n  \'dateformat\' => \'int\',\r\n  \'format\' => \'Y-m-d H:i:s\',\r\n  \'defaulttype\' => \'1\',\r\n  \'defaultvalue\' => \'\',\r\n)', '', '', '', '1', '1', '0', '1', '0', '0', '0', '0', '12', '0', '0');
INSERT INTO `fc_content_model_field` VALUES ('199', '10', 'content', '内容', '<div class=\"content_attr\"><label><input name=\"add_introduce\" type=\"checkbox\"  value=\"1\" checked>是否截取内容</label><input type=\"text\" name=\"introcude_length\" value=\"200\" size=\"3\">字符至内容摘要</div>', '', '1', '999999', '', '内容不能为空', 'editor', 'array (\n  \'toolbar\' => \'full\',\n  \'defaultvalue\' => \'\',\n  \'enablekeylink\' => \'1\',\n  \'replacenum\' => \'2\',\n  \'link_mode\' => \'0\',\n  \'enablesaveimage\' => \'1\',\n)', '', '', '', '0', '0', '0', '1', '0', '1', '1', '0', '13', '0', '0');
INSERT INTO `fc_content_model_field` VALUES ('200', '10', 'thumb', '缩略图', '', '', '0', '100', '', '', 'image', 'array (\n  \'size\' => \'50\',\n  \'defaultvalue\' => \'\',\n  \'show_type\' => \'1\',\n  \'upload_maxsize\' => \'1024\',\n  \'upload_allowext\' => \'jpg|jpeg|gif|png|bmp\',\n  \'watermark\' => \'0\',\n  \'isselectimage\' => \'1\',\n  \'images_width\' => \'\',\n  \'images_height\' => \'\',\n)', '', '', '', '0', '1', '0', '0', '0', '1', '0', '1', '14', '0', '0');
INSERT INTO `fc_content_model_field` VALUES ('201', '10', 'pages', '分页方式', '', '', '0', '0', '', '', 'pages', '', '', '-99', '-99', '0', '0', '0', '1', '0', '0', '0', '0', '16', '1', '0');
INSERT INTO `fc_content_model_field` VALUES ('202', '10', 'inputtime', '发布时间', '', '', '0', '0', '', '', 'datetime', 'array (\n  \'fieldtype\' => \'int\',\n  \'format\' => \'Y-m-d H:i:s\',\n  \'defaulttype\' => \'0\',\n)', '', '', '', '0', '1', '0', '0', '0', '0', '0', '1', '17', '0', '0');
INSERT INTO `fc_content_model_field` VALUES ('203', '10', 'posids', '推荐位', '', '', '0', '0', '', '', 'posid', 'array (\n  \'cols\' => \'4\',\n  \'width\' => \'125\',\n)', '', '', '', '0', '1', '0', '1', '0', '0', '0', '0', '18', '0', '0');
INSERT INTO `fc_content_model_field` VALUES ('204', '10', 'url', 'URL', '', '', '0', '100', '', '', 'text', '', '', '', '', '1', '1', '0', '1', '0', '0', '0', '0', '50', '0', '0');
INSERT INTO `fc_content_model_field` VALUES ('205', '10', 'listorder', '排序', '', '', '0', '6', '', '', 'number', '', '', '', '', '1', '1', '0', '1', '0', '0', '0', '0', '51', '0', '0');
INSERT INTO `fc_content_model_field` VALUES ('206', '10', 'template', '内容页模板', '', '', '0', '30', '', '', 'template', 'array (\n  \'size\' => \'\',\n  \'defaultvalue\' => \'\',\n)', '', '-99', '-99', '0', '0', '0', '0', '0', '0', '0', '0', '53', '0', '0');
INSERT INTO `fc_content_model_field` VALUES ('207', '10', 'allow_comment', '允许评论', '', '', '0', '0', '', '', 'box', 'array (\n  \'options\' => \'允许评论|1\r\n不允许评论|0\',\n  \'boxtype\' => \'radio\',\n  \'fieldtype\' => \'tinyint\',\n  \'minnumber\' => \'1\',\n  \'width\' => \'88\',\n  \'size\' => \'1\',\n  \'defaultvalue\' => \'1\',\n  \'outputtype\' => \'0\',\n)', '', '', '', '0', '0', '0', '0', '0', '0', '0', '0', '54', '1', '0');
INSERT INTO `fc_content_model_field` VALUES ('208', '10', 'status', '状态', '', '', '0', '2', '', '', 'box', '', '', '', '', '1', '1', '0', '1', '0', '0', '0', '0', '55', '0', '0');
INSERT INTO `fc_content_model_field` VALUES ('209', '10', 'readpoint', '阅读收费', '', '', '0', '5', '', '', 'readpoint', 'array (\n  \'minnumber\' => \'1\',\n  \'maxnumber\' => \'99999\',\n  \'decimaldigits\' => \'0\',\n  \'defaultvalue\' => \'\',\n)', '', '-99', '-99', '0', '0', '0', '0', '0', '0', '0', '0', '55', '1', '0');
INSERT INTO `fc_content_model_field` VALUES ('210', '10', 'username', '用户名', '', '', '0', '20', '', '', 'text', '', '', '', '', '1', '1', '0', '1', '0', '0', '0', '0', '98', '0', '0');
INSERT INTO `fc_content_model_field` VALUES ('211', '10', 'islink', '转向链接', '', '', '0', '0', '', '', 'islink', 'array (\n  \'size\' => \'\',\n)', '', '', '', '0', '1', '0', '0', '0', '1', '0', '0', '20', '0', '0');
INSERT INTO `fc_content_model_field` VALUES ('212', '11', 'catid', '栏目', '', '', '1', '6', '/^[0-9]{1,6}$/', '请选择栏目', 'catid', 'array (\n  \'defaultvalue\' => \'\',\n)', '', '-99', '-99', '0', '1', '0', '1', '1', '1', '0', '0', '1', '0', '0');
INSERT INTO `fc_content_model_field` VALUES ('213', '11', 'title', '标题', '', 'inputtitle', '1', '80', '', '请输入标题', 'title', '', '', '', '', '0', '1', '0', '1', '1', '1', '1', '1', '4', '0', '0');
INSERT INTO `fc_content_model_field` VALUES ('214', '11', 'keywords', '关键词', '多关键词之间用空格或者“,”隔开', '', '0', '40', '', '', 'keyword', 'array (\r\n  \'size\' => \'100\',\r\n  \'defaultvalue\' => \'\',\r\n)', '', '-99', '-99', '0', '1', '0', '1', '1', '1', '1', '0', '7', '0', '0');
INSERT INTO `fc_content_model_field` VALUES ('215', '11', 'description', '摘要', '', '', '0', '255', '', '', 'textarea', 'array (\r\n  \'width\' => \'98\',\r\n  \'height\' => \'46\',\r\n  \'defaultvalue\' => \'\',\r\n  \'enablehtml\' => \'0\',\r\n)', '', '', '', '0', '1', '0', '1', '0', '1', '1', '1', '10', '0', '0');
INSERT INTO `fc_content_model_field` VALUES ('216', '11', 'updatetime', '更新时间', '', '', '0', '0', '', '', 'datetime', 'array (\r\n  \'dateformat\' => \'int\',\r\n  \'format\' => \'Y-m-d H:i:s\',\r\n  \'defaulttype\' => \'1\',\r\n  \'defaultvalue\' => \'\',\r\n)', '', '', '', '1', '1', '0', '1', '0', '0', '0', '0', '12', '0', '0');
INSERT INTO `fc_content_model_field` VALUES ('217', '11', 'content', '内容', '<div class=\"content_attr\"><label><input name=\"add_introduce\" type=\"checkbox\"  value=\"1\" checked>是否截取内容</label><input type=\"text\" name=\"introcude_length\" value=\"200\" size=\"3\">字符至内容摘要</div>', '', '1', '999999', '', '内容不能为空', 'editor', 'array (\n  \'toolbar\' => \'full\',\n  \'defaultvalue\' => \'\',\n  \'enablekeylink\' => \'1\',\n  \'replacenum\' => \'2\',\n  \'link_mode\' => \'0\',\n  \'enablesaveimage\' => \'1\',\n)', '', '', '', '0', '0', '0', '1', '0', '1', '1', '0', '13', '0', '0');
INSERT INTO `fc_content_model_field` VALUES ('218', '11', 'thumb', '缩略图', '', '', '0', '100', '', '', 'image', 'array (\n  \'size\' => \'50\',\n  \'defaultvalue\' => \'\',\n  \'show_type\' => \'1\',\n  \'upload_maxsize\' => \'1024\',\n  \'upload_allowext\' => \'jpg|jpeg|gif|png|bmp\',\n  \'watermark\' => \'0\',\n  \'isselectimage\' => \'1\',\n  \'images_width\' => \'\',\n  \'images_height\' => \'\',\n)', '', '', '', '0', '1', '0', '0', '0', '1', '0', '1', '14', '0', '0');
INSERT INTO `fc_content_model_field` VALUES ('219', '11', 'pages', '分页方式', '', '', '0', '0', '', '', 'pages', '', '', '-99', '-99', '0', '0', '0', '1', '0', '0', '0', '0', '16', '1', '0');
INSERT INTO `fc_content_model_field` VALUES ('220', '11', 'inputtime', '发布时间', '', '', '0', '0', '', '', 'datetime', 'array (\n  \'fieldtype\' => \'int\',\n  \'format\' => \'Y-m-d H:i:s\',\n  \'defaulttype\' => \'0\',\n)', '', '', '', '0', '1', '0', '0', '0', '0', '0', '1', '17', '0', '0');
INSERT INTO `fc_content_model_field` VALUES ('221', '11', 'posids', '推荐位', '', '', '0', '0', '', '', 'posid', 'array (\n  \'cols\' => \'4\',\n  \'width\' => \'125\',\n)', '', '', '', '0', '1', '0', '1', '0', '0', '0', '0', '18', '0', '0');
INSERT INTO `fc_content_model_field` VALUES ('222', '11', 'url', 'URL', '', '', '0', '100', '', '', 'text', '', '', '', '', '1', '1', '0', '1', '0', '0', '0', '0', '50', '0', '0');
INSERT INTO `fc_content_model_field` VALUES ('223', '11', 'listorder', '排序', '', '', '0', '6', '', '', 'number', '', '', '', '', '1', '1', '0', '1', '0', '0', '0', '0', '51', '0', '0');
INSERT INTO `fc_content_model_field` VALUES ('224', '11', 'template', '内容页模板', '', '', '0', '30', '', '', 'template', 'array (\n  \'size\' => \'\',\n  \'defaultvalue\' => \'\',\n)', '', '-99', '-99', '0', '0', '0', '0', '0', '0', '0', '0', '53', '0', '0');
INSERT INTO `fc_content_model_field` VALUES ('225', '11', 'allow_comment', '允许评论', '', '', '0', '0', '', '', 'box', 'array (\n  \'options\' => \'允许评论|1\r\n不允许评论|0\',\n  \'boxtype\' => \'radio\',\n  \'fieldtype\' => \'tinyint\',\n  \'minnumber\' => \'1\',\n  \'width\' => \'88\',\n  \'size\' => \'1\',\n  \'defaultvalue\' => \'1\',\n  \'outputtype\' => \'0\',\n)', '', '', '', '0', '0', '0', '0', '0', '0', '0', '0', '54', '1', '0');
INSERT INTO `fc_content_model_field` VALUES ('226', '11', 'status', '状态', '', '', '0', '2', '', '', 'box', '', '', '', '', '1', '1', '0', '1', '0', '0', '0', '0', '55', '0', '0');
INSERT INTO `fc_content_model_field` VALUES ('227', '11', 'readpoint', '阅读收费', '', '', '0', '5', '', '', 'readpoint', 'array (\n  \'minnumber\' => \'1\',\n  \'maxnumber\' => \'99999\',\n  \'decimaldigits\' => \'0\',\n  \'defaultvalue\' => \'\',\n)', '', '-99', '-99', '0', '0', '0', '0', '0', '0', '0', '0', '55', '1', '0');
INSERT INTO `fc_content_model_field` VALUES ('228', '11', 'username', '用户名', '', '', '0', '20', '', '', 'text', '', '', '', '', '1', '1', '0', '1', '0', '0', '0', '0', '98', '0', '0');
INSERT INTO `fc_content_model_field` VALUES ('229', '11', 'islink', '转向链接', '', '', '0', '0', '', '', 'islink', 'array (\n  \'size\' => \'\',\n)', '', '', '', '0', '1', '0', '0', '0', '1', '0', '0', '20', '0', '0');

-- ----------------------------
-- Table structure for fc_content_nav
-- ----------------------------
DROP TABLE IF EXISTS `fc_content_nav`;
CREATE TABLE `fc_content_nav` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `url` varchar(100) NOT NULL,
  `name` varchar(50) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `listorder` mediumint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='网站导航';

-- ----------------------------
-- Records of fc_content_nav
-- ----------------------------
INSERT INTO `fc_content_nav` VALUES ('2', '/Home/Index/detail/catid/1/id/3.html', '人才招聘', '1', '5');
INSERT INTO `fc_content_nav` VALUES ('3', '/Home/Index/lists/catid/2.html', '新闻资讯', '1', '4');
INSERT INTO `fc_content_nav` VALUES ('4', '/Home/Index/lists/catid/6.html', '产品中心', '1', '3');
INSERT INTO `fc_content_nav` VALUES ('5', '/Home/Index/lists/catid/10.html', '项目案例', '1', '2');
INSERT INTO `fc_content_nav` VALUES ('6', '/Home/Index/lists/catid/15.html', '常见问题', '1', '1');

-- ----------------------------
-- Table structure for fc_content_news
-- ----------------------------
DROP TABLE IF EXISTS `fc_content_news`;
CREATE TABLE `fc_content_news` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `catid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `title` varchar(150) NOT NULL DEFAULT '',
  `style` char(24) NOT NULL DEFAULT '',
  `thumb` varchar(100) NOT NULL DEFAULT '',
  `keywords` char(40) NOT NULL DEFAULT '',
  `description` char(255) NOT NULL DEFAULT '',
  `posids` varchar(30) NOT NULL DEFAULT '',
  `url` varchar(100) NOT NULL DEFAULT '',
  `listorder` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1',
  `sysadd` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `islink` int(10) unsigned NOT NULL DEFAULT '0',
  `username` char(20) NOT NULL,
  `inputtime` int(10) unsigned NOT NULL DEFAULT '0',
  `disdel` tinyint(1) NOT NULL DEFAULT '0',
  `updatetime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `status` (`status`,`listorder`,`id`),
  KEY `listorder` (`catid`,`status`,`listorder`,`id`),
  KEY `catid` (`catid`,`status`,`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of fc_content_news
-- ----------------------------
INSERT INTO `fc_content_news` VALUES ('1', '1', '企业文化', '', '', '', '尽心力、近未来:尽心尽力为客户选择和提供有价值的理财渠道，尽心尽力服务于每一位客户，尽心尽力做好每一件事情，是中融文化的精髓。我们', ',0,', '', '0', '99', '0', '0', 'thinker', '1455965186', '0', '1455975297');
INSERT INTO `fc_content_news` VALUES ('2', '1', '发展历程', '', '', '', '发展历程', ',0,', '', '0', '99', '0', '0', 'thinker', '1455975251', '0', '1455975251');
INSERT INTO `fc_content_news` VALUES ('3', '1', '诚聘英才', '', '', '', '诚聘英才', ',0,', '', '0', '99', '0', '0', 'thinker', '1455975278', '0', '1455975278');
INSERT INTO `fc_content_news` VALUES ('4', '1', '联系我们', '', '', '', '联系我们', ',0,', '', '0', '99', '0', '0', 'thinker', '1455975289', '0', '1455975289');
INSERT INTO `fc_content_news` VALUES ('5', '3', '公司新闻标题公司新闻标题公司新闻标题公司新闻标题公司新闻标题', '', '', '', '公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内', ',0,', '', '0', '99', '0', '0', 'thinker', '1455979610', '0', '1455979610');
INSERT INTO `fc_content_news` VALUES ('6', '3', '公司新闻标题公司新闻标题公司新闻标题公司新闻标题公司新闻标题', '', '/Uploads/files/2016/0406/57051548728f3.jpg', '', '公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内', ',0,', '', '0', '99', '0', '0', 'thinker', '1455979643', '0', '1459951046');

-- ----------------------------
-- Table structure for fc_content_news_data
-- ----------------------------
DROP TABLE IF EXISTS `fc_content_news_data`;
CREATE TABLE `fc_content_news_data` (
  `id` mediumint(8) unsigned DEFAULT '0',
  `content` text NOT NULL,
  `readpoint` smallint(5) unsigned NOT NULL DEFAULT '0',
  `groupids_view` varchar(100) NOT NULL,
  `paginationtype` tinyint(1) NOT NULL,
  `maxcharperpage` mediumint(6) NOT NULL,
  `template` varchar(30) NOT NULL,
  `paytype` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `allow_comment` tinyint(1) unsigned NOT NULL DEFAULT '1',
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of fc_content_news_data
-- ----------------------------
INSERT INTO `fc_content_news_data` VALUES ('1', '<p style=\"margin-top: 5px; text-indent: 2em;\">&nbsp;<img src=\"/Uploads/article/image/2016/0220/56c844ad87b38.jpg\" title=\"56c844ad87b38.jpg\" alt=\"qywh.jpg\" style=\"float: right;\"/>尽心力、近未来:尽心尽力为客户选择和提供有价值的理财渠道，尽心尽力服务于每一位客户，尽心尽力做好每一件事情，是中融文化的精髓。我们坚信做任何事情只要做到尽心尽力，一定会无限接近美好的未来。<br/></p><p style=\"text-indent: 2em;\">企业愿景：与客户共享全球经济发展带来的价值。<br/></p><p style=\"text-align: left; text-indent: 2em;\">企业使命：成为大中华区最具价值的资本投资运营平台。<br/></p><p style=\"text-indent: 2em;\">企业精神：专业诚信、合作共赢、持续成长。<br/></p><p style=\"text-indent: 2em;\">企业价值观：为高净值人士提供专业的、可信赖的投资管理与资产增值综合服务，助力客户成长。<br/></p><p style=\"text-indent: 2em;\">服务理念：中融始终秉承以客户利益最大化的服务理念。<br/></p><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;服务项目：在全球范围内为客户提供风险可控、收益稳定的具有高价值的投资平台。最终满足客户资产保值增值的目的。</p><p><br/></p>', '0', '', '0', '0', '', '0', '1');
INSERT INTO `fc_content_news_data` VALUES ('2', '<p>发展历程</p>', '0', '', '0', '0', '', '0', '1');
INSERT INTO `fc_content_news_data` VALUES ('3', '<p>诚聘英才</p>', '0', '', '0', '0', '', '0', '1');
INSERT INTO `fc_content_news_data` VALUES ('4', '<p>联系我们</p>', '0', '', '0', '0', '', '0', '1');
INSERT INTO `fc_content_news_data` VALUES ('5', '<p>公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容</p>', '0', '', '0', '0', '', '0', '1');
INSERT INTO `fc_content_news_data` VALUES ('6', '<p>公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容公司新闻内容</p>', '0', '', '0', '0', '', '0', '1');

-- ----------------------------
-- Table structure for fc_content_position
-- ----------------------------
DROP TABLE IF EXISTS `fc_content_position`;
CREATE TABLE `fc_content_position` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `modelid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `catid` int(8) DEFAULT NULL,
  `name` char(30) NOT NULL DEFAULT '',
  `listorder` smallint(5) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=39 DEFAULT CHARSET=utf8 COMMENT='推荐位';

-- ----------------------------
-- Records of fc_content_position
-- ----------------------------
INSERT INTO `fc_content_position` VALUES ('36', '1', null, '首页', '0', '1');
INSERT INTO `fc_content_position` VALUES ('34', '1', null, '图文头条', '0', '1');

-- ----------------------------
-- Table structure for fc_content_product
-- ----------------------------
DROP TABLE IF EXISTS `fc_content_product`;
CREATE TABLE `fc_content_product` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `catid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `title` char(80) NOT NULL DEFAULT '',
  `style` char(24) NOT NULL DEFAULT '',
  `thumb` char(100) NOT NULL DEFAULT '',
  `keywords` char(40) NOT NULL DEFAULT '',
  `description` char(255) NOT NULL DEFAULT '',
  `posids` varchar(30) NOT NULL DEFAULT '',
  `url` char(100) NOT NULL,
  `listorder` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1',
  `sysadd` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `islink` int(10) unsigned NOT NULL DEFAULT '0',
  `username` char(20) NOT NULL,
  `inputtime` int(10) unsigned NOT NULL DEFAULT '0',
  `updatetime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `status` (`status`,`listorder`,`id`),
  KEY `listorder` (`catid`,`status`,`listorder`,`id`),
  KEY `catid` (`catid`,`status`,`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of fc_content_product
-- ----------------------------
INSERT INTO `fc_content_product` VALUES ('1', '7', 'thinkershop', '', '/Uploads/files/2016/0406/57051548e273b.jpg', '', 'thinkershop', ',0,', '', '0', '99', '0', '0', 'thinker', '1456027500', '1459951035');
INSERT INTO `fc_content_product` VALUES ('2', '7', 'ECSHOP', '', '/Uploads/files/2016/0406/570515494cddb.jpg', '', 'ECSHOP', ',0,', '', '0', '99', '0', '0', 'thinker', '1456027521', '1459951030');

-- ----------------------------
-- Table structure for fc_content_product_data
-- ----------------------------
DROP TABLE IF EXISTS `fc_content_product_data`;
CREATE TABLE `fc_content_product_data` (
  `id` mediumint(8) unsigned DEFAULT '0',
  `content` text NOT NULL,
  `readpoint` smallint(5) unsigned NOT NULL DEFAULT '0',
  `groupids_view` varchar(100) NOT NULL,
  `paginationtype` tinyint(1) NOT NULL,
  `maxcharperpage` mediumint(6) NOT NULL,
  `template` varchar(30) NOT NULL,
  `paytype` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `allow_comment` tinyint(1) unsigned NOT NULL DEFAULT '1',
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of fc_content_product_data
-- ----------------------------
INSERT INTO `fc_content_product_data` VALUES ('1', '<p>thinkershop</p>', '0', '', '0', '0', '', '0', '1');
INSERT INTO `fc_content_product_data` VALUES ('2', '<p>ECSHOP</p>', '0', '', '0', '0', '', '0', '1');

-- ----------------------------
-- Table structure for fc_plug_block
-- ----------------------------
DROP TABLE IF EXISTS `fc_plug_block`;
CREATE TABLE `fc_plug_block` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `modelid` smallint(5) unsigned DEFAULT '0',
  `name` char(30) NOT NULL DEFAULT '',
  `group` varchar(150) NOT NULL DEFAULT '',
  `listorder` smallint(5) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `smallblocks` tinytext COMMENT '大区块中的小区块',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='广告块';

-- ----------------------------
-- Records of fc_plug_block
-- ----------------------------
INSERT INTO `fc_plug_block` VALUES ('1', '0', 'PC首页顶部大幅焦点图', 'PC站', '0', '1', null);

-- ----------------------------
-- Table structure for fc_plug_block_content
-- ----------------------------
DROP TABLE IF EXISTS `fc_plug_block_content`;
CREATE TABLE `fc_plug_block_content` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `blockid` smallint(8) NOT NULL,
  `title` varchar(50) NOT NULL,
  `content` text NOT NULL,
  `publishtime` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='广告块内容';

-- ----------------------------
-- Records of fc_plug_block_content
-- ----------------------------
INSERT INTO `fc_plug_block_content` VALUES ('1', '1', '1920 x 520 焦点图（提示：注意位置）', 'a:6:{i:0;a:3:{s:3:\"src\";s:42:\"/Uploads/files/2016/0406/570515494cddb.jpg\";s:5:\"title\";s:9:\"焦点图\";s:3:\"url\";s:0:\"\";}i:1;a:3:{s:3:\"src\";s:42:\"/Uploads/files/2016/0406/57051548e273b.jpg\";s:5:\"title\";s:9:\"焦点图\";s:3:\"url\";s:0:\"\";}i:2;a:3:{s:3:\"src\";s:42:\"/Uploads/files/2016/0406/57051548728f3.jpg\";s:5:\"title\";s:9:\"焦点图\";s:3:\"url\";s:0:\"\";}i:3;a:3:{s:3:\"src\";s:42:\"/Uploads/files/2016/0406/5705151377ac4.jpg\";s:5:\"title\";s:9:\"焦点图\";s:3:\"url\";s:0:\"\";}i:4;a:3:{s:3:\"src\";s:42:\"/Uploads/files/2016/0406/5705151318f8a.jpg\";s:5:\"title\";s:9:\"焦点图\";s:3:\"url\";s:0:\"\";}i:5;a:3:{s:3:\"src\";s:42:\"/Uploads/files/2016/0406/57051512a27a2.jpg\";s:5:\"title\";s:9:\"焦点图\";s:3:\"url\";s:0:\"\";}}', '1446097005');

-- ----------------------------
-- Table structure for fc_plug_link
-- ----------------------------
DROP TABLE IF EXISTS `fc_plug_link`;
CREATE TABLE `fc_plug_link` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `url` varchar(100) NOT NULL,
  `name` varchar(50) NOT NULL DEFAULT '',
  `image` varchar(100) NOT NULL DEFAULT '',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `display` tinyint(1) NOT NULL DEFAULT '0',
  `listorder` mediumint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='友情链接';

-- ----------------------------
-- Records of fc_plug_link
-- ----------------------------
INSERT INTO `fc_plug_link` VALUES ('2', 'http://www.thinkerphp.om', 'thinkerphp', '', '1', '1', '0');

-- ----------------------------
-- Table structure for fc_plug_message
-- ----------------------------
DROP TABLE IF EXISTS `fc_plug_message`;
CREATE TABLE `fc_plug_message` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `areaid` int(10) NOT NULL,
  `memberid` mediumint(6) DEFAULT NULL,
  `time` int(10) NOT NULL,
  `name` varchar(15) NOT NULL,
  `title` varchar(50) NOT NULL,
  `content` varchar(255) NOT NULL,
  `phone` varchar(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `url` varchar(100) NOT NULL,
  `type` enum('普通','定制家具','建议','投诉') NOT NULL DEFAULT '普通',
  `reply` varchar(255) NOT NULL COMMENT '回复',
  `status` tinyint(4) NOT NULL DEFAULT '9',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8 COMMENT='留言板';

-- ----------------------------
-- Records of fc_plug_message
-- ----------------------------

-- ----------------------------
-- Table structure for fc_plug_uploads
-- ----------------------------
DROP TABLE IF EXISTS `fc_plug_uploads`;
CREATE TABLE `fc_plug_uploads` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(60) NOT NULL DEFAULT '',
  `url` varchar(150) NOT NULL DEFAULT '',
  `mediatype` varchar(10) NOT NULL DEFAULT '1',
  `width` char(10) NOT NULL DEFAULT '',
  `height` char(10) NOT NULL DEFAULT '',
  `size` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `uptime` int(10) unsigned NOT NULL DEFAULT '0',
  `owner` char(15) NOT NULL,
  `type` varchar(20) DEFAULT NULL,
  `thumb` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COMMENT='图片上传';

-- ----------------------------
-- Records of fc_plug_uploads
-- ----------------------------
INSERT INTO `fc_plug_uploads` VALUES ('25', '', '/Uploads/files/2016/0406/570515494cddb.jpg', 'jpg', '', '', '302242', '1459950921', 'admin', null, '135,400,800');
INSERT INTO `fc_plug_uploads` VALUES ('24', '', '/Uploads/files/2016/0406/57051548e273b.jpg', 'jpg', '', '', '182109', '1459950920', 'admin', null, '135,400,800');
INSERT INTO `fc_plug_uploads` VALUES ('23', '', '/Uploads/files/2016/0406/57051548728f3.jpg', 'jpg', '', '', '739312', '1459950920', 'admin', null, '135,400,800');
INSERT INTO `fc_plug_uploads` VALUES ('19', '', '/Uploads/files/2016/0406/57051503bc4e3.jpg', 'jpg', '', '', '301112', '1459950851', 'admin', null, '135,400,800');
INSERT INTO `fc_plug_uploads` VALUES ('20', '', '/Uploads/files/2016/0406/57051512a27a2.jpg', 'jpg', '', '', '478133', '1459950866', 'admin', null, '135,400,800');
INSERT INTO `fc_plug_uploads` VALUES ('21', '', '/Uploads/files/2016/0406/5705151318f8a.jpg', 'jpg', '', '', '196822', '1459950867', 'admin', null, '135,400,800');
INSERT INTO `fc_plug_uploads` VALUES ('22', '', '/Uploads/files/2016/0406/5705151377ac4.jpg', 'jpg', '', '', '135343', '1459950867', 'admin', null, '135,400,800');
