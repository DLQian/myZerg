<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/1 0001
 * Time: 21:20
 */

namespace app\lib\exception;


class ForbiddenException extends BaseException
{
    //HTTP 状态码
    public $code = 403;
    //错误信息
    public $msg = '没有此权限';
    //自定义错误码
    public $errorCode = 10003;
}