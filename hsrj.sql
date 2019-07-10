/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : hsrj

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2019-07-10 16:57:07
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
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tr_article
-- ----------------------------
INSERT INTO `tr_article` VALUES ('1', '4', '一万个美丽的未来', '花销Q', '1', '&lt;p&gt;safsfsdfsfffs&lt;img src=&quot;/ueditor/php/upload/image/20190324/1553424904.png&quot; title=&quot;1553424904.png&quot; alt=&quot;QQ截图20190111144433.png&quot;/&gt;&lt;/p&gt;', '99', '1', '0', '1553424906', '2019-06-25 18:46:58');
INSERT INTO `tr_article` VALUES ('2', '1', 'ceshi', 'sfa', '1', '&lt;p&gt;asdasda&lt;/p&gt;', '12', '2', '0', '1561354631', '2019-06-25 18:46:56');
INSERT INTO `tr_article` VALUES ('3', '1', 'safsfsf', 'sfdasd', '1', '&lt;p&gt;fsdafdsfsfs&lt;/p&gt;', '1', '2', '0', '1561354762', '2019-06-25 18:46:55');
INSERT INTO `tr_article` VALUES ('4', '1', 'asdasd ', 'adsda', '/Uploads/article_img/2019-06-25/5d11dfb4da5aa.jpg', '&lt;p&gt;asdadas&lt;/p&gt;', '12', '2', '0', '1561452468', '2019-06-25 16:47:48');

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
INSERT INTO `tr_category` VALUES ('1', '手机', '/Uploads/cate_img/2019-05-10/5cd4e0e5d068c.jpg', '0', '0', '1', '1553683405', '2019-05-10 10:24:37');

-- ----------------------------
-- Table structure for tr_channel
-- ----------------------------
DROP TABLE IF EXISTS `tr_channel`;
CREATE TABLE `tr_channel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `state` tinyint(1) NOT NULL DEFAULT '1',
  `channel_rate` float(10,4) NOT NULL DEFAULT '0.0000' COMMENT '普通运营商分佣比例',
  `referee_rate` float(10,4) NOT NULL DEFAULT '0.0000' COMMENT '推荐人分佣比例',
  `member_rate` float(10,4) NOT NULL DEFAULT '0.0000' COMMENT '普通用户分佣比例',
  `total_income` decimal(14,2) NOT NULL DEFAULT '0.00' COMMENT '累计收入',
  `created_at` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tr_channel
-- ----------------------------
INSERT INTO `tr_channel` VALUES ('1', '0', '1', '0.1000', '0.1000', '0.0000', '0.00', '1554607305', '2019-04-11 20:26:22');

-- ----------------------------
-- Table structure for tr_commission
-- ----------------------------
DROP TABLE IF EXISTS `tr_commission`;
CREATE TABLE `tr_commission` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `trade_parent_id` varchar(100) NOT NULL DEFAULT '' COMMENT '淘宝父订单号',
  `trade_id` varchar(100) NOT NULL DEFAULT '' COMMENT '淘宝订单号',
  `num_iid` varchar(100) NOT NULL DEFAULT '' COMMENT '商品ID',
  `item_title` varchar(255) NOT NULL DEFAULT '' COMMENT '商品标题',
  `item_num` smallint(4) NOT NULL DEFAULT '1' COMMENT '商品数量',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '单价',
  `pay_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '实际支付金额',
  `seller_nick` varchar(255) NOT NULL DEFAULT '' COMMENT '卖家昵称',
  `seller_shop_title` varchar(255) NOT NULL DEFAULT '' COMMENT '卖家店铺名称',
  `commission` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '推广者获得的收入金额，对应联盟后台报表“预估收入”',
  `commission_rate` float(10,4) NOT NULL DEFAULT '0.0000' COMMENT '推广者获得的分成比率，对应联盟后台报表“分成比率”',
  `create_time` varchar(50) NOT NULL DEFAULT '' COMMENT '淘客订单创建时间',
  `earning_time` varchar(50) NOT NULL DEFAULT '' COMMENT '订单确认收货后且商家完成佣金支付的时间',
  `tk_status` tinyint(2) NOT NULL DEFAULT '12' COMMENT '淘客订单状态，3：订单结算，12：订单付款， 13：订单失效，14：订单成功',
  `tk3rd_type` varchar(255) NOT NULL DEFAULT '' COMMENT '第三方服务来源，没有第三方服务，取值为“--”',
  `tk3rd_pub_id` varchar(100) NOT NULL DEFAULT '' COMMENT '第三方推广者ID',
  `order_type` varchar(255) NOT NULL DEFAULT '' COMMENT '订单类型，如天猫，淘宝',
  `income_rate` float(10,4) NOT NULL DEFAULT '0.0000' COMMENT '收入比率，卖家设置佣金比率+平台补贴比率',
  `pub_share_pre_fee` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '效果预估，付款金额*(佣金比率+补贴比率)*分成比率',
  `subsidy_rate` float(10,4) NOT NULL DEFAULT '0.0000' COMMENT '补贴比率',
  `subsidy_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '补贴类型，天猫:1，聚划算:2，航旅:3，阿里云:4',
  `terminal_type` tinyint(1) NOT NULL DEFAULT '2' COMMENT '成交平台，PC:1，无线:2',
  `auction_category` varchar(255) NOT NULL DEFAULT '' COMMENT '类目名称',
  `site_id` varchar(100) NOT NULL DEFAULT '' COMMENT '来源媒体ID',
  `site_name` varchar(255) NOT NULL DEFAULT '' COMMENT '来源媒体名称',
  `adzone_id` varchar(100) NOT NULL DEFAULT '' COMMENT '广告位ID',
  `adzone_name` varchar(255) NOT NULL DEFAULT '' COMMENT '广告位名称',
  `alipay_total_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '付款金额',
  `total_commission_fee` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '佣金金额',
  `total_commission_rate` float(10,4) NOT NULL DEFAULT '0.0000' COMMENT '佣金比率',
  `subsidy_fee` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '补贴金额',
  `click_time` varchar(50) NOT NULL DEFAULT '' COMMENT '跟踪时间',
  `relation_id` varchar(100) NOT NULL DEFAULT '' COMMENT '渠道关系ID',
  `special_id` varchar(100) NOT NULL DEFAULT '' COMMENT '会员运营id',
  `member_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态 0 未匹配 1 已匹配 2 已结算',
  `created_at` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `tb_paid_time` varchar(50) NOT NULL DEFAULT '' COMMENT '订单在淘宝拍下付款的时间',
  `tk_paid_time` varchar(50) NOT NULL DEFAULT '' COMMENT '订单付款的时间，该时间同步淘宝，可能会略晚于买家在淘宝的订单创建时间',
  `tk_order_role` varchar(11) NOT NULL DEFAULT '' COMMENT '二方：佣金收益的第一归属者； 三方：从其他淘宝客佣金中进行分成的推广者',
  `refund_tag` tinyint(1) NOT NULL DEFAULT '0' COMMENT '维权标签，0 含义为非维权 1 含义为维权订单。即如果订单发生维权退款，会给予提示标识。所有的维权推广订单也能在维权推广订单API查询。',
  `tk_total_rate` varchar(20) NOT NULL DEFAULT '0' COMMENT '提成=收入比率*分成比率。指实际获得收益的比率',
  `alimama_rate` varchar(20) NOT NULL DEFAULT '0' COMMENT '推广者赚取佣金后支付给阿里妈妈的技术服务费用的比率',
  `alimama_share_fee` varchar(20) NOT NULL DEFAULT '0' COMMENT '技术服务费=结算金额*收入比率*技术服务费率。推广者赚取佣金后支付给阿里妈妈的技术服务费用',
  `item_img` varchar(255) NOT NULL DEFAULT '' COMMENT '商品图片',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tr_commission
-- ----------------------------
INSERT INTO `tr_commission` VALUES ('12', '504593984237542270', '504593984237542270', '580398164028', '常吃掌记掌心脆干脆面方便面干吃面混装90后怀旧休闲零食整箱批发', '1', '12.00', '0.00', '三只小熊食品专营店', '三只小熊食品专营店', '0.00', '1.0000', '2019-06-26 12:12:14', '', '12', '', '', '天猫', '0.3000', '2.07', '0.0000', '1', '2', '食品-零食/特产', '391700292', '', '108697100321', '', '6.90', '0.00', '0.3000', '0.00', '2019-06-26 12:11:39', '', '2143645485', '21', '2', '1561522501', '2019-07-04 11:00:07', '', '', '', '0', '0', '0', '0', '');
INSERT INTO `tr_commission` VALUES ('13', '504593984237542270', '504593984237542270', '580398164028', '常吃掌记掌心脆干脆面方便面干吃面混装90后怀旧休闲零食整箱批发', '1', '12.00', '0.00', '三只小熊食品专营店', '三只小熊食品专营店', '0.00', '1.0000', '2019-06-26 12:12:14', '', '12', '', '', '天猫', '0.3000', '2.07', '0.0000', '1', '2', '食品-零食/特产', '391700292', '', '108697100321', '', '6.90', '0.00', '0.3000', '0.00', '2019-06-26 12:11:39', '', '558178813', '1', '2', '1561522472', '2019-07-04 11:00:07', '', '', '', '0', '0', '0', '0', '');
INSERT INTO `tr_commission` VALUES ('14', '506035296535505116', '506035296535505116', '592658805260', '3瓶|红石榴防晒霜喷雾SPF50+脖子变美白防水紫外线面部男女一抹白', '1', '76.90', '0.00', '鑫牌一号', '小美护肤TB专柜', '0.00', '1.0000', '2019-06-27 14:55:00', '', '12', '', '', '淘宝', '0.1000', '3.19', '0.0000', '1', '2', '美妆-美容护肤', '391700292', '', '108697100321', '', '31.90', '0.00', '0.1000', '0.00', '2019-06-27 14:54:30', '', '2140442961', '20', '2', '1561618801', '2019-07-04 11:00:07', '', '', '', '0', '0', '0', '0', '');
INSERT INTO `tr_commission` VALUES ('15', '508244867116549507', '508244867116549507', '4629835786', '舒客舒克声波电动牙刷软毛成人防水家用电动牙刷充电自动牙刷G22', '1', '209.00', '0.00', '舒客官方旗舰店', '舒客官方旗舰店', '0.00', '1.0000', '2019-06-28 14:32:32', '', '12', '', '', '天猫', '0.2000', '19.80', '0.0000', '1', '2', '美容美体仪器', '391700292', '', '108697100321', '', '99.00', '0.00', '0.2000', '0.00', '2019-06-28 13:57:41', '', '2140458761', '19', '2', '1561703701', '2019-07-04 11:00:07', '', '', '', '0', '0', '0', '0', '');
INSERT INTO `tr_commission` VALUES ('16', '507371552175505116', '507371552175505116', '592696623251', '爱威康测胃幽门螺旋杆菌检测试纸试剂医用口臭胃痛胃病HP快速检测', '1', '68.00', '0.00', '澳凯信医疗器械专营店', '澳凯信医疗器械专营店', '0.00', '1.0000', '2019-06-28 16:00:10', '', '12', '', '', '天猫', '0.2000', '1.96', '0.0000', '1', '2', 'OTC药品/医疗器械', '391700292', '', '108697100321', '', '9.80', '0.00', '0.2000', '0.00', '2019-06-28 15:59:01', '', '2140442961', '20', '2', '1561709101', '2019-07-04 11:00:07', '', '', '', '0', '0', '0', '0', '');
INSERT INTO `tr_commission` VALUES ('18', '508803746401194632', '508803746401194632', '565290850993', '冰凉贴降温神器解暑防暑降暑冰贴夏天学生可爱手机散热贴清凉喷雾', '1', '5.10', '0.00', 'sen270239784', '九玺冰凉贴', '0.00', '1.0000', '2019-06-29 09:18:38', '', '12', '', '', '淘宝', '0.2000', '0.36', '0.0000', '1', '2', '居家日用', '391700292', '', '108697500236', '', '1.80', '0.00', '0.2000', '0.00', '2019-06-29 09:18:17', '2193292517', '', '51', '2', '1561779720', '2019-07-04 11:00:07', '', '', '', '0', '0', '0', '0', '');
INSERT INTO `tr_commission` VALUES ('19', '509920899386182545', '509920899386182545', '567778813973', '儿童凉鞋男童鞋子韩版宝宝女童小童软底中大童沙滩鞋2019夏季新款', '1', '228.00', '0.00', '米修服饰专营店', '米修服饰专营店', '0.00', '1.0000', '2019-06-29 19:35:59', '', '12', '', '', '天猫', '0.0504', '3.48', '0.0000', '1', '2', '母婴-童鞋', '391700292', '', '108697100321', '', '69.00', '0.00', '0.0504', '0.00', '2019-06-29 19:35:14', '', '2191048587', '72', '2', '1561808402', '2019-07-04 11:00:07', '', '', '', '0', '0', '0', '0', '');
INSERT INTO `tr_commission` VALUES ('20', '510752640025182545', '510752640025182545', '586123511590', '【带收纳箱】大娃娃套装女孩公主洋娃娃儿童过家家玩具别墅屋', '1', '188.74', '0.00', '无敌小先锋2', '特惠大商场', '0.00', '1.0000', '2019-07-01 07:17:07', '', '12', '', '', '淘宝', '0.3001', '44.64', '0.0000', '1', '2', '母婴-玩具', '391700292', '', '108697100321', '', '148.74', '0.00', '0.3001', '0.00', '2019-07-01 07:14:47', '', '2191048587', '72', '2', '1561936801', '2019-07-04 11:00:07', '', '', '', '0', '0', '0', '0', '');
INSERT INTO `tr_commission` VALUES ('21', '287032391100742887', '287032391100742887', '590084304040', '垃圾袋家用手提式加厚黑色一次性小号大号拉圾塑料袋抽绳自动收口', '1', '9.90', '0.00', '多家宜旗舰店', '多家宜旗舰店', '0.00', '1.0000', '2019-07-01 13:53:19', '', '12', '', '', '天猫', '0.2000', '0.78', '0.0000', '1', '2', '家庭/个人清洁工具', '391700292', '', '108697100321', '', '3.90', '0.00', '0.2000', '0.00', '2019-07-01 13:52:39', '', '2196050367', '64', '2', '1561960501', '2019-07-04 11:00:07', '', '', '', '0', '0', '0', '0', '');
INSERT INTO `tr_commission` VALUES ('22', '511328256574739004', '511328256574739004', '584466588447', '车载手机支架汽车用吸盘式万能通用型导航支驾支撑夹车内车上卡扣', '1', '129.00', '0.00', 'bshtir旗舰店', 'bshtir旗舰店', '0.00', '1.0000', '2019-07-01 14:14:11', '', '12', '', '', '天猫', '0.1000', '2.50', '0.0000', '1', '2', '汽车用品', '391700292', '', '108697100321', '', '25.00', '0.00', '0.1000', '0.00', '2019-07-01 14:12:57', '', '2179791050', '43', '2', '1561961702', '2019-07-04 11:00:07', '', '', '', '0', '0', '0', '0', '');
INSERT INTO `tr_commission` VALUES ('23', '512424513106505116', '512424513106505116', '596191804936', '男士短袖t恤夏季韩版潮男装运动休闲宽松套装加肥加大码胖子夏装', '1', '109.00', '0.00', '尔奇时尚馆', '尔奇时尚馆', '0.00', '1.0000', '2019-07-01 22:36:14', '', '12', '', '', '淘宝', '0.0500', '4.95', '0.0000', '1', '2', '男装', '391700292', '', '108697100321', '', '99.00', '0.00', '0.0500', '0.00', '2019-07-01 22:35:06', '', '2140442961', '20', '2', '1561992002', '2019-07-04 11:00:07', '', '', '', '0', '0', '0', '0', '');
INSERT INTO `tr_commission` VALUES ('24', '512778144856739004', '512778144856739004', '596567705113', 'viniya本色抽纸批发整箱家庭装家用实惠装加厚纸巾卫生纸抽餐巾纸', '1', '59.90', '0.00', 'viniya旗舰店', 'viniya旗舰店', '0.00', '1.0000', '2019-07-02 14:03:17', '', '12', '', '', '天猫', '0.1300', '1.16', '0.0000', '1', '2', '洗护用品', '391700292', '', '108697100321', '', '8.90', '0.00', '0.1300', '0.00', '2019-07-02 14:02:54', '', '2179791050', '43', '2', '1562047501', '2019-07-04 11:00:07', '', '', '', '0', '0', '0', '0', '');
INSERT INTO `tr_commission` VALUES ('25', '512776736861739004', '512776736861739004', '596567705113', 'viniya本色抽纸批发整箱家庭装家用实惠装加厚纸巾卫生纸抽餐巾纸', '1', '59.90', '0.00', 'viniya旗舰店', 'viniya旗舰店', '0.00', '1.0000', '2019-07-02 14:02:18', '', '12', '', '', '天猫', '0.1300', '1.16', '0.0000', '1', '2', '洗护用品', '391700292', '', '108697100321', '', '8.90', '0.00', '0.1300', '0.00', '2019-07-02 14:01:17', '', '2179791050', '43', '2', '1562047501', '2019-07-04 11:00:07', '', '', '', '0', '0', '0', '0', '');
INSERT INTO `tr_commission` VALUES ('26', '515065794929912978', '515065794929912978', '546142431184', '网红泰迪狗窝可拆洗夏季猫窝四季通用中小型犬宠物垫夏天凉窝用品', '1', '30.00', '0.00', '许哒少', '狗狗之家工厂店', '0.00', '1.0000', '2019-07-03 15:12:40', '', '12', '', '', '淘宝', '0.0664', '1.25', '0.0000', '1', '2', '宠物用品', '391700292', '', '108697100321', '', '18.90', '0.00', '0.0664', '0.00', '2019-07-03 15:10:23', '', '2198124383', '90', '2', '1562138102', '2019-07-04 11:00:07', '', '', '', '0', '0', '0', '0', '');
INSERT INTO `tr_commission` VALUES ('27', '515974018569140120', '515974018569140120', '579575817658', '舒克舒客宝贝婴儿湿巾纸巾婴幼儿新生宝宝手口专用无香80抽5包', '1', '84.50', '0.00', 'sakykids舒客宝贝旗舰店', 'sakykids舒客宝贝旗舰店', '0.00', '1.0000', '2019-07-04 09:31:16', '', '12', '', '', '天猫', '0.3000', '3.87', '0.0000', '1', '2', '母婴-尿片洗护', '391700292', '', '108697100321', '', '12.90', '0.00', '0.3000', '0.00', '2019-07-04 09:30:48', '', '2196041510', '26', '2', '1562204101', '2019-07-04 11:00:07', '', '', '', '0', '0', '0', '0', '');
INSERT INTO `tr_commission` VALUES ('28', '515986722712140120', '515986722712140120', '588841405371', '王麻子菜刀家用切片刀厨师专用锋利不锈钢切菜刀切肉切片厨房刀具', '1', '200.00', '0.00', '厨房用具特卖', '中华老字号王麻子厨房用具特卖店', '0.00', '1.0000', '2019-07-04 09:41:54', '', '12', '', '', '淘宝', '0.2000', '23.00', '0.0000', '1', '2', '厨房/烹饪用具', '391700292', '', '108697100321', '', '115.00', '0.00', '0.2000', '0.00', '2019-07-04 09:39:39', '', '2196041510', '26', '2', '1562204701', '2019-07-04 11:00:07', '', '', '', '0', '0', '0', '0', '');

-- ----------------------------
-- Table structure for tr_commission_detail
-- ----------------------------
DROP TABLE IF EXISTS `tr_commission_detail`;
CREATE TABLE `tr_commission_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL DEFAULT '0' COMMENT '订单ID type为1时有效',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1分享分佣 2一级推荐分佣 3二级推荐分佣',
  `member_id` int(1) NOT NULL DEFAULT '0' COMMENT '会员id',
  `partner_id` int(11) NOT NULL DEFAULT '0' COMMENT '合伙人id',
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '分佣金额',
  `descr` varchar(255) NOT NULL DEFAULT '' COMMENT '分佣来源描述',
  `created_at` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=245 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tr_commission_detail
