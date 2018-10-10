<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/2 0002
 * Time: 22:51
 */

namespace app\api\model;

use app\api\model\Order as OrderModel;
class Order extends BaseModel
{
    protected $hidden = ['create_time','delete_time','update_time'];
    protected $autoWriteTimestamp = true;

    public static function getSummaryByUser($uid,$page,$limit)
    {
        $result = OrderModel::where('user_id','=',$uid)
            ->order('create_time desc')
            ->paginate($limit , true , ['page'=>$page]);
        return $result;
    }
}