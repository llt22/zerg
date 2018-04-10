<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/8
 * Time: 17:55
 */

namespace app\api\model;


use app\lib\exception\NoCategoryException;

class Category extends BaseModel
{
    protected $hidden = ['update_time', 'delete_time', 'topic_img_id'];

    public function topicImg()
    {
        return $this->belongsTo('Image', 'topic_img_id', 'id');
    }

//    public function products()
//    {
//        return $this->hasMany('product', 'category_id', 'id');
//    }

    public static function getCategory(){
        $category = self::with('topicImg')->all();
        if($category->isEmpty()){
            throw new NoCategoryException();
        }
        return $category;
    }
}