-- ----------------------------
INSERT INTO `tr_commission_detail` VALUES ('208', '12', '1', '21', '0', '1.04', '自购分佣', '1562209207', '2019-07-04 11:00:07');
INSERT INTO `tr_commission_detail` VALUES ('209', '12', '4', '1', '0', '0.75', '运营商分佣', '1562209207', '2019-07-04 11:00:07');
INSERT INTO `tr_commission_detail` VALUES ('210', '13', '1', '1', '0', '1.78', '自购分佣', '1562209207', '2019-07-04 11:00:07');
INSERT INTO `tr_commission_detail` VALUES ('211', '14', '1', '20', '0', '1.60', '自购分佣', '1562209207', '2019-07-04 11:00:07');
INSERT INTO `tr_commission_detail` VALUES ('212', '14', '4', '1', '0', '1.15', '运营商分佣', '1562209207', '2019-07-04 11:00:07');
INSERT INTO `tr_commission_detail` VALUES ('213', '16', '1', '20', '0', '0.98', '自购分佣', '1562209207', '2019-07-04 11:00:07');
INSERT INTO `tr_commission_detail` VALUES ('214', '16', '4', '1', '0', '0.71', '运营商分佣', '1562209207', '2019-07-04 11:00:07');
INSERT INTO `tr_commission_detail` VALUES ('215', '23', '1', '20', '0', '2.48', '自购分佣', '1562209207', '2019-07-04 11:00:07');
INSERT INTO `tr_commission_detail` VALUES ('216', '23', '4', '1', '0', '1.78', '运营商分佣', '1562209207', '2019-07-04 11:00:07');
INSERT INTO `tr_commission_detail` VALUES ('217', '15', '1', '19', '0', '9.90', '自购分佣', '1562209207', '2019-07-04 11:00:07');
INSERT INTO `tr_commission_detail` VALUES ('218', '15', '4', '1', '0', '7.13', '运营商分佣', '1562209207', '2019-07-04 11:00:07');
INSERT INTO `tr_commission_detail` VALUES ('219', '18', '1', '51', '0', '0.18', '自购分佣', '1562209207', '2019-07-04 11:00:07');
INSERT INTO `tr_commission_detail` VALUES ('220', '18', '4', '1', '0', '0.13', '运营商分佣', '1562209207', '2019-07-04 11:00:07');
INSERT INTO `tr_commission_detail` VALUES ('221', '19', '1', '72', '0', '1.74', '自购分佣', '1562209207', '2019-07-04 11:00:07');
INSERT INTO `tr_commission_detail` VALUES ('222', '19', '3', '52', '0', '0.35', '推荐分佣', '1562209207', '2019-07-04 11:00:07');
INSERT INTO `tr_commission_detail` VALUES ('223', '19', '4', '1', '0', '0.90', '运营商分佣', '1562209207', '2019-07-04 11:00:07');
INSERT INTO `tr_commission_detail` VALUES ('224', '20', '1', '72', '0', '22.32', '自购分佣', '1562209207', '2019-07-04 11:00:07');
INSERT INTO `tr_commission_detail` VALUES ('225', '20', '3', '52', '0', '4.46', '推荐分佣', '1562209207', '2019-07-04 11:00:07');
INSERT INTO `tr_commission_detail` VALUES ('226', '20', '4', '1', '0', '11.61', '运营商分佣', '1562209207', '2019-07-04 11:00:07');
INSERT INTO `tr_commission_detail` VALUES ('227', '21', '1', '64', '0', '0.39', '自购分佣', '1562209207', '2019-07-04 11:00:07');
INSERT INTO `tr_commission_detail` VALUES ('228', '21', '4', '22', '0', '0.22', '运营商分佣', '1562209207', '2019-07-04 11:00:07');
INSERT INTO `tr_commission_detail` VALUES ('229', '21', '4', '1', '0', '0.06', '运营商分佣', '1562209207', '2019-07-04 11:00:07');
INSERT INTO `tr_commission_detail` VALUES ('230', '22', '1', '43', '0', '1.25', '自购分佣', '1562209207', '2019-07-04 11:00:07');
INSERT INTO `tr_commission_detail` VALUES ('231', '22', '3', '33', '0', '0.25', '推荐分佣', '1562209207', '2019-07-04 11:00:07');
INSERT INTO `tr_commission_detail` VALUES ('232', '22', '4', '1', '0', '0.65', '运营商分佣', '1562209207', '2019-07-04 11:00:07');
INSERT INTO `tr_commission_detail` VALUES ('233', '24', '1', '43', '0', '0.58', '自购分佣', '1562209207', '2019-07-04 11:00:07');
INSERT INTO `tr_commission_detail` VALUES ('234', '24', '3', '33', '0', '0.12', '推荐分佣', '1562209207', '2019-07-04 11:00:07');
INSERT INTO `tr_commission_detail` VALUES ('235', '24', '4', '1', '0', '0.30', '运营商分佣', '1562209207', '2019-07-04 11:00:07');
INSERT INTO `tr_commission_detail` VALUES ('236', '25', '1', '43', '0', '0.58', '自购分佣', '1562209207', '2019-07-04 11:00:07');
INSERT INTO `tr_commission_detail` VALUES ('237', '25', '3', '33', '0', '0.12', '推荐分佣', '1562209207', '2019-07-04 11:00:07');
INSERT INTO `tr_commission_detail` VALUES ('238', '25', '4', '1', '0', '0.30', '运营商分佣', '1562209207', '2019-07-04 11:00:07');
INSERT INTO `tr_commission_detail` VALUES ('239', '26', '1', '90', '0', '0.63', '自购分佣', '1562209207', '2019-07-04 11:00:07');
INSERT INTO `tr_commission_detail` VALUES ('240', '26', '4', '1', '0', '0.45', '运营商分佣', '1562209207', '2019-07-04 11:00:07');
INSERT INTO `tr_commission_detail` VALUES ('241', '27', '1', '26', '0', '1.94', '自购分佣', '1562209207', '2019-07-04 11:00:07');
INSERT INTO `tr_commission_detail` VALUES ('242', '27', '4', '1', '0', '1.39', '运营商分佣', '1562209207', '2019-07-04 11:00:07');
INSERT INTO `tr_commission_detail` VALUES ('243', '28', '1', '26', '0', '11.50', '自购分佣', '1562209207', '2019-07-04 11:00:07');
INSERT INTO `tr_commission_detail` VALUES ('244', '28', '4', '1', '0', '8.28', '运营商分佣', '1562209207', '2019-07-04 11:00:07');

