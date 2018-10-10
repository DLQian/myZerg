<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/24 0024
 * Time: 22:22
 */

namespace app\api\controller\v1;
use app\api\model\Category as CategoryModel;

class Category
{
    public function getAllCategory()
    {
        $category = CategoryModel::getAll();
        return $category;
    }


}