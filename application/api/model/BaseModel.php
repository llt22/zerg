<?php

namespace app\api\model;

use think\Model;

class BaseModel extends Model
{
    // 为了灵活控制，将这个方法变为普通方法
    protected function addPrefixForImage($value, $data)
    {
        if ($data['from'] == 1) {
            return config('setting.img_prefix') . $value;
        } else {
            return $value;
        }
    }
}
