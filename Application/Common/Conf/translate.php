<?php

return [
    //数据基础状态
    'state' => [1 => '启用', 2 => '禁用', 3 => '删除'],
    //会员明细类型
    'flow_type'  => [1 => '分佣', 2 => '提现', 3 => '冻结', 4 => '解冻'],
    //提现申请 审核状态
    'audit_state' => [1 => '申请中', 2=> '通过', 3=>'拒绝'],
    //提现账户类型
    'withdraw_type' => [1 => '银行卡',2 => '支付宝',3 => '微信'],
    //商品属性检索
    'attr_index' => [0 => '否', 1 => '是'],
    //商品属性值录入方式
    'input_type' => [0 => '手工录入',1 => '列表中选择',2 => '多行文本框'],
    //淘宝客订单状态
    'order_state' => [1 => '未匹配',2 => '已匹配',3 => '已结算'],
    //结算单状态
    'settle_state' => [0=>'未结算', 1 => '结算中', 2 => '已结算',4 => '结算失败',5 => '佣金发放中',6 => '已发放',7 => '发放失败'],
    //结算明细状态
    'settle_detail_state' => [1 => '未发放' ,2 => '已发放'],
    //分佣类型
    'settle_type' => [1=>'自购分佣', 2=>'分享分佣', 3=>'推荐分佣',4=>'运营商分佣'],
    //用户级别
    'member_level' => [1=>'普通会员', 2=>'超级会员', 3=>'普通运营商',4=>'超级运营商'],
    //轮播位置
    'banner_location' => ['index' => '首页主','indexad' => '首页中',
        'ziying' => '自营商城主','myad'=>'个人中心主','video'=>'影视中心'],
    //链接类型
    'url_type' => [1 => '内部链接', 2 => '外部链接'],
    //升级状态
    'up_state' => [0 => '审核拒绝',1 => '申请升级',2 => '审核通过'],
    //合伙人结算状态
    'partner_settle_state' => [0 => '未结算',1 => '结算中', 2 => '已结算', 4 => '结算失败']
];