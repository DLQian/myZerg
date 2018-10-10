<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/7 0007
 * Time: 22:46
 */

namespace app\api\service;

use app\api\model\Product as ProductModel;
use app\lib\enum\OrderStatusEnum;
use think\Db;
use think\Exception;
use think\Loader;
use app\api\model\Order as OrderModel;
use think\Log;

Loader::import('WxNotify',EXTEND_PATH,'Api.php');
class WxNotify extends \WxPayNotify
{
    public function NotifyProcess($data, &$msg)
    {
        if($data['result_code'] == 'SUCCESS')
        {
            $orderNo = $data['out_trade_no'];
            Db::startTrans();
            try
            {
                $order = OrderModel::where('order_no','=','orderNo')->find();
                if($order == OrderStatusEnum::UNPAID)
                {
                    $service = new OrderPlace();
                    $stockStatus = $service->checkOrderStock($order->id);
                    if ($stockStatus['pass'])
                    {

                        $this->updateOrderStatus($order->id,true);
                        $this->reduceStock($stockStatus);
                    }
                    else
                    {
                        $this->updateOrderStatus($order->id,false);
                    }
                }
                Db::commit();
                return true;
            }catch (Exception $e)
            {
                Db::rollback();
                Log::error($e);
                return false;
            }
        }
        else{
            return true;
        }
    }


    private function reduceStock($stockStatus)
    {
        foreach ($stockStatus['pStatusArray'] as $singlePStatus)
        {
//            $singlePStatus['count'];
            ProductModel::where('id','=',$singlePStatus['id'])->setDec('stock',$singlePStatus['cont']);
        }
    }
    private function updateOrderStatus($orderId,$result)
    {
        $status = $result?OrderStatusEnum::PAID:OrderStatusEnum::PAID_BUT_OUT_OF;
        OrderModel::where('id','=',$orderId)->update(['status'=>$status]);

    }
}
