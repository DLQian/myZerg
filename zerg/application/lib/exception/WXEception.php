<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/28 0028
 * Time: 0:30
 */

namespace app\lib\exception;


class WXEception extends BaseException
{
    //HTTP 状态码
    public $code = 401;
    //错误信息
    public $msg = '微信错误';
    //自定义错误码
    public $errorCode = 40001;
}