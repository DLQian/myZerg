<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/29 0029
 * Time: 21:00
 */

namespace app\api\controller\v1;

use app\api\model\User as UserModel;
use app\api\service\Token as TokenService;
use app\api\validate\AddressValidate;
use app\lib\exception\SuccessMessage;
use app\lib\exception\UserException;
use think\Controller;

class Address extends BaseController
{
    protected $beforeActionList = [
        'checkPrimaryScope' => ['only' => 'CreateOrUpdateAddress']
    ];


    public function CreateOrUpdateAddress()
    {
        $validate = new AddressValidate();
        $validate->goCheck();
        //TODO
        //根据Token获取uid
        //根据uid查找用户数据，判断用户是否存在
        //获取用户从客户端提交来的地址信息
        //根据用户地址信息是否存在，从而判断是创建还是添加
        $uid = TokenService::getCurrentUid();
        $user = UserModel::get($uid);
        if (!$user)
            throw new UserException();

        $data = $validate->getDataByRule(input('post.'));
        $userAddress = $user->address;
        if (!$userAddress)
            $user->address()->save($data);
        else
            $user->address->save($data);
//        return $user;
        return new SuccessMessage();
    }

}