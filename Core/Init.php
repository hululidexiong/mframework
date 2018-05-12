<?php
/**
 * Created by PhpStorm.
 * User: Bear <hululidexiong@163.com>
 * Date: 2018/4/22
 * Time: 16:09
 */

$mode = get_cfg_var('M.RUN_MODE');
/*
 * debug_level_base
 * debug_level_full
 */
if( !in_array( $mode , ['online' , 'debug_level_base' , 'debug_level_full'] ) ){
    $mode='debug_level_full';
}

if( (isset($_REQUEST['_dug']) && $_REQUEST['_dug']==2 ) || $mode=='debug_level_full' )
{
    switch($mode){
        case 'debug_level_full':
            error_reporting(E_ALL);
            break;
        case 'debug_level_base':
            error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
            break;
    }
    ini_set("display_errors",'On');
}else{
    error_reporting(0);
    ini_set("display_errors",'Off');
}
define('DEBUG_MODE' , $mode);

/*********************************************
 *
 */

define('CORE_DIR' , __DIR__);
define( 'ROOT_DIR' , dirname( CORE_DIR ));

//配置文件所在目录  Dev （测试） ， Online （线上）   php.ini M.RUN_MODE = 'online' , 'debug_level_base' , 'debug_level_full'  或者  $_REQUEST['_dug'] 相当于 debug_level_full。   其中 debug_level_base 等于 error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);  否则是 error_reporting(E_ALL);

if(!defined('CONF_DIR')){
    define('CONF_DIR' , ROOT_DIR . DIRECTORY_SEPARATOR . 'Conf');
}

//默认控制器（业务类 ， 路由默认加载）
if(!defined('APP_DIR')){
    define('APP_DIR' , ROOT_DIR . DIRECTORY_SEPARATOR . 'App');
}

//后台页面模板 ， 暂时没用
if(!defined('APP_DIR_TEMPLATE')){

}

//站内模块类目录
if(!defined('MODULE_DIR')){
    define('MODULE_DIR' , ROOT_DIR . DIRECTORY_SEPARATOR . 'Module');
}

//前台模板的根目录
if(!defined('TEMPLATE_DIR')){
    define('TEMPLATE_DIR' , ROOT_DIR . DIRECTORY_SEPARATOR . 'Templates');
}
/*********************************************
 *
 */
#require_once CORE_DIR . DIRECTORY_SEPARATOR .'Main.php';