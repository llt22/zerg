<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/7
 * Time: 18:42
 */

namespace app\api\model;


class Product extends BaseModel
{
    protected $hidden = ['update_time', 'delete_time', 'create_time', 'pivot', 'from'];
    public function getMainImgUrlAttr($value, $data)
    {
        return $this->addPrefixForImage($value, $data);
    }
}