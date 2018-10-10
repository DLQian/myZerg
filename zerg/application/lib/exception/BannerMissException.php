<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/15 0015
 * Time: 14:06
 */

namespace app\lib\exception;

class BannerMissException extends BaseException
{
    //HTTP 状态码
    public $code = 404;
    //错误信息
    public $msg = '对不起，找不到网页';
    //自定义错误码
    public $errorCode = 40404;

}