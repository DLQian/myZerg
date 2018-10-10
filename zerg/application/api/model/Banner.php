<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/15 0015
 * Time: 12:48
 */
namespace app\api\model;
use think\Db;


class Banner extends BaseModel
{
//    protected $table = 'banner_item';
    protected  $hidden = array('update_time','delete_time');
    public function items()
    {
        //第一个参数 关联模型的模型名 第二 关联模型外键 第三 当前模型主键
        return $this->hasMany('BannerItem','banner_id','id');
    }
    public static function getBannerById($id)
    {
        //原生代码
//        return Db::query('select * from banner_item where banner_id=?;',[$id]);

//       构造器  链式方法
        $result = Db::table('banner_item')->where('banner_id','=',$id)->select();
        return $result;
    }

}