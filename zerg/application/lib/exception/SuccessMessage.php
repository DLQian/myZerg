<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/30 0030
 * Time: 22:05
 */

namespace app\lib\exception;


use think\exception\ErrorException;

class SuccessMessage extends BaseException
{
    //HTTP 状态码
    public $code = 201;
    //错误信息
    public $msg = '操作成功';
    //自定义错误码
    public $errorCode = 0;
}