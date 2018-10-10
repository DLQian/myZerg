<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/22 0022
 * Time: 15:07
 */

namespace app\api\model;


class Product extends BaseModel
{

    protected $hidden = array('delete_time','create_time','update_time','from','pivot','category_id');

    public function imgs()
    {
        return $this->hasMany('ProductImage','product_id','id');
    }
    public function properties()
    {
        return $this->hasMany('ProductProperty','product_id','id');
    }
    public function getMainImgUrlAttr($valuse,$data)
    {
        $finaUrl = $this->preImgUrl($valuse,$data);
        return $finaUrl;

    }

    public static function getRecent($count)
    {
        $product = self::limit($count)
            ->order('create_time desc')
            ->select();
        return $product;
    }

    public static function getAllByCategoryID($categoryID)
    {
        $products = self::where('category_id','=',$categoryID)->select();
        return $products;
    }

    public static function getOneById($id)
    {
        $product = self::with([
            'imgs'=>function($query){
            $query->with(['imgUrl'])
                ->order('order','asc');
        }])
            ->where('id','=',$id)
            ->find();
        return $product;
    }

}