<?php
return array(
	//'配置项'=>'配置值'

    'DB_TYPE'               =>  'mysql',     // 数据库类型
    'DB_HOST'               =>  'localhost', // 服务器地址
    'DB_NAME'               =>  'Arthas',          // 数据库名
    'DB_USER'               =>  'root',      // 用户名
    'DB_PWD'                =>  '',          // 密码
    'DB_PORT'               =>  '3306',        // 端口
    'DB_PREFIX'             =>  'arthas_',    // 数据库表前缀
    'URL_CASE_INSENSITIVE'  =>  true,   // 默认false 表示URL区分大小写 true则表示不区分大小写
    'URL_HTML_SUFFIX'       =>  'py',  // URL伪静态后缀设置
    'URL_MODEL'             =>  2,       // URL访问模式,可选参数0、1、2、3,代表以下四种模式：
    // 0 (普通模式); 1 (PATHINFO 模式); 2 (REWRITE  模式); 3 (兼容模式)  默认为PATHINFO 模式
    'URL_ROUTER_ON'         =>  true,   //开启路由
    'URL_ROUTE_RULES'       =>  array(
                                    'news/:id' => 'News/read',  //路由规则
                                ),

    //设置模块的访问列表
    'MODULE_ALLOW_LIST' =>    array('Home','Snowing'),
    'DEFAULT_MODULE'       =>    'Home',

    'TMPL_ACTION_ERROR'    =>   './Public/tempPage/error.html',
    'TMPL_EXCEPTION_FILE'  =>   './Public/tempPage/error.html',
);