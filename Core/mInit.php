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


/*********************************************
 *
 */

define('CORE_DIR' , __DIR__);
define( 'ROOT_DIR' , dirname( CORE_DIR ));


/*********************************************
 *
 */
require_once CORE_DIR . DIRECTORY_SEPARATOR .'mMain.php';