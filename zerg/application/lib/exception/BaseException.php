<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/15 0015
 * Time: 13:09
 */

namespace app\lib\exception;
use think\Exception;

class BaseException extends Exception
{
    //HTTP 状态码
    public $code = 0;
    //错误信息
    public $msg = '网页初始';
    //自定义错误码
    public $errorCode = 0;


}