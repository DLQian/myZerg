<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/3 0003
 * Time: 13:41
 */

namespace app\api\service;

use app\api\model\Order as OrderModel;
use app\api\service\OrderPlace as OrderPlaceService;

use app\lib\enum\OrderStatusEnum;
use app\lib\exception\OrderException;
use app\lib\exception\TokenException;
use think\Loader;
use think\Log;

Loader::import('WxPay.WxPay', EXTEND_PATH, '.Api.php');
class Pay
{
    private $orderId;
    private $orderNo;
    function __construct($orderId)
    {
        if (!$orderId)
        {
            throw new OrderException('未接收到订单编号');
        }
        $this->orderId = $orderId;
    }
    public function  pay()
    {
        //判断订单是否存在
        //判断订单和ID是否匹配
        //判断订单当前状态
        //判断订单库存量
        $this->checkOrderValid();
        $orderPlace = new OrderPlaceService();
        $status = $orderPlace->checkOrderStock($this->orderId);
        if (!$status['pass'])
        {
            return $status;
        }
        return $this->makePreOrder($status['orderPrice']);
    }
    private function makePreOrder($totalPrice)
    {
        $openid = Token::getCurrentUid();
        if (!$openid)
        {
            throw new TokenException();
        }
        //配置商户信息可以在 extend/WxPay.Config.Interface.php下配置
        //配置SDK
        $wxOrderData = new \WxPayUnifiedOrder();
        $wxOrderData->SetOut_trade_no($this->orderNo);
        $wxOrderData->SetTrade_type('JSAPI');
        $wxOrderData->SetTotal_fee($totalPrice * 100);
        $wxOrderData->SetBody('零食商贩');
        $wxOrderData->SetOpenid($openid);
//        $wxOrderData->SetNotify_url('');
        $wxOrderData->SetNotify_url('');

        return $this->getPaySignature($wxOrderData);
    }
    private function getPaySignature($wxOrderData)
    {
//        $wxOrder = \WxPayApi::unifiedOrder($config,$wxOrderData);
        $wxOrder = \WxPayApi::unifiedOrder($wxOrderData);
        if ($wxOrder['return_code'] != 'SUCCESS' || $wxOrder['result_code'] !='SUCCESS')
        {
            Log::record($wxOrder,'error');
            Log::record('获取预支付订单失败','error');
        }

        $this->recordPreOrder($wxOrder);
        $signature = $this->sign($wxOrder);
        return $signature;
    }
    private function recordPreOrder($wxOrder)
    {
        OrderModel::where('id','=',$this->orderId)
            ->update(['prepay_id'=>$wxOrder['prepay_id']]);
    }
    private function sign($wxOrder)
    {
        $jsApiPayData = new \WxPayJsApiPay();
        $jsApiPayData->SetAppid(config('wx.app_id'));
        $jsApiPayData->SetTimeStamp((string)time());
        $rand = md5(time().mt_rand(0,1000));
        $jsApiPayData->SetNonceStr($rand);
        $jsApiPayData->SetPackage('prepay_id='.$wxOrder['prepay_id']);
        $jsApiPayData->SetSignType('md5');

        $sign = $jsApiPayData->MakeSign();
        $rawValue = $jsApiPayData->GetValues();
        $rawValue['sign'] = $sign;
        unset($rawValue['appId']);
        return $rawValue;
    }
    private function checkOrderValid()
    {
        $order = OrderModel::where('id','=',$this->orderId)->find();
        if (!$order)
        {
            throw new OrderException();
        }
        if (!Token::isValidOperate($order->user_id))
        {
            throw new TokenException([
                'msg' => '订单用户和当前用户不匹配',
                'erorrCode' => '10004',
                'code' => '401',
            ]);
        }
        if ($order->status != OrderStatusEnum::UNPAID)
        {
            throw new OrderException([
                'msg' => '订单已支付',
                'erorrCode' => '80001',
                'code' => '401',
            ]);
        }
        $this->orderNo = $order->order_no;
        return true;
    }
}