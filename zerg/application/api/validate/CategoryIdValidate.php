<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/24 0024
 * Time: 22:52
 */

namespace app\api\validate;


class CategoryIdValidate extends BaseValidate
{
    protected $rule = array('id' => 'require|between:2,7|isPositiveInteger');

}