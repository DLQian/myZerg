<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/17 0017
 * Time: 10:48
 */

namespace app\api\validate;


class IdValidate extends  BaseValidate
{
    protected $rule = array(
        'id' => 'require|isPositiveInteger'
    );
    protected $message = array('id'=>'id必须为正整数');
}