CREATE TABLE `sys_admin` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`usa` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '用户名',
`pswd` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '密码',
`phone` varchar(11) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '手机号',
`email` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '邮箱',
`role_id` int NOT NULL DEFAULT 0 COMMENT '角色id',
`state` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态 1正常 2禁用 3删除 ',
`login_time` int(10) NOT NULL DEFAULT 0 COMMENT '登录时间',
`login_ip` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '登陆ip',
`last_login_time` int(10) NOT NULL DEFAULT 0 COMMENT '上次登陆时间',
`last_login_ip` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '上次登陆ip',
`login_num` int(11) NOT NULL DEFAULT 0 COMMENT '登陆次数',
`login_error` int(11) NOT NULL DEFAULT 0 COMMENT '登陆错误次数',
`created_at` int(10) NOT NULL DEFAULT 0 COMMENT '创建时间',
`updated_at` timestamp NOT NULL DEFAULT '' COMMENT 'CURRENT_TIMESTAMP',
PRIMARY KEY (`id`) 
);

CREATE TABLE `sys_role` (
`id` int NOT NULL AUTO_INCREMENT,
`name` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '角色名称',
`role_menu` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '角色菜单id 以英文逗号分隔',
`state` tinyint(1) NULL,
`created_at` int(10) NULL DEFAULT 0 COMMENT '创建时间',
`updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '更新时间',
PRIMARY KEY (`id`) 
);

CREATE TABLE `sys_menu` (
`id` int NOT NULL AUTO_INCREMENT,
`name` varchar CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '菜单名称',
`pid` int NULL DEFAULT 0 COMMENT '父级id 0为顶级',
`path` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '菜单地址',
`icon` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '菜单icon',
`state` tinyint(1) NULL DEFAULT 1 COMMENT '状态 1 正常 2 禁用 3删除',
`sort` int(11) NULL DEFAULT 0 COMMENT '菜单排序 数字越大越排在前面',
`created_at` int(10) NULL DEFAULT 0 COMMENT '创建时间',
`updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '更新时间',
PRIMARY KEY (`id`) 
);

CREATE TABLE `sys_basic_info` (
`id` int NOT NULL AUTO_INCREMENT,
`name` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '名称',
`param_name` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '参数名称，用于查询',
`param_value` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '信息',
`state` tinyint(1) NULL DEFAULT 1 COMMENT '状态 1正常 2禁用 3删除',
`created_at` int(10) NULL DEFAULT 0,
`updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`) 
);

CREATE TABLE `sys_api` (
`id` int NOT NULL AUTO_INCREMENT,
`name` varchar CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '接口名称',
`param_name` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '参数名称用于识别',
`note` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '备注',
`type` tinyint(1) NULL DEFAULT 1 COMMENT '接口类型 1短信',
`is_default` tinyint(1) NULL DEFAULT 0 COMMENT '是否默认 0否 1是',
`state` tinyint(1) NULL DEFAULT 1 COMMENT '状态',
`created_at` int(10) NULL DEFAULT 0 COMMENT '创建时间',
`updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`) 
);

CREATE TABLE `sys_api_params` (
`id` int NOT NULL AUTO_INCREMENT,
`api_id` int(11) NULL DEFAULT 0 COMMENT '接口id',
`name` varchar CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '参数显示名称',
`param_name` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '参数英文名称，接口参数名',
`param_value` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '参数值',
`created_at` int(10) NULL DEFAULT 0,
`updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`) 
);

CREATE TABLE `sys_logs` (
`id` bigint(20) NOT NULL AUTO_INCREMENT,
PRIMARY KEY (`id`) 
);

CREATE TABLE `goods` (
);

CREATE TABLE `members` (
);

CREATE TABLE `stat_register` (
);

CREATE TABLE `stat_goods` (
);

CREATE TABLE `tickets` (
);

CREATE TABLE `sys_banner` (
);

CREATE TABLE `sys_nav` (
);

CREATE TABLE `goods_cate` (
);

CREATE TABLE `article` (
);

CREATE TABLE `article_cate` (
);

CREATE TABLE `members_tickets` (
);

CREATE TABLE `members_share_Log` (
);

CREATE TABLE `sys_message` (
);

