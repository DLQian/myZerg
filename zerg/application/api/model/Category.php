<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/24 0024
 * Time: 22:25
 */

namespace app\api\model;


class Category extends BaseModel
{
    protected $hidden = array('delete_time','update_time');
    public function img()
    {
        $result = $this->belongsTo('Image','topic_img_id','id');
        return $result;
    }

    public static function getAll(){
        $categories = self::with('img')->select();
        return $categories;
    }
}