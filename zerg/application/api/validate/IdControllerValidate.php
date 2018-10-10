<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/22 0022
 * Time: 15:41
 */

namespace app\api\validate;


class IdControllerValidate extends BaseValidate
{
    protected $rule = array(
        'ids' => 'require|checkIds'
    );
    protected  $message=array('ids'=>'参数必须是以，隔开的正整数');
    protected function checkIds($ids)
    {
        $arrIds = explode(',',$ids);
        if(empty($arrIds))
            return false;
        foreach ($arrIds as $v)
        {
            if(! $this->isPositiveInteger($v)) {
                return false;
            }
        }
        return true;
    }
}