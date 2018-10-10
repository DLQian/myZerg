<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/25 0025
 * Time: 20:47
 */

namespace app\api\model;


class User extends BaseModel
{
    public function address()
    {
        return $this->hasOne('UserAddress','user_id','id');
    }
    public static function findUserByOpenId($openId)
    {
        $user = self::where('openid','=',$openId)
        ->find();
        return $user;
    }
}