<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/24 0024
 * Time: 13:58
 */

namespace app\lib\exception;


class ProductException extends BaseException
{
    //HTTP 状态码
    public $code = 404;
    //错误信息
    public $msg = '找不到商品，请稍候再试';
    //自定义错误码
    public $errorCode = 30000;
}