<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/11
 * Time: 15:37
 */

namespace app\api\validate;


use app\lib\exception\ParameterException;

class OrderPlace extends BaseValidate
{
    protected $rule = [
        'products' => 'checkProducts',
    ];

    protected $singleRule = [
        'product_id' => 'require|isPositiveInteger',
        'count' => 'require|isPositiveInteger',
    ];

    protected function checkProduct($v)
    {
        $validate = new BaseValidate($this->singleRule);
        $result = $validate->check($v);
        if(!$result){
            throw new ParameterException([
                'msg' => '商品参数错误'
            ]);
        }
    }

    protected function checkProducts($values)
    {
        if (!is_array($values)) {
            throw new ParameterException([
                'msg' => '参数类型错误'
            ]);
        }

        if (empty($values)) {
            throw new ParameterException([
                'msg' => '商品列表不能为空'
            ]);
        }

        foreach ($values as $v) {
            $this->checkProduct($v);
        }
        return true;
    }
}