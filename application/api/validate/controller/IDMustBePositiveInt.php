<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/6
 * Time: 10:59
 */

namespace app\api\validate\controller;


class IDMustBePositiveInt extends BaseValidate
{
    // 可以从外部传入，也可以直接写好
    protected $rule = [
        'id' => 'require|isPositiveInteger'
    ];

    protected $message = [
        'id' => 'id必须是正整数'
    ];
}