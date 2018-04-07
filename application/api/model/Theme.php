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

    public static function getThemeByID($ids)
    {
        $themes = self::with(['topicImg', 'headImg'])->select($ids);
        if (!$themes) {
            throw new ThemeMissException();
        }
        return $themes;
    }
}