/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : hsrj

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2019-03-18 21:53:17
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for tr_member_fund_flow
-- ----------------------------
DROP TABLE IF EXISTS `tr_member_fund_flow`;
CREATE TABLE `tr_member_fund_flow` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `amount` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '金额',
  `balance` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '余额',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '明细类型 1分佣 2提现 3冻结 4解冻  ',
  `note` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `created_at` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tr_member_fund_flow
-- ----------------------------

-- ----------------------------
-- Table structure for tr_member_info
-- ----------------------------
DROP TABLE IF EXISTS `tr_member_info`;
CREATE TABLE `tr_member_info` (
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
  `available_fund` decimal(12,2) DEFAULT '0.00' COMMENT '可用余额',
  `frozen_fund` decimal(12,2) DEFAULT '0.00' COMMENT '冻结金额',
  `frozen_withdraw` decimal(12,2) DEFAULT '0.00' COMMENT '提现冻结',
  `total_income` decimal(12,2) DEFAULT '0.00' COMMENT '累计收益',
  `total_withdraw` decimal(12,2) DEFAULT '0.00' COMMENT '累计提现',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tr_member_info
-- ----------------------------
INSERT INTO `tr_member_info` VALUES ('1', 'admin', 'd93a5def7511da3d0f2d171d9c344e91', '13588272939', '0', '', '1', '', '0', '0', '', '0', '', '0', '0', '1552908886', '2019-03-18 19:34:46', '0.00', '0.00', '0.00', '0.00', '0.00');

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
INSERT INTO `tr_sys_admin` VALUES ('1', 'admin', 'd93a5def7511da3d0f2d171d9c344e91', '13588272727', '', '132@qq.com', '1', '1', '1552916920', '', '1552916713', '', '44', '0', '1548075651', '2019-03-18 01:12:03');
INSERT INTO `tr_sys_admin` VALUES ('2', 'ceshi', '123', '13588272727', '', '123@qq.com', '2', '1', '0', '', '0', '', '0', '0', '1548075651', '2019-03-15 15:35:57');
INSERT INTO `tr_sys_admin` VALUES ('3', 'btx', '10470c3b4b1fed12c3baac014be15fac', '', 'xgh', '', '2', '3', '1548075651', '', '1548075651', '', '0', '0', '1548075651', '2019-03-15 15:33:40');
INSERT INTO `tr_sys_admin` VALUES ('4', 'btxs', '10470c3b4b1fed12c3baac014be15fac', '', 'xgh', '', '2', '2', '1548075651', '', '1548075651', '', '0', '0', '1548075651', '2019-03-15 15:33:36');
INSERT INTO `tr_sys_admin` VALUES ('5', 'btxxgh', '10470c3b4b1fed12c3baac014be15fac', '13588272728', 'xgh', '', '2', '2', '1548075651', '', '1548075651', '', '0', '0', '1548075651', '2019-03-15 15:33:36');
INSERT INTO `tr_sys_admin` VALUES ('6', '20190304', '2cde387df37e2900de2b7c855770aae4', '13588272939', 'njbgjr_daicao', '', '2', '1', '1548075651', '', '1548075651', '', '0', '0', '1548075651', '2019-03-15 15:33:36');
INSERT INTO `tr_sys_admin` VALUES ('7', '20190304002', '46f42a036c0755fa9a52f1ab87385665', '13588272939', '后台', '', '2', '1', '1548075651', '', '1548075651', '', '0', '0', '1548075651', '2019-03-15 15:33:35');
INSERT INTO `tr_sys_admin` VALUES ('8', '20190305001', '10470c3b4b1fed12c3baac014be15fac', '13588272939', 'njbgjr_daicao', '', '2', '1', '1548075651', '', '1548075651', '', '0', '0', '1548075651', '2019-03-15 15:33:35');
INSERT INTO `tr_sys_admin` VALUES ('9', '20190305002', '10470c3b4b1fed12c3baac014be15fac', '13588272939', 'njbgjr_daicao', '', '2', '1', '1548075651', '', '1548075651', '', '0', '0', '1548075651', '2019-03-15 15:33:34');
INSERT INTO `tr_sys_admin` VALUES ('10', '20190314001', 'b96a2f155fe64963219c24abeed1f252', '13588272939', 'njbgjr_daicao', '', '2', '1', '1548075651', '', '1548075651', '', '0', '0', '1548075651', '2019-03-17 15:42:02');

-- ----------------------------
-- Table structure for tr_sys_basic
-- ----------------------------
DROP TABLE IF EXISTS `tr_sys_basic`;
CREATE TABLE `tr_sys_basic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `system_name` varchar(30) DEFAULT '' COMMENT '系统名称',
  `system_domain` varchar(255) DEFAULT '' COMMENT '系统域名',
  `system_run` tinyint(1) DEFAULT '1' COMMENT '系统维护状态 1正常 0 维护',
  `login_error` tinyint(1) DEFAULT '0' COMMENT '登录错误次数',
  `login_overtime` int(5) DEFAULT '0' COMMENT '登录时长上限',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tr_sys_basic
-- ----------------------------
INSERT INTO `tr_sys_basic` VALUES ('1', '唐人', '系统名称', '1', '3', '120');

-- ----------------------------
-- Table structure for tr_sys_node
-- ----------------------------
DROP TABLE IF EXISTS `tr_sys_node`;
CREATE TABLE `tr_sys_node` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(30) DEFAULT '' COMMENT '标题',
  `path` varchar(100) DEFAULT '' COMMENT '地址',
  `pid` tinyint(1) DEFAULT '0' COMMENT '父级id',
  `name` varchar(10) DEFAULT '1' COMMENT '名称',
  `sort` int(11) DEFAULT '0' COMMENT '排序',
  `type` tinyint(1) DEFAULT '1' COMMENT '1 菜单 2 按钮',
  `state` tinyint(1) DEFAULT '1' COMMENT '状态',
  `created_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tr_sys_node
-- ----------------------------
INSERT INTO `tr_sys_node` VALUES ('1', '首页', 'system/index/index', '0', '1', '99', '1', '1', '0', '2019-03-16 11:04:41');
INSERT INTO `tr_sys_node` VALUES ('2', '系统管理', '', '0', '1', '98', '1', '1', '0', '2019-03-17 10:15:58');
INSERT INTO `tr_sys_node` VALUES ('3', '角色管理', 'system/role/index', '2', '1', '7', '1', '1', '0', '2019-03-07 22:54:22');
INSERT INTO `tr_sys_node` VALUES ('6', '删除', 'system/role/del', '3', 'del', '7', '2', '1', '0', '2019-03-18 21:40:02');
INSERT INTO `tr_sys_node` VALUES ('4', '添加', 'system/role/add', '3', 'add', '9', '2', '1', '0', '2019-03-18 21:39:27');
INSERT INTO `tr_sys_node` VALUES ('5', '编辑', 'system/role/edit', '3', 'edit', '8', '2', '1', '0', '2019-03-18 21:39:28');
INSERT INTO `tr_sys_node` VALUES ('8', '管理员', 'system/user/index', '2', '1', '8', '1', '1', '0', '2019-03-07 22:54:20');
INSERT INTO `tr_sys_node` VALUES ('9', '添加', 'system/user/add', '8', 'add', '9', '2', '1', '0', '2019-03-18 21:39:29');
INSERT INTO `tr_sys_node` VALUES ('7', '权限配置', 'system/role/access', '3', 'access', '6', '2', '1', '0', '2019-03-18 21:39:29');
INSERT INTO `tr_sys_node` VALUES ('10', '编辑', 'system/user/edit', '8', 'edit', '8', '2', '1', '0', '2019-03-18 21:39:30');
INSERT INTO `tr_sys_node` VALUES ('11', '删除', 'system/user/del', '8', 'del', '7', '2', '1', '0', '2019-03-18 21:39:31');
INSERT INTO `tr_sys_node` VALUES ('21', '会员明细', 'member/index/flow', '17', 'flow', '6', '2', '1', '0', '2019-03-18 21:39:32');
INSERT INTO `tr_sys_node` VALUES ('14', '系统设置', 'system/basic/index', '2', '1', '9', '1', '1', '0', '2019-03-18 18:50:35');
INSERT INTO `tr_sys_node` VALUES ('15', '编辑', 'system/basic/edit', '14', 'edit', '9', '2', '1', '0', '2019-03-18 21:39:32');
INSERT INTO `tr_sys_node` VALUES ('16', '会员管理', '', '0', '1', '97', '1', '1', '0', '2019-03-18 20:22:37');
INSERT INTO `tr_sys_node` VALUES ('17', '会员信息', 'member/index/index', '16', '1', '9', '1', '1', '0', '2019-03-18 20:22:31');
INSERT INTO `tr_sys_node` VALUES ('18', '添加', 'member/index/add', '17', 'add', '9', '2', '1', '0', '2019-03-18 21:39:33');
INSERT INTO `tr_sys_node` VALUES ('19', '编辑', 'member/index/edit', '17', 'edit', '8', '2', '1', '0', '2019-03-18 21:39:34');
INSERT INTO `tr_sys_node` VALUES ('20', '删除', 'member/index/del', '17', 'del', '7', '2', '1', '0', '2019-03-18 21:39:35');

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
