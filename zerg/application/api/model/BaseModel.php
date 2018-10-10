<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/22 0022
 * Time: 14:19
 */

namespace app\api\model;


use think\Model;

class BaseModel extends Model
{
    protected function preImgUrl($value,$data)
    {
        $finaImg = $value;
        if($data['from'] == 1)
            $finaImg = config('setting.img_prefix').$value;
        return $finaImg;
    }
}