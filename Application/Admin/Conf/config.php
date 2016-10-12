<?php
return array(


    /* 模板相关配置 */
    '__Front__' => __ROOT__ . '/Public/' . MODULE_NAME,
    '__Avatar__' => __ROOT__ . '/Public/' . MODULE_NAME,
    '__IMG__'    => __ROOT__ . '/Public/' . MODULE_NAME,
    '__CSS__'    => __ROOT__ . '/Public/' . MODULE_NAME,
    '__JS__'     => __ROOT__ . '/Public/' . MODULE_NAME,
    /* SESSION 和 COOKIE 配置 */
    'SESSION_PREFIX' => 'admin', //session前缀
    'COOKIE_PREFIX'  => 'admin_', // Cookie前缀 避免冲突
    'VAR_SESSION_ID' => 'session_id',	//修复uploadify插件无法传递session_id的bug

);