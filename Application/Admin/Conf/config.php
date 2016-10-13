<?php
return array(


    /* 模板相关配置 */
    '__STATIC__' => __ROOT__ . '/Public/' . MODULE_NAME,
    /* SESSION 和 COOKIE 配置 */
    'SESSION_PREFIX' => 'admin', //session前缀
    'COOKIE_PREFIX'  => 'admin_', // Cookie前缀 避免冲突
    'VAR_SESSION_ID' => 'session_id',	//修复uploadify插件无法传递session_id的bug


    'admin_status' => array(
        'active' => '正常',
        'forbidden' => '禁用',
        'pending' => '未激活',
    ),

    'admin_role' => array(
        0 => '管理员',
        1 => '论坛管理员',
        2 => '编辑人员',
        3 => '开奖人员',
    ),

);