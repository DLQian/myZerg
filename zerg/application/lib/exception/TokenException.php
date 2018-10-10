<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/27 0027
 * Time: 22:21
 */

namespace app\lib\exception;


class TokenException extends BaseException
{
    //HTTP 状态码
    public $code = 401;
    //错误信息
    public $msg = 'Token无效或Token已过期';
    //自定义错误码
    public $errorCode = 10001;
}