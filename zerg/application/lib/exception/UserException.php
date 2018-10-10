<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/30 0030
 * Time: 21:54
 */

namespace app\lib\exception;


use think\Exception;

class UserException extends Exception
{
    //HTTP 状态码
    public $code = 404;
    //错误信息
    public $msg = '用户不存在';
    //自定义错误码
    public $errorCode = 50001;
}