-- ----------------------------
-- Table structure for tr_commission_v1
-- ----------------------------
DROP TABLE IF EXISTS `tr_commission_v1`;
CREATE TABLE `tr_commission_v1` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `trade_parent_id` varchar(100) NOT NULL DEFAULT '' COMMENT '淘宝父订单号',
  `trade_id` varchar(100) NOT NULL DEFAULT '' COMMENT '淘宝订单号',
  `num_iid` varchar(100) NOT NULL DEFAULT '' COMMENT '商品ID',
  `item_title` varchar(255) NOT NULL DEFAULT '' COMMENT '商品标题',
  `item_num` smallint(4) NOT NULL DEFAULT '1' COMMENT '商品数量',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '单价',
  `pay_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '实际支付金额',
  `seller_nick` varchar(255) NOT NULL DEFAULT '' COMMENT '卖家昵称',
  `seller_shop_title` varchar(255) NOT NULL DEFAULT '' COMMENT '卖家店铺名称',
  `commission` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '推广者获得的收入金额，对应联盟后台报表“预估收入”',
  `commission_rate` float(10,4) NOT NULL DEFAULT '0.0000' COMMENT '推广者获得的分成比率，对应联盟后台报表“分成比率”',
  `create_time` varchar(50) NOT NULL DEFAULT '' COMMENT '淘客订单创建时间',
  `tk_status` tinyint(2) NOT NULL DEFAULT '12' COMMENT '淘客订单状态，3：订单结算，12：订单付款， 13：订单失效，14：订单成功',
  `tk3rd_type` varchar(255) NOT NULL DEFAULT '' COMMENT '第三方服务来源，没有第三方服务，取值为“--”',
  `tk3rd_pub_id` varchar(100) NOT NULL DEFAULT '' COMMENT '第三方推广者ID',
  `order_type` varchar(255) NOT NULL DEFAULT '' COMMENT '订单类型，如天猫，淘宝',
  `income_rate` float(10,4) NOT NULL DEFAULT '0.0000' COMMENT '收入比率，卖家设置佣金比率+平台补贴比率',
  `pub_share_pre_fee` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '效果预估，付款金额*(佣金比率+补贴比率)*分成比率',
  `subsidy_rate` float(10,4) NOT NULL DEFAULT '0.0000' COMMENT '补贴比率',
  `subsidy_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '补贴类型，天猫:1，聚划算:2，航旅:3，阿里云:4',
  `terminal_type` tinyint(1) NOT NULL DEFAULT '2' COMMENT '成交平台，PC:1，无线:2',
  `auction_category` varchar(255) NOT NULL DEFAULT '' COMMENT '类目名称',
  `site_id` varchar(100) NOT NULL DEFAULT '' COMMENT '来源媒体ID',
  `site_name` varchar(255) NOT NULL DEFAULT '' COMMENT '来源媒体名称',
  `adzone_id` varchar(100) NOT NULL DEFAULT '' COMMENT '广告位ID',
  `adzone_name` varchar(255) NOT NULL DEFAULT '' COMMENT '广告位名称',
  `alipay_total_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '付款金额',
  `total_commission_fee` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '佣金金额',
  `total_commission_rate` float(10,4) NOT NULL DEFAULT '0.0000' COMMENT '佣金比率',
  `subsidy_fee` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '补贴金额',
  `click_time` varchar(50) NOT NULL DEFAULT '' COMMENT '跟踪时间',
  `relation_id` varchar(100) NOT NULL DEFAULT '' COMMENT '渠道关系ID',
  `special_id` varchar(100) NOT NULL DEFAULT '' COMMENT '会员运营id',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `referee_id` int(11) NOT NULL DEFAULT '0' COMMENT '一级推荐人ID',
  `grand_id` int(11) NOT NULL DEFAULT '0' COMMENT '二级推荐人ID',
  `channel_id` int(11) NOT NULL DEFAULT '0' COMMENT '渠道ID',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态 0 未匹配 1 已匹配 2 已结算',
  `created_at` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tr_commission_v1
-- ----------------------------
INSERT INTO `tr_commission_v1` VALUES ('1', '4.3576297717001E+17', '4.3576297717001E+17', '577087679869', '车门防撞条汽车门边防撞条防撞贴防擦条汽车防刮条通用型隐形装饰', '1', '8.20', '0.00', '西曼雷特车品旗舰店', '西曼雷特车品旗舰店', '0.00', '1.0000', '2019-05-06 13:22:53', '12', '', '', '天猫', '0.1200', '0.35', '0.0000', '1', '2', '汽车用品', '391700292', '', '108697100321', '', '2.90', '0.00', '0.1200', '0.00', '2019-05-06 13:22:10', '', '558178813', '0', '0', '0', '0', '0', '1557929185', '2019-05-15 22:06:25');
INSERT INTO `tr_commission_v1` VALUES ('2', '435762977170011041', '435762977170011041', '577087679869', '车门防撞条汽车门边防撞条防撞贴防擦条汽车防刮条通用型隐形装饰', '1', '8.20', '0.00', '西曼雷特车品旗舰店', '西曼雷特车品旗舰店', '0.00', '1.0000', '2019-05-06 13:22:53', '12', '', '', '天猫', '0.1200', '0.35', '0.0000', '1', '2', '汽车用品', '391700292', '', '108697100321', '', '2.90', '0.00', '0.1200', '0.00', '2019-05-06 13:22:10', '', '558178813', '0', '0', '0', '0', '0', '1557989462', '2019-05-16 14:51:02');

-- ----------------------------
-- Table structure for tr_commission_v2
-- ----------------------------
DROP TABLE IF EXISTS `tr_commission_v2`;
CREATE TABLE `tr_commission_v2` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `trade_parent_id` varchar(100) NOT NULL DEFAULT '' COMMENT '淘宝父订单号',
  `trade_id` varchar(100) NOT NULL DEFAULT '' COMMENT '淘宝订单号',
  `item_id` varchar(100) NOT NULL DEFAULT '' COMMENT '商品ID',
  `item_title` varchar(255) NOT NULL DEFAULT '' COMMENT '商品标题',
  `item_num` smallint(4) NOT NULL DEFAULT '1' COMMENT '商品数量',
  `item_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '单价',
  `pay_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '实际支付金额',
  `seller_nick` varchar(255) NOT NULL DEFAULT '' COMMENT '卖家昵称',
  `seller_shop_title` varchar(255) NOT NULL DEFAULT '' COMMENT '卖家店铺名称',
  `pub_share_fee` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '推广者获得的收入金额，对应联盟后台报表“预估收入”',
  `pub_share_rate` float(10,4) NOT NULL DEFAULT '0.0000' COMMENT '推广者获得的分成比率，对应联盟后台报表“分成比率”',
  `tk_create_time` varchar(50) NOT NULL DEFAULT '' COMMENT '淘客订单创建时间',
  `tk_status` tinyint(2) NOT NULL DEFAULT '12' COMMENT '淘客订单状态，3：订单结算，12：订单付款， 13：订单失效，14：订单成功',
  `flow_source` varchar(255) NOT NULL DEFAULT '' COMMENT '第三方服务来源，没有第三方服务，取值为“--”',
  `pub_id` varchar(100) NOT NULL DEFAULT '' COMMENT '第三方推广者ID',
  `order_type` varchar(255) NOT NULL DEFAULT '' COMMENT '订单类型，如天猫，淘宝',
  `income_rate` float(10,4) NOT NULL DEFAULT '0.0000' COMMENT '收入比率，卖家设置佣金比率+平台补贴比率',
  `pub_share_pre_fee` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '效果预估，付款金额*(佣金比率+补贴比率)*分成比率',
  `subsidy_rate` float(10,4) NOT NULL DEFAULT '0.0000' COMMENT '补贴比率',
  `subsidy_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '补贴类型，天猫:1，聚划算:2，航旅:3，阿里云:4',
  `terminal_type` tinyint(1) NOT NULL DEFAULT '2' COMMENT '成交平台，PC:1，无线:2',
  `item_category_name` varchar(255) NOT NULL DEFAULT '' COMMENT '类目名称',
  `site_id` varchar(100) NOT NULL DEFAULT '' COMMENT '来源媒体ID',
  `site_name` varchar(255) NOT NULL DEFAULT '' COMMENT '来源媒体名称',
  `adzone_id` varchar(100) NOT NULL DEFAULT '' COMMENT '广告位ID',
  `adzone_name` varchar(255) NOT NULL DEFAULT '' COMMENT '广告位名称',
  `alipay_total_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '付款金额',
  `total_commission_rate` float(10,4) NOT NULL DEFAULT '0.0000' COMMENT '佣金比率',
  `subsidy_fee` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '补贴金额',
  `click_time` varchar(50) NOT NULL DEFAULT '' COMMENT '跟踪时间',
  `relation_id` varchar(100) NOT NULL DEFAULT '' COMMENT '渠道关系ID',
  `special_id` varchar(100) NOT NULL DEFAULT '' COMMENT '会员运营id',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `referee_id` int(11) NOT NULL DEFAULT '0' COMMENT '一级推荐人ID',
  `grand_id` int(11) NOT NULL DEFAULT '0' COMMENT '二级推荐人ID',
  `channel_id` int(11) NOT NULL DEFAULT '0' COMMENT '渠道ID',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态 0 未匹配 1 已匹配 2 已结算',
  `created_at` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `tb_paid_time` varchar(50) NOT NULL DEFAULT '' COMMENT '订单在淘宝拍下付款的时间',
  `tk_paid_time` varchar(50) NOT NULL DEFAULT '' COMMENT '订单付款的时间，该时间同步淘宝，可能会略晚于买家在淘宝的订单创建时间',
  `tk_order_role` varchar(11) NOT NULL DEFAULT '' COMMENT '二方：佣金收益的第一归属者； 三方：从其他淘宝客佣金中进行分成的推广者',
  `refund_tag` tinyint(1) NOT NULL DEFAULT '0' COMMENT '维权标签，0 含义为非维权 1 含义为维权订单。即如果订单发生维权退款，会给予提示标识。所有的维权推广订单也能在维权推广订单API查询。',
  `tk_total_rate` varchar(20) NOT NULL DEFAULT '0' COMMENT '提成=收入比率*分成比率。指实际获得收益的比率',
  `alimama_rate` varchar(20) NOT NULL DEFAULT '0' COMMENT '推广者赚取佣金后支付给阿里妈妈的技术服务费用的比率',
  `alimama_share_fee` varchar(20) NOT NULL DEFAULT '0' COMMENT '技术服务费=结算金额*收入比率*技术服务费率。推广者赚取佣金后支付给阿里妈妈的技术服务费用',
  `item_img` varchar(255) NOT NULL DEFAULT '' COMMENT '商品图片',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tr_commission_v2
-- ----------------------------
INSERT INTO `tr_commission_v2` VALUES ('1', '4.3576297717001E+17', '4.3576297717001E+17', '577087679869', '车门防撞条汽车门边防撞条防撞贴防擦条汽车防刮条通用型隐形装饰', '1', '8.20', '0.00', '西曼雷特车品旗舰店', '西曼雷特车品旗舰店', '0.00', '1.0000', '2019-05-06 13:22:53', '12', '', '', '天猫', '0.1200', '0.35', '0.0000', '1', '2', '汽车用品', '391700292', '', '108697100321', '', '2.90', '0.1200', '0.00', '2019-05-06 13:22:10', '', '558178813', '0', '0', '0', '0', '0', '1557929185', '2019-05-15 22:06:25', '', '', '', '0', '0', '0', '0', '');

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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tr_goods
-- ----------------------------
INSERT INTO `tr_goods` VALUES ('1', '65875', '', '', '', '0', '0', '0', '', '0.00', '0.00', '0.00', '0', '', '', '0', '101', '1', '0', '0', '0', '0', '1', '0', '0', '0', '0', '2019-06-28 17:20:09');

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
  `videoid` varchar(255) DEFAULT '0' COMMENT '商品视频ID（id大于0的为有视频单，视频拼接地址http://cloud.video.taobao.com/play/u/1/p/1/e/6/t/1/+videoid+.mp4）',
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
-- Records of tr_items
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
  `location` varchar(255) NOT NULL DEFAULT '' COMMENT '轮播所属位置 首页主index首页中indexad自营商城主ziying个人中心主myad影视中心video',
  `state` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '链接类型',
  `parm` varchar(255) NOT NULL DEFAULT '' COMMENT '链接参数',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `created_at` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tr_manage_banner
-- ----------------------------
INSERT INTO `tr_manage_banner` VALUES ('1', '轮播i图1', 'www.baidu.com', '/Public/Upload/manage/banner/banner1.jpg', '', '1', '1', '', '0', '1553156575', '2019-03-21 16:23:03');
INSERT INTO `tr_manage_banner` VALUES ('2', '轮播i图1', 'www.baidu.com', '/Public/Upload/manage/banner/banner1.jpg', '', '1', '1', '', '0', '1553156575', '2019-03-21 16:23:03');
INSERT INTO `tr_manage_banner` VALUES ('3', '轮播i图1', 'www.baidu.com', '/Public/Upload/manage/banner/banner1.jpg', '', '1', '1', '', '0', '1553156575', '2019-03-21 16:23:03');
INSERT INTO `tr_manage_banner` VALUES ('4', '轮播i图1', 'www.baidu.com', '/Public/Upload/manage/banner/banner1.jpg', '', '1', '1', '', '0', '1553156575', '2019-03-21 16:23:03');
INSERT INTO `tr_manage_banner` VALUES ('5', '轮播i图1', 'www.baidu.com', '/Public/Upload/manage/banner/banner1.jpg', '', '1', '1', '', '0', '1553156575', '2019-03-21 16:23:03');
INSERT INTO `tr_manage_banner` VALUES ('6', '轮播i图1', 'www.baidu.com', '/Public/Upload/manage/banner/banner1.jpg', '', '1', '1', '', '0', '1553156575', '2019-03-21 16:23:03');
INSERT INTO `tr_manage_banner` VALUES ('7', '轮播i图1', 'www.baidu.com', '/Public/Upload/manage/banner/banner1.jpg', '', '1', '1', '', '0', '1553156575', '2019-03-21 16:23:03');
INSERT INTO `tr_manage_banner` VALUES ('8', '轮播i图1', 'www.baidu.com', '/Public/Upload/manage/banner/banner1.jpg', '', '1', '1', '', '0', '1553156575', '2019-03-21 16:23:03');
INSERT INTO `tr_manage_banner` VALUES ('9', '轮播i图1', 'www.baidu.com', '/Public/Upload/manage/banner/banner1.jpg', '', '1', '1', '', '0', '1553156575', '2019-03-21 16:23:03');
INSERT INTO `tr_manage_banner` VALUES ('10', '轮播i图12', 'www.baidu.com', '/Public/Upload/manage/banner/banner1.jpg', '', '1', '1', '', '12', '1553156575', '2019-03-24 09:58:16');
INSERT INTO `tr_manage_banner` VALUES ('11', '鞋2222', 'asdadas', '/Uploads/banner/2019-06-24/5d106849566ba.jpg', 'index', '1', '2', '', '0', '1561356361', '2019-06-24 14:53:43');

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
  `type` tinyint(1) NOT NULL DEFAULT '1',
  `parm` varchar(255) NOT NULL DEFAULT '',
  `created_at` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tr_manage_nav
-- ----------------------------
INSERT INTO `tr_manage_nav` VALUES ('1', '今日特价', '/Uploads/nav/2019-03-24/5c9797e28b33d.png', 'http://www.iqiyi.com/v_19rr8sbot0.html#vfrm=2-4-0-1', '1', '1', '0', '', '1553438690', '2019-03-24 22:46:37');

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
  `channel_id` int(11) NOT NULL DEFAULT '1' COMMENT '顶级运营商id',
  `special_id` varchar(255) DEFAULT NULL COMMENT '淘宝会员ID',
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
  `last_settle` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '上月结算金额',
  `referee_map` longtext COMMENT '推荐关系 推荐人id逗号分隔',
  `partner_id` int(11) NOT NULL DEFAULT '0' COMMENT '合伙人id',
  `referee_count` int(11) NOT NULL DEFAULT '0' COMMENT '推荐会员数量',
  `relation_id` varchar(20) DEFAULT NULL,
  `invitecode` varchar(10) DEFAULT NULL,
  `zfbaccount` varchar(255) DEFAULT NULL,
  `zfbname` varchar(255) DEFAULT NULL,
  `isvip` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `aadd` (`invitecode`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=94 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tr_member
-- ----------------------------
INSERT INTO `tr_member` VALUES ('1', '小唐人', 'fcea920f7412b5da7be0cf42b8c93759', '13252513125', '0', 'QVRU8J+Slw==', '1', 'http://thirdwx.qlogo.cn/mmopen/vi_32/DYAIOgq83erJ9rkvNTsdMDGpecgGF4lI55FclTHHwG5AibFzpDeiadzdANFl3pqQvHgGmHuxm0AJ4IYMRcTKibNpQ/132', '0', '0', '', '0', '', '0', '1', '558178813', '4', '1554609771', '2019-04-07 12:02:51', '9.00', '0.00', '0.00', '0.00', '0.00', '0', 'oksW41Fbb7y93NM2bk_12QRgBSW4', 'oDTwV5h4i4jpiYLVqevPes7_Nhno', 'QVRU8J+Slw==', '800', '0.00', '0', '0', '0', '131227256', '7U49ZP7T', '13252513125', '李野', '1');
INSERT INTO `tr_member` VALUES ('17', '18643817922', 'c3ae6c112e21d7052a3c4b9b8c9ab5b4', '18643817922', '1', 'QVRU', '1', 'http://thirdwx.qlogo.cn/mmopen/vi_32/DYAIOgq83erJ9rkvNTsdMDGpecgGF4lI55FclTHHwG5AibFzpDeiadzdANFl3pqQvHgGmHuxm0AJ4IYMRcTKibNpQ/132', '0', '0', '', '0', '', '0', '1', null, '1', '1557894068', '2019-05-15 12:21:08', '0.00', '0.00', '0.00', '0.00', '0.00', '2', 'oksW41Fbb7y93NM2bk_12QRgBSW4', 'oDTwV5h4i4jpiYLVqevPes7_Nhno', 'QVRU8J+Slw==', '0', '0.00', '1', '0', '0', null, 'J2S42N9V', null, null, '1');
INSERT INTO `tr_member` VALUES ('18', '15604387921', '6a071a6ae678bfe94aad3c4305c35f09', '15604387921', '1', '5pyq6K6+572u5pi156ew', '1', 'http://jx.tangrenjuhui.com/Public/Tb/img/logo.png', '0', '0', '', '0', '', '0', '1', null, '1', '1557896697', '2019-05-15 13:04:57', '0.00', '0.00', '0.00', '0.00', '0.00', '1', '', '', '', '0', '0.00', '1', '0', '0', null, '3LVZ4PCD', null, null, '1');
INSERT INTO `tr_member` VALUES ('19', '16643122007', '8ea0db8ea227becc4f1b7b5ec502a2d2', '16643122007', '1', '6ams5p6X', '1', 'http://thirdwx.qlogo.cn/mmopen/vi_32/SMMMianhenTTh8WRtugibXRoeJvUcsBlsEwSjCWTPjaqzBgS6S5ibia3h4FWnmTdRw7iariaxWBiat8M1gLId7BRu69DQ/132', '0', '0', '', '0', '', '0', '1', '2140458761', '1', '1557990206', '2019-05-16 15:03:26', '0.00', '0.00', '0.00', '0.00', '0.00', '0', 'oksW41CXk5T55UNTn4jG9HTDjgjk', 'oDTwV5oPC-qYCBqrfNrou3e-O-HM', '6ams5p6X', '375', '0.00', '1', '0', '0', '2140458758', '55555678', null, null, '1');
INSERT INTO `tr_member` VALUES ('20', '18166884184', '1017a6b302e354c2d7df06f107025421', '18166884184', '1', '5aSP5pel5b6u6aOO', '1', 'http://thirdwx.qlogo.cn/mmopen/vi_32/PiajxSqBRaEIRxRbh6qmdqicjp0QiaRJtaibibEVRrQng1EHNQwDaIGlxXILGvyl0qtfiadkia8rtHOGu3W7iaHRghhCVQ/132', '0', '0', '', '0', '', '0', '1', '2140442961', '1', '1557990443', '2019-05-16 15:07:23', '0.00', '0.00', '0.00', '0.00', '0.00', '0', 'oksW41FF-hvXuFR0ezAV-5edflsM', 'oDTwV5kYGZnJD8tXhTBPnA9q2j84', '5aSP5pel5b6u6aOO', '0', '0.00', '1', '0', '0', '2140442956', 'FFRJHPPT', null, null, '1');
INSERT INTO `tr_member` VALUES ('21', '13251751120', '0a17cba0f32524363d17bb13e80e6dca', '13251751120', '1', '5ZWK5ZOI', '1', 'http://jx.tangrenjuhui.com/Public/Tb/img/logo.png', '0', '0', '', '0', '', '0', '1', '2143645485', '1', '1557990477', '2019-05-16 15:07:57', '0.00', '0.00', '0.00', '0.00', '0.00', '0', '', '', '', '0', '0.00', '1', '0', '0', '2143645481', 'NLZ6V3RF', null, null, '1');
INSERT INTO `tr_member` VALUES ('22', '13674353777', '96eab8f0d5f33020460a334fb7e2f7bc', '13674353777', '1', '5pyq6K6+572u5pi156ew', '1', 'http://thirdwx.qlogo.cn/mmopen/vi_32/DYAIOgq83eoQib0mX7mibnxxMczicJgicUZ28psfiaShFo6mVDdhHHEgIj8cZD3ibJicUEqmdZ5XW2dQSp2qaajQCq3WQ/132', '0', '0', '', '0', '', '0', '1', null, '3', '1557994849', '2019-05-16 16:20:49', '0.00', '0.00', '0.00', '0.00', '0.00', '0', 'oksW41IUUjK1ItAtSU6_FXz7j4LI', 'oDTwV5gR_o5ug9dU6ssjU4QclQW8', '5ZSQ5Lq66IGa5oOgLS3lkajpgJo=', '150', '0.00', '1', '0', '0', null, 'F4AJ4XTP', null, null, '1');
INSERT INTO `tr_member` VALUES ('23', '15500345366', 'e976ce13054045695045e22b38b5b644', '15500345366', '1', '5pyq6K6+572u5pi156ew', '1', 'http://jx.tangrenjuhui.com/Public/Tb/img/logo.png', '0', '0', '', '0', '', '0', '1', null, '1', '1558746313', '2019-05-25 09:05:13', '0.00', '0.00', '0.00', '0.00', '0.00', '0', '', '', '', '0', '0.00', '1', '0', '0', null, 'DRBGGT85', null, null, '1');
INSERT INTO `tr_member` VALUES ('24', '17031947777', 'd04682b829575065fb29a97e7f3abd79', '17031947777', '22', '56ul5aSn5a6d', '1', 'http://jx.tangrenjuhui.com/Public/Tb/img/logo.png', '0', '0', '', '0', '', '0', '1', '2193877310', '3', '1558763579', '2019-05-25 13:52:59', '0.00', '0.00', '0.00', '0.00', '0.00', '1', '', '', '', '275', '0.00', '1,22', '0', '0', '2193877308', '3HHZPXPR', '', '', '1');
INSERT INTO `tr_member` VALUES ('25', '15248332223', 'cf47c4e5a5b8f855c5cb08b08d03c654', '15248332223', '1', '772e', '1', 'http://thirdwx.qlogo.cn/mmopen/vi_32/x39v5UMzFw3gyb8O0CNk8VfFF9ZlVq7AJ2tU6FVZKQOHGKfhtVYXhyLfMVgbh3o1DibrJEbsbTPleb9picju0WjQ/132', '0', '0', '', '0', '', '0', '1', '2138013066', '1', '1558944545', '2019-05-27 16:09:05', '0.00', '0.00', '0.00', '0.00', '0.00', '1', 'oksW41Hv8zgiPi6oOoj2nKGsLTro', 'oDTwV5mztQSvu_4pLpLJMnLVbWVs', '772e', '50', '0.00', '1', '0', '0', '2138013063', 'TB3T63DP', '15248332223', '姜鑫', '1');
INSERT INTO `tr_member` VALUES ('26', '18686611623', '7b2d7d9034cce3b65128f29a144d944b', '18686611623', '1', '5Zu95bqG', '1', 'http://thirdwx.qlogo.cn/mmopen/vi_32/DYAIOgq83eqmASShoEn0CibnJVXVndCfRB7eDbhJOfOScHSSNCKZ8qxMQHaEL7oQiasL3RvvyfW3zWtF4fAttG4g/132', '0', '0', '', '0', '', '0', '1', '2196041510', '1', '1559006422', '2019-05-28 09:20:22', '0.00', '0.00', '0.00', '0.00', '0.00', '1', 'oksW41DJNfBR7hIAj7qNlMf3LVEw', 'oDTwV5rYkb_QCaHVyMpscn93oyS0', '5Zu95bqG', '300', '0.00', '1', '0', '0', '2196041504', 'EUJYGM86', null, null, '1');
INSERT INTO `tr_member` VALUES ('27', '13504412117', 'd3943427b51e5bf9a93b991a1a06a8e6', '13504412117', '20', '8J+RhENhbmR5', '1', 'http://thirdwx.qlogo.cn/mmopen/vi_32/PiajxSqBRaEIeAkj2wUBrxszeNBITLVBB3RtnuSRDB69eDYtbnHpwDttibqhyC9ggw4E3Qrf1j5n4OPKeVIKTS6A/132', '0', '0', '', '0', '', '0', '1', '2190646041', '1', '1559117256', '2019-05-29 16:07:36', '0.00', '0.00', '0.00', '0.00', '0.00', '1', 'oksW41DqPXIxVq6UxVnKvhLSXc-E', 'oDTwV5kHPkX7jJX1eso_CmHDP7Ko', '8J+RhENhbmR5', '0', '0.00', '1,20', '0', '0', '2190646037', 'K77R8DGM', null, null, '1');
INSERT INTO `tr_member` VALUES ('28', '13756708912', '85eb5bdc860e9055560461d10a92a60d', '13756708912', '1', '77yf77yf', '1', 'http://thirdwx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTLHbq1qLCJ6UUE3dibQgvu1TPQ7GupUsRIGDGPRiaGgqibhJn1FAOP714QeRMV9JaCATGxVXsx1bZib6g/132', '0', '0', '', '0', '', '0', '1', null, '1', '1559199690', '2019-05-30 15:01:30', '0.00', '0.00', '0.00', '0.00', '0.00', '1', 'oksW41GPNMhXjKk8ifX7NyjdmQ0A', 'oDTwV5s9lyhZjIAzjZZQch9HCSms', '77yf77yf', '0', '0.00', '1', '0', '0', null, '7WGHY2X2', null, null, '1');
INSERT INTO `tr_member` VALUES ('29', '18543142194', '1c52bbf58a62576188744095de101f4d', '18543142194', '25', '5LiA', '1', 'http://jx.tangrenjuhui.com/Public/Tb/img/logo.png', '0', '0', '', '0', '', '0', '1', null, '1', '1559488331', '2019-06-02 23:12:11', '0.00', '0.00', '0.00', '0.00', '0.00', '2', '', '', '', '0', '0.00', '1,25', '0', '0', null, '47UTGRUT', null, null, '1');
INSERT INTO `tr_member` VALUES ('30', '18643530012', 'a71365743120c4de3581a41ed2d3174b', '18643530012', '22', '5pyq6K6+572u5pi156ew', '1', 'http://jx.tangrenjuhui.com/Public/Tb/img/logo.png', '0', '0', '', '0', '', '0', '1', null, '1', '1559631174', '2019-06-04 14:52:54', '0.00', '0.00', '0.00', '0.00', '0.00', '1', '', '', '', '0', '0.00', '1,22', '0', '0', null, 'YCYFDD28', null, null, '1');
INSERT INTO `tr_member` VALUES ('31', '13433938624', 'ed4f0b3d58ae4cde6493c1ecaf81b09d', '13433938624', '1', '5pyq6K6+572u5pi156ew', '1', 'http://jx.tangrenjuhui.com/Public/Tb/img/logo.png', '0', '0', '', '0', '', '0', '1', null, '1', '1559891390', '2019-06-07 15:09:50', '0.00', '0.00', '0.00', '0.00', '0.00', '0', '', '', '', '0', '0.00', '1', '0', '0', null, 'R686K2FX', null, null, '1');
INSERT INTO `tr_member` VALUES ('32', '13664339379', '89c85e2a8a1d95b1a25adbfca026b0a0', '13664339379', '1', '5pyq6K6+572u5pi156ew', '1', 'http://jx.tangrenjuhui.com/Public/Tb/img/logo.png', '0', '0', '', '0', '', '0', '1', '2194696696', '1', '1560128087', '2019-06-10 08:54:47', '0.00', '0.00', '0.00', '0.00', '0.00', '1', '', '', '', '0', '0.00', '1', '0', '0', '2194696690', '2KF5R3L4', null, null, '1');
INSERT INTO `tr_member` VALUES ('33', '18143640088', '6f05d1294b458ee4395a61ca2e02da32', '18143640088', '1', '5LuO6Imv5Li2', '1', 'http://thirdwx.qlogo.cn/mmopen/vi_32/DYAIOgq83epv7MYr8RRIC3KkFUMRAv7g7dROf7dib96nI77bXKGmicXKY1FAaCjyuXFicHtGoCp4w319OSCArzK6g/132', '0', '0', '', '0', '', '0', '1', '2191427979', '1', '1560128100', '2019-06-10 08:55:00', '0.00', '0.00', '0.00', '0.00', '0.00', '1', 'oksW41ExfvrbgaeXUuFUsPEz0aFQ', 'oDTwV5hjiHvyv53KYlNO6E8nD3Yo', '5LuO6Imv5Li2', '100', '0.00', '1', '0', '0', '2191427977', 'ALB9SA7Z', null, null, '1');
INSERT INTO `tr_member` VALUES ('34', '15663880865', '5f088106ad1c46958f5c36c46b9cd704', '15663880865', '1', '5pyq6K6+572u5pi156ew', '1', 'http://jx.tangrenjuhui.com/Public/Tb/img/logo.png', '0', '0', '', '0', '', '0', '1', '2193283437', '1', '1560128320', '2019-06-10 08:58:40', '0.00', '0.00', '0.00', '0.00', '0.00', '1', '', '', '', '0', '0.00', '1', '0', '0', '2193283434', 'YDRCH4UH', null, null, '1');
INSERT INTO `tr_member` VALUES ('35', '13252635672', 'eb1416a32aa1570b753c306e3912c0b6', '13252635672', '1', '5pyq6K6+572u5pi156ew', '1', 'http://jx.tangrenjuhui.com/Public/Tb/img/logo.png', '0', '0', '', '0', '', '0', '1', null, '1', '1560128542', '2019-06-10 09:02:22', '0.00', '0.00', '0.00', '0.00', '0.00', '1', '', '', '', '0', '0.00', '1', '0', '0', null, '3HHAT7HX', null, null, '1');
INSERT INTO `tr_member` VALUES ('36', '13654374524', '53cf0b77136fd618d517e730b4fbd663', '13654374524', '1', '5pyq6K6+572u5pi156ew', '1', 'http://jx.tangrenjuhui.com/Public/Tb/img/logo.png', '0', '0', '', '0', '', '0', '1', '2192585693', '1', '1560128776', '2019-06-10 09:06:16', '0.00', '0.00', '0.00', '0.00', '0.00', '1', '', '', '', '0', '0.00', '1', '0', '0', '2192585691', 'Z6GR4RMM', null, null, '1');
INSERT INTO `tr_member` VALUES ('37', '17304376959', '6184a65498542f13843c93af2163a13a', '17304376959', '1', '6Zi/5ouJ6JW+', '1', 'http://jx.tangrenjuhui.com/Public/Tb/img/logo.png', '0', '0', '', '0', '', '0', '1', '2191420915', '1', '1560129172', '2019-06-10 09:12:52', '0.00', '0.00', '0.00', '0.00', '0.00', '1', '', '', '', '0', '0.00', '1', '0', '0', '2191420909', 'ZEUUM5VV', null, null, '1');
INSERT INTO `tr_member` VALUES ('38', '15764366485', '8c22975734bbaa7d926a697110c7198f', '15764366485', '1', '5pyq6K6+572u5pi156ew', '1', 'http://jx.tangrenjuhui.com/Public/Tb/img/logo.png', '0', '0', '', '0', '', '0', '1', '2161488126', '1', '1560130071', '2019-06-10 09:27:51', '0.00', '0.00', '0.00', '0.00', '0.00', '1', '', '', '', '0', '0.00', '1', '0', '0', '2161488125', 'G5DVM3J4', null, null, '1');
INSERT INTO `tr_member` VALUES ('39', '15304410167', 'd0970714757783e6cf17b26fb8e2298f', '15304410167', '1', '5pyq6K6+572u5pi156ew', '1', 'http://jx.tangrenjuhui.com/Public/Tb/img/logo.png', '0', '0', '', '0', '', '0', '1', '2192550346', '1', '1560138158', '2019-06-10 11:42:38', '0.00', '0.00', '0.00', '0.00', '0.00', '1', '', '', '', '50', '0.00', '1', '0', '0', '2192550343', 'EKNYPKFG', null, null, '1');
INSERT INTO `tr_member` VALUES ('40', '13596103232', 'f2364dba50fd0e907cb9a064b83a52eb', '13596103232', '25', '5pyq6K6+572u5pi156ew', '1', 'http://jx.tangrenjuhui.com/Public/Tb/img/logo.png', '0', '0', '', '0', '', '0', '1', '2194933306', '1', '1560172770', '2019-06-10 21:19:30', '0.00', '0.00', '0.00', '0.00', '0.00', '2', '', '', '', '0', '0.00', '1,25', '0', '0', '2194933302', 'ZEUU5VKR', null, null, '1');
INSERT INTO `tr_member` VALUES ('41', '13304331135', '76468df52774ce0a63df3cf900f083e7', '13304331135', '33', '5pyq6K6+572u5pi156ew', '1', 'http://jx.tangrenjuhui.com/Public/Tb/img/logo.png', '0', '0', '', '0', '', '0', '1', null, '1', '1560860671', '2019-06-18 20:24:31', '0.00', '0.00', '0.00', '0.00', '0.00', '2', '', '', '', '0', '0.00', '1,33', '0', '0', null, 'EW8DCPSS', null, null, '1');
INSERT INTO `tr_member` VALUES ('42', '15904423657', 'cc8b293e16e48c9806eafd2be1d82661', '15904423657', '1', '5pyq', '1', 'http://jx.tangrenjuhui.com/Public/Tb/img/logo.png', '0', '0', '', '0', '', '0', '1', null, '1', '1560911127', '2019-06-19 10:25:27', '0.00', '0.00', '0.00', '0.00', '0.00', '1', '', '', '', '0', '0.00', '1', '0', '0', null, '3HH8ECM4', null, null, '1');
INSERT INTO `tr_member` VALUES ('43', '17519489789', '06227411adaf0d2dffec099a60aaeba7', '17519489789', '33', '5ZOI5Za95oiR5piv5rSL5rSL5ZCW', '1', 'http://thirdwx.qlogo.cn/mmopen/vi_32/DYAIOgq83er72mm32Os8uQ4upbrczVZRicibQJutiaGzrxTyHsfTPEmYbENx81bXmnEUqyOKiaJyh251rM2eorlLPg/132', '0', '0', '', '0', '', '0', '1', '2179791050', '1', '1560920447', '2019-06-19 13:00:47', '0.00', '0.00', '0.00', '0.00', '0.00', '2', 'oksW41JHynxsjL50Lp8C4-QCc85s', 'oDTwV5mxCvhWE2s85ZsLulXIDV4g', '5ZOI5Za95oiR5piv5rSL5rSL5ZCW', '100', '0.00', '1,33', '0', '0', '2179791043', '3LVK72TS', null, null, '1');
INSERT INTO `tr_member` VALUES ('51', '18643817921', 'c3ae6c112e21d7052a3c4b9b8c9ab5b4', '18643817921', '1', '8J+MpA==', '1', 'http://thirdwx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTLRr6Okgge2KuOr9PN4bNI0qMfAoHkk9XnM5wEUxVPbZE5fmMwAKlncF6sHxZpb5WXSgG7ISKl5RQ/132', '0', '0', '', '0', '', '0', '1', '2193292520', '1', '1561344798', '2019-06-24 10:53:18', '0.00', '0.00', '0.00', '0.00', '0.00', '1', 'oksW41LFUThHPxRjIQOG08mCHNF0', 'oDTwV5i5LXflvKBH4Eht9ILKFnG4', '8J+MpA==', '0', '0.00', '1', '0', '0', '2193292517', 'R68473YX', null, null, '1');
INSERT INTO `tr_member` VALUES ('52', '18343502777', '1f398457ba1ca4666e81415841785e87', '18343502777', '1', '5pyq6K6+572u5pi156ew', '1', 'http://jx.tangrenjuhui.com/Public/Tb/img/logo.png', '0', '0', '', '0', '', '0', '1', '2191048587', '1', '1561540675', '2019-06-26 17:17:55', '0.00', '0.00', '0.00', '0.00', '0.00', '1', '', '', '', '100', '0.00', '1', '0', '0', '2191048585', 'F4AVZARE', null, null, '1');
INSERT INTO `tr_member` VALUES ('53', '18604359298', '0631469aa47516eb2e03a5b227ffdce3', '18604359298', '24', '5pyq6K6+572u5pi156ew', '1', 'http://jx.tangrenjuhui.com/Public/Tb/img/logo.png', '0', '0', '', '0', '', '0', '1', null, '1', '1561607616', '2019-06-27 11:53:36', '0.00', '0.00', '0.00', '0.00', '0.00', '2', '', '', '', '0', '0.00', '1,22,24', '0', '0', null, 'RBN4E9GH', null, null, '1');
INSERT INTO `tr_member` VALUES ('54', '13258856066', 'a2d6d404b0032cfe04484e87d8aca6d8', '13258856066', '24', '5pyq6K6+572u5pi156ew', '1', 'http://jx.tangrenjuhui.com/Public/Tb/img/logo.png', '0', '0', '', '0', '', '0', '1', null, '1', '1561620122', '2019-06-27 15:22:02', '0.00', '0.00', '0.00', '0.00', '0.00', '2', '', '', '', '0', '0.00', '1,22,24', '0', '0', null, '8GTPVPVU', null, null, '1');
INSERT INTO `tr_member` VALUES ('55', '13074332667', 'f04f04520b1aed2c9ec1aa7e4a887474', '13074332667', '1', '5pyq6K6+572u5pi156ew', '1', 'http://jx.tangrenjuhui.com/Public/Tb/img/logo.png', '0', '0', '', '0', '', '0', '1', '2191424979', '1', '1561625500', '2019-06-27 16:51:40', '0.00', '0.00', '0.00', '0.00', '0.00', '1', '', '', '', '0', '0.00', '1', '0', '0', '2191424975', '49ZGKRNN', null, null, '1');
INSERT INTO `tr_member` VALUES ('56', '17504403657', '5f820c65f2fc791fc64b441e233a9b68', '17504403657', '19', '5pyq6K6+572u5pi156ew', '1', 'http://jx.tangrenjuhui.com/Public/Tb/img/logo.png', '0', '0', '', '0', '', '0', '1', null, '1', '1561635908', '2019-06-27 19:45:08', '0.00', '0.00', '0.00', '0.00', '0.00', '1', '', '', '', '0', '0.00', '1,19', '0', '0', null, 'K77Z73RZ', null, null, '1');
INSERT INTO `tr_member` VALUES ('57', '15567752989', 'bb0947ce41a0b672b5bf5c3f040c2c82', '15567752989', '19', '5oKg5oKg', '1', 'http://jx.tangrenjuhui.com/Public/Tb/img/logo.png', '0', '0', '', '0', '', '0', '1', '2192411699', '1', '1561636100', '2019-06-27 19:48:20', '0.00', '0.00', '0.00', '0.00', '0.00', '1', '', '', '', '0', '0.00', '1,19', '0', '0', '2192411697', 'VZRZLMMZ', null, null, '1');
INSERT INTO `tr_member` VALUES ('58', '13756134811', 'c4fd812ce50ef51e95fbeaa303ae1bde', '13756134811', '24', '5pyq6K6+572u5pi156ew', '1', 'http://jx.tangrenjuhui.com/Public/Tb/img/logo.png', '0', '0', '', '0', '', '0', '1', null, '1', '1561692485', '2019-06-28 11:28:05', '0.00', '0.00', '0.00', '0.00', '0.00', '2', '', '', '', '175', '0.00', '1,22,24', '0', '0', null, 'DT6LHP2A', null, null, '1');
INSERT INTO `tr_member` VALUES ('59', '13756461797', 'fd9ef13a99fe359670c419917ed4b582', '13756461797', '19', '5pyq6K6+572u5pi156ew', '1', 'http://jx.tangrenjuhui.com/Public/Tb/img/logo.png', '0', '0', '', '0', '', '0', '1', null, '1', '1561698600', '2019-06-28 13:10:00', '0.00', '0.00', '0.00', '0.00', '0.00', '1', '', '', '', '0', '0.00', '1,19', '0', '0', null, 'VZRZVA5M', null, null, '1');
INSERT INTO `tr_member` VALUES ('60', '18626772050', 'bfee443ec87c45bf1bef012fd9e8a769', '18626772050', '19', '5pyq6K6+572u5pi156ew', '1', 'http://jx.tangrenjuhui.com/Public/Tb/img/logo.png', '0', '0', '', '0', '', '0', '1', null, '1', '1561699016', '2019-06-28 13:16:56', '0.00', '0.00', '0.00', '0.00', '0.00', '1', '', '', '', '0', '0.00', '1,19', '0', '0', null, 'ALBSCE6M', null, null, '1');
INSERT INTO `tr_member` VALUES ('61', '15144088353', '39d69f167897585dbdd1d3cd5ab62782', '15144088353', '24', '5pyq6K6+572u5pi156ew', '1', 'http://jx.tangrenjuhui.com/Public/Tb/img/logo.png', '0', '0', '', '0', '', '0', '1', null, '1', '1561701177', '2019-06-28 13:52:57', '0.00', '0.00', '0.00', '0.00', '0.00', '2', '', '', '', '0', '0.00', '1,22,24', '0', '0', null, '49ZG5BDD', null, null, '1');
INSERT INTO `tr_member` VALUES ('62', '15584346987', 'b32047c7606fda6281b5d9990e72e359', '15584346987', '25', '5pyq6K6+572u5pi156ew', '1', 'http://jx.tangrenjuhui.com/Public/Tb/img/logo.png', '0', '0', '', '0', '', '0', '1', null, '1', '1561704370', '2019-06-28 14:46:10', '0.00', '0.00', '0.00', '0.00', '0.00', '2', '', '', '', '0', '0.00', '1,25', '0', '0', null, 'NRGU9Y9L', null, null, '1');
INSERT INTO `tr_member` VALUES ('63', '13159682571', '90211df95cc7c2f3a02e48e191b6ffb2', '13159682571', '39', '5pyq6K6+572u5pi156ew', '1', 'http://jx.tangrenjuhui.com/Public/Tb/img/logo.png', '0', '0', '', '0', '', '0', '1', null, '1', '1561707026', '2019-06-28 15:30:26', '0.00', '0.00', '0.00', '0.00', '0.00', '2', '', '', '', '0', '0.00', '1,39', '0', '0', null, 'W89987JL', null, null, '1');
INSERT INTO `tr_member` VALUES ('64', '15164451234', '7dad8d7737afdf0f7590590f78acf67b', '15164451234', '22', 'QS7ukJggTXIuVyDujK4=', '1', 'http://thirdwx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTJsYN1GsKpe0oYDZUl3ZOdtWVLdEo6raqpfDjvbIT1siaCHnMc80CeLA9hBEqcJNat0c5A2hqriaczQ/132', '0', '0', '', '0', '', '0', '1', '2196050367', '1', '1561716386', '2019-06-28 18:06:26', '0.00', '0.00', '0.00', '0.00', '0.00', '1', 'oksW41KUPzdJTz9XIfC9GKFU_nw8', 'oDTwV5qsEkDZqM9q7Kx00SxKBB4I', 'QS7ukJggTXIuVyDujK4=', '0', '0.00', '1,22', '0', '0', '2196050361', 'EW8U5A3R', '15164451234', '魏群富', '1');
INSERT INTO `tr_member` VALUES ('65', '13578986802', 'eb65737c0554c0b11d4ab2dffadccf9d', '13578986802', '19', '5pyq6K6+572u5pi156ew', '1', 'http://jx.tangrenjuhui.com/Public/Tb/img/logo.png', '0', '0', '', '0', '', '0', '1', null, '1', '1561719991', '2019-06-28 19:06:31', '0.00', '0.00', '0.00', '0.00', '0.00', '1', '', '', '', '0', '0.00', '1,19', '0', '0', null, 'M8K8ADA4', null, null, '1');
INSERT INTO `tr_member` VALUES ('66', '18626590186', '990b9e86ab13e555bf501878a270289f', '18626590186', '58', '5pyq6K6+572u5pi156ew', '1', 'http://jx.tangrenjuhui.com/Public/Tb/img/logo.png', '0', '0', '', '0', '', '0', '1', null, '1', '1561720597', '2019-06-28 19:16:37', '0.00', '0.00', '0.00', '0.00', '0.00', '24', '', '', '', '50', '0.00', '1,22,24,58', '0', '0', null, 'GNF4MYY3', null, null, '1');
INSERT INTO `tr_member` VALUES ('67', '18543550768', '1e16ac1d13839830adf99af164238d55', '18543550768', '58', '5pyq6K6+572u5pi156ew', '1', 'http://jx.tangrenjuhui.com/Public/Tb/img/logo.png', '0', '0', '', '0', '', '0', '1', '2192796920', '1', '1561720650', '2019-06-28 19:17:30', '0.00', '0.00', '0.00', '0.00', '0.00', '24', '', '', '', '0', '0.00', '1,22,24,58', '0', '0', '2192796917', 'G5D3D8U2', null, null, '1');
INSERT INTO `tr_member` VALUES ('68', '18626592698', 'e3b87a48d90a0df0ecbdfe9f49f0e059', '18626592698', '66', '5pyq6K6+572u5pi156ew', '1', 'http://jx.tangrenjuhui.com/Public/Tb/img/logo.png', '0', '0', '', '0', '', '0', '1', null, '1', '1561722081', '2019-06-28 19:41:21', '0.00', '0.00', '0.00', '0.00', '0.00', '58', '', '', '', '0', '0.00', '1,22,24,58,66', '0', '0', null, '3LVMTDKP', null, null, '1');
INSERT INTO `tr_member` VALUES ('69', '15004435351', '3ab86a9e49bf499b00ce2f0a0abcd70f', '15004435351', '19', '5pyq6K6+572u5pi156ew', '1', 'http://jx.tangrenjuhui.com/Public/Tb/img/logo.png', '0', '0', '', '0', '', '0', '1', null, '1', '1561724378', '2019-06-28 20:19:38', '0.00', '0.00', '0.00', '0.00', '0.00', '1', '', '', '', '50', '0.00', '1,19', '0', '0', null, 'TB3GTJGU', null, null, '1');
INSERT INTO `tr_member` VALUES ('70', '15886066718', 'c14d78133b3a8986e5221e2ed1584f23', '15886066718', '69', '5pyq6K6+572u5pi156ew', '1', 'http://jx.tangrenjuhui.com/Public/Tb/img/logo.png', '0', '0', '', '0', '', '0', '1', null, '1', '1561781199', '2019-06-29 12:06:39', '0.00', '0.00', '0.00', '0.00', '0.00', '19', '', '', '', '0', '0.00', '1,19,69', '0', '0', null, 'GNF427XC', null, null, '1');
INSERT INTO `tr_member` VALUES ('71', '18643501110', '72ecd4bff914cb13d3d8041c85815084', '18643501110', '1', '56ul56ul', '1', 'http://jx.tangrenjuhui.com/Public/Tb/img/logo.png', '0', '0', '', '0', '', '0', '1', '2196807620', '1', '1561794787', '2019-06-29 15:53:07', '0.00', '0.00', '0.00', '0.00', '0.00', '0', '', '', '', '0', '0.00', '0,1', '0', '0', '2196807618', 'KN9AJ5KE', '18643501110', '佟颖楠', '1');
INSERT INTO `tr_member` VALUES ('72', '13943094564', '3229ca99e44490e706e27dd961515679', '13943094564', '52', '5pyq6K6+572u5pi156ew', '1', 'http://jx.tangrenjuhui.com/Public/Tb/img/logo.png', '0', '0', '', '0', '', '0', '1', '2191048587', '1', '1561807633', '2019-06-29 19:27:13', '0.00', '0.00', '0.00', '0.00', '0.00', '1', '', '', '', '0', '0.00', '1,52', '0', '0', '2191048585', 'CA4YE8YW', null, null, '0');
INSERT INTO `tr_member` VALUES ('73', '15604456735', 'dbcfc5ac08cf3f25382abdf597d22b15', '15604456735', '19', '5pyq6K6+572u5pi156ew', '1', 'http://jx.tangrenjuhui.com/Public/Tb/img/logo.png', '0', '0', '', '0', '', '0', '1', null, '1', '1561810061', '2019-06-29 20:07:41', '0.00', '0.00', '0.00', '0.00', '0.00', '1', '', '', '', '0', '0.00', '1,19', '0', '0', null, 'MCWKH2ZF', null, null, '0');
INSERT INTO `tr_member` VALUES ('74', '15164450772', '1ff4d260951d21b5824dad88f180b398', '15164450772', '1', '5pyq6K6+572u5pi156ew', '1', 'http://jx.tangrenjuhui.com/Public/Tb/img/logo.png', '0', '0', '', '0', '', '0', '1', null, '1', '1561939338', '2019-07-01 08:02:18', '0.00', '0.00', '0.00', '0.00', '0.00', '0', '', '', '', '100', '0.00', '0,1', '0', '0', null, 'VZRZRJB2', null, null, '0');
INSERT INTO `tr_member` VALUES ('75', '18643507858', 'e879bd0375b73e4a1ff79e9cf670afaf', '18643507858', '22', '5pyq6K6+572u5pi156ew', '1', 'http://jx.tangrenjuhui.com/Public/Tb/img/logo.png', '0', '0', '', '0', '', '0', '1', null, '1', '1561959034', '2019-07-01 13:30:34', '0.00', '0.00', '0.00', '0.00', '0.00', '1', '', '', '', '0', '0.00', '1,22', '0', '0', null, 'R68Y8F7U', null, null, '1');
INSERT INTO `tr_member` VALUES ('76', '13353211308', '344484d8bafbe2bfc5108416725b5b4f', '13353211308', '74', '5pyq6K6+572u5pi156ew', '1', 'http://jx.tangrenjuhui.com/Public/Tb/img/logo.png', '0', '0', '', '0', '', '0', '1', '2196815839', '1', '1561986275', '2019-07-01 21:04:35', '0.00', '0.00', '0.00', '0.00', '0.00', '1', '', '', '', '0', '0.00', '0,1,74', '0', '0', '2196815832', 'D3H7HDKE', null, null, '0');
INSERT INTO `tr_member` VALUES ('77', '18804381987', '3692d8d14a8500964f732b4d9fd7d88b', '18804381987', '74', '5pyq6K6+572u5pi156ew', '1', 'http://jx.tangrenjuhui.com/Public/Tb/img/logo.png', '0', '0', '', '0', '', '0', '1', null, '1', '1561986383', '2019-07-01 21:06:23', '0.00', '0.00', '0.00', '0.00', '0.00', '1', '', '', '', '0', '0.00', '0,1,74', '0', '0', null, 'UUM3S92C', null, null, '0');
INSERT INTO `tr_member` VALUES ('78', '17790002666', 'e88194e2c18f0ea1ad82b5afbbae32af', '17790002666', '52', '5pyq6K6+572u5pi156ew', '1', 'http://jx.tangrenjuhui.com/Public/Tb/img/logo.png', '0', '0', '', '0', '', '0', '1', '2196860649', '1', '1562032064', '2019-07-02 09:47:44', '0.00', '0.00', '0.00', '0.00', '0.00', '1', '', '', '', '0', '0.00', '1,52', '0', '0', '2196860646', 'G5D6R6XH', null, null, '1');
INSERT INTO `tr_member` VALUES ('79', '18343136048', '5ea26e429ec0d6088fc3cc943294addb', '18343136048', '58', '5pyq6K6+572u5pi156ew', '1', 'http://jx.tangrenjuhui.com/Public/Tb/img/logo.png', '0', '0', '', '0', '', '0', '1', null, '1', '1562044851', '2019-07-02 13:20:51', '0.00', '0.00', '0.00', '0.00', '0.00', '24', '', '', '', '0', '0.00', '1,22,24,58', '0', '0', null, 'KHSEJLF7', null, null, '0');
INSERT INTO `tr_member` VALUES ('80', '13578924402', '9208ff08bbcf96b20e973bb24399ebec', '13578924402', '33', '5pyq6K6+572u5pi156ew', '1', 'http://jx.tangrenjuhui.com/Public/Tb/img/logo.png', '0', '0', '', '0', '', '0', '1', null, '1', '1562061858', '2019-07-02 18:04:18', '0.00', '0.00', '0.00', '0.00', '0.00', '1', '', '', '', '0', '0.00', '1,33', '0', '0', null, 'K25ZMNPR', null, null, '1');
INSERT INTO `tr_member` VALUES ('81', '15004389887', '1d91b08994e33437279f230a8bb1f67d', '15004389887', '1', '5pyq6K6+572u5pi156ew', '1', 'http://jx.tangrenjuhui.com/Public/Tb/img/logo.png', '0', '0', '', '0', '', '0', '1', '558178813', '1', '1562115526', '2019-07-03 08:58:46', '0.00', '0.00', '0.00', '0.00', '0.00', '0', '', '', '', '0', '0.00', '0,1', '0', '0', '558166503', 'EUJRFZG8', null, null, '0');
INSERT INTO `tr_member` VALUES ('82', '13331466668', '0b411cfe103dd620764f4161e1aa8a44', '13331466668', '22', '5pyq6K6+572u5pi156ew', '1', 'http://jx.tangrenjuhui.com/Public/Tb/img/logo.png', '0', '0', '', '0', '', '0', '1', '2198412532', '1', '1562118529', '2019-07-03 09:48:49', '0.00', '0.00', '0.00', '0.00', '0.00', '1', '', '', '', '0', '0.00', '1,22', '0', '0', '2198412530', 'M8K8WXBP', null, null, '0');
INSERT INTO `tr_member` VALUES ('83', '13578631024', 'b16dd20ea4722f078a83db11c5597803', '13578631024', '26', '5pyq6K6+572u5pi156ew', '1', 'http://jx.tangrenjuhui.com/Public/Tb/img/logo.png', '0', '0', '', '0', '', '0', '1', null, '1', '1562124637', '2019-07-03 11:30:37', '0.00', '0.00', '0.00', '0.00', '0.00', '1', '', '', '', '0', '0.00', '1,26', '0', '0', null, 'FZW78F29', null, null, '0');
INSERT INTO `tr_member` VALUES ('84', '15500041713', '8643821949ce1ac393590cc60a60a97c', '15500041713', '26', '5pyq6K6+572u5pi156ew', '1', 'http://jx.tangrenjuhui.com/Public/Tb/img/logo.png', '0', '0', '', '0', '', '0', '1', '2197992748', '1', '1562124672', '2019-07-03 11:31:12', '0.00', '0.00', '0.00', '0.00', '0.00', '1', '', '', '', '100', '0.00', '1,26', '0', '0', '2197992746', 'VZRAPABH', null, null, '1');
INSERT INTO `tr_member` VALUES ('85', '17808080877', 'ceea9d6ede0f426bca4ed52fa52b2721', '17808080877', '26', '5pyq6K6+572u5pi156ew', '1', 'http://jx.tangrenjuhui.com/Public/Tb/img/logo.png', '0', '0', '', '0', '', '0', '1', null, '1', '1562124677', '2019-07-03 11:31:17', '0.00', '0.00', '0.00', '0.00', '0.00', '1', '', '', '', '0', '0.00', '1,26', '0', '0', null, 'R68YTCBA', null, null, '1');
INSERT INTO `tr_member` VALUES ('86', '15543000524', 'baea5b1c24beb0bb60be10104d0885e0', '15543000524', '26', '5pyq6K6+572u5pi156ew', '1', 'http://jx.tangrenjuhui.com/Public/Tb/img/logo.png', '0', '0', '', '0', '', '0', '1', null, '1', '1562125524', '2019-07-03 11:45:24', '0.00', '0.00', '0.00', '0.00', '0.00', '1', '', '', '', '0', '0.00', '1,26', '0', '0', null, '47UFXL8V', null, null, '1');
INSERT INTO `tr_member` VALUES ('87', '13214460231', '97956e6eef935666acd1e821e1c966d8', '13214460231', '26', '5pyq6K6+572u5pi156ew', '1', 'http://jx.tangrenjuhui.com/Public/Tb/img/logo.png', '0', '0', '', '0', '', '0', '1', null, '1', '1562125669', '2019-07-03 11:47:49', '0.00', '0.00', '0.00', '0.00', '0.00', '1', '', '', '', '0', '0.00', '1,26', '0', '0', null, 'JP3UVMTA', null, null, '1');
INSERT INTO `tr_member` VALUES ('88', '18612300063', '9cc43c3fa451fa919acfb68e143b5e9d', '18612300063', '84', '5pyq6K6+572u5pi156ew', '1', 'http://jx.tangrenjuhui.com/Public/Tb/img/logo.png', '0', '0', '', '0', '', '0', '1', null, '1', '1562130863', '2019-07-03 13:14:23', '0.00', '0.00', '0.00', '0.00', '0.00', '26', '', '', '', '0', '0.00', '1,26,84', '0', '0', null, 'MCWK2EFC', null, null, '1');
INSERT INTO `tr_member` VALUES ('89', '15590549577', '2a8cc9a33e7faedc00cbd4a628a18477', '15590549577', '84', '5pyq6K6+572u5pi156ew', '1', 'http://jx.tangrenjuhui.com/Public/Tb/img/logo.png', '0', '0', '', '0', '', '0', '1', null, '1', '1562131769', '2019-07-03 13:29:29', '0.00', '0.00', '0.00', '0.00', '0.00', '26', '', '', '', '0', '0.00', '1,26,84', '0', '0', null, 'TS5JKJ5D', null, null, '1');
INSERT INTO `tr_member` VALUES ('90', '18304356599', '537fdc3cb235f68043f3483d4666bcf3', '18304356599', '1', '5YWs54i15piv5Y+q6LKT', '1', 'http://jx.tangrenjuhui.com/Public/Tb/img/logo.png', '0', '0', '', '0', '', '0', '1', '2198124383', '1', '1562135218', '2019-07-03 14:26:58', '0.00', '0.00', '0.00', '0.00', '0.00', '0', '', '', '', '50', '0.00', '0,1', '0', '0', '2198124377', 'JV5NZE3L', 'sun8757@sina.com', '孙微', '0');
INSERT INTO `tr_member` VALUES ('91', '18604398086', '98bd85f9c200eac90705ad30df0ef320', '18604398086', '90', '5pyq6K6+572u5pi156ew', '1', 'http://jx.tangrenjuhui.com/Public/Tb/img/logo.png', '0', '0', '', '0', '', '0', '1', null, '1', '1562140752', '2019-07-03 15:59:12', '0.00', '0.00', '0.00', '0.00', '0.00', '1', '', '', '', '0', '0.00', '0,1,90', '0', '0', null, '64NT46RJ', null, null, '0');
INSERT INTO `tr_member` VALUES ('92', '18946516558', '0b38743c6ed60eead3f273a48ed0cad1', '18946516558', '43', 'TFk=', '1', 'http://jx.tangrenjuhui.com/Public/Tb/img/logo.png', '0', '0', '', '0', '', '0', '1', null, '1', '1562152707', '2019-07-03 19:18:27', '0.00', '0.00', '0.00', '0.00', '0.00', '33', '', '', '', '0', '0.00', '1,33,43', '0', '0', null, 'RSJDZEYE', null, null, '0');
INSERT INTO `tr_member` VALUES ('93', '15044435633', 'bb3fe093917d5cdbbb4e85f30715e59a', '15044435633', '43', '5pyq6K6+572u5pi156ew', '1', 'http://jx.tangrenjuhui.com/Public/Tb/img/logo.png', '0', '0', '', '0', '', '0', '1', null, '1', '1562205591', '2019-07-04 09:59:51', '0.00', '0.00', '0.00', '0.00', '0.00', '33', '', '', '', '0', '0.00', '1,33,43', '0', '0', null, '3LVMUWB4', null, null, '0');

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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tr_member_fund_flow
-- ----------------------------
INSERT INTO `tr_member_fund_flow` VALUES ('1', '1', '100.00', '100.00', '1', '系统充值', '1552994379');
INSERT INTO `tr_member_fund_flow` VALUES ('2', '1', '-100.00', '0.00', '2', '系统扣除', '1552994390');
INSERT INTO `tr_member_fund_flow` VALUES ('7', '1', '1.00', '3.00', '1', '推荐分佣', '1554985582');
INSERT INTO `tr_member_fund_flow` VALUES ('8', '2', '8.00', '24.00', '1', '分享下单成功分佣', '1554985582');

-- ----------------------------
-- Table structure for tr_member_level
-- ----------------------------
DROP TABLE IF EXISTS `tr_member_level`;
CREATE TABLE `tr_member_level` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` int(11) NOT NULL DEFAULT '0',
  `old_level` int(11) NOT NULL DEFAULT '1',
  `new_level` int(11) NOT NULL DEFAULT '2',
  `state` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1申请升级 2升级成功 0拒绝升级',
  `admin_id` int(11) NOT NULL DEFAULT '0',
  `up_time` int(11) NOT NULL DEFAULT '0' COMMENT '审核通过时间',
  `note` text COMMENT '备注',
  `created_at` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tr_member_level
