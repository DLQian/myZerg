<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/25 0025
 * Time: 20:42
 */

namespace app\api\controller\v1;

use app\api\service\UserToken;
use app\api\validate\TokenCodeValidate;


class Token
{
    public function getToken($code = '')
    {
        (new TokenCodeValidate())->goCheck($code);
        $userToken = new UserToken($code);
        $token =$userToken->get($code);
        return array(
            'token' => $token
        );
    }

}