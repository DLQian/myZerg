<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/29 0029
 * Time: 19:53
 */

namespace app\api\model;


class ProductProperty extends BaseModel
{
    protected $hidden = array('update_time','delete_time','product_id');
}