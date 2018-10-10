<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/9 0009
 * Time: 20:03
 */

namespace app\api\validate;


class PageValidate extends BaseValidate
{
    protected $rule = [
        'page' => 'isPositiveInteger',
        'limit' => 'isPositiveInteger',
    ];
    protected  $message = [
        'page' => '参数必须为整数',
        'limit' => '参数必须为整数',
    ];
}