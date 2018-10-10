<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/29 0029
 * Time: 19:52
 */

namespace app\api\model;


class ProductImage extends BaseModel
{
    protected $hidden = array('img_id','delete_time','product_id');
    public function imgUrl()
    {
        return $this->hasMany('Image','id','img_id');
    }
}