<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/7
 * Time: 18:39
 */

namespace app\api\controller\v1;


use app\api\validate\controller\IDCollection;

class Theme
{
    /*
     * @url /theme?ids=id1,id2,id3...
     * @return 一组 theme 模型
     */
    public function getSimpleList($ids)
    {
        (new IDCollection())->goCheck();
        return 'success';
    }
}