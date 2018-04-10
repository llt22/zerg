<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/6
 * Time: 12:16
 */

namespace app\api\validate;

use app\lib\exception\ParameterException;
use think\Request;
use think\Validate;

class BaseValidate extends Validate
{
    public function goCheck()
    {
        $request = Request::instance();
        $params = $request->param();

        $isPass = $this->batch()->check($params);

        if (!$isPass) {
            throw new ParameterException([
                'msg' => $this->error
            ]);
        }
    }

    protected function isPositiveInteger($value, $rule = [], $data = [], $field = '')
    {
        if (is_numeric($value) && is_int($value + 0) && ($value + 0) > 0) {
            return true;
        } else {
            return false;
        }
    }

    protected function isNotEmpty($value)
    {
        if (empty($value)) {
            return false;
        }
        return true;
    }

    protected function isMobile($value)
    {
//        $rule = '^1(3|4|5|7|8)[0-9]\d{8}$^';
//        $result = preg_grep($rule,$value);
//        if ($result) {
//            return false;
//        }
        return true;
    }

    public function getDataByRule($array)
    {
        if (array_key_exists('user_id', $array) | array_key_exists('uid', $array)) {
            throw new ParameterException([
                'msg'=>'参数中包含非法参数名user_id'
            ]);
        }
        $newArray =[];
        // 当前单元的键名也会在每次循环中被赋给变量 $key
        foreach ( $this->rule as $key => $value) {
            $newArray[$key] = $array[$key];
        }
        return $newArray;

    }

}