-- ----------------------------
INSERT INTO `tr_member_level` VALUES ('1', '1', '1', '2', '2', '1', '1561443166', null, '0', '2019-06-25 14:12:46');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tr_member_points
-- ----------------------------

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tr_member_sign
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tr_member_withdraw
-- ----------------------------
INSERT INTO `tr_member_withdraw` VALUES ('1', '1', '100.00', '2', '1', '', '1553065944', '1553083800', '2019-03-20 20:10:00', '1', '22070219890120', '{\r\n\"bank_name\": \"招商银行\",\r\n\"province\": \"浙江省\",\r\n\"city\": \"杭州市\",\r\n\"area\": \"滨江区\",\r\n\"bank_branch\": \"钱塘支行\"\r\n}');
INSERT INTO `tr_member_withdraw` VALUES ('2', '1', '100.00', '2', '1', '审核通过', '1553065944', '1553065944', '2019-03-20 16:18:35', '2', '13588269863', '');
INSERT INTO `tr_member_withdraw` VALUES ('3', '1', '100.00', '3', '1', '拒绝', '1553065944', '1553065944', '2019-03-20 16:18:28', '3', 'weixin', '');
INSERT INTO `tr_member_withdraw` VALUES ('4', '1', '100.00', '3', '1', 'asd', '1553065944', '1553083773', '2019-03-20 20:09:33', '2', '13588269863', '');

