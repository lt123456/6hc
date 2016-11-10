<?php

/*
 * author: 刘涛
 */
namespace Common\Common;

use Think\Controller;

class Verify extends Controller
{
    /**
     * 生成验证码
     */
    static public function  verify()
    {

        $config = array(
            'fontSize' => 25,    // 验证码字体大小
            'length' => 2,     // 验证码位数
            'useNoise' => false, // 关闭验证码杂点
            'codeSet' => '1234567890',
        );

        $Verify = new \Think\Verify($config);

        return $Verify->entry();

    }
}


?>