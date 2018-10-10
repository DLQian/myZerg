<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/24 0024
 * Time: 13:42
 */

namespace app\api\controller\v1;


use app\api\validate\CategoryIdValidate;
use app\api\validate\CountValidate;
use app\api\model\Product as ProductModel;
use app\api\validate\IdValidate;
use app\lib\exception\ProductException;

class Product
{
    public function getMostRecent($count = 10)
    {
        (new CountValidate())->goCheck($count);
        $product = ProductModel::getRecent($count);
        if (!$product)
        {
            throw new ProductException();
        }
        return $product;

    }

    /**
     * @param $id 分类ID
     * 通过分类ID获取该分类下的商品
     */
    public function getAllInCategory($id)
    {
        (new CategoryIdValidate())->goCheck($id);
        $products = ProductModel::getAllByCategoryID($id);
        if (!$products)
        {
            throw new ProductException();
        }
        return $products;
    }

    public function getOne($id)
    {
        (new IdValidate())->goCheck($id);
        $product = ProductModel::getOneById($id);
        if (!$product)
        {
            throw new ProductException();
        }
        return $product;
    }
}