-- ----------------------------
-- Table structure for tr_partners
-- ----------------------------
DROP TABLE IF EXISTS `tr_partners`;
CREATE TABLE `tr_partners` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '会员id',
  `member_id` int(11) NOT NULL DEFAULT '0',
  `rate` float(5,4) NOT NULL DEFAULT '0.0000' COMMENT '分佣比例',
  `total_income` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '累计收入',
  `state` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tr_partners
-- ----------------------------
INSERT INTO `tr_partners` VALUES ('1', '1', '0.0000', '0.00', '1', '1559789906', '2019-06-06 10:58:26');
INSERT INTO `tr_partners` VALUES ('2', '2', '0.0012', '0.00', '1', '1559790258', '2019-06-06 13:39:40');

-- ----------------------------
-- Table structure for tr_partner_flow
-- ----------------------------
DROP TABLE IF EXISTS `tr_partner_flow`;
CREATE TABLE `tr_partner_flow` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` int(11) NOT NULL DEFAULT '0' COMMENT '合伙人id',
  `settle_id` int(11) NOT NULL DEFAULT '0' COMMENT '结算id',
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '收入',
  `income` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '合伙人所在分支结算总收入',
  `rate` float(10,4) NOT NULL DEFAULT '0.0000',
  `created_at` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tr_partner_flow
-- ----------------------------
INSERT INTO `tr_partner_flow` VALUES ('1', '1', '1', '10.00', '100.00', '0.1000', '0');

-- ----------------------------
-- Table structure for tr_partner_settlement
-- ----------------------------
DROP TABLE IF EXISTS `tr_partner_settlement`;
CREATE TABLE `tr_partner_settlement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `settle_id` int(11) NOT NULL DEFAULT '0',
  `partner_num` int(11) NOT NULL DEFAULT '0' COMMENT '参与分佣合伙人数量',
  `income` decimal(14,2) NOT NULL DEFAULT '0.00' COMMENT '总收入',
  `amount` decimal(14,2) NOT NULL DEFAULT '0.00' COMMENT '实际结算金额',
  `state` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tr_partner_settlement
