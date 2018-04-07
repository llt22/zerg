<?php

namespace app\api\model;

use think\Model;

class Banner extends BaseModel
{

    protected $hidden = ['update_time', 'delete_time'];

    public function items()
    {
        return $this->hasMany('banner_item', 'banner_id', 'id');
    }

    public static function getBannerByID($id)
    {
        // 后面是条件，找到 id 是1 banner,以及关联的数据，返回的数据本身就是json
        $banner = self::with(['items', 'items.img'])->find($id);
        return $banner;
    }
}