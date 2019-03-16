/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : hsrj

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2019-03-16 21:18:59
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for tr_members
-- ----------------------------
DROP TABLE IF EXISTS `tr_members`;
CREATE TABLE `tr_members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) DEFAULT '' COMMENT '用户名',
  `password` varchar(32) DEFAULT '' COMMENT '密码',
  `phone` varchar(20) DEFAULT '' COMMENT '手机',
  `referee_id` int(11) DEFAULT '0' COMMENT '推荐人id',
  `name` varchar(30) DEFAULT '' COMMENT '姓名',
  `state` tinyint(1) DEFAULT '1' COMMENT '状态 1正常 2禁用 3删除',
  `avatar` varchar(255) DEFAULT '' COMMENT '头像',
  `sex` tinyint(1) DEFAULT '0' COMMENT '性别 0未设置 1男 2女',
  `login_time` int(10) DEFAULT '0' COMMENT '登陆时间',
  `login_ip` varchar(20) DEFAULT '' COMMENT '登陆ip',
  `last_login_time` int(10) DEFAULT '0' COMMENT '上次登录时间',
  `last_login_ip` varchar(20) DEFAULT '' COMMENT '上次登陆ip',
  `login_num` int(11) DEFAULT '0' COMMENT '登陆次数',
  `level` tinyint(1) DEFAULT '0' COMMENT '用户等级 0普通用户',
  `created_at` int(10) DEFAULT '0' COMMENT '注册时间',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tr_members
-- ----------------------------

-- ----------------------------
-- Table structure for tr_sys_admin
-- ----------------------------
DROP TABLE IF EXISTS `tr_sys_admin`;
CREATE TABLE `tr_sys_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usa` varchar(30) NOT NULL DEFAULT '' COMMENT '用户名',
  `pswd` varchar(32) NOT NULL DEFAULT '' COMMENT '密码',
  `phone` varchar(11) NOT NULL DEFAULT '' COMMENT '手机号',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '管理员姓名',
  `email` varchar(255) NOT NULL DEFAULT '' COMMENT '邮箱',
  `role_id` int(11) NOT NULL DEFAULT '0' COMMENT '角色id',
  `state` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 1正常 2禁用 3删除 ',
  `login_time` int(10) NOT NULL DEFAULT '0' COMMENT '登录时间',
  `login_ip` varchar(20) NOT NULL DEFAULT '' COMMENT '登陆ip',
  `last_login_time` int(10) NOT NULL DEFAULT '0' COMMENT '上次登陆时间',
  `last_login_ip` varchar(20) NOT NULL DEFAULT '' COMMENT '上次登陆ip',
  `login_num` int(11) NOT NULL DEFAULT '0' COMMENT '登陆次数',
  `login_error` int(11) NOT NULL DEFAULT '0' COMMENT '登陆错误次数',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tr_sys_admin
-- ----------------------------
INSERT INTO `tr_sys_admin` VALUES ('1', 'admin', 'd93a5def7511da3d0f2d171d9c344e91', '13588272727', '', '132@qq.com', '1', '1', '1552741355', '', '1552741166', '', '3', '0', '1548075651', '2019-03-16 20:06:01');
INSERT INTO `tr_sys_admin` VALUES ('2', 'ceshi', '123', '13588272727', '', '123@qq.com', '2', '1', '0', '', '0', '', '0', '0', '1548075651', '2019-03-15 15:35:57');
INSERT INTO `tr_sys_admin` VALUES ('3', 'btx', '10470c3b4b1fed12c3baac014be15fac', '', 'xgh', '', '2', '3', '1548075651', '', '1548075651', '', '0', '0', '1548075651', '2019-03-15 15:33:40');
INSERT INTO `tr_sys_admin` VALUES ('4', 'btxs', '10470c3b4b1fed12c3baac014be15fac', '', 'xgh', '', '2', '2', '1548075651', '', '1548075651', '', '0', '0', '1548075651', '2019-03-15 15:33:36');
INSERT INTO `tr_sys_admin` VALUES ('5', 'btxxgh', '10470c3b4b1fed12c3baac014be15fac', '13588272728', 'xgh', '', '2', '2', '1548075651', '', '1548075651', '', '0', '0', '1548075651', '2019-03-15 15:33:36');
INSERT INTO `tr_sys_admin` VALUES ('6', '20190304', '2cde387df37e2900de2b7c855770aae4', '13588272939', 'njbgjr_daicao', '', '2', '1', '1548075651', '', '1548075651', '', '0', '0', '1548075651', '2019-03-15 15:33:36');
INSERT INTO `tr_sys_admin` VALUES ('7', '20190304002', '46f42a036c0755fa9a52f1ab87385665', '13588272939', '后台', '', '2', '1', '1548075651', '', '1548075651', '', '0', '0', '1548075651', '2019-03-15 15:33:35');
INSERT INTO `tr_sys_admin` VALUES ('8', '20190305001', '10470c3b4b1fed12c3baac014be15fac', '13588272939', 'njbgjr_daicao', '', '2', '1', '1548075651', '', '1548075651', '', '0', '0', '1548075651', '2019-03-15 15:33:35');
INSERT INTO `tr_sys_admin` VALUES ('9', '20190305002', '10470c3b4b1fed12c3baac014be15fac', '13588272939', 'njbgjr_daicao', '', '2', '1', '1548075651', '', '1548075651', '', '0', '0', '1548075651', '2019-03-15 15:33:34');
INSERT INTO `tr_sys_admin` VALUES ('10', '20190314001', 'b96a2f155fe64963219c24abeed1f252', '13588272939', 'njbgjr_daicao', '', '2', '1', '1548075651', '', '1548075651', '', '0', '0', '1548075651', '2019-03-15 15:33:34');

-- ----------------------------
-- Table structure for tr_sys_node
-- ----------------------------
DROP TABLE IF EXISTS `tr_sys_node`;
CREATE TABLE `tr_sys_node` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) DEFAULT '' COMMENT '名称',
  `path` varchar(100) DEFAULT '' COMMENT '地址',
  `pid` tinyint(1) DEFAULT '0' COMMENT '父级id',
  `type` varchar(10) DEFAULT '1' COMMENT '类型 1 菜单 其他为按钮',
  `sort` int(11) DEFAULT '0' COMMENT '排序',
  `is_menu` varchar(10) DEFAULT '0' COMMENT '是否需要菜单赋值 0 不需要 1 需要 其他为父级菜单',
  `state` tinyint(1) DEFAULT '1' COMMENT '状态',
  `created_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tr_sys_node
