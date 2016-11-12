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
    protected $poultry;
    protected $beast;
    protected $male;
    protected $female;
    protected $heaven;
    protected $earth;

//    protected  $seasons;

    public function  __construct()
    {
        $this->animalArray = array('鼠', '牛', '虎', '兔', '龙', '蛇', '马', '羊', '猴', '鸡', '狗', '猪');
        $this->sortAnimal = array('鼠', '牛', '虎', '兔', '龙', '蛇', '马', '羊', '猴', '鸡', '狗', '猪');
        $this->poultry = array('牛','马','羊','鸡','狗','猪');
        $this->beast   = array('鼠','虎','兔','龙','蛇','猴');
        $this->male  = array('鼠','牛','虎','龙','马','猴','狗');
        $this->female  = array('兔','蛇','羊','鸡','猪');
        $this->heaven = array('牛','兔','龙','马','猴','猪');
        $this->earth = array('鼠','虎','蛇','羊','鸡','狗');


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

    /** 判断动物是否为家禽
     * @param $animals
     * @return string
     */
    public function getPoultryBeast ($animals) {
        if(is_array($animals)){

            foreach ($animals as $key=>$val) {

                if(in_array($val,$this->poultry)){
                    $result[$key] = '家';
                }else{
                    $result[$key] = '野';
                }
            }
            $result = implode(',',$result);
            return $result;
        }else{
            if(in_array($animals,$this->poultry)){
                $result= '家';
            }else{
                $result = '野';
                }
            return $result;
        }

    }

    /** 男女生肖
     * @param $animals
     * @return string
     */
    public function getMaleFemale ($animals) {
        if(is_array($animals)){

            foreach ($animals as $key=>$val) {

                if(in_array($val,$this->male)){
                    $result[$key] = '男';
                }else{
                    $result[$key] = '女';
                }
            }
            $result = implode(',',$result);
            return $result;
        }else{
            if(in_array($animals,$this->poultry)){
                $result= '男';
            }else{
                $result = '女';
            }
            return $result;
        }

    }

    /**
     * 天地生肖
     * @param $animals
     * @return string
     */
    public function getHeavenEarth ($animals) {
        if(is_array($animals)){

            foreach ($animals as $key=>$val) {

                if(in_array($val,$this->heaven)){
                    $result[$key] = '天';
                }else{
                    $result[$key] = '地';
                }
            }
            $result = implode(',',$result);
            return $result;
        }else{
            if(in_array($animals,$this->poultry)){
                $result= '天';
            }else{
                $result = '地';
            }
            return $result;
        }

    }

    /** 四季生肖
     * @param $animals
     * @return string
     */
    public function getSeasons ($animals) {
        if(is_array($animals)){

            foreach ($animals as $key=>$val) {
                $result[$key] = $this->setSeasons($val);
            }
            $result = implode(',',$result);
            return $result;
        }else{
            $result =  $this->setSeasons($animals);
            return $result;
        }

    }

    /** 设置 四季生肖
     * @param $animals
     * @return string
     */
    public function  setSeasons($animals){
        if(in_array($animals,array('虎','兔','龙'))){
            $result = '春';
        }elseif(in_array($animals,array('蛇','马','羊'))){
            $result= '夏';
        }elseif(in_array($animals,array('猴','鸡','狗'))){
            $result = '秋';
        }elseif(in_array($animals,array('鼠','牛','猪'))) {
            $result = '冬';
        }else{
            $result = '-';
        }
        return $result;
    }

    /** 倾其书画生肖
     * @param $animals
     * @return string
     */
    public function getArt ($animals) {
        if(is_array($animals)){

            foreach ($animals as $key=>$val) {
                $result[$key] = $this->setArt($val);
            }
            $result = implode(',',$result);
            return $result;
        }else{
            $result =  $this->setArt($animals);
            return $result;
        }

    }

    /** 设置 倾其书画生肖生肖
     * @param $animals
     * @return string
     */
    public function  setArt($animals){
        if(in_array($animals,array('兔','蛇','鸡'))){
            $result = '琴';
        }elseif(in_array($animals,array('鼠','牛','狗'))){
            $result= '棋';
        }elseif(in_array($animals,array('虎','龙','马'))){
            $result = '书';
        }elseif(in_array($animals,array('羊','猴','猪'))) {
            $result = '画';
        }else{
            $result = '-';
        }
        return $result;
    }

    /** 三色生肖
     * @param $animals
     * @return string
     */
    public function getThreeColor ($animals) {
        if(is_array($animals)){

            foreach ($animals as $key=>$val) {
                $result[$key] = $this->setThreeeColor($val);
            }
            $result = implode(',',$result);
            return $result;
        }else{
            $result =  $this->setThreeeColor($animals);
            return $result;
        }

    }

    /** 设置 三色生肖
     * @param $animals
     * @return string
     */
    public function  setThreeeColor($animals){
        if(in_array($animals,array('鼠','兔','马','鸡'))){
            $result = '红';
        }elseif(in_array($animals,array('虎','蛇','猴','猪'))){
            $result = '蓝';
        }elseif(in_array($animals,array('牛','龙','羊','狗'))){
            $result = '绿';
        }else{
            $result = '-';
        }
        return $result;
    }
}
