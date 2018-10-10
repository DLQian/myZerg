<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/15 0015
 * Time: 12:38
 */
namespace app\api\validate;
use app\lib\exception\ParameterException;
use think\Request;
use think\Validate;

class BaseValidate extends Validate
{
    public function goCheck()
    {
        //获取http请求的参数，并对这些参数进行校验
        $request = Request::instance();
        $params = $request->param();

        $result = $this->batch()-> check($params);
        if(!$result)
        {
            $e = new ParameterException(array(
                'msg'=>$this->error
            ));
//            $e->msg = $this->error;
            throw $e;
        }
        else
        {
            return true;
        }

    }

    /**
     * 判断是否为正整数
     * @param $value
     * @param string $rule
     * @param string $data
     * @return bool
     */
    protected function isPositiveInteger($value,$rule='',$data='',$field='')
    {
        if(is_numeric($value) && is_int($value + 0 ) && ($value + 0 ) > 0 )
           return true;
        else
           return false;
    }

    protected  function isNotEmpty($value,$rule='',$data='',$field='')
    {
        if (empty($value))
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    protected function isMobile($value,$rule='',$data='',$field='')
    {
        $rule = '^1(3|4|5|7|8)[0-9]\d{8}$^';
        $result = preg_match($rule,$value);
        if ($result) return true;
        return false;
    }
    public function getDataByRule($array)
    {
        if (array_key_exists('uid',$array) || array_key_exists('user_id',$array))
        {
            throw new ParameterException();
        }
        $data = [];
        foreach ($this->rule as $k=>$v)
        {
            $data[$k] = $array[$k];
        }
        return $data;
    }
}