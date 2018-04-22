<?php
/**
 * Created by PhpStorm.
 * User: Bear <hululidexiong@163.com>
 * Date: 2018/4/22
 * Time: 16:34
 */

namespace m;

class mFramework{

    static $_G;
    static $_config;

    /**
     * 加载conf.php中的配置
     * @param $group 配置分组
     * @param string $item 分组中的配置名
     * @return mixed
     */
    public static function commconf($group,$item=""){
        $conf_path = is_dir( ROOT_DIR . DIRECTORY_SEPARATOR . 'Conf') ? ROOT_DIR . DIRECTORY_SEPARATOR . 'Conf' : CORE_DIR . DIRECTORY_SEPARATOR . 'Conf';

        if(!is_dir( $conf_path )){
            throw new MException( ' code:50001 conf dir is null! ' );
        }

        $filename=D.'/core/conf.php';
        WEB_ADS=='localhost' and $filename=D.'/core/testconf.php';
        $_conf=require($filename);
        $tmp='';
        if($item)
        {
            isset($_conf[$group][$item]) and $tmp=$_conf[$group][$item];
        }else{
            isset($_conf[$group]) and $tmp=$_conf[$group];
        }
        return $tmp;
    }

}


class M extends mFramework{}