<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/20 0020
 * Time: 20:26
 */

namespace app\api\model;

class BannerItem extends BaseModel
{
    //设置隐藏字段
    protected  $hidden = array('id','img_id','banner_id','update_time','delete_time');
    public function img()
    {
        return $this->belongsTo('Image','img_id','id');
    }
}