<?php
return array(
    //'配置项'=>'配置值'

    'DATA_CACHE_PREFIX' => 'Redis_',//缓存前缀
    'DATA_CACHE_TYPE' => 'redis',//默认动态缓存为Redis
    'REDIS_RW_SEPARATE' => true, //Redis读写分离 true 开启
    'REDIS_HOST' => '127.0.0.1', //redis服务器ip，多台用逗号隔开；读写分离开启时，第一台负责写，其它[随机]负责读；
    'REDIS_PORT' => '6379',//端口号
    'REDIS_TIMEOUT' => '300',//超时时间
    'REDIS_PERSISTENT' => false,//是否长连接 false=短连接
    'REDIS_AUTH' => '',//AUTH认证密码
    'Marquee' => ' 尊敬的彩库用户,请用手机浏览器登陆即可体验彩库触屏版.',
    'Index' => array(
        'title' => '永盛-六合宝典,由香港皇家科技专门打造的一个丰富资料的分享平台',
        'keywords' => '永盛六合彩库,六合宝典',
        'description' => '皇家六合彩库,由香港皇家科技专门打造的一个资料平台，内含丰富的数据统计，图纸，人气旺盛的交流大厅，移动高端产品六合宝典.'

    ),

    'Mark' => array(
        'title' => '六合开奖结果完整版_六合开奖日期查询',
        'keywords' => '六合开奖结果完整版,六合开奖日期查询',
        'description' => '1976-2016年完整版六合彩开奖历史记录,可按生肖,五行,家禽野兽,男女生肖,天地生肖,特码,金木水火土等进行筛选.',
        'caiku'   => '我不会说我是天下第一，但我也绝不会承认我是第二。——彩库宝典',
    ),

    'CACHE_TIME' => array(
        'index' => '7200',
        'base' => "3600",
        'middle' => '1800',
        'smart' => '600',
    ),

    'ILLUSTRATION' => '2',  //图解小组分类


);