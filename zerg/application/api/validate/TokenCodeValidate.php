<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/25 0025
 * Time: 20:44
 */

namespace app\api\validate;


class TokenCodeValidate extends BaseValidate
{
    protected $rule = array(
        'code' => 'isNotEmpty'
    );
    protected  $message = array(
        'code' => '未能获取到code，请稍候再试'
    );
}