-- ----------------------------
INSERT INTO `tr_sys_node` VALUES ('1', '首页', 'system/index/index', '0', '1', '99', '1', '1', '0', '2019-03-16 11:04:41');
INSERT INTO `tr_sys_node` VALUES ('2', '系统管理', '', '0', '1', '98', '0', '1', '0', '2019-03-16 11:04:44');
INSERT INTO `tr_sys_node` VALUES ('3', '角色管理', 'system/role/index', '2', '1', '7', '1', '1', '0', '2019-03-07 22:54:22');
INSERT INTO `tr_sys_node` VALUES ('6', '删除', 'system/role/del', '3', 'del', '7', '0', '1', '0', '2019-03-16 11:04:45');
INSERT INTO `tr_sys_node` VALUES ('4', '添加', 'system/role/add', '3', 'add', '9', '0', '1', '0', '2019-03-16 11:04:46');
INSERT INTO `tr_sys_node` VALUES ('5', '编辑', 'system/role/edit', '3', 'edit', '8', '0', '1', '0', '2019-03-16 11:04:46');
INSERT INTO `tr_sys_node` VALUES ('8', '管理员', 'system/user/index', '2', '1', '8', '1', '1', '0', '2019-03-07 22:54:20');
INSERT INTO `tr_sys_node` VALUES ('9', '添加', 'system/user/add', '8', 'add', '9', '0', '1', '0', '2019-03-16 11:04:47');
INSERT INTO `tr_sys_node` VALUES ('7', '权限配置', 'system/role/access', '3', 'access', '6', 'index', '1', '0', '2019-03-16 11:36:03');
INSERT INTO `tr_sys_node` VALUES ('10', '编辑', 'system/user/edit', '8', 'edit', '8', '0', '1', '0', '2019-03-16 11:04:48');
INSERT INTO `tr_sys_node` VALUES ('11', '删除', 'system/user/del', '8', 'del', '7', '0', '1', '0', '2019-03-16 11:04:48');
INSERT INTO `tr_sys_node` VALUES ('12', '菜单管理', 'system/menu/index', '2', '1', '6', '1', '1', '0', '2019-03-07 22:55:05');
INSERT INTO `tr_sys_node` VALUES ('13', '编辑', 'system/menu/edit', '12', 'edit', '9', '0', '1', '0', '2019-03-16 11:04:49');
INSERT INTO `tr_sys_node` VALUES ('14', '基本信息', 'system/basic/index', '2', '1', '9', '1', '1', '0', '2019-03-07 22:54:00');
INSERT INTO `tr_sys_node` VALUES ('15', '编辑', 'system/basic/edit', '14', 'edit', '9', '0', '1', '0', '2019-03-16 11:04:50');
INSERT INTO `tr_sys_node` VALUES ('16', '用户管理', '', '0', '1', '97', '0', '1', '0', '2019-03-16 11:04:50');
INSERT INTO `tr_sys_node` VALUES ('17', '用户信息', 'user/index', '16', '1', '9', '1', '1', '0', '2019-03-08 09:53:17');
INSERT INTO `tr_sys_node` VALUES ('18', '添加', 'user/add', '17', 'add', '9', '0', '1', '0', '2019-03-16 11:04:51');
INSERT INTO `tr_sys_node` VALUES ('19', '编辑', 'user/edit', '17', 'edit', '8', '0', '1', '0', '2019-03-16 11:04:52');
INSERT INTO `tr_sys_node` VALUES ('20', '删除', 'user/del', '17', 'del', '7', '0', '1', '0', '2019-03-16 11:04:53');

-- ----------------------------
-- Table structure for tr_sys_role
-- ----------------------------
DROP TABLE IF EXISTS `tr_sys_role`;
CREATE TABLE `tr_sys_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '角色名称',
  `access_node` text NOT NULL COMMENT '角色菜单id 以英文逗号分隔',
  `state` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tr_sys_role
-- ----------------------------
INSERT INTO `tr_sys_role` VALUES ('1', '超级管理员', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20', '1', '1548145765', '2019-01-22 16:29:25');
INSERT INTO `tr_sys_role` VALUES ('2', '管理员', '1,2,3,4,5,7,8,9,10', '1', '1548145765', '2019-03-16 16:55:19');
INSERT INTO `tr_sys_role` VALUES ('3', '客服', '', '2', '1552632230', '2019-03-15 14:43:50');
INSERT INTO `tr_sys_role` VALUES ('4', '运营', '', '1', '1552632386', '2019-03-15 15:31:52');
