<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/22 0022
 * Time: 15:07
 */

namespace app\api\controller\v1;
use app\api\validate\IdControllerValidate;
use app\api\model\Theme as ThemeModel;
use app\api\validate\IdValidate;
use app\lib\exception\ThemeException;
use think\Validate;

class Theme
{
    public function getSimpleList($ids='')
    {
        $test = new IdControllerValidate();
        if ($test->goCheck($ids))
        {
            $arrayId = explode(',',$ids);
            $theme = new ThemeModel();
            $result = $theme->backTheme($arrayId);
            return $result;
        }
        else{
            throw new ThemeException();
        }
    }

    /**
     * @route api/v1/theme/:id
     */
    public function getComplexOne($id)
    {
        (new IdValidate())->goCheck();
        $themeModel = new ThemeModel();
        $theme = $themeModel->getThemeAndProductByThemeId($id);
        if (!$theme){
            throw new ThemeException();
        }
        return $theme;
    }
}