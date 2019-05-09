/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : hsrj

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2019-05-09 22:12:37
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for tr_article
-- ----------------------------
DROP TABLE IF EXISTS `tr_article`;
CREATE TABLE `tr_article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cate_id` int(11) NOT NULL DEFAULT '0' COMMENT '分类id',
  `title` varchar(90) NOT NULL DEFAULT '' COMMENT '标题',
  `author` varchar(30) NOT NULL DEFAULT '' COMMENT '作者',
  `img` varchar(255) NOT NULL DEFAULT '' COMMENT '图片',
  `content` text NOT NULL COMMENT '内容',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `state` tinyint(1) NOT NULL DEFAULT '2' COMMENT '状态',
  `click_num` int(11) NOT NULL DEFAULT '0',
  `created_at` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tr_article_cate
-- ----------------------------
DROP TABLE IF EXISTS `tr_article_cate`;
CREATE TABLE `tr_article_cate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '类型名称',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '父级分类id',
  `state` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `created_at` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tr_attribute
-- ----------------------------
DROP TABLE IF EXISTS `tr_attribute`;
CREATE TABLE `tr_attribute` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cate_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品类型id',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '属性名称',
  `sort` smallint(5) NOT NULL DEFAULT '0',
  `input_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 手工录入 1从列表中选择 2多行文本框',
  `input_value` varchar(255) NOT NULL DEFAULT '' COMMENT '属性值 逗号相隔',
  `attr_index` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0不需要检索 1关键字检索 2范围检索',
  `state` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tr_category
-- ----------------------------
DROP TABLE IF EXISTS `tr_category`;
CREATE TABLE `tr_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '父级id',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '分类名称',
  `img` varchar(255) NOT NULL DEFAULT '' COMMENT '分类图片',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `sort` smallint(4) NOT NULL,
  `state` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `created_at` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tr_channel
-- ----------------------------
DROP TABLE IF EXISTS `tr_channel`;
CREATE TABLE `tr_channel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(90) NOT NULL DEFAULT '' COMMENT '渠道名称',
  `relation_id` varchar(255) NOT NULL DEFAULT '' COMMENT '淘宝渠道ID',
  `pid` varchar(255) NOT NULL DEFAULT '' COMMENT '渠道推广位ID',
  `state` tinyint(1) NOT NULL DEFAULT '1',
  `channel_rate` float(10,4) NOT NULL DEFAULT '0.0000' COMMENT '渠道收费比例',
  `referee_rate` float(10,4) NOT NULL DEFAULT '0.0000' COMMENT '一级推荐人分佣比例',
  `grand_rate` float(10,4) NOT NULL DEFAULT '0.0000' COMMENT '二级推荐人分佣比例',
  `fee_rate` float(10,4) NOT NULL DEFAULT '0.0000' COMMENT '服务费收费比例',
  `total_income` decimal(14,2) NOT NULL DEFAULT '0.00' COMMENT '累计收入',
  `created_at` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tr_commission
-- ----------------------------
DROP TABLE IF EXISTS `tr_commission`;
CREATE TABLE `tr_commission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `settlement_sn` varchar(100) NOT NULL DEFAULT '' COMMENT '结算编号',
  `channel_id` int(11) NOT NULL DEFAULT '0' COMMENT '渠道ID',
  `total_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '总佣金',
  `channel_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '渠道收取的费用（未扣除平台费用）',
  `fee_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '平台费用',
  `real_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '渠道实际收入',
  `grand_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '二级推荐人总佣金',
  `referee_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '一级推荐人总佣金',
  `member_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '会员分佣总金额',
  `member_num` int(11) NOT NULL DEFAULT '0' COMMENT '参与分佣会员数量',
  `fee_rate` float(10,4) NOT NULL DEFAULT '0.0000' COMMENT '服务费比例',
  `channel_rate` float(10,4) NOT NULL DEFAULT '0.0000' COMMENT '渠道分佣比例',
  `referee_rate` float(10,4) NOT NULL DEFAULT '0.0000' COMMENT '一级推荐人分佣比例',
  `grand_rate` float(10,4) NOT NULL DEFAULT '0.0000' COMMENT '二级推荐人分佣比例',
  `state` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1发起结算 2结算成功 3已发放',
  `settle_time` int(11) NOT NULL DEFAULT '0' COMMENT '结算时间',
  `pay_time` int(11) NOT NULL DEFAULT '0' COMMENT '发放时间',
  `created_at` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tr_commission_detail
