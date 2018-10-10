<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/3 0003
 * Time: 13:38
 */

namespace app\api\controller\v1;


use app\api\validate\IdValidate;
use app\api\service\WxNotify as WxNotifyService;
use app\lib\exception\ParameterException;
use app\api\service\Pay as PayService;

class Pay extends BaseController
{
    protected $beforeActionList = [
        'needExclusiveScope' =>['only'=>'getPreOrder']
    ];
    public function getPreOrder($id='')
    {
        (new IdValidate())->goCheck();
        $pay = new \app\api\service\Pay($id);
        return $pay->pay($id);
    }
    public function receiveNotify()
    {
        //检测库存量
        //更新订单的status
        //减库存
        $notify = new WxNotifyService();
        $notify->Handle(); //会调用service/WxNotify下重写的NotifyProcess方法
    }
}