-- ----------------------------
INSERT INTO `tr_partner_settlement` VALUES ('1', '1', '0', '0.00', '0.00', '2', '1562744611', '2019-07-10 16:15:05');

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
-- Records of tr_product
-- ----------------------------
INSERT INTO `tr_product` VALUES ('0', 'sn0001', '房源管理', 'asda', '1', '1500.00', '100.00', '1400.00', '1553011200', '1584547200', '100', '90', '10', 'http://www.iqiyi.com/v_19rr8sbot0.html#vfrm=2-4-0-1', 'asda', '1234', '99', '1', '1554429639', '2019-04-05 10:19:03');

-- ----------------------------
-- Table structure for tr_settlement
-- ----------------------------
DROP TABLE IF EXISTS `tr_settlement`;
CREATE TABLE `tr_settlement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `settlement_sn` varchar(100) NOT NULL DEFAULT '' COMMENT '结算编号',
  `total_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '总佣金',
  `pay_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '发放金额',
  `member_num` int(11) NOT NULL DEFAULT '0' COMMENT '参与分佣会员总数量',
  `rate_info` varchar(255) NOT NULL DEFAULT '0.0000' COMMENT '分佣比例json',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0发起结算 1匹配成功 2结算成功  4结算失败 5已发放 6发放成功 7 发放失败',
  `settle_time` int(11) NOT NULL DEFAULT '0' COMMENT '结算时间',
  `pay_time` int(11) NOT NULL DEFAULT '0' COMMENT '发放时间',
  `created_at` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tr_settlement
-- ----------------------------
INSERT INTO `tr_settlement` VALUES ('1', '20190411192803', '100.00', '78.00', '2', '{\"1\":\"0.5000\",\"2\":\"0.1000\",\"3\":\"0.1800\",\"4\":\"0.0800\"}', '6', '1562584935', '1562637779', '1554982083', '2019-07-09 10:03:08');
INSERT INTO `tr_settlement` VALUES ('2', '20190708191314', '0.00', '0.00', '0', '{\"4\":\"0.0800\",\"3\":\"0.1800\",\"2\":\"0.1000\",\"1\":\"0.5000\"}', '1', '1562585528', '0', '1562584394', '2019-07-08 19:32:08');
INSERT INTO `tr_settlement` VALUES ('3', '20190709095934', '0.00', '0.00', '0', '{\"4\":\"0.0800\",\"3\":\"0.1800\",\"2\":\"0.1000\",\"1\":\"0.5000\"}', '0', '0', '0', '1562637574', '2019-07-09 09:59:34');

-- ----------------------------
-- Table structure for tr_settlement_detail
-- ----------------------------
DROP TABLE IF EXISTS `tr_settlement_detail`;
CREATE TABLE `tr_settlement_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `settle_id` int(11) NOT NULL DEFAULT '0' COMMENT '结算ID',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1自购分佣 2分享分佣 3推荐分佣',
  `member_id` int(1) NOT NULL DEFAULT '0' COMMENT '分佣对象ID type为1 则是会员id',
  `member_level` tinyint(1) NOT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '分佣金额',
  `order_id` int(11) NOT NULL DEFAULT '0' COMMENT '订单ID type为1时有效',
  `partner_id` int(11) NOT NULL DEFAULT '0' COMMENT '合伙人id',
  `descr` varchar(255) NOT NULL DEFAULT '' COMMENT '分佣来源描述',
  `state` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 未发放 2 已发放',
  `created_at` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tr_settlement_detail
