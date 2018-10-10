<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/1 0001
 * Time: 21:54
 */

namespace app\api\controller\v1;


use app\api\validate\OrderPlaceValidate;
use app\api\model\Order as OrderModel;
use app\api\validate\PageValidate;
use think\Controller;
use app\api\service\Token as TokenService;
use app\api\service\OrderPlace as OrderPlaceService;

class Order extends BaseController
{
    //用户在选择商品收，服务端接收商品 相关数据
    //接收到数据后，检查订单商品相关的库存信息
    //有库存，把订单数据写入数据库中，下单成功，返回客户端消息，并提示客户端支付
    //（1）调用我们的支付接口进行支付
    //（2）检测库存
    //（3）进行支付
    //（4）微信返回支付结果
    //（5）成功：检测库存量，并修改库存量数据

    protected $beforeActionList = [
        'needExclusiveScope' =>['only'=>'placeOrder']
    ];

    public function placeOrder()
    {
        (new OrderPlaceValidate())->goCheck();
        $products = input('post.products/a');
        $uid = TokenService::getCurrentUid();


        $order = new OrderPlaceService();
        $status = $order->place($uid,$products);
        return $status;
;
    }

    public function getSummaryByUser($page,$limit)
    {
        (new PageValidate())->goCheck();
        $uid = TokenService::getCurrentUid();
        $pagingOrders = OrderModel::getSummaryByUser($uid,$page,$limit);
        if ($pagingOrders->isEmpty())
        {
            return [
                'data' => [],
                'current_page' => $pagingOrders->getCurrentPage()
            ];
        }
        $data = $pagingOrders->hidden(['snap_items','snap_address','prepay_id'])->toArray();
        return [
            'data' => $data ,
            'current_page' => $pagingOrders->getCurrentPage()
        ];

    }
}