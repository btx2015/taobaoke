/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : hsrj

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2019-04-08 21:01:19
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
-- Records of tr_article
-- ----------------------------
INSERT INTO `tr_article` VALUES ('1', '4', '一万个美丽的未来', '花销Q', '', '&lt;p&gt;safsfsdfsfffs&lt;img src=&quot;/ueditor/php/upload/image/20190324/1553424904.png&quot; title=&quot;1553424904.png&quot; alt=&quot;QQ截图20190111144433.png&quot;/&gt;&lt;/p&gt;', '99', '1', '0', '1553424906', '2019-03-24 18:57:06');

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
-- Records of tr_article_cate
-- ----------------------------
INSERT INTO `tr_article_cate` VALUES ('1', '每日爆款', '0', '1', '9', '0', '2019-03-24 17:26:58');
INSERT INTO `tr_article_cate` VALUES ('2', '趣分享', '0', '1', '8', '0', '2019-03-24 17:25:00');
INSERT INTO `tr_article_cate` VALUES ('3', '宣传素材', '0', '1', '7', '0', '2019-03-24 17:26:03');
INSERT INTO `tr_article_cate` VALUES ('4', '早安日签', '3', '1', '9', '0', '2019-03-24 17:26:36');
INSERT INTO `tr_article_cate` VALUES ('5', '花生说', '3', '1', '8', '0', '2019-03-24 17:26:11');
INSERT INTO `tr_article_cate` VALUES ('6', '小店', '3', '1', '7', '0', '2019-03-24 17:04:56');
INSERT INTO `tr_article_cate` VALUES ('7', '花生严选', '3', '1', '6', '0', '2019-03-24 17:05:12');
INSERT INTO `tr_article_cate` VALUES ('8', '花粉学堂', '0', '1', '6', '0', '2019-03-24 17:05:36');
INSERT INTO `tr_article_cate` VALUES ('9', '精品课程', '8', '1', '9', '0', '2019-03-24 17:05:51');
INSERT INTO `tr_article_cate` VALUES ('10', '大咖分享', '8', '1', '8', '0', '2019-03-24 17:06:12');

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
-- Records of tr_attribute
-- ----------------------------
INSERT INTO `tr_attribute` VALUES ('1', '1', '内存', '0', '0', '                                                        ', '0', '1', '1553702169', '2019-03-27 23:56:09');
INSERT INTO `tr_attribute` VALUES ('2', '1', 'cpu', '0', '1', '123,456,789', '0', '1', '1553703005', '2019-03-28 00:10:05');

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
-- Records of tr_category
-- ----------------------------
INSERT INTO `tr_category` VALUES ('1', '手机', '', '0', '0', '1', '1553683405', '2019-03-27 19:40:11');

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
  `fee_rate` float(10,3) NOT NULL DEFAULT '0.000' COMMENT '渠道收费比例',
  `referee_rate` float(10,3) NOT NULL DEFAULT '0.000' COMMENT '一级推荐人分佣比例',
  `grand_rate` float(10,3) NOT NULL DEFAULT '0.000' COMMENT '二级推荐人分佣比例',
  `created_at` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tr_channel
-- ----------------------------
INSERT INTO `tr_channel` VALUES ('1', '自营', '', 'qeqw', '1', '0.100', '0.100', '0.030', '1554607305', '2019-04-07 11:26:09');

-- ----------------------------
-- Table structure for tr_commission
-- ----------------------------
DROP TABLE IF EXISTS `tr_commission`;
CREATE TABLE `tr_commission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `settlement_sn` varchar(100) NOT NULL DEFAULT '' COMMENT '结算编号',
  `total_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '分佣总金额',
  `channel_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '渠道分佣金额',
  `channel_num` int(11) NOT NULL DEFAULT '0' COMMENT '参与分佣渠道数量',
  `channel_percent` float(10,4) NOT NULL DEFAULT '0.0000' COMMENT '渠道分佣占总金额百分比',
  `member_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '会员分佣金额',
  `member_num` int(11) NOT NULL DEFAULT '0' COMMENT '参与分佣会员数量',
  `member_percent` float(10,4) NOT NULL DEFAULT '0.0000' COMMENT '会员分佣占总金额百分比',
  `state` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1开始结算 2结算成功 3已发放',
  `created_at` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tr_commission
