<?php
namespace app\api\model;


class Image extends BaseModel
{
    protected  $hidden = array('from','update_time','delete_time','id');

    /**
     * @param $value 值为 getXXXAttr 取出XXX对应字段的值
     * @param $data  值为 Url对应表全部字段的值
     */
    public function getUrlAttr($value ,$data)
    {
        return parent::preImgUrl($value,$data);
    }
}