<?php
/**
 * Created by PhpStorm.
 * User: Bear <hululidexiong@163.com>
 * Date: 2018/4/22
 * Time: 16:34
 */

namespace m;

class Framework{


    static $_instance;
    static $outhtml;

    protected static $_instance_module;
    public static $_G;

    protected $_config;
    protected $entry='Index';



    private $style = 'default'; //模板风格

    protected $beforeFunc=[]; //初始化前执行 ， 没有 _G
    protected $afterFunc=[]; //初始化后执行  有 _G   第一个参数 &$_G

    protected $renderedFunc=[]; // 模板渲染后执行 有 $template

    public function beforePush( callback $callback ){
        array_push( $this->beforeFunc , $callback);
    }

    public function afterPush( callback $callback ){
        array_push( $this->afterFunc , $callback);
    }

    public function renderedPush( callback $callback ){
        array_push( $this->renderedFunc , $callback);
    }

    public static function app(){
        if(!self::$_instance instanceof self){
            self::$_instance = new static;
        }
        return self::$_instance;
    }

    public function start( $entry=null ){
        $this->init( $entry );
    }

    protected function init( $entry = null){
        if($entry){
            $this->entry = $entry;
        }
        if($this->beforeFunc ){
            foreach ( $this->beforeFunc as $func){
                $func();
            }
        }

        $this->loadConf();

        //...

        if($this->afterFunc ){
            foreach ( $this->afterFunc as $func){
                $func();
            }
        }

        $this->run();

    }
    /**
     * 框架运行方法， 路由加载控制器
     */
    protected function run()
    {
        (isset($_GET['_c']) and trim($_GET['_c'])!='') or $_GET['_c']="index";
        (isset($_GET['_m']) and trim($_GET['_m'])!='') or $_GET['_m']="index";
        $_GET["_c"]=strtr($_GET['_c'],['\\'=>'','/'=>'']); // ,'.'=>''
        $_GET["_m"]=strtr($_GET['_m'],['\\'=>'','/'=>'']); // ,'.'=>''
        //$file_path= ROOT_DIR ."/App/Controller/".$_GET["_c"].".php";
        $class = $this->entry.'_'.$_GET["_c"];
        $fileName = $class.".php";
        $file_path= APP_DIR . DIRECTORY_SEPARATOR . $this->entry. "/".$fileName;
        echo $file_path;
        if(!is_file($file_path) ) {
          throw new MException($_GET['_c'] . ' controller in Entry:' . $this->entry);
        };
        require($file_path);
        $m=$_GET['_m'];
        $o=new $class( $this );
        $r = $o->$m();
        return $r;
    }

    /**
     * 命令行模式运行框架 ， 路由加载控制器
     * @param string $_c
     * @param string $_m
     * @param array $argv
     */
    public static function runcmd($_c='',$_m='',$argv=[])
    {
        $_c or $_c="index";
        $_m or $_m="index";
        $file_path=D."/app/c/".$_c.".php";
        is_file($file_path) or self::nofile($_c);
        require($file_path);
        $c="\\c\\".$_c;
        $m=$_m;
        $o=new $c();
        $o->$m($argv);

    }


    protected function loadConf(){
        $conf_path = CONF_DIR . DIRECTORY_SEPARATOR .  ( DEBUG_MODE === 'online' ? 'Online' : 'Dev');
        if(!is_dir( $conf_path )){
            throw new MException( ' code:50001 conf dir is null! ' );
        }
        foreach (glob( $conf_path . DIRECTORY_SEPARATOR . "*.php") as $filename) {
            $_conf=require($filename);
            self::$_G['config'] = array_merge( (array)self::$_G['config'] , $_conf);
        }
    }

    static public function post( $attribute = null , $force = false){
        if( self::$_G || $force==true){
            self::$_G[ 'REQUEST' ] = array_merge($_GET,$_POST);
        }
        return $attribute === null ? self::$_G[ 'REQUEST' ][$attribute] : self::$_G[ 'REQUEST' ];
    }


    /**
     * 模板输出  (配合模板引擎)
     * @param array $_vn
     * @param array $_vd
     * @param bool $r
     * @return string
     */
    public function v($template , $_vn=[],$_vd=[],$r=false){
        ob_start();
        extract($_vd);
//        self::setxsrf();
//        $_xsrf=self::$_xsrf;//开启xsrf;
//        $_xsrfh="<input type=\"hidden\" name=\"_xsrf\" value=\"".$_xsrf."\" />";
//        foreach($_vn as $k=>$v){
//            $file_path=D."/app/v/".$v.".php";
//            if(!is_file($file_path))
//            {
//                continue;//如果不存在文件就跳过
//            }
//            require($file_path);
//        }

        $style = $this->style;
        $path = TEMPLATE_DIR . DIRECTORY_SEPARATOR . $style . DIRECTORY_SEPARATOR .$template . '.html' ;

        require( $path );
        $ret=ob_get_clean();
        self::$outhtml=$ret;
        if($r)
        {
            return $ret;
        }else{
            echo $ret;
            exit;
        }
    }

    /**
     * 内部类自动调用 ， 优先识别别名
     * @param $class_name
     */
    public static function L($class_name)
    {
        if($class_name{0} === '#'){
            if( !self::$_instance_module[$class_name] ){
                $actual_class = substr( $class_name , 1);
                if( !class_exists( $actual_class ) ){
                    $path =  MODULE_DIR . DIRECTORY_SEPARATOR . $actual_class . '.php';
                    if(!is_file( $path )) throw new MException( $actual_class . ' module does not exist!');
                    require $path;
                }
                self::$_instance_module[$class_name] = new $actual_class();
            }
            return self::$_instance_module[$class_name];
        }
        throw new MException( ' 待开发 !' );
    }



//------------------------------------

    /**
     * 加载conf.php中的配置
     * @param $group 配置分组
     * @param string $item 分组中的配置名
     * @return mixed
     */
    public static function commconf($group = '',$item=""){
        $conf_path = is_dir( ROOT_DIR . DIRECTORY_SEPARATOR . 'Conf') ? ROOT_DIR . DIRECTORY_SEPARATOR . 'Conf' : CORE_DIR . DIRECTORY_SEPARATOR . 'Conf';

        if(!is_dir( $conf_path )){
            throw new MException( ' code:50001 conf dir is null! ' );
        }

        foreach (glob( $conf_path . DIRECTORY_SEPARATOR . "*.php") as $filename) {
            echo "$filename size " . filesize($filename) . "\n";
        }
        return 0;
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









    /**
     * four zero four 报404错误。
     */
    public static function fzf($str='')
    {
        header("Status: 404 Not Found");
        $str and print($str);
        exit();
    }

    /**
     * 301 跳转
     * @param string $to
     */
    public static function r($to="")
    {
//        header("HTTP/1.1 301 Moved Permanently");
        header('Location:' . $to,true,301);
        exit();
    }
}


class M extends Framework{}