-- ----------------------------
INSERT INTO `tr_settlement_detail` VALUES ('36', '1', '1', '17', '2', '60.00', '1', '0', '自购分佣', '1', '1560338760', '2019-07-08 15:45:10');
INSERT INTO `tr_settlement_detail` VALUES ('37', '1', '4', '1', '3', '18.00', '1', '0', '运营商分佣', '1', '1560338760', '2019-06-12 19:26:00');

-- ----------------------------
-- Table structure for tr_settlement_order
-- ----------------------------
DROP TABLE IF EXISTS `tr_settlement_order`;
CREATE TABLE `tr_settlement_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `trade_id` varchar(255) NOT NULL DEFAULT '' COMMENT '订单编号',
  `relation_id` varchar(255) NOT NULL DEFAULT '' COMMENT '渠道ID',
  `special_id` varchar(255) NOT NULL DEFAULT '' COMMENT '会员ID',
  `commission_fee` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '佣金金额',
  `state` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1未匹配 2已匹配 3已结算',
  `member_id` int(11) NOT NULL DEFAULT '0',
  `create_time` varchar(255) DEFAULT '',
  `created_at` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tr_settlement_order
-- ----------------------------
INSERT INTO `tr_settlement_order` VALUES ('1', 'order20190321143212', '', 'x123456', '100.00', '3', '17', '2019-06-26 12:12:14', '0', '2019-07-08 14:55:32');

-- ----------------------------
-- Table structure for tr_settlement_pay
-- ----------------------------
DROP TABLE IF EXISTS `tr_settlement_pay`;
CREATE TABLE `tr_settlement_pay` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `settle_id` int(11) NOT NULL DEFAULT '0',
  `member_id` int(11) NOT NULL DEFAULT '0',
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `created_at` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tr_settlement_pay
-- ----------------------------
INSERT INTO `tr_settlement_pay` VALUES ('12', '1', '2', '60.00', '1560338760');
INSERT INTO `tr_settlement_pay` VALUES ('13', '1', '1', '18.00', '1560338760');

-- ----------------------------
-- Table structure for tr_settlement_rate
-- ----------------------------
DROP TABLE IF EXISTS `tr_settlement_rate`;
CREATE TABLE `tr_settlement_rate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL DEFAULT '' COMMENT '等级名称',
  `rate` float(10,4) NOT NULL DEFAULT '0.0000' COMMENT '分佣比例',
  `state` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tr_settlement_rate
-- ----------------------------
INSERT INTO `tr_settlement_rate` VALUES ('4', '第四次分佣比例', '0.0800', '1', '1559640604', '2019-06-11 10:17:44');
INSERT INTO `tr_settlement_rate` VALUES ('3', '第三次分佣比例', '0.1800', '1', '1559640604', '2019-06-11 10:17:49');
INSERT INTO `tr_settlement_rate` VALUES ('2', '第二次分佣比例', '0.1000', '1', '1559640604', '2019-06-11 10:17:32');
INSERT INTO `tr_settlement_rate` VALUES ('1', '第一次分佣比例', '0.5000', '1', '1559640604', '2019-06-11 10:17:25');

