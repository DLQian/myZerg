<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/2 0002
 * Time: 15:14
 */

namespace app\lib\exception;


class OrderException extends BaseException
{
    //HTTP 状态码
    public $code = 404;
    //错误信息
    public $msg = '订单不存在，请检查ID';
    //自定义错误码
    public $errorCode = 80000;
}