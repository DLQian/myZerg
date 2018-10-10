<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/24 0024
 * Time: 13:43
 */

namespace app\api\validate;


class CountValidate extends BaseValidate
{
    protected $rule = array(
        'count' => 'isPositiveInteger|between:1,10'
    );
}