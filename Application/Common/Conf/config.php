<?php
return array(
    // url
    'URL_MODEL'             =>  2,
    //'配置项'=>'配置值'
    'DB_TYPE'               =>  'mysqli',     // 数据库类型
    'DB_HOST'               =>  'localhost', // 服务器地址
    'DB_NAME'               =>  'wait',          // 数据库名
    'DB_USER'               =>  'root',      // 用户名
    'DB_PWD'                =>  '',      // 密码
    'DB_PORT'               =>  '3306',        // 端口
    'DB_PREFIX'             =>  '6hc_',    // 数据库表前缀
    'DB_FIELDTYPE_CHECK'    =>  false,       // 是否进行字段类型检查
    'DB_FIELDS_CACHE'       =>  true,        // 启用字段缓存
    'DB_CHARSET'            =>  'utf8',      // 数据库编码默认采用utf8
    'SHOW_PAGE_TRACE' 		=>	false,				//开启页面trace
    'DEFAULT_FILTER'        =>  'htmlspecialchars,trim', // 默认参数过滤方法 用于I函数...
    'MODULE_ALLOW_LIST'    =>    array('Home','Admin','Mobile'),
    'DEFAULT_MODULE'       =>    'Home',
    'PUBLIC_PC'            => __ROOT__.'/Public/Frontend/Web',  // pc 端 资源路径
    'PUBLIC_Mobile'        => __ROOT__.'/Public/Frontend/Mobile',  // mobile 端资源路径
    'ADMIN_NAME'          => '6合彩后台',   // 后台配置

    'VAR_FILTERS'=>'stripslashes,strip_tags', // 开启过滤

    'DEFAULT_TIMEZONE'=>'PRC', // 设置默认时区为新加坡

    'USER_STANDING' => array('才子','秀才','举人','进士','院士'),

    'USER_ROLES'   =>array('god'=>'用户','expert'=>'专家','post_admin'=>'官方用户'),

    'USER_STATUS'  =>array('pending'=>'待激活','active'=>'激活','suspended'=>'禁用'),

//    'TMPL_EXCEPTION_FILE' => '/Public/Html/404.html', // 错误页面
    // 'TMPL_ACTION_ERROR'   => '/Public/Html/404.html',
//	'ERROR_PAGE'   =>  '/Public/Html/404.html',

//    'DEFAULT_THEME'    =>    'pc',



);

