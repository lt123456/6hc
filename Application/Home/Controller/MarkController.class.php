<?php
namespace Home\Controller;

use  Common\Common\Nature;

class MarkController extends BaseController
{

    protected  $nuture;
    public function  __construct()
    {
        parent::__construct();
        $this->nuture = new Nature();
    }

    public function  index()
    {
        $data = I();
        $history_year =  $this->lotteryRecord->group("year")->order('year desc')->field('year')->select();

        $where =  empty($data['year']) ? date('Y') : $data['year'];
        $type  =  !isset($data['type']) ? 1 : $data['type'];
        $history_records = S(md5($where));
        if(empty($record)){
            $history_records  = $this->lotteryRecord->where(array('year'=>$where,'status'=>'active'))
                ->field('id,periods,just_string,animal_string,five_string,special_five,special_animal,special_number,special_one_two,created_at')
                ->order('id desc')->select();

            S($where,$history_records,C('CACHE_TIME')['base']);
        }

        $url = md5($_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]);
        $records  =S($url);
        if(empty($records)) {
            foreach($history_records as $key=>$val) {

                $records[$key]['periods']  = $history_records[$key]['periods'];
                $records[$key]['created_at']  = $history_records[$key]['created_at'];
                $records[$key]['just_string']  = $history_records[$key]['just_string'];
                $records[$key]['just_type'] = $this->nutureJustType($history_records[$key],$type);
                $records[$key]['special_number']  = $history_records[$key]['special_number'];
                $records[$key]['special_type']  = $this->nutureSpecialType($history_records[$key],$type);
                $records[$key]['one_two']  =  ( $history_records[$key]['special_number'] % 2) ? '单' : '双';
                $records[$key]['big_small']  =  ( $history_records[$key]['special_number'] > 24) ? '大' : '小';
                $records[$key]['sum']  = $this->sum($history_records[$key]['just_string'], $history_records[$key]['special_number']);
                $records[$key]['sum_one_two']  =  ( $records[$key]['sum'] %2 ) ? '单' : '双';
                $records[$key]['sum_big_small']  =  ( $records[$key]['sum'] > 174) ? '大' : '小';

            }
            S($url,$records,C('CACHE_TIME')['base']);
        }


        $this->lotteryRecord->getLastSql();
        $this->assign('history_year',$history_year);
        $this->assign('records',$records);
        $this->display();

    }

    /**
     * @param $just
     * @param $special
     * @return int
     */
    public function sum($just,$special) {
        $justArr = array_filter(explode(',',$just));
        $sum=0;
        foreach ($justArr as $key=>$val) {
            $sum += intval($val);
        }
        $sum = $sum+$special;

        return $sum;
    }

    /***
     *
     * @param $res
     * @param $type
     * @return string
     */
    public function  nutureJustType($res,$type) {

            $result = $res['animal_string'];
            $animalArr  = array_filter(explode(',',$result));
            switch($type) {
                case '1':
                    $result = $res['animal_string'];
                    break;
                case '2':
                    $result = $res['five_string'];
                    break;
                case '3':
                    $result = $this->nuture->getPoultryBeast($animalArr);
                    break;
                case '4':
                    $result = $this->nuture->getMaleFemale($animalArr);
                    break;
                case '5':
                    $result = $this->nuture->getHeavenEarth($animalArr);
                    break;
                case '6':
                    $result = $this->nuture->getSeasons($animalArr);
                    break;
                case '7':
                    $result = $this->nuture->getArt($animalArr);
                    break;
                case '8':
                    $result = $this->nuture->getThreeColor($animalArr);
                    break;

            }
        return $result;
    }

    public function  nutureSpecialType($res,$type) {

        $result =$animalArr  = $res['special_animal'];

        switch($type) {
            case '1':
                $result = $res['special_animal'];
                break;

            case '2':
                $result = $res['special_five'];
                break;
            case '3':
                $result = $this->nuture->getPoultryBeast($animalArr);
                break;
            case '4':
                $result = $this->nuture->getMaleFemale($animalArr);
                break;
            case '5':
                $result = $this->nuture->getHeavenEarth($animalArr);
                break;
            case '6':
                $result = $this->nuture->getSeasons($animalArr);
                break;
            case '7':
                $result = $this->nuture->getArt($animalArr);
                break;
            case '8':
                $result = $this->nuture->getThreeColor($animalArr);
                break;

        }
        return $result;
    }


}