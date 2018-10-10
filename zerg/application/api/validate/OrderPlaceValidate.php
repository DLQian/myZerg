<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/2 0002
 * Time: 13:15
 */

namespace app\api\validate;


use app\lib\exception\ParameterException;

class OrderPlaceValidate extends BaseValidate
{
    protected $rule = [
        'product' => 'checkProducts'
    ];
    protected $singleProduct = [
        'product_id' => 'require|isPositiveInteger',
        'count' => 'require|isPositiveInteger'
    ];
    protected function checkProducts($value)
    {
        if(empty($value))
            throw new ParameterException(['msg' => '商品参数不正确']);
        if (is_array($value))
            throw new ParameterException(['msg' => '商品列表不能为空']);
        foreach ($value as $v)
        {
            $this->checkProduct();
        }
        return true;
    }
    protected  function  checkProduct($v)
    {
        $validate = new BaseValidate($this->singleProduct);
        $result = $validate->check($v);
        if(!$result)
            throw new ParameterException(['msg' => '商品列表参数异常']);
    }

}