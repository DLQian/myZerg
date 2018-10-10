<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/24 0024
 * Time: 0:11
 */

namespace app\lib\exception;


class ThemeException extends BaseException
{
    //HTTP 状态码
    public $code = 404;
    //错误信息
    public $msg = '找不到主题，请稍后再试';
    //自定义错误码
    public $errorCode = 20000;
}