-- ----------------------------

-- ----------------------------
-- Table structure for tr_commission_detail
-- ----------------------------
DROP TABLE IF EXISTS `tr_commission_detail`;
CREATE TABLE `tr_commission_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `settlement_id` int(11) NOT NULL DEFAULT '0' COMMENT '结算ID',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1会员分佣 2渠道分佣',
  `object_id` int(1) NOT NULL DEFAULT '0' COMMENT '分佣对象ID type为1 则是会员id',
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '分佣金额',
  `order_id` int(11) NOT NULL DEFAULT '0' COMMENT '订单ID type为1时有效',
  `descr` varchar(255) NOT NULL DEFAULT '' COMMENT '分佣来源描述',
  `created_at` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tr_commission_detail
-- ----------------------------

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
  `state` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1未结算 2已匹配 3未匹配 4 已结算',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `referee_id` int(11) NOT NULL DEFAULT '0' COMMENT '推荐人ID',
  `grand_id` int(11) NOT NULL DEFAULT '0' COMMENT '二级推荐人ID',
  `channel_id` int(11) NOT NULL DEFAULT '0' COMMENT '渠道ID',
  `settlement_id` int(11) NOT NULL DEFAULT '0' COMMENT '结算ID',
  `channel_rate` float(10,4) NOT NULL DEFAULT '0.0000' COMMENT '渠道分佣比例',
  `referee_rate` float(10,4) NOT NULL DEFAULT '0.0000' COMMENT '以及推荐人分佣比例',
  `grand_rate` float(10,4) NOT NULL DEFAULT '0.0000' COMMENT '二级推荐人分佣比例',
  `created_at` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tr_commission_order
-- ----------------------------

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
-- Records of tr_goods
-- ----------------------------

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
-- Records of tr_goods_attr
-- ----------------------------

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
-- Records of tr_goods_gallery
-- ----------------------------

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
-- Records of tr_goods_spec
-- ----------------------------

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
-- Records of tr_goods_spec_image
-- ----------------------------

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
-- Records of tr_manage_banner
-- ----------------------------
INSERT INTO `tr_manage_banner` VALUES ('1', '轮播i图1', 'www.baidu.com', '/Public/Upload/manage/banner/banner1.jpg', '1', '0', '1553156575', '2019-03-21 16:23:03');
INSERT INTO `tr_manage_banner` VALUES ('2', '轮播i图1', 'www.baidu.com', '/Public/Upload/manage/banner/banner1.jpg', '1', '0', '1553156575', '2019-03-21 16:23:03');
INSERT INTO `tr_manage_banner` VALUES ('3', '轮播i图1', 'www.baidu.com', '/Public/Upload/manage/banner/banner1.jpg', '1', '0', '1553156575', '2019-03-21 16:23:03');
INSERT INTO `tr_manage_banner` VALUES ('4', '轮播i图1', 'www.baidu.com', '/Public/Upload/manage/banner/banner1.jpg', '1', '0', '1553156575', '2019-03-21 16:23:03');
INSERT INTO `tr_manage_banner` VALUES ('5', '轮播i图1', 'www.baidu.com', '/Public/Upload/manage/banner/banner1.jpg', '1', '0', '1553156575', '2019-03-21 16:23:03');
INSERT INTO `tr_manage_banner` VALUES ('6', '轮播i图1', 'www.baidu.com', '/Public/Upload/manage/banner/banner1.jpg', '1', '0', '1553156575', '2019-03-21 16:23:03');
INSERT INTO `tr_manage_banner` VALUES ('7', '轮播i图1', 'www.baidu.com', '/Public/Upload/manage/banner/banner1.jpg', '1', '0', '1553156575', '2019-03-21 16:23:03');
INSERT INTO `tr_manage_banner` VALUES ('8', '轮播i图1', 'www.baidu.com', '/Public/Upload/manage/banner/banner1.jpg', '1', '0', '1553156575', '2019-03-21 16:23:03');
INSERT INTO `tr_manage_banner` VALUES ('9', '轮播i图1', 'www.baidu.com', '/Public/Upload/manage/banner/banner1.jpg', '1', '0', '1553156575', '2019-03-21 16:23:03');
INSERT INTO `tr_manage_banner` VALUES ('10', '轮播i图12', 'www.baidu.com', '/Public/Upload/manage/banner/banner1.jpg', '1', '12', '1553156575', '2019-03-24 09:58:16');

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
-- Records of tr_manage_faq
-- ----------------------------
INSERT INTO `tr_manage_faq` VALUES ('1', '1', '关于订单的', '', '', '0', '3', '0', '2019-03-23 22:32:50');
INSERT INTO `tr_manage_faq` VALUES ('2', '1', '房源管理', '', '&lt;p&gt;safas&lt;/p&gt;', '0', '3', '1553350748', '2019-03-23 22:37:32');
INSERT INTO `tr_manage_faq` VALUES ('3', '1', '房源列表', '', '&lt;p&gt;sfsfsfsdf&lt;/p&gt;', '0', '1', '1553350897', '2019-03-23 22:38:35');
INSERT INTO `tr_manage_faq` VALUES ('4', '1', '城市地址', '', '&lt;p&gt;sdfsfsdfs&lt;/p&gt;', '0', '2', '1553350974', '2019-03-23 22:22:54');

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
-- Records of tr_manage_faq_cate
-- ----------------------------
INSERT INTO `tr_manage_faq_cate` VALUES ('1', '常见问题', '1', '12', '0', '2019-03-24 09:53:16');
INSERT INTO `tr_manage_faq_cate` VALUES ('2', '关于分佣', '1', '0', '1553315829', '2019-03-23 12:38:44');

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
-- Records of tr_manage_guide
-- ----------------------------
INSERT INTO `tr_manage_guide` VALUES ('1', '0', '如何分享', '', '&lt;p&gt;&lt;img src=&quot;/ueditor/php/upload/image/20190324/1553391651.png&quot; title=&quot;1553391651.png&quot; alt=&quot;QQ截图20190111144200.png&quot;/&gt;&lt;/p&gt;', '0', '2', '1553391653', '2019-03-24 09:40:53');

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
-- Records of tr_manage_nav
-- ----------------------------
INSERT INTO `tr_manage_nav` VALUES ('1', '今日特价', '/Uploads/nav/2019-03-24/5c9797e28b33d.png', 'http://www.iqiyi.com/v_19rr8sbot0.html#vfrm=2-4-0-1', '1', '1', '1553438690', '2019-03-24 22:46:37');

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
-- Records of tr_manage_notice
-- ----------------------------
INSERT INTO `tr_manage_notice` VALUES ('1', '系统升级提醒', '', '&lt;p&gt;&lt;img src=&quot;/ueditor/php/upload/image/20190324/1553391920.png&quot; title=&quot;1553391920.png&quot; alt=&quot;QQ截图20190111144433.png&quot;/&gt;&lt;/p&gt;', '12', '1', '1553391923', '2019-03-24 09:47:27');

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
  `channel_id` int(11) NOT NULL DEFAULT '0' COMMENT '渠道表的ID',
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
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tr_member
-- ----------------------------
INSERT INTO `tr_member` VALUES ('1', 'admin', 'b1d1ab72336885719b522a1920d56e5c', '13588272939', '0', '超级管理员', '1', '', '0', '0', '', '0', '', '0', '1', '0', '0', '1552908886', '2019-03-18 19:34:46', '0.00', '0.00', '0.00', '0.00', '0.00', '0');
INSERT INTO `tr_member` VALUES ('2', 'ceshi', '8198eee1cc2c105377e09ddb5df4709e', '13588272727', '1', '', '1', '', '0', '0', '', '0', '', '0', '1', '0', '0', '1554609771', '2019-04-07 12:02:51', '0.00', '0.00', '0.00', '0.00', '0.00', '0');

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
-- Records of tr_member_account
-- ----------------------------

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
-- Records of tr_member_address
-- ----------------------------

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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tr_member_fund_flow
-- ----------------------------
INSERT INTO `tr_member_fund_flow` VALUES ('1', '1', '100.00', '100.00', '1', '系统充值', '1552994379');
INSERT INTO `tr_member_fund_flow` VALUES ('2', '1', '-100.00', '0.00', '2', '系统扣除', '1552994390');

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
-- Records of tr_member_pid
-- ----------------------------

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
-- Records of tr_member_withdraw
-- ----------------------------
INSERT INTO `tr_member_withdraw` VALUES ('1', '1', '100.00', '2', '1', '', '1553065944', '1553083800', '2019-03-20 20:10:00', '1', '22070219890120', '{\r\n\"bank_name\": \"招商银行\",\r\n\"province\": \"浙江省\",\r\n\"city\": \"杭州市\",\r\n\"area\": \"滨江区\",\r\n\"bank_branch\": \"钱塘支行\"\r\n}');
INSERT INTO `tr_member_withdraw` VALUES ('2', '1', '100.00', '2', '1', '审核通过', '1553065944', '1553065944', '2019-03-20 16:18:35', '2', '13588269863', '');
INSERT INTO `tr_member_withdraw` VALUES ('3', '1', '100.00', '3', '1', '拒绝', '1553065944', '1553065944', '2019-03-20 16:18:28', '3', 'weixin', '');
INSERT INTO `tr_member_withdraw` VALUES ('4', '1', '100.00', '3', '1', 'asd', '1553065944', '1553083773', '2019-03-20 20:09:33', '2', '13588269863', '');

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
  `url` varchar(255) DEFAULT '' COMMENT '优惠券链接',
  `coupon_url` varchar(255) DEFAULT '' COMMENT '优惠券链接',
  `sale_num` int(11) DEFAULT '0' COMMENT '销量',
  `sort` int(11) DEFAULT '0' COMMENT '商品排序',
  `state` tinyint(1) DEFAULT '1',
  `created_at` int(11) DEFAULT '0',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tr_product
-- ----------------------------
INSERT INTO `tr_product` VALUES ('0', 'sn0001', '房源管理', 'asda', '1', '1500.00', '100.00', '1400.00', '1553011200', '1584547200', '100', '90', '10', 'http://www.iqiyi.com/v_19rr8sbot0.html#vfrm=2-4-0-1', 'asda', '1234', '99', '1', '1554429639', '2019-04-05 10:19:03');

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
-- Records of tr_spec
-- ----------------------------

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
-- Records of tr_spec_item
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
INSERT INTO `tr_sys_admin` VALUES ('1', 'admin', 'd93a5def7511da3d0f2d171d9c344e91', '13588272727', '超级管理员', '132@qq.com', '1', '1', '1554710667', '', '1554622895', '', '107', '0', '1548075651', '2019-04-07 12:13:40');
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
) ENGINE=MyISAM AUTO_INCREMENT=100 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tr_sys_node
-- ----------------------------
INSERT INTO `tr_sys_node` VALUES ('1', '首页', 'system/index/index', '0', '1', '99', '1', '1', '0', '2019-03-16 11:04:41');
INSERT INTO `tr_sys_node` VALUES ('2', '系统管理', '', '0', '1', '0', '1', '1', '0', '2019-03-20 22:34:02');
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
INSERT INTO `tr_sys_node` VALUES ('16', '会员管理', '', '0', '1', '98', '1', '1', '0', '2019-03-27 15:20:16');
INSERT INTO `tr_sys_node` VALUES ('17', '会员信息', 'member/index/index', '16', '1', '9', '1', '1', '0', '2019-03-18 20:22:31');
INSERT INTO `tr_sys_node` VALUES ('18', '添加', 'member/index/add', '17', 'add', '9', '2', '1', '0', '2019-03-18 21:39:33');
INSERT INTO `tr_sys_node` VALUES ('19', '编辑', 'member/index/edit', '17', 'edit', '8', '2', '1', '0', '2019-03-18 21:39:34');
INSERT INTO `tr_sys_node` VALUES ('20', '删除', 'member/index/del', '17', 'del', '7', '2', '1', '0', '2019-03-18 21:39:35');
INSERT INTO `tr_sys_node` VALUES ('23', '会员明细', 'member/flow/index', '16', '1', '8', '1', '1', '0', '2019-03-20 10:59:41');
INSERT INTO `tr_sys_node` VALUES ('24', '提现申请', 'member/withdraw/index', '16', '1', '7', '1', '1', '0', '2019-03-20 11:00:29');
INSERT INTO `tr_sys_node` VALUES ('25', '审核', 'member/withdraw/audit', '24', 'audit', '9', '2', '1', '0', '2019-03-20 18:13:03');
INSERT INTO `tr_sys_node` VALUES ('26', '分佣比例', 'member/fee/index', '16', '1', '6', '1', '3', '0', '2019-04-07 12:13:09');
INSERT INTO `tr_sys_node` VALUES ('27', '编辑', 'member/fee/edit', '26', 'edit', '9', '2', '3', '0', '2019-04-07 12:13:10');
INSERT INTO `tr_sys_node` VALUES ('28', '运营管理', '', '0', '1', '96', '1', '1', '0', '2019-03-20 23:19:25');
INSERT INTO `tr_sys_node` VALUES ('29', '轮播图', 'manage/banner/index', '28', '1', '9', '1', '1', '0', '2019-03-20 23:20:58');
INSERT INTO `tr_sys_node` VALUES ('30', '添加', 'manage/banner/add', '29', 'add', '9', '2', '1', '0', '2019-03-20 23:21:32');
INSERT INTO `tr_sys_node` VALUES ('31', '编辑', 'manage/banner/edit', '29', 'edit', '8', '2', '1', '0', '2019-03-20 23:23:01');
INSERT INTO `tr_sys_node` VALUES ('32', '删除', 'manage/banner/del', '29', 'del', '7', '2', '1', '0', '2019-03-20 23:23:07');
INSERT INTO `tr_sys_node` VALUES ('33', '系统公告', 'manage/notice/index', '28', '1', '7', '1', '1', '0', '2019-03-24 22:26:43');
INSERT INTO `tr_sys_node` VALUES ('34', '添加', 'manage/notice/add', '33', 'add', '9', '2', '1', '0', '2019-03-23 10:04:36');
INSERT INTO `tr_sys_node` VALUES ('35', '编辑', 'manage/notice/edit', '33', 'edit', '8', '2', '1', '0', '2019-03-23 10:04:42');
INSERT INTO `tr_sys_node` VALUES ('36', '删除', 'manage/notice/del', '33', 'del', '7', '2', '1', '0', '2019-03-23 10:04:48');
INSERT INTO `tr_sys_node` VALUES ('37', '新手指引', 'manage/guide/index', '28', '1', '6', '1', '1', '0', '2019-03-24 22:26:45');
INSERT INTO `tr_sys_node` VALUES ('38', '添加', 'manage/guide/add', '37', 'add', '9', '2', '1', '0', '2019-03-23 10:07:41');
INSERT INTO `tr_sys_node` VALUES ('39', '编辑', 'manage/guide/edit', '37', 'edit', '8', '2', '1', '0', '2019-03-23 10:07:48');
INSERT INTO `tr_sys_node` VALUES ('40', '删除', 'manage/guide/del', '37', 'del', '7', '2', '1', '0', '2019-03-23 10:07:54');
INSERT INTO `tr_sys_node` VALUES ('41', '常见问题', '', '0', '1', '6', '1', '1', '0', '2019-03-27 15:06:07');
INSERT INTO `tr_sys_node` VALUES ('42', '分类管理', 'manage/faqcate/index', '41', '1', '9', '1', '1', '0', '2019-03-23 13:33:28');
INSERT INTO `tr_sys_node` VALUES ('43', '添加', 'manage/faqcate/add', '42', 'add', '8', '2', '1', '0', '2019-03-23 13:56:41');
INSERT INTO `tr_sys_node` VALUES ('44', '编辑', 'manage/faqcate/edit', '42', 'edit', '7', '2', '1', '0', '2019-03-23 13:56:41');
INSERT INTO `tr_sys_node` VALUES ('45', '删除', 'manage/faqcate/del', '42', 'del', '6', '2', '1', '0', '2019-03-23 13:56:43');
INSERT INTO `tr_sys_node` VALUES ('47', '添加', 'manage/faq/add', '46', 'add', '8', '2', '1', '0', '2019-03-23 13:34:20');
INSERT INTO `tr_sys_node` VALUES ('48', '编辑', 'manage/faq/edit', '46', 'edit', '7', '2', '1', '0', '2019-03-23 13:34:10');
INSERT INTO `tr_sys_node` VALUES ('49', '删除', 'manage/faq/del', '46', 'del', '6', '2', '1', '0', '2019-03-23 13:32:55');
INSERT INTO `tr_sys_node` VALUES ('46', '文章列表', 'manage/faq/index', '41', '1', '9', '1', '1', '0', '2019-03-23 13:32:52');
INSERT INTO `tr_sys_node` VALUES ('50', '社区管理', '', '0', '1', '5', '1', '1', '0', '2019-03-24 10:32:44');
INSERT INTO `tr_sys_node` VALUES ('51', '社区分类', 'article/cate/index', '50', '1', '8', '1', '1', '0', '2019-03-24 17:07:14');
INSERT INTO `tr_sys_node` VALUES ('52', '添加', 'article/cate/add', '51', 'add', '9', '2', '1', '0', '2019-03-24 10:39:28');
INSERT INTO `tr_sys_node` VALUES ('53', '编辑', 'article/cate/edit', '51', 'edit', '8', '2', '1', '0', '2019-03-24 10:39:02');
INSERT INTO `tr_sys_node` VALUES ('54', '删除', 'article/cate/del', '51', 'del', '7', '2', '1', '0', '2019-03-24 10:39:30');
INSERT INTO `tr_sys_node` VALUES ('55', '文章列表', 'article/article/index', '50', '1', '9', '1', '1', '0', '2019-03-24 17:07:24');
INSERT INTO `tr_sys_node` VALUES ('56', '添加', 'article/article/add', '55', 'add', '9', '2', '1', '0', '2019-03-24 10:39:32');
INSERT INTO `tr_sys_node` VALUES ('57', '编辑', 'article/article/edit', '55', 'edit', '8', '2', '1', '0', '2019-03-24 10:39:33');
INSERT INTO `tr_sys_node` VALUES ('58', '删除', 'article/article/del', '55', 'del', '7', '2', '1', '0', '2019-03-24 10:39:34');
INSERT INTO `tr_sys_node` VALUES ('59', '导航管理', 'manage/nav/index', '28', '1', '8', '1', '1', '0', '2019-03-24 22:26:48');
INSERT INTO `tr_sys_node` VALUES ('60', '添加', 'manage/nav/add', '59', 'add', '9', '2', '1', '0', '2019-03-24 22:27:22');
INSERT INTO `tr_sys_node` VALUES ('61', '编辑', 'manage/nav/edit', '59', 'edit', '8', '2', '1', '0', '2019-03-24 22:27:23');
INSERT INTO `tr_sys_node` VALUES ('62', '删除', 'manage/nav/del', '59', 'del', '7', '2', '1', '0', '2019-03-24 22:27:24');
INSERT INTO `tr_sys_node` VALUES ('63', '商品管理', '', '0', '1', '97', '1', '1', '0', '2019-03-27 15:20:18');
INSERT INTO `tr_sys_node` VALUES ('64', '商品信息', 'product/info/index', '63', '1', '9', '1', '1', '0', '2019-04-03 09:27:18');
INSERT INTO `tr_sys_node` VALUES ('65', '添加', 'product/info/add', '64', 'add', '9', '2', '1', '0', '2019-04-03 09:27:33');
INSERT INTO `tr_sys_node` VALUES ('66', '编辑', 'product/info/edit', '64', 'edit', '8', '2', '1', '0', '2019-04-03 09:27:47');
INSERT INTO `tr_sys_node` VALUES ('67', '删除', 'product/info/del', '64', 'del', '7', '2', '1', '0', '2019-04-03 09:27:58');
INSERT INTO `tr_sys_node` VALUES ('68', '商品分类', 'goods/cate/index', '63', '1', '8', '1', '1', '0', '2019-03-27 15:09:09');
INSERT INTO `tr_sys_node` VALUES ('69', '添加', 'goods/cate/add', '68', 'add', '9', '2', '1', '0', '2019-03-27 15:12:10');
INSERT INTO `tr_sys_node` VALUES ('70', '编辑', 'goods/cate/edit', '68', 'edit', '8', '2', '1', '0', '2019-03-27 15:12:12');
INSERT INTO `tr_sys_node` VALUES ('71', '删除', 'goods/cate/del', '68', 'del', '7', '2', '1', '0', '2019-03-27 15:12:14');
INSERT INTO `tr_sys_node` VALUES ('85', '添加', 'goods/attr/add', '76', 'add', '9', '2', '3', '0', '2019-04-03 09:28:50');
INSERT INTO `tr_sys_node` VALUES ('86', '编辑', 'goods/attr/edit', '76', 'edit', '8', '2', '3', '0', '2019-04-03 09:28:50');
INSERT INTO `tr_sys_node` VALUES ('87', '删除', 'goods/attr/del', '76', 'del', '7', '2', '3', '0', '2019-04-03 09:28:51');
INSERT INTO `tr_sys_node` VALUES ('76', '属性列表', 'goods/cate/attr', '68', 'attr', '6', '3', '3', '0', '2019-04-03 09:28:30');
INSERT INTO `tr_sys_node` VALUES ('77', '商品属性', 'goods/attr/index', '63', '1', '6', '1', '3', '0', '2019-04-03 09:28:28');
INSERT INTO `tr_sys_node` VALUES ('78', '添加', 'goods/attr/add', '77', 'add', '9', '2', '3', '0', '2019-04-03 09:28:26');
INSERT INTO `tr_sys_node` VALUES ('79', '编辑', 'goods/attr/edit', '77', 'edit', '8', '2', '3', '0', '2019-04-03 09:28:26');
INSERT INTO `tr_sys_node` VALUES ('80', '删除', 'goods/attr/del', '77', 'del', '7', '2', '3', '0', '2019-04-03 09:28:25');
INSERT INTO `tr_sys_node` VALUES ('81', '商品规格', 'goods/spec/index', '63', '1', '5', '1', '3', '0', '2019-04-03 09:28:21');
INSERT INTO `tr_sys_node` VALUES ('82', '添加', 'goods/spec/add', '81', 'add', '9', '2', '3', '0', '2019-04-03 09:28:21');
INSERT INTO `tr_sys_node` VALUES ('83', '编辑', 'goods/spec/edit', '81', 'edit', '8', '2', '3', '0', '2019-04-03 09:28:22');
INSERT INTO `tr_sys_node` VALUES ('84', '删除', 'goods/spec/del', '81', 'del', '7', '2', '3', '0', '2019-04-03 09:28:23');
INSERT INTO `tr_sys_node` VALUES ('89', '渠道管理', '', '0', '1', '96', '1', '1', '0', '2019-04-07 10:24:01');
INSERT INTO `tr_sys_node` VALUES ('90', '渠道信息', 'channel/info/index', '89', '1', '9', '1', '1', '0', '2019-04-07 10:24:22');
INSERT INTO `tr_sys_node` VALUES ('91', '添加', 'channel/info/add', '90', 'add', '9', '2', '1', '0', '2019-04-07 11:12:12');
INSERT INTO `tr_sys_node` VALUES ('92', '编辑', 'channel/info/edit', '90', 'edit', '8', '2', '1', '0', '2019-04-07 11:12:14');
INSERT INTO `tr_sys_node` VALUES ('93', '删除', 'channel/info/del', '90', 'del', '7', '2', '1', '0', '2019-04-07 11:12:21');
INSERT INTO `tr_sys_node` VALUES ('94', '分佣管理', '', '0', '1', '95', '1', '1', '0', '2019-04-08 16:25:00');
INSERT INTO `tr_sys_node` VALUES ('95', '分佣记录', 'commission/settlement/index', '94', '1', '9', '1', '1', '0', '2019-04-08 17:12:36');
INSERT INTO `tr_sys_node` VALUES ('96', '结算', 'commission/settlement/start', '95', 'settle', '9', '2', '1', '0', '2019-04-08 17:17:04');
INSERT INTO `tr_sys_node` VALUES ('97', '明细', 'commission/settlement/detail', '95', 'detail', '8', '2', '1', '0', '2019-04-08 19:57:53');
INSERT INTO `tr_sys_node` VALUES ('99', '发放', 'commission/settlement/pay', '95', 'pay', '7', '2', '1', '0', '2019-04-08 19:57:34');

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
INSERT INTO `tr_sys_role` VALUES ('1', '超级管理员', '', '1', '1548145765', '2019-03-19 18:57:35');
INSERT INTO `tr_sys_role` VALUES ('2', '管理员', '1,2,3,4,5,7,8,9,10', '1', '1548145765', '2019-03-16 16:55:19');
INSERT INTO `tr_sys_role` VALUES ('3', '客服', '', '1', '1552632230', '2019-03-23 14:13:40');
INSERT INTO `tr_sys_role` VALUES ('4', '运营', '', '1', '1552632386', '2019-03-15 15:31:52');

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

-- ----------------------------
-- Records of tr_ticket_cate
-- ----------------------------
