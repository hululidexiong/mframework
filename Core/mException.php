<?php
/**
 * Created by PhpStorm.
 * User: Bear <hululidexiong@163.com>
 * Date: 2018/4/22
 * Time: 16:48
 */

namespace m;


class MException extends \Exception
{
    function __construct($message, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}