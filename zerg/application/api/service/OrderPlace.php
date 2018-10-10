<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/2 0002
 * Time: 14:21
 */

namespace app\api\service;
use app\api\controller\v1\Order;
use app\api\model\OrderProduct as OrderProductModel;
use app\api\model\Product as ProductModel;
use app\api\model\UserAddress;
use app\lib\exception\OrderException;
use app\lib\exception\UserException;
use app\api\model\Order as OrderModel;
use think\Db;

class OrderPlace
{
    //订单传过来的商品信息
    protected $orderProducts;
    //数据库中真实的商品信息
    protected $products;
    //用户id
    protected $uid;

    public function place($uid , $orderProducts)
    {
        $this->orderProducts = $orderProducts;
        $this->products = $this->getProductByOrder($orderProducts)  ;
        $this->uid = $uid;
        $status = $this->getOrderStatus();
        if(!$status['pass'])
        {
            $status['order_id'] = -1;
            return $status;
        }
        //生成订单快照
        $orderSnap = $this->orderSnap($status);
        $order = $this->createOrder($orderSnap);
        $order['pass'] = true;
        return $order;
    }

    protected function createOrder($snap)
    {
        Db::startTrans();
        try {
            $orderNo = $this->makeOrderNo();
            $order = new OrderModel();
            $order->user_id = $this->uid;
            $order->order_no = $orderNo;
            $order->total_price = $snap['orderPrice'];
            $order->total_count = $snap['totalCount'];
            $order->snap_img = $snap['snapImg'];
            $order->snap_name = $snap['snapName'];
            $order->snap_address = json_encode($snap['snapAddress']);
            $order->snap_items = json_encode($snap['pStatus']);
            $order->save();

            $orderID = $order->id;
            $create_time = $order->create_time;
            foreach ($this->orderProducts as &$v)
            {
                $v['order_id'] = $orderID;
            }
            $orderProduct = new OrderProductModel();
            $orderProduct->saveAll($this->orderProducts);
            Db::commit();
            return array(
                'order_no' => $orderNo,
                'order_id' => $orderID,
                'create_time' => $create_time,
            );


        } catch (Exception $ex) {
            Db::rollback();
            throw $ex;
        }
    }
    protected function orderSnap($status)
    {
        $snap = [
            'orderPrice' => 0,
            'totalCount' => 0,
            'pStatus' =>[],
            'snapAddress' => null,
            'snapName' => null,
            'snapImg' => '',
        ];
        $snap['orderPrice'] = $status['orderPrice'];
        $snap['totalCount'] = $status['totalCount'];
        $snap['pStatus'] = $status['pStatusArray'];
        $snap['snapAddress'] = $this->getUserAddress();
        $snap['snapName'] = $this->products[0]['name'];
        $snap['snapImg'] = $this->products[0]['main_img_url'];
        if (count($snap['totalCount'])>1)
        {
            $snap['snapName'] = $snap['snapName'].' 等';
        }
        return $snap;
    }

    public static function makeOrderNo()
    {
        $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
        $orderSn =
            $yCode[intval(date('Y')) - 2017] . strtoupper(dechex(date('m'))) . date(
                'd') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf(
                '%02d', rand(0, 99));
        return $orderSn;
    }

    protected function getUserAddress()
    {
        $userAddress = UserAddress::where('user_id','=',$this->uid)
        ->find();
        if (!$userAddress)
        {
            throw new UserException([
                'msg' => '用户收货地址不存在'
            ]);
        }
        return $userAddress;

    }
    public function checkOrderStock($orderId)
    {
        $oProduct = (new OrderProductModel)->where('order_id','=',$orderId)->select();
        $this->orderProducts = $oProduct;
        $this->products = $this->getProductByOrder($oProduct);
        $status = $this->getOrderStatus();
        return $status;

    }
    protected function getOrderStatus()
    {
        //从订单中传来的数据和真实的数据做比较，判断订单状态
        $orderStatus = [
            'pass' => true,
            'orderPrice' => 0,
            'pStatusArray' => [],
            'totalCount' => 0,
        ];
        foreach($this->orderProducts as $v)
        {
            $pStatus = $this->getProductStatus($v['product_id'],$v['count'],$this->products);
            if (!$pStatus['pass'])
            {
                $orderStatus['pass'] = false;
            }
            $orderStatus['orderPrice'] += $pStatus['totalPrice'];
            $orderStatus['totalCount'] += $pStatus['count'];
            array_push($orderStatus['pStatusArray'],$pStatus);
        }
        return $orderStatus;

    }
    protected function getProductStatus($oPid,$count,$products)
    {
        $pIndex = -1;

        for ($i = 0 ; $i < count($products) ; $i++)
        {
            if($oPid == $products[$i]['id'])
            {
                $pIndex = $i;
            }
        }
        if($pIndex == -1)
        {
            //客户端传过来的ID商品不存在
            throw new OrderException([
                'msg' => 'id为'.$oPid.'的商品不存在,创建订单失败'
            ]);
        }
        else
        {
            $product = $products[$pIndex];
            $productStatus = [
                'id' => $product['id'],
                'count' => $count,
                'name' => $product['name'],
                'totalPrice' => $product['price'] * $count,
                'pass' => false,
            ];
            if ($product['stock'] >= $count)
                $productStatus['pass'] = true;
            return $productStatus;
        }
    }

    //根据订单信息查找真实信息
    protected function getProductByOrder($orderProducts)
    {
        $productIds = [];
        foreach ($orderProducts as $v)
        {
            array_push($productIds,$v['product_id']);
        }
        $products = ProductModel::all($productIds)->visible()->toArray();
//        $products->visible(['id', 'price', 'stock', 'name', 'main_img_url'])
//            ->toArraay();
        return $products;
    }
}