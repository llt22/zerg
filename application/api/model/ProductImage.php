<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/9
 * Time: 19:45
 */

namespace app\api\model;


class ProductImage extends BaseModel
{
    public function image()
    {
        return $this->belongsTo('Image', 'img_id', 'id');
    }
}