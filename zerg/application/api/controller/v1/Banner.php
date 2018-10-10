<?php
namespace app\api\controller\v1;

use app\api\model\Banner as BannerModel;
use app\api\validate\IdValidate;
use app\lib\exception\BannerMissException;



class Banner
{
    /**
     * @param $id
     * @return mixed
     */
    public  function  getBanner($id)
    {
        $validate = new IdValidate();

        if($validate->goCheck($id))
        {
            $banner = BannerModel::with(array('items','items.img'))->find($id);

//             $banner = Banner::getBannerById($id);


            return $banner;
        }

    }
}
//异常
///             try
//            {
//                $banner = Bannerl::getBannerById($id);
//            }
//            catch (Exception $ex)
//            {
//                $err = array(
//                    $code = 1001,
//                    $err_message = $ex->getMessage()
//                );
//                return json($err,400);
//            }
//            return $banner;


////    独立验证  注意引入 use think\validate;
//$data = array(
//    'name' => 'wangqian',
//    'email' => '907578665@qq.com'
//);
//
//$validate = new Validate(array(
//    'name' => 'require|max:5',
//    'email' => 'email'
//));
//
////       验证
//$result = $validate->check($data);
//var_dump($result,$validate->getError());
//echo '<br>';
//
////        批量验证
//$result = $validate->batch()->check($data);
//var_dump($result,$validate->getError());
//echo '<br>';
