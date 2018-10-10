<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/25 0025
 * Time: 20:49
 */
namespace app\api\service;
use app\api\model\User;
use app\api\model\User as UserModel;
use app\lib\enum\ScopeEnum;
use app\lib\exception\WXEception;


class UserToken extends Token
{
    protected $code;
    protected $appId;
    protected $appSecret;
    protected $appUrl;

    function __construct($code)
    {
        $this->code = $code;
        $this->appId = config('wx.appId');
        $this->appSecret = config('wx.appSecret');
        $this->appUrl = sprintf(config('wx.appUrl'),$this->appId,$this->appSecret,$this->code);

    }
    public function get($code)
    {
       $result = curl_get($this->appUrl);
       $wxResult =json_decode($result,true);
       if (empty($wxResult))
       {
           throw new Exception('code码返回数据为空，微信内部错误');
       }
       else
       {
           if (array_key_exists('errcode',$wxResult))
           {
              $this-> WXThrowException($wxResult);
           }
           return $this->grantToken($wxResult);
       }
    }

    public function grantToken($wxResult)
    {
        //TODO 1.获取通过openID去数据库查询是否已经存在
        // 2 . 存在返回userID 不存在生成返回userID
        // 3. 生成缓存
        // 4. 返回客户端
        $openId = $wxResult['openid'];
        $result = UserModel::findUserByOpenId($openId);
        if ($result)
        {
            $userId = $result['id'];
        }
        else {
            $userId = $this->newUser($openId);
        }
        $cacheValue = $this->perpareCacheValue($wxResult,$userId);
        $token = $this->saveToToken($cacheValue);
        return $token;
    }

    public function saveToToken($cacheValue)
    {
        $key = self::generateToken();
        $value = json_encode($cacheValue);
        $expire_in = config('secure.token_invalid');
        $request = cache($key,$value,$expire_in);
        if (!$request)
        {
            throw new TokenException(array(
                'msg' => '服务器异常',
                'errorCode' => 10005
            ));
        }
        return $key;
    }
    public function newUser($openId)
    {
        $userId = UserModel::create(array(
            'openid' => $openId
        ));
        return $userId['id'];
    }

    public function perpareCacheValue($wxResult,$userId)
    {
        $cacheValue = $wxResult;
        $cacheValue['userid'] = $userId;
        $cacheValue['scope'] = ScopeEnum::user;

        return $cacheValue;
    }

    protected  function WXThrowException($wxResulte)
    {
        throw new WXEception(array(
            'msg' => $wxResulte['errmsg'],
            'errorCode' => $wxResulte['errcode'],
            ));
    }

}