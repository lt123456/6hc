<?php

/*
 * author: 刘涛
 */
namespace Common\Common;

use Think\Controller;

class Nature extends Controller
{

    protected $animalArray;
    protected $sortAnimal;

    public function  __construct()
    {
        $this->animalArray = array('鼠', '牛', '虎', '兔', '龙', '蛇', '马', '羊', '猴', '鸡', '狗', '猪');
        $this->sortAnimal = array('鼠', '牛', '虎', '兔', '龙', '蛇', '马', '羊', '猴', '鸡', '狗', '猪');
    }

    /**
     * 获取当前生肖
     */
    public function  currentAnimal($year = '', $bool = '')
    {
        if (empty($year)) {
            $year = date('Y', time());
        }

        // 设施 初始年 为 2008 年;
        $startYear = '1936';

        $remainder = ($year - $startYear) % 12;

        if ($bool) {
            return $remainder;
        }
        return $this->animalArray[$remainder];
    }

    /**
     * 获取当前 号码生肖
     * @param string $year
     * @param string $code
     * @return mixed
     */
    public function codeToAnimal($year = '', $code = '')
    {
        $num = $this->currentAnimal($year, true);
        if ($num == 11) {
            $animalList = $this->sortAnimal;
        } else {
            foreach ($this->animalArray as $key => $val) {
                if ($key <= $num) {
                    $animalSmall[$num - $key] = $val;
                } else {
                    $animalBig[$num + (12 - $key)] = $val;
                }
            }
            ksort($animalSmall);
            ksort($animalBig);
            $animalList = array_merge($animalSmall, $animalBig);
        }
        $remainder = ($code % 12 == 0) ? 11 : ($code % 12) - 1;
        return $animalList[$remainder];
    }

    /**
     * @param $number
     * @return string
     */
    public function getBallColor($number)
    {
        $ceil = $number % 6;
        switch ($ceil) {
            case '1':
            case '2':
                $color = 'red';
                break;
            case '3':
            case '4':
                $color = 'blue';
                break;
            case '5':
            case '0':
                $color = 'green';
                break;
            default:
                $color = 'red';
        }
        return $color;

    }
}
