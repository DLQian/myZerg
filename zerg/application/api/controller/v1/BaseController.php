<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/2 0002
 * Time: 12:41
 */

namespace app\api\controller\v1;

use app\api\service\Token as TokenService;
use think\Controller;

class BaseController extends Controller
{
    protected function checkPrimaryScope()
    {
        TokenService::needPrimaryScope();
    }
    protected function needExclusiveScope()
    {
        TokenService::needExclusiveScope();
    }
}