-- ----------------------------
DROP TABLE IF EXISTS `tr_commission_detail`;
CREATE TABLE `tr_commission_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `settle_id` int(11) NOT NULL DEFAULT '0' COMMENT '结算ID',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1分享分佣 2一级推荐分佣 3二级推荐分佣',
  `user_id` int(1) NOT NULL DEFAULT '0' COMMENT '分佣对象ID type为1 则是会员id',
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '分佣金额',
  `order_id` int(11) NOT NULL DEFAULT '0' COMMENT '订单ID type为1时有效',
  `descr` varchar(255) NOT NULL DEFAULT '' COMMENT '分佣来源描述',
  `state` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 未发放 2 已发放',
  `created_at` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tr_commission_order
-- ----------------------------
DROP TABLE IF EXISTS `tr_commission_order`;
CREATE TABLE `tr_commission_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_sn` varchar(255) NOT NULL DEFAULT '' COMMENT '订单编号',
  `relation_id` varchar(255) NOT NULL DEFAULT '' COMMENT '渠道ID',
  `special_id` varchar(255) NOT NULL DEFAULT '' COMMENT '会员ID',
  `adzone_id` varchar(255) NOT NULL DEFAULT '' COMMENT '推广位ID',
  `commission_fee` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '佣金金额',
  `state` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1未匹配 2已匹配 3已结算',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `referee_id` int(11) NOT NULL DEFAULT '0' COMMENT '推荐人ID',
  `grand_id` int(11) NOT NULL DEFAULT '0' COMMENT '二级推荐人ID',
  `channel_id` int(11) NOT NULL DEFAULT '0' COMMENT '渠道ID',
  `settlement_id` int(11) NOT NULL DEFAULT '0' COMMENT '结算ID',
  `created_at` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tr_goods
-- ----------------------------
DROP TABLE IF EXISTS `tr_goods`;
CREATE TABLE `tr_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_no` varchar(20) NOT NULL DEFAULT '' COMMENT '商品编号',
  `name` varchar(90) NOT NULL DEFAULT '' COMMENT '商品名称',
  `title` varchar(90) NOT NULL DEFAULT '' COMMENT '产品标题',
  `keywords` varchar(255) NOT NULL DEFAULT '' COMMENT '关键词',
  `cate_id` int(11) NOT NULL DEFAULT '0' COMMENT '分类id',
  `attr_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品规格',
  `type_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品类型ID',
  `image` varchar(255) NOT NULL DEFAULT '' COMMENT '商品主图',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `market_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '原价 市场价',
  `cost_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '成本价',
  `weight` int(11) NOT NULL DEFAULT '0' COMMENT '商品重量 克',
  `detail` text NOT NULL COMMENT '商品详情',
  `unit_name` varchar(30) NOT NULL DEFAULT '' COMMENT '单位名称',
  `sale_num` int(11) NOT NULL DEFAULT '0' COMMENT '销量',
  `click_count` int(11) NOT NULL DEFAULT '0' COMMENT '商品点击量',
  `state` tinyint(1) NOT NULL DEFAULT '2',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `is_hot` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否热销',
  `is_best` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否精品',
  `is_new` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否新品',
  `Is_shipping` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否包邮',
  `brand_id` int(1) NOT NULL DEFAULT '0' COMMENT '品牌id',
  `comment_count` int(11) NOT NULL DEFAULT '0' COMMENT '评论数量',
  `store_count` int(11) NOT NULL DEFAULT '0' COMMENT '库存数量',
  `created_at` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tr_goods_attr
-- ----------------------------
DROP TABLE IF EXISTS `tr_goods_attr`;
CREATE TABLE `tr_goods_attr` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) NOT NULL DEFAULT '1' COMMENT '商品id',
  `attr_id` int(11) NOT NULL DEFAULT '0' COMMENT '属性id',
  `attr_value` varchar(255) NOT NULL DEFAULT '' COMMENT '属性值',
  `attr_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `state` tinyint(1) NOT NULL DEFAULT '1',
  `sort` smallint(5) NOT NULL DEFAULT '0',
  `created_at` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tr_goods_gallery
