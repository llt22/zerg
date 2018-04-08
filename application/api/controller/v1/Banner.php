<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/6
 * Time: 9:53
 */

namespace app\api\controller\v1;

use app\api\model\Banner as BannerModel;

use app\api\validate\IDMustBePositiveInt;
use app\lib\exception\BannerMissException;

class Banner
{
    /*
     * 获取指定类型的banner
     * @url /banner/:id
     * @http GET
     * @id banner 的id
     * */
    public function getBanner($id)
    {
        // 参数验证，如果没通过直接抛出异常
        (new IDMustBePositiveInt())->goCheck();

        // 让模型提供数据
        $banner = BannerModel::getBannerByID($id);

        // 这里的验证放到哪里有待商榷,我认为放在model里面合适
        if(!$banner){
            throw new BannerMissException();
        }

        return $banner;
    }
}