<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/7
 * Time: 18:42
 */

namespace app\api\model;


use app\lib\exception\ThemeMissException;

class Theme extends BaseModel
{
    protected $hidden = ['update_time', 'delete_time', 'topic_img_id', 'head_img_id'];

    public function topicImg()
    {
        return $this->belongsTo('Image', 'topic_img_id', 'id');
    }

    public function headImg()
    {
        return $this->belongsTo('Image', 'head_img_id', 'id');
    }

    public function products(){
        return $this->belongsToMany('Product', 'theme_product', 'product_id','theme_id');
    }

    public static function getThemesByIDs($ids)
    {
        $themes = self::with(['topicImg', 'headImg', 'products'])->select($ids);
        if (!$themes) {
            throw new ThemeMissException();
        }
        return $themes;
    }

    public static function getOneKindProductsByID($id)
    {
        $theme = self::with(['topicImg', 'headImg', 'products'])->find($id);
        if (!$theme) {
            throw new ThemeMissException();
        }
        return $theme;
    }
}