-- ----------------------------
DROP TABLE IF EXISTS `tr_goods_gallery`;
CREATE TABLE `tr_goods_gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品id',
  `img` varchar(255) NOT NULL DEFAULT '' COMMENT '商品图片',
  `state` tinyint(1) NOT NULL DEFAULT '1',
  `sort` tinyint(2) NOT NULL DEFAULT '0' COMMENT '排序',
  `created_at` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tr_goods_spec
-- ----------------------------
DROP TABLE IF EXISTS `tr_goods_spec`;
CREATE TABLE `tr_goods_spec` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品id',
  `key` varchar(255) NOT NULL DEFAULT '' COMMENT '规格id 下划线分割',
  `key_name` varchar(255) NOT NULL DEFAULT '' COMMENT '对应规格id 的中文名',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `store_count` int(11) NOT NULL DEFAULT '0' COMMENT '库存',
  `state` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tr_goods_spec_image
-- ----------------------------
DROP TABLE IF EXISTS `tr_goods_spec_image`;
CREATE TABLE `tr_goods_spec_image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品id',
  `spec_id` int(11) NOT NULL DEFAULT '0' COMMENT '规格id',
  `img` varchar(255) NOT NULL DEFAULT '' COMMENT '规格图片',
  `state` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tr_items
-- ----------------------------
DROP TABLE IF EXISTS `tr_items`;
CREATE TABLE `tr_items` (
  `product_id` bigint(20) NOT NULL DEFAULT '0',
  `itemid` bigint(20) NOT NULL DEFAULT '0' COMMENT '宝贝ID',
  `itemtitle` varchar(255) DEFAULT '' COMMENT '宝贝标题',
  `itemshorttitle` varchar(255) DEFAULT '' COMMENT '宝贝短标题',
  `itemdesc` varchar(255) DEFAULT '' COMMENT '宝贝推荐语',
  `itemprice` decimal(10,2) DEFAULT '0.00' COMMENT '在售价',
  `itemsale` int(11) DEFAULT '0' COMMENT '宝贝月销量',
  `itemsale2` int(11) DEFAULT '0' COMMENT '宝贝近2小时跑单',
  `todaysale` int(11) DEFAULT '0' COMMENT '当天销量',
  `itempic` varchar(255) DEFAULT '' COMMENT '宝贝主图原始图像',
  `itempic_copy` varchar(255) DEFAULT '' COMMENT '推广长图',
  `fqcat` int(11) DEFAULT '0' COMMENT '商品类目',
  `itemendprice` decimal(10,2) DEFAULT '0.00' COMMENT '宝贝券后价',
  `shoptype` varchar(10) DEFAULT '' COMMENT '店铺类型',
  `couponurl` varchar(255) DEFAULT '' COMMENT '优惠券链接',
  `couponmoney` decimal(10,2) DEFAULT '0.00' COMMENT '优惠券金额',
  `is_brand` tinyint(1) DEFAULT '0' COMMENT '是否为品牌产品（1是）',
  `is_live` tinyint(1) DEFAULT '0' COMMENT '是否为直播（1是）',
  `guide_article` varchar(255) DEFAULT '' COMMENT '推广导购文案',
  `videoid` int(11) DEFAULT '0' COMMENT '商品视频ID（id大于0的为有视频单，视频拼接地址http://cloud.video.taobao.com/play/u/1/p/1/e/6/t/1/+videoid+.mp4）',
  `activity_type` varchar(255) DEFAULT '' COMMENT '活动类型：',
  `planlink` varchar(255) DEFAULT '' COMMENT '营销计划链接',
  `userid` bigint(20) DEFAULT '0' COMMENT '店主的userid',
  `sellernick` varchar(255) DEFAULT '' COMMENT '店铺掌柜名',
  `shopname` varchar(255) DEFAULT '' COMMENT '店铺名',
  `tktype` varchar(30) DEFAULT '' COMMENT '佣金计划：',
  `tkrates` float(7,4) DEFAULT '0.0000' COMMENT '佣金比例',
  `cuntao` tinyint(1) DEFAULT '0' COMMENT '是否村淘（1是）',
  `tkmoney` decimal(10,2) DEFAULT '0.00' COMMENT '预计可得（宝贝价格 * 佣金比例 / 100）',
  `couponreceive2` int(11) DEFAULT '0' COMMENT '当天优惠券领取量',
  `couponsurplus` int(11) DEFAULT '0' COMMENT '优惠券剩余量',
  `couponnum` int(11) DEFAULT '0' COMMENT '优惠券总数量',
  `couponexplain` varchar(255) DEFAULT '' COMMENT '优惠券使用条件',
  `couponstarttime` int(11) DEFAULT '0' COMMENT '优惠券开始时间',
  `couponendtime` int(11) DEFAULT '0' COMMENT '优惠券结束时间',
  `start_time` int(11) DEFAULT '0' COMMENT '活动开始时间',
  `end_time` int(11) DEFAULT '0' COMMENT '活动结束时间',
  `starttime` int(11) DEFAULT '0' COMMENT '发布时间',
  `report_status` tinyint(1) DEFAULT '0' COMMENT '举报处理条件',
  `general_index` int(11) DEFAULT '0' COMMENT '好单指数',
  `seller_name` varchar(255) DEFAULT '' COMMENT '放单人名号',
  `discount` float(7,4) DEFAULT '0.0000' COMMENT '折扣力度',
  `state` tinyint(1) DEFAULT '1',
  `down_type` varchar(255) DEFAULT '' COMMENT '下架原因',
  `down_time` varchar(20) DEFAULT '',
  `online_users` int(11) DEFAULT '0',
  `couponreceive` int(11) DEFAULT '0',
  `activityid` varchar(100) DEFAULT '',
  `created_at` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tr_items_sync
-- ----------------------------
DROP TABLE IF EXISTS `tr_items_sync`;
CREATE TABLE `tr_items_sync` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `start` tinyint(2) NOT NULL DEFAULT '0',
  `end` tinyint(2) NOT NULL,
  `state` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1同步中 2完成',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1商品拉取 2商品下架',
  `created_at` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tr_manage_banner
-- ----------------------------
DROP TABLE IF EXISTS `tr_manage_banner`;
CREATE TABLE `tr_manage_banner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(60) NOT NULL DEFAULT '' COMMENT 'banner标题',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '链接',
  `img` varchar(255) NOT NULL DEFAULT '' COMMENT '图片地址',
  `state` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `created_at` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tr_manage_faq
-- ----------------------------
DROP TABLE IF EXISTS `tr_manage_faq`;
CREATE TABLE `tr_manage_faq` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cate_id` int(11) NOT NULL DEFAULT '0' COMMENT '分类id',
  `title` varchar(90) NOT NULL DEFAULT '' COMMENT '标题',
  `img` varchar(255) NOT NULL DEFAULT '' COMMENT '图片',
  `content` text NOT NULL COMMENT '内容',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `state` tinyint(1) NOT NULL DEFAULT '2' COMMENT '状态',
  `created_at` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tr_manage_faq_cate
-- ----------------------------
DROP TABLE IF EXISTS `tr_manage_faq_cate`;
CREATE TABLE `tr_manage_faq_cate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '类型名称',
  `state` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `created_at` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tr_manage_guide
-- ----------------------------
DROP TABLE IF EXISTS `tr_manage_guide`;
CREATE TABLE `tr_manage_guide` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cate_id` int(11) NOT NULL DEFAULT '0' COMMENT '分类id',
  `title` varchar(90) NOT NULL DEFAULT '' COMMENT '标题',
  `img` varchar(255) NOT NULL DEFAULT '' COMMENT '图片',
  `content` text NOT NULL COMMENT '内容',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `state` tinyint(1) NOT NULL DEFAULT '2' COMMENT '状态',
  `created_at` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tr_manage_nav
-- ----------------------------
DROP TABLE IF EXISTS `tr_manage_nav`;
CREATE TABLE `tr_manage_nav` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '名称',
  `img` varchar(255) NOT NULL DEFAULT '' COMMENT '图片',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '链接',
  `sort` tinyint(2) NOT NULL DEFAULT '0' COMMENT '排序',
  `state` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `created_at` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tr_manage_notice
-- ----------------------------
DROP TABLE IF EXISTS `tr_manage_notice`;
CREATE TABLE `tr_manage_notice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(90) NOT NULL DEFAULT '' COMMENT '标题',
  `img` varchar(255) NOT NULL DEFAULT '' COMMENT '图片',
  `content` text NOT NULL COMMENT '内容',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `state` tinyint(1) NOT NULL DEFAULT '2' COMMENT '状态',
  `created_at` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tr_member
-- ----------------------------
DROP TABLE IF EXISTS `tr_member`;
CREATE TABLE `tr_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(32) NOT NULL DEFAULT '' COMMENT '密码',
  `phone` varchar(20) NOT NULL DEFAULT '' COMMENT '手机',
  `referee_id` int(11) NOT NULL DEFAULT '0' COMMENT '推荐人id',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '姓名',
  `state` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 1正常 2禁用 3删除',
  `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '头像',
  `sex` tinyint(1) NOT NULL DEFAULT '0' COMMENT '性别 0未设置 1男 2女',
  `login_time` int(10) NOT NULL DEFAULT '0' COMMENT '登陆时间',
  `login_ip` varchar(20) NOT NULL DEFAULT '' COMMENT '登陆ip',
  `last_login_time` int(10) NOT NULL DEFAULT '0' COMMENT '上次登录时间',
  `last_login_ip` varchar(20) NOT NULL DEFAULT '' COMMENT '上次登陆ip',
  `login_num` int(11) NOT NULL DEFAULT '0' COMMENT '登陆次数',
  `channel_id` int(11) NOT NULL DEFAULT '1' COMMENT '渠道表的ID',
  `special_id` varchar(255) NOT NULL DEFAULT '0' COMMENT '淘宝会员ID',
  `level` tinyint(1) NOT NULL DEFAULT '0' COMMENT '用户等级 0普通用户',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '注册时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `available_fund` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '可用余额',
  `frozen_fund` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '冻结金额',
  `frozen_withdraw` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '提现冻结',
  `total_income` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '累计收益',
  `total_withdraw` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '累计提现',
  `grand_id` int(11) NOT NULL DEFAULT '0' COMMENT '推荐人的推荐人的id',
  `openid` varchar(100) NOT NULL DEFAULT '',
  `unionid` varchar(100) NOT NULL DEFAULT '',
  `wx_nickname` varchar(255) NOT NULL DEFAULT '',
  `member_points` int(11) NOT NULL DEFAULT '0' COMMENT '会员积分',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tr_member_account
-- ----------------------------
DROP TABLE IF EXISTS `tr_member_account`;
CREATE TABLE `tr_member_account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '会员id',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '提现类型 1银行卡 2 支付宝 3微信',
  `account` varchar(30) NOT NULL DEFAULT '' COMMENT '提现账号',
  `note` varchar(255) NOT NULL DEFAULT '' COMMENT '其他信息json 银行卡开户行,银行名称等信息',
  `created_at` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tr_member_address
-- ----------------------------
DROP TABLE IF EXISTS `tr_member_address`;
CREATE TABLE `tr_member_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` int(11) NOT NULL DEFAULT '0' COMMENT '会员id',
  `phone` varchar(20) NOT NULL DEFAULT '' COMMENT '手机号',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '收件人',
  `province` varchar(30) NOT NULL DEFAULT '' COMMENT '省份',
  `city` varchar(30) NOT NULL DEFAULT '' COMMENT '市',
  `area` varchar(30) NOT NULL,
  `addr` varchar(255) NOT NULL DEFAULT '' COMMENT '详细地址',
  `is_default` tinyint(1) NOT NULL DEFAULT '0' COMMENT '默认',
  `state` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tr_member_pid
-- ----------------------------
DROP TABLE IF EXISTS `tr_member_pid`;
CREATE TABLE `tr_member_pid` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` varchar(255) NOT NULL DEFAULT '' COMMENT '阿里妈妈pid',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `created_at` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tr_member_points
-- ----------------------------
DROP TABLE IF EXISTS `tr_member_points`;
CREATE TABLE `tr_member_points` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `amount` int(11) NOT NULL DEFAULT '0' COMMENT '积分数量',
  `note` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `created_at` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tr_member_sign
-- ----------------------------
DROP TABLE IF EXISTS `tr_member_sign`;
CREATE TABLE `tr_member_sign` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `sign_amonut` int(11) NOT NULL DEFAULT '0' COMMENT '签到所得积分',
  `sign_count` int(11) NOT NULL DEFAULT '0' COMMENT '连续签到次数',
  `created_at` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tr_member_withdraw
-- ----------------------------
DROP TABLE IF EXISTS `tr_member_withdraw`;
CREATE TABLE `tr_member_withdraw` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `amount` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '提现金额',
  `state` tinyint(1) NOT NULL DEFAULT '1' COMMENT '审核状态 1申请中 2 通过 3拒绝',
  `admin_id` int(11) NOT NULL DEFAULT '0' COMMENT '审核人管理员id',
  `descr` varchar(255) NOT NULL DEFAULT '' COMMENT '审核理由',
  `created_at` int(11) NOT NULL DEFAULT '0' COMMENT '申请时间',
  `audit_time` int(11) NOT NULL DEFAULT '0' COMMENT '审核时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '提现方式',
  `account` varchar(30) NOT NULL DEFAULT '' COMMENT '提现账户',
  `note` varchar(255) NOT NULL DEFAULT '' COMMENT '账户信息',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tr_product
-- ----------------------------
DROP TABLE IF EXISTS `tr_product`;
CREATE TABLE `tr_product` (
  `id` int(11) NOT NULL DEFAULT '0',
  `product_sn` varchar(255) DEFAULT '' COMMENT '商品编号',
  `title` varchar(60) DEFAULT '' COMMENT '商品标题',
  `brief` varchar(90) DEFAULT '' COMMENT '商品副标题',
  `cate_id` int(11) DEFAULT '0' COMMENT '分类id',
  `price` decimal(10,2) DEFAULT '0.00' COMMENT '原价',
  `coupon_price` decimal(10,2) DEFAULT '0.00' COMMENT '优惠券金额',
  `real_price` decimal(10,2) DEFAULT '0.00' COMMENT '实际金额',
  `start_time` int(11) DEFAULT '0' COMMENT '优惠券开始时间',
  `end_time` int(11) DEFAULT '0' COMMENT '优惠券结束日期',
  `total_amount` int(11) DEFAULT '0' COMMENT '优惠券总数量',
  `amount` int(11) DEFAULT '0' COMMENT '剩余优惠券数量',
  `used_amount` int(11) DEFAULT '0' COMMENT '优惠券领取数量',
  `url` varchar(255) DEFAULT '' COMMENT '商品链接',
  `coupon_url` varchar(255) DEFAULT '' COMMENT '优惠券链接',
  `sale_num` int(11) DEFAULT '0' COMMENT '销量',
  `sort` int(11) DEFAULT '0' COMMENT '商品排序',
  `state` tinyint(1) DEFAULT '1',
  `created_at` int(11) DEFAULT '0',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tr_spec
-- ----------------------------
DROP TABLE IF EXISTS `tr_spec`;
CREATE TABLE `tr_spec` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品分类id',
  `name` varchar(60) NOT NULL DEFAULT '' COMMENT '规格名称',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `state` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` int(11) NOT NULL DEFAULT '1',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tr_spec_item
-- ----------------------------
DROP TABLE IF EXISTS `tr_spec_item`;
CREATE TABLE `tr_spec_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `spec_id` int(11) NOT NULL DEFAULT '0' COMMENT '规格id',
  `item` varchar(60) NOT NULL DEFAULT '' COMMENT '规格项',
  `state` tinyint(1) NOT NULL DEFAULT '1',
  `sort` smallint(4) NOT NULL DEFAULT '0' COMMENT '排序',
  `created_at` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
-- Table structure for tr_sys_basic
-- ----------------------------
DROP TABLE IF EXISTS `tr_sys_basic`;
CREATE TABLE `tr_sys_basic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `config_title` varchar(255) NOT NULL DEFAULT '' COMMENT '配置标题',
  `config_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '配置类型 1系统配置 2 运营配置',
  `config_name` varchar(30) NOT NULL DEFAULT '' COMMENT '系统名称',
  `config_value` varchar(255) NOT NULL DEFAULT '' COMMENT '系统域名',
  `input_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '输入类型 1input 2radio 3switcher',
  `input_option` varchar(255) NOT NULL DEFAULT '' COMMENT '输入配置json',
  `note` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `state` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 1启用 2 禁用',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `created_at` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tr_sys_basic_copy
-- ----------------------------
DROP TABLE IF EXISTS `tr_sys_basic_copy`;
CREATE TABLE `tr_sys_basic_copy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `system_name` varchar(30) DEFAULT '' COMMENT '系统名称',
  `system_domain` varchar(255) DEFAULT '' COMMENT '系统域名',
  `system_run` tinyint(1) DEFAULT '1' COMMENT '系统维护状态 1正常 0 维护',
  `login_error` tinyint(1) DEFAULT '0' COMMENT '登录错误次数',
  `login_overtime` int(5) DEFAULT '0' COMMENT '登录时长上限',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM AUTO_INCREMENT=109 DEFAULT CHARSET=utf8;

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
-- Table structure for tr_ticket_cate
-- ----------------------------
DROP TABLE IF EXISTS `tr_ticket_cate`;
CREATE TABLE `tr_ticket_cate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '',
  `state` tinyint(1) NOT NULL,
  `sort` tinyint(2) NOT NULL DEFAULT '0',
  `created_at` int(11) NOT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
