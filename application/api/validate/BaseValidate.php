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

}