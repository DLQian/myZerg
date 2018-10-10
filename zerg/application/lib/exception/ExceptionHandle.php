<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/15 0015
 * Time: 14:05
 */

namespace app\lib\exception;

use think\exception\Handle;
use think\Log;
use think\Request;
use Exception;

class ExceptionHandle extends Handle
{
    public function render(\Exception $e)
    {
        $err = array('code','msg','errorCode');

        if($e instanceof BaseException)
        {
            //自定义异常类
            $err['code'] = $e->code;
            $err['msg'] = $e->msg;
            $err['errorCode'] = $e->errorCode;
        }
        else
        {
            if(config('app_debug'))
            {
                return parent::render($e);
            }
            else
            {
                $err['code'] = 500;
                $err['msg'] = '内部错误';
                $err['errorCode'] = '999';
                $this->recordErrorLog($e);
            }

        }
        $request = Request::instance();
        $result = array(
            'code' => $err['code'],
            'msg' => $err['msg'],
            'errorCode' => $err['errorCode'],
            'url' => $request->url()
        );
        return json($result , $err['code']);
//        return '!!!!!!!!!!!111';
    }
    public function recordErrorLog(\Exception $e)
    {
        Log::init(array(
            // 日志记录方式，内置 file socket 支持扩展
              'type'  => 'File',
              // 日志保存目录
              'path'  => LOG_PATH,
              // 日志记录级别
              'level' => ['error']));
        LOG::record($e->getMessage(),'error');

    }
}