-- ----------------------------
-- Table structure for tr_settlement_stat
-- ----------------------------
DROP TABLE IF EXISTS `tr_settlement_stat`;
CREATE TABLE `tr_settlement_stat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `settle_id` int(11) NOT NULL DEFAULT '0' COMMENT '结算单ID',
  `level_id` int(11) NOT NULL DEFAULT '0' COMMENT '会员等级',
  `amount` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '该等级会员总收入',
  `num` int(11) NOT NULL DEFAULT '0' COMMENT '该等级参与分佣人数',
  `created_at` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tr_settlement_stat
-- ----------------------------

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
-- Table structure for tr_stat
-- ----------------------------
DROP TABLE IF EXISTS `tr_stat`;
CREATE TABLE `tr_stat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_num` int(11) NOT NULL DEFAULT '0' COMMENT '付款订单数量',
  `member_num` int(11) NOT NULL DEFAULT '0' COMMENT '新增用户数量',
  `service_num` int(11) NOT NULL DEFAULT '0' COMMENT '新增运营商数量',
  `pre_commission` decimal(14,2) NOT NULL DEFAULT '0.00' COMMENT '预估佣金',
  `withdraw_amount` decimal(14,2) NOT NULL,
  `created_at` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tr_stat
-- ----------------------------
INSERT INTO `tr_stat` VALUES ('1', '0', '0', '0', '0.00', '0.00', '1562656257');
INSERT INTO `tr_stat` VALUES ('2', '0', '0', '0', '100.00', '0.00', '1562656383');
INSERT INTO `tr_stat` VALUES ('3', '0', '0', '0', '0.00', '0.00', '1562656542');

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
INSERT INTO `tr_sys_admin` VALUES ('1', 'admin', 'd93a5def7511da3d0f2d171d9c344e91', '13588272727', '超级管理员', '132@qq.com', '1', '1', '1562743174', '', '1562726886', '', '205', '0', '1548075651', '2019-06-04 21:25:43');
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
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tr_sys_basic
-- ----------------------------
INSERT INTO `tr_sys_basic` VALUES ('1', '系统名称', '1', 'system_name', '唐人', '1', '', '系统名称', '1', '98', '3', '2019-05-09 10:24:23');
INSERT INTO `tr_sys_basic` VALUES ('2', '系统域名', '1', 'system_domain', 'localhost', '1', '', '系统域名', '1', '97', '0', '2019-05-09 10:24:48');
INSERT INTO `tr_sys_basic` VALUES ('3', '系统维护', '1', 'system_run', '1', '3', '[{\"label\":\"\\u5f00\",\"value\":1},{\"label\":\"\\u5173\",\"value\":0}]', '1运行 其他维护', '1', '99', '0', '2019-05-10 11:24:27');
INSERT INTO `tr_sys_basic` VALUES ('4', '登录错误上限', '1', 'login_error', '3', '1', '', '密码错误次数达到上限后，禁止登录系统，填写0则表示可无限次数尝试', '1', '0', '0', '2019-05-09 10:25:10');
INSERT INTO `tr_sys_basic` VALUES ('5', '登录有效时长', '1', 'login_overtime', '120', '1', '', '后台超过登录有效时长，未进行任何操作，再操作需要重新登录', '1', '0', '0', '2019-05-09 10:25:17');
INSERT INTO `tr_sys_basic` VALUES ('6', '每日任务功能', '2', 'day_task', '1', '2', '[{\"label\":\"\\u5f00\",\"value\":1},{\"label\":\"\\u5173\",\"value\":0}]', '', '1', '0', '0', '2019-05-10 11:24:33');
INSERT INTO `tr_sys_basic` VALUES ('7', '每日浏览数量', '2', 'browse_count', '1', '1', '', '件商品', '1', '0', '0', '2019-05-10 13:55:20');
INSERT INTO `tr_sys_basic` VALUES ('8', '每日浏览奖励', '2', 'browse_award', '1', '1', '', '积分', '1', '0', '0', '2019-05-10 13:55:07');
INSERT INTO `tr_sys_basic` VALUES ('9', '每日分享app链接', '2', 'share_app', '1', '1', '', '次', '1', '0', '0', '2019-05-10 13:52:06');
INSERT INTO `tr_sys_basic` VALUES ('10', '每日分享奖励', '2', 'share_app_award', '3', '1', '', '积分', '1', '0', '0', '2019-05-10 13:52:25');
INSERT INTO `tr_sys_basic` VALUES ('11', '每日邀请用户', '2', 'invite_count', '5', '1', '', '位', '1', '0', '0', '2019-05-10 13:52:29');
INSERT INTO `tr_sys_basic` VALUES ('12', '每日邀请奖励', '2', 'invite_award', '5', '1', '', '积分', '1', '0', '0', '2019-05-10 13:52:35');
INSERT INTO `tr_sys_basic` VALUES ('13', '签到功能', '2', 'user_sign', '1', '2', '[{\"label\":\"\\u5f00\",\"value\":1},{\"label\":\"\\u5173\",\"value\":0}]', '', '1', '0', '0', '2019-05-10 13:53:17');
INSERT INTO `tr_sys_basic` VALUES ('14', '每日签到奖励', '2', 'sign_award', '100', '1', '', '积分', '1', '0', '0', '2019-05-10 13:52:43');
INSERT INTO `tr_sys_basic` VALUES ('15', '支持连续签到奖励', '2', 'sign_continue', '1', '2', '[{\"label\":\"\\u5f00\",\"value\":1},{\"label\":\"\\u5173\",\"value\":0}]', '', '1', '0', '0', '2019-05-10 13:53:16');
INSERT INTO `tr_sys_basic` VALUES ('16', '连续签到天数', '2', 'sign_count', '3', '1', '', '天', '1', '0', '0', '2019-05-10 13:52:52');
INSERT INTO `tr_sys_basic` VALUES ('17', '连续签到奖励', '2', 'continue_award', '5', '1', '', '积分', '1', '0', '0', '2019-05-10 13:56:05');
INSERT INTO `tr_sys_basic` VALUES ('18', '签到规则', '2', 'sign_rule', '连续签到可额外获得奖励', '4', '', '', '1', '0', '0', '2019-05-10 13:48:35');
INSERT INTO `tr_sys_basic` VALUES ('19', '淘宝客appKey', '1', 'tbk_app_key', '25521171', '1', '', '', '1', '0', '0', '2019-05-13 11:05:55');
INSERT INTO `tr_sys_basic` VALUES ('20', '淘宝客secret', '1', 'tbk_app_secret', '43967a2c75599e4027f7b5ff698b604b', '1', '', '', '1', '0', '0', '2019-05-13 15:47:12');
INSERT INTO `tr_sys_basic` VALUES ('21', '好单库apiKey', '1', 'hdk_api_key', 'jifenbao', '1', '', '', '1', '0', '0', '2019-05-13 11:06:54');

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
-- Records of tr_sys_basic_copy
-- ----------------------------
INSERT INTO `tr_sys_basic_copy` VALUES ('1', '唐人', '系统名称', '1', '3', '120');

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
) ENGINE=MyISAM AUTO_INCREMENT=126 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tr_sys_node
-- ----------------------------
INSERT INTO `tr_sys_node` VALUES ('1', '首页', 'System/Index/index', '0', '1', '99', '1', '1', '0', '2019-04-20 03:07:06');
INSERT INTO `tr_sys_node` VALUES ('2', '系统管理', '', '0', '1', '0', '1', '1', '0', '2019-03-20 22:34:02');
INSERT INTO `tr_sys_node` VALUES ('3', '角色管理', 'System/Role/index', '2', '1', '7', '1', '1', '0', '2019-04-20 03:07:11');
INSERT INTO `tr_sys_node` VALUES ('6', '删除', 'System/Role/del', '3', 'del', '7', '2', '1', '0', '2019-04-20 03:07:17');
INSERT INTO `tr_sys_node` VALUES ('4', '添加', 'System/Role/add', '3', 'add', '9', '2', '1', '0', '2019-04-20 03:07:24');
INSERT INTO `tr_sys_node` VALUES ('5', '编辑', 'System/Role/edit', '3', 'edit', '8', '2', '1', '0', '2019-04-20 03:07:30');
INSERT INTO `tr_sys_node` VALUES ('8', '管理员', 'System/User/index', '2', '1', '8', '1', '1', '0', '2019-04-20 03:07:37');
INSERT INTO `tr_sys_node` VALUES ('9', '添加', 'System/User/add', '8', 'add', '9', '2', '1', '0', '2019-04-20 03:07:45');
INSERT INTO `tr_sys_node` VALUES ('7', '权限配置', 'System/Role/access', '3', 'access', '6', '2', '1', '0', '2019-04-20 03:07:51');
INSERT INTO `tr_sys_node` VALUES ('10', '编辑', 'System/User/edit', '8', 'edit', '8', '2', '1', '0', '2019-04-20 03:08:00');
INSERT INTO `tr_sys_node` VALUES ('11', '删除', 'System/User/del', '8', 'del', '7', '2', '1', '0', '2019-04-20 03:08:07');
INSERT INTO `tr_sys_node` VALUES ('21', '会员明细', 'Member/Index/flow', '17', 'flow', '6', '2', '1', '0', '2019-04-20 03:08:15');
INSERT INTO `tr_sys_node` VALUES ('14', '系统设置', 'System/Basic/index', '2', '1', '9', '1', '1', '0', '2019-04-20 03:08:21');
INSERT INTO `tr_sys_node` VALUES ('15', '编辑', 'System/Basic/edit', '14', 'edit', '9', '2', '1', '0', '2019-04-20 03:08:29');
INSERT INTO `tr_sys_node` VALUES ('16', '会员管理', '', '0', '1', '98', '1', '1', '0', '2019-03-27 15:20:16');
INSERT INTO `tr_sys_node` VALUES ('17', '会员信息', 'Member/Index/index', '16', '1', '9', '1', '1', '0', '2019-04-20 03:08:36');
INSERT INTO `tr_sys_node` VALUES ('18', '添加', 'Member/Index/add', '17', 'add', '9', '2', '1', '0', '2019-04-20 03:08:43');
INSERT INTO `tr_sys_node` VALUES ('19', '编辑', 'Member/Index/edit', '17', 'edit', '8', '2', '1', '0', '2019-04-20 03:08:50');
INSERT INTO `tr_sys_node` VALUES ('20', '删除', 'Member/Index/del', '17', 'del', '7', '2', '1', '0', '2019-04-20 03:08:57');
INSERT INTO `tr_sys_node` VALUES ('23', '会员明细', 'Member/Flow/index', '16', '1', '8', '1', '1', '0', '2019-04-20 03:09:05');
INSERT INTO `tr_sys_node` VALUES ('24', '提现申请', 'Member/Withdraw/index', '16', '1', '7', '1', '1', '0', '2019-04-20 03:09:12');
INSERT INTO `tr_sys_node` VALUES ('25', '审核', 'Member/Withdraw/audit', '24', 'audit', '9', '2', '1', '0', '2019-04-20 03:09:19');
INSERT INTO `tr_sys_node` VALUES ('26', '分佣比例', 'Member/Fee/index', '16', '1', '6', '1', '3', '0', '2019-04-20 03:09:28');
INSERT INTO `tr_sys_node` VALUES ('27', '编辑', 'Member/Fee/edit', '26', 'edit', '9', '2', '3', '0', '2019-04-20 03:09:34');
INSERT INTO `tr_sys_node` VALUES ('28', '运营管理', '', '0', '1', '96', '1', '1', '0', '2019-03-20 23:19:25');
INSERT INTO `tr_sys_node` VALUES ('29', '轮播图', 'Manage/Banner/index', '28', '1', '9', '1', '1', '0', '2019-04-20 03:09:41');
INSERT INTO `tr_sys_node` VALUES ('30', '添加', 'Manage/Banner/add', '29', 'add', '9', '2', '1', '0', '2019-04-20 03:09:47');
INSERT INTO `tr_sys_node` VALUES ('31', '编辑', 'Manage/Banner/edit', '29', 'edit', '8', '2', '1', '0', '2019-04-20 03:09:55');
INSERT INTO `tr_sys_node` VALUES ('32', '删除', 'Manage/Banner/del', '29', 'del', '7', '2', '1', '0', '2019-04-20 03:10:01');
INSERT INTO `tr_sys_node` VALUES ('33', '系统公告', 'Manage/Notice/index', '28', '1', '7', '1', '1', '0', '2019-04-20 03:10:10');
INSERT INTO `tr_sys_node` VALUES ('34', '添加', 'Manage/Notice/add', '33', 'add', '9', '2', '1', '0', '2019-04-20 03:10:18');
INSERT INTO `tr_sys_node` VALUES ('35', '编辑', 'Manage/Notice/edit', '33', 'edit', '8', '2', '1', '0', '2019-04-20 03:10:26');
INSERT INTO `tr_sys_node` VALUES ('36', '删除', 'Manage/Notice/del', '33', 'del', '7', '2', '1', '0', '2019-04-20 03:10:33');
INSERT INTO `tr_sys_node` VALUES ('37', '新手指引', 'Manage/Guide/index', '28', '1', '6', '1', '1', '0', '2019-04-20 03:10:42');
INSERT INTO `tr_sys_node` VALUES ('38', '添加', 'Manage/Guide/add', '37', 'add', '9', '2', '1', '0', '2019-04-20 03:10:47');
INSERT INTO `tr_sys_node` VALUES ('39', '编辑', 'Manage/Guide/edit', '37', 'edit', '8', '2', '1', '0', '2019-04-20 03:10:54');
INSERT INTO `tr_sys_node` VALUES ('40', '删除', 'Manage/Guide/del', '37', 'del', '7', '2', '1', '0', '2019-04-20 03:11:00');
INSERT INTO `tr_sys_node` VALUES ('41', '常见问题', '', '0', '1', '6', '1', '1', '0', '2019-03-27 15:06:07');
INSERT INTO `tr_sys_node` VALUES ('42', '分类管理', 'Manage/Faqcate/index', '41', '1', '9', '1', '1', '0', '2019-04-20 03:11:31');
INSERT INTO `tr_sys_node` VALUES ('43', '添加', 'Manage/Faqcate/add', '42', 'add', '8', '2', '1', '0', '2019-04-20 03:11:39');
INSERT INTO `tr_sys_node` VALUES ('44', '编辑', 'Manage/Faqcate/edit', '42', 'edit', '7', '2', '1', '0', '2019-04-20 03:11:46');
INSERT INTO `tr_sys_node` VALUES ('45', '删除', 'Manage/Faqcate/del', '42', 'del', '6', '2', '1', '0', '2019-04-20 03:11:53');
INSERT INTO `tr_sys_node` VALUES ('47', '添加', 'Manage/Faq/add', '46', 'add', '8', '2', '1', '0', '2019-04-20 03:12:00');
INSERT INTO `tr_sys_node` VALUES ('48', '编辑', 'Manage/Faq/edit', '46', 'edit', '7', '2', '1', '0', '2019-04-20 03:12:06');
INSERT INTO `tr_sys_node` VALUES ('49', '删除', 'Manage/Faq/del', '46', 'del', '6', '2', '1', '0', '2019-04-20 03:12:13');
INSERT INTO `tr_sys_node` VALUES ('46', '文章列表', 'Manage/Faq/index', '41', '1', '9', '1', '1', '0', '2019-04-20 03:12:19');
INSERT INTO `tr_sys_node` VALUES ('50', '社区管理', '', '0', '1', '5', '1', '1', '0', '2019-03-24 10:32:44');
INSERT INTO `tr_sys_node` VALUES ('51', '社区分类', 'Article/Cate/index', '50', '1', '8', '1', '1', '0', '2019-04-20 03:12:25');
INSERT INTO `tr_sys_node` VALUES ('52', '添加', 'Article/Cate/add', '51', 'add', '9', '2', '1', '0', '2019-04-20 03:12:33');
INSERT INTO `tr_sys_node` VALUES ('53', '编辑', 'Article/Cate/edit', '51', 'edit', '8', '2', '1', '0', '2019-04-20 03:12:40');
INSERT INTO `tr_sys_node` VALUES ('54', '删除', 'Article/Cate/del', '51', 'del', '7', '2', '1', '0', '2019-04-20 03:12:46');
INSERT INTO `tr_sys_node` VALUES ('55', '文章列表', 'Article/Article/index', '50', '1', '9', '1', '1', '0', '2019-04-20 03:12:52');
INSERT INTO `tr_sys_node` VALUES ('56', '添加', 'Article/Article/add', '55', 'add', '9', '2', '1', '0', '2019-04-20 03:13:00');
INSERT INTO `tr_sys_node` VALUES ('57', '编辑', 'Article/Article/edit', '55', 'edit', '8', '2', '1', '0', '2019-04-20 03:13:08');
INSERT INTO `tr_sys_node` VALUES ('58', '删除', 'Article/Article/del', '55', 'del', '7', '2', '1', '0', '2019-04-20 03:13:14');
INSERT INTO `tr_sys_node` VALUES ('59', '导航管理', 'Manage/Nav/index', '28', '1', '8', '1', '1', '0', '2019-04-20 03:13:19');
INSERT INTO `tr_sys_node` VALUES ('60', '添加', 'Manage/Nav/add', '59', 'add', '9', '2', '1', '0', '2019-04-20 03:13:27');
INSERT INTO `tr_sys_node` VALUES ('61', '编辑', 'Manage/Nav/edit', '59', 'edit', '8', '2', '1', '0', '2019-04-20 03:13:33');
INSERT INTO `tr_sys_node` VALUES ('62', '删除', 'Manage/Nav/del', '59', 'del', '7', '2', '1', '0', '2019-04-20 03:13:41');
INSERT INTO `tr_sys_node` VALUES ('63', '商品管理', '', '0', '1', '97', '1', '1', '0', '2019-03-27 15:20:18');
INSERT INTO `tr_sys_node` VALUES ('64', '自营商品', 'Product/Info/index', '63', '1', '9', '1', '1', '0', '2019-04-20 03:13:47');
INSERT INTO `tr_sys_node` VALUES ('65', '添加', 'Product/Info/add', '64', 'add', '9', '2', '1', '0', '2019-04-20 03:13:57');
INSERT INTO `tr_sys_node` VALUES ('66', '编辑', 'Product/Info/edit', '64', 'edit', '8', '2', '1', '0', '2019-04-20 03:14:03');
INSERT INTO `tr_sys_node` VALUES ('67', '删除', 'Product/Info/del', '64', 'del', '7', '2', '1', '0', '2019-04-20 03:14:10');
INSERT INTO `tr_sys_node` VALUES ('68', '商品分类', 'Goods/Cate/index', '63', '1', '8', '1', '1', '0', '2019-04-20 03:14:19');
INSERT INTO `tr_sys_node` VALUES ('69', '添加', 'Goods/Cate/add', '68', 'add', '9', '2', '1', '0', '2019-04-20 03:14:24');
INSERT INTO `tr_sys_node` VALUES ('70', '编辑', 'Goods/Cate/edit', '68', 'edit', '8', '2', '1', '0', '2019-04-20 03:14:31');
INSERT INTO `tr_sys_node` VALUES ('71', '删除', 'Goods/Cate/del', '68', 'del', '7', '2', '1', '0', '2019-04-20 03:14:36');
INSERT INTO `tr_sys_node` VALUES ('85', '添加', 'Goods/Attr/add', '76', 'add', '9', '2', '3', '0', '2019-04-20 03:14:42');
INSERT INTO `tr_sys_node` VALUES ('86', '编辑', 'Goods/Attr/edit', '76', 'edit', '8', '2', '3', '0', '2019-04-20 03:14:49');
INSERT INTO `tr_sys_node` VALUES ('87', '删除', 'Goods/Attr/del', '76', 'del', '7', '2', '3', '0', '2019-04-20 03:14:57');
INSERT INTO `tr_sys_node` VALUES ('76', '属性列表', 'Goods/Cate/attr', '68', 'attr', '6', '3', '3', '0', '2019-04-20 03:15:04');
INSERT INTO `tr_sys_node` VALUES ('77', '商品属性', 'Goods/Attr/index', '63', '1', '6', '1', '3', '0', '2019-04-20 03:15:10');
INSERT INTO `tr_sys_node` VALUES ('78', '添加', 'Goods/Attr/add', '77', 'add', '9', '2', '3', '0', '2019-04-20 03:15:18');
INSERT INTO `tr_sys_node` VALUES ('79', '编辑', 'Goods/Attr/edit', '77', 'edit', '8', '2', '3', '0', '2019-04-20 03:15:26');
INSERT INTO `tr_sys_node` VALUES ('80', '删除', 'Goods/Attr/del', '77', 'del', '7', '2', '3', '0', '2019-04-20 03:15:32');
INSERT INTO `tr_sys_node` VALUES ('81', '商品规格', 'Goods/Spec/index', '63', '1', '5', '1', '3', '0', '2019-04-20 03:15:38');
INSERT INTO `tr_sys_node` VALUES ('82', '添加', 'Goods/Spec/add', '81', 'add', '9', '2', '3', '0', '2019-04-20 03:15:44');
INSERT INTO `tr_sys_node` VALUES ('83', '编辑', 'Goods/Spec/edit', '81', 'edit', '8', '2', '3', '0', '2019-04-20 03:15:49');
INSERT INTO `tr_sys_node` VALUES ('84', '删除', 'Goods/Spec/del', '81', 'del', '7', '2', '3', '0', '2019-04-20 03:15:54');
INSERT INTO `tr_sys_node` VALUES ('89', '渠道管理', '', '0', '1', '96', '1', '3', '0', '2019-06-06 14:22:31');
INSERT INTO `tr_sys_node` VALUES ('90', '渠道信息', 'Channel/Info/index', '89', '1', '9', '1', '3', '0', '2019-06-06 14:22:33');
INSERT INTO `tr_sys_node` VALUES ('91', '添加', 'Channel/Info/add', '90', 'add', '9', '2', '3', '0', '2019-06-06 14:22:33');
INSERT INTO `tr_sys_node` VALUES ('92', '编辑', 'Channel/Info/edit', '90', 'edit', '8', '2', '3', '0', '2019-06-06 14:22:34');
INSERT INTO `tr_sys_node` VALUES ('93', '删除', 'Channel/Info/del', '90', 'del', '7', '2', '3', '0', '2019-06-06 14:22:35');
INSERT INTO `tr_sys_node` VALUES ('94', '佣金管理', '', '0', '1', '95', '1', '1', '0', '2019-04-09 16:46:15');
INSERT INTO `tr_sys_node` VALUES ('95', '分佣记录', 'Commission/Settlement/index', '94', '1', '9', '1', '1', '0', '2019-04-20 03:16:46');
INSERT INTO `tr_sys_node` VALUES ('96', '结算', 'Commission/Settlement/settle', '95', 'settle', '9', '2', '1', '0', '2019-04-20 03:16:54');
INSERT INTO `tr_sys_node` VALUES ('97', '明细', 'Commission/Settlement/detail', '95', 'detail', '8', '2', '1', '0', '2019-04-20 03:17:02');
INSERT INTO `tr_sys_node` VALUES ('99', '发放', 'Commission/Settlement/pay', '95', 'pay', '7', '2', '1', '0', '2019-04-20 03:17:10');
INSERT INTO `tr_sys_node` VALUES ('100', '佣金订单', 'Commission/Order/index', '94', '1', '8', '1', '1', '0', '2019-04-20 03:17:17');
INSERT INTO `tr_sys_node` VALUES ('101', '同步订单', 'Commission/Order/add', '100', 'add', '9', '2', '1', '0', '2019-04-20 03:17:26');
INSERT INTO `tr_sys_node` VALUES ('102', '数据匹配', 'Commission/Order/edit', '100', 'edit', '8', '2', '1', '0', '2019-04-20 03:17:33');
INSERT INTO `tr_sys_node` VALUES ('103', '佣金明细', 'Commission/Detail/index', '94', '1', '7', '1', '1', '0', '2019-04-20 03:17:39');
INSERT INTO `tr_sys_node` VALUES ('104', '添加', 'Commission/Settlement/add', '95', 'add', '9', '2', '1', '0', '2019-04-20 03:17:47');
INSERT INTO `tr_sys_node` VALUES ('105', '三方商品', 'Goods/Item/index', '63', '1', '9', '1', '1', '0', '2019-04-20 03:17:53');
INSERT INTO `tr_sys_node` VALUES ('106', '同步商品', 'Goods/Item/add', '105', 'add', '9', '2', '1', '0', '2019-04-20 03:17:59');
INSERT INTO `tr_sys_node` VALUES ('107', '商品更新', 'Goods/Item/edit', '105', 'edit', '8', '2', '1', '0', '2019-04-20 03:18:05');
INSERT INTO `tr_sys_node` VALUES ('108', '商品下架', 'Goods/Item/del', '105', 'del', '7', '2', '1', '0', '2019-04-20 03:18:14');
INSERT INTO `tr_sys_node` VALUES ('109', '每日任务', 'Manage/Task/index', '28', '1', '9', '1', '1', '0', '2019-05-10 10:01:30');
INSERT INTO `tr_sys_node` VALUES ('110', '编辑', 'Manage/Task/edit', '109', 'edit', '8', '2', '1', '0', '2019-05-10 10:01:57');
INSERT INTO `tr_sys_node` VALUES ('119', '会员升级', 'Member/Level/index', '16', '1', '0', '1', '1', '0', '2019-06-25 09:49:50');
INSERT INTO `tr_sys_node` VALUES ('120', '审核', 'Member/Level/edit', '119', 'edit', '0', '2', '1', '0', '2019-06-25 09:50:30');
INSERT INTO `tr_sys_node` VALUES ('113', '合伙人', 'Member/Partner/index', '16', '1', '0', '1', '1', '0', '2019-06-05 09:59:54');
INSERT INTO `tr_sys_node` VALUES ('117', '添加', 'Member/Partner/add', '17', 'add', '6', '2', '1', '0', '2019-07-10 10:40:32');
INSERT INTO `tr_sys_node` VALUES ('115', '编辑', 'Member/Partner/edit', '113', 'edit', '8', '2', '1', '0', '2019-06-05 10:01:14');
INSERT INTO `tr_sys_node` VALUES ('116', '删除', 'Member/Partner/del', '113', 'del', '7', '2', '1', '0', '2019-06-05 10:01:20');
INSERT INTO `tr_sys_node` VALUES ('121', '统计管理', '', '0', '1', '0', '1', '1', '0', '2019-07-09 13:48:38');
INSERT INTO `tr_sys_node` VALUES ('122', '每日统计', 'Stat/Index/index', '121', '1', '9', '1', '1', '0', '2019-07-09 13:49:42');
INSERT INTO `tr_sys_node` VALUES ('123', '合伙人', 'Commission/Settlement/Partner', '95', 'partner', '0', '2', '1', '0', '2019-07-10 10:42:35');
INSERT INTO `tr_sys_node` VALUES ('124', '合伙人', 'Commission/Partner/index', '94', '1', '0', '1', '1', '0', '2019-07-10 10:41:35');
INSERT INTO `tr_sys_node` VALUES ('125', '结算明细', 'Commission/Partner/detail', '124', 'detail', '0', '2', '1', '0', '2019-07-10 10:42:34');

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
