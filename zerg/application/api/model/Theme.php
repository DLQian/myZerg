<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/22 0022
 * Time: 15:07
 */

namespace app\api\model;


class Theme extends BaseModel
{
    protected $hidden = array('delete_time','update_time');
    public function topicImg()
    {
        return $this->belongsTo('Image','topic_img_id','id');
    }
    public function headImg()
    {
        return $this->belongsTo('Image','head_img_id',' id');
    }
    public function product()
    {
        return $this->belongsToMany('Product','theme_product','product_id','theme_id');
    }
    public  function backTheme($ids)
    {
        return parent::with('topicImg','headImg')->select();
    }
    public function getThemeAndProductByThemeId($id)
    {
        $theme = $this->with('product','headImg','topicImg')->select($id);
        return $theme;
    }
}