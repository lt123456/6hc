<?php
return array(


    /* 模板相关配置 */
    '__STATIC__' => __ROOT__ . '/Public/' . MODULE_NAME,
    /* SESSION 和 COOKIE 配置 */
    'SESSION_PREFIX' => 'admin', //session前缀
    'COOKIE_PREFIX'  => 'admin_', // Cookie前缀 避免冲突
    'VAR_SESSION_ID' => 'session_id',	//修复uploadify插件无法传递session_id的bug

    'PAGE'  =>'6', // 分页条数

    'admin_status' => array(
        'active' => '正常',
        'forbidden' => '禁用',
        'pending' => '未激活',
    ),


    'admin_roles' => array(
        0 => '管理员',
        1 => '论坛管理员',
        2 => '编辑人员',
        3 => '开奖人员',
    ),
    'AUTH_CONFIG'=>array(
        'AUTH_ON' => true, //认证开关
        'AUTH_TYPE' => 1, // 认证方式，1为时时认证；2为登录认证。
        'AUTH_GROUP' => '6hc_think_auth_group', //用户组数据表名
        'AUTH_GROUP_ACCESS' => '6hc_think_auth_group_access', //用户组明细表
        'AUTH_RULE' => '6hc_think_auth_rule', //权限规则表
        'AUTH_USER' => '6hc_admin'//用户信息表
    ),

);