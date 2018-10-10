<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/27 0027
 * Time: 21:24
 */

namespace app\api\service;


use app\lib\enum\ScopeEnum;
use app\lib\exception\ForbiddenException;
use app\lib\exception\TokenException;
use think\Cache;
use think\Exception;
use think\Request;

class Token
{
    public static function generateToken()
    {
        //返回一个32位的随机字符串
        $randChars = getRandChar(32);
        //时间撮
        $time = time();
        //salt
        $salt = config('secure.token_salt');
        $str = $randChars.$time.$salt;
        return md5($str);
    }

    public static function getTokenVars($key)
    {
        $token = Request::instance()->header('token');
        $vars = Cache::get($token);
        if (!$vars)
            throw new TokenException();
        if (!is_array($vars))
            $vars = json_decode($vars,true);
        if (!array_key_exists($key,$vars))
            throw new TokenException(array(
                'code' => '500',
                'mgs' => '指定的值不存在',
                'errorCode' => '10002',
            ));

        return $vars[$key];
    }
    public static function getCurrentUid()
    {
        $uid = self::getTokenVars('userid');
        return $uid;
    }
    //用户管理员访问权限
    public static function needPrimaryScope()
    {
        $scope = self::getTokenVars('scope');
        if (!$scope)
            throw new TokenException();
        if ($scope >= ScopeEnum::user)
            return true;
        else
            throw new ForbiddenException();
    }
    //只有用户才能访问权限
    public static function needExclusiveScope()
    {
        $scope = self::getTokenVars('scope');
        if (!$scope)
            throw new TokenException();
        if ($scope == ScopeEnum::user)
            return true;
        else
            throw new ForbiddenException();
    }

    public static function isValidOperate($checkedUid)
    {
        if(!$checkedUid)
        {
            throw new Exception('检测ID时必须传入ID值');
        }
        if ($checkedUid == self::getCurrentUid())
        {
            return true;
        }
        return false;
    }
}