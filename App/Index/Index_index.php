<?php
/**
 * Created by PhpStorm.
 * User: Bear <hululidexiong@163.com>
 * Date: 2018/5/12
 * Time: 11:48
 */

class Index_index{

    /**
     * @var Framework
     */
    private $frame;

    function __construct( $frame = null)
    {
        if($frame !== null){
            $this->frame=$frame;
        }
    }

    function index(){
        $this->frame->v('index');
    }
}