<?php

namespace Home\Controller;

use  Common\Common\Nature;

class LeitaiController extends BaseController
{

    protected $nuture;
    protected $specialRecord;
    protected $animalRecord;
    public function  __construct()
    {
        parent::__construct();
        $this->nuture = new Nature();
        $this->specialRecord = D('special_record');
        $this->animalRecord = D('animal_record');
    }

    public function  index()
    {
        // 周期
        $periods  =  $this->lotteryRecord->field('id,periods')->order('id desc')->select();

        //
        $animals  = $this->animalRecord
                        ->join('__USERS__ ON __ANIMAL_RECORD__.user_id = __USERS__.id')
                        ->join('__LOTTERY_RECORD__ ON __ANIMAL_RECORD__.periods_id = __LOTTERY_RECORD__.id')
                        ->join('__SCORE__ ON __USERS__.id = __SCORE__.user_id')
                        ->field('6hc_animal_record.*,6hc_users.id as user_id,6hc_users.username,6hc_score.part_periods,6hc_score.reword_periods,6hc_score.all_ranking,6hc_lottery_record.periods as periods')
                        -> order('6hc_animal_record.id desc')->limit(50)->select();
        $res = $this->formatAnimals($animals,'1');

        // 下期开开奖
        $nextPeriods = $this->lotteryRecord->field('id,year,periods,lottery_time,status')->where(' status="wait"')->find();

//        var_dump($nextPeriods);
       // var_dump($res);
//        var_dump($periods);
//        die;
        $count = $this->animalRecord->count();
        $pages =  ceil($count / 50);
        $this->assign('nextPeriods',$nextPeriods);
        $this->assign('periods',$periods);
        $this->assign('animals',$res);
        $this->assign('pages',$pages);
        $this->display();
    }



    public function  tema()
    {
        $this->display();
    }

    public function historydata()
    {
        $data = I();

        if($data['type'] =='1') {
            $res  = $this->specialRecord->join('__USERS__ ON __SPECIAL_RECORD__.user_id = __USERS__.id')
                ->join('__LOTTERY_RECORD__ ON __SPECIAL_RECORD__.periods_id = __SPECIAL_RECORD__.id')
                ->join('__SCORE__ ON __USERS__.id = __SCORE__.user_id')
                ->field('6hc_special_record.*,6hc_users.id as user_id,6hc_users.username,6hc_score.part_periods,6hc_score.reword_periods,6hc_score.all_ranking,6hc_lottery_record.periods as periods');
        }else {
            $res  = $this->animalRecord->join('__USERS__ ON __ANIMAL_RECORD__.user_id = __USERS__.id')
                ->join('__LOTTERY_RECORD__ ON __ANIMAL_RECORD__.periods_id = __LOTTERY_RECORD__.id')
                ->join('__SCORE__ ON __USERS__.id = __SCORE__.user_id')
                ->field('6hc_animal_record.*,6hc_users.id as user_id,6hc_users.username,6hc_score.part_periods,6hc_score.reword_periods,6hc_score.all_ranking,6hc_lottery_record.periods as periods');
        }

        if(!empty($data['period'])){
            $where['periods_id'] = $data['period'];
        }
        if( $data['result'] != -1) {
            switch($data['result']){
                case '0':
                    $status ='wait';
                    break;
                case '1':
                    $status = 'prize';
                    break;
                case '2':
                    $status = 'no-prize';
            }
            if($data['type'] =='1'){
                $where['6hc_special_record.status'] = $status;
            }else{
                $where['6hc_animal_record.status'] = $status;
            }

        }
//        var_dump($where);
        if($data['isCurPeriod'] =='true') {
             $id = $this->lotteryRecord->order('id desc')->limit(1)->getField('id');

            $res = $res ->where(array('periods_id'=> $id));

        }elseif($data['isMind'] =='true'){
            session('user_id',1);
            $res = $res ->where(array("user_id",session('user_id')));
        }elseif($data['memberName']) {
            $whereList['username'] = array('like', '%' . $data['memberName'] . '%');
            $res = $res->where($whereList);
        }elseif(!empty(array_filter($where))){
            $res = $res->where($where);
        }

        $page = empty($data['page']) ? '1' : $data['page'];

        $start  = ($page-1)*50;

        $resultArr = $res->limit($start,50)->order('id desc')->select();

        $lists['page'] = intval($page);
        $lists['recordcount'] = $res->count();

        $lists['pagecount'] = ceil($lists['recordcount']/50);


        $lists['datas'] = $this->formatAnimals($resultArr,$data['type']);

        $this->ajaxReturn($lists);
    }
    public function periodbet(){
        $data = I();
        // 特码推荐
        session('user_id',1);
        $fillable= $this->commValue($data);

        if($data['type'] == 1) {
            $res =   $this->specialRecord->add($fillable);
            if($res){
                $this->ajaxReturn(array('success'=>'1'));
            }else{
                $this->ajaxReturn(array('success'=>'0'));
            }
        }elseif($data['type'] ==2) {
            $res= $this->animalRecord->add($fillable);

            if($res){
                $this->ajaxReturn(array('success'=>'1'));

            }else{
                $this->ajaxReturn(array('success'=>'0'));

            }
        }else{
            $this->ajaxReturn(array('success'=>'0'));
        }
    }

    /**
     * @param $data
     * @return mixed
     */
    public function  commValue($data){
        $fillable['user_id']    = session('user_id');
        $fillable['periods_id'] = $data['period'];
        $fillable['week'] = date('W');
        $fillable['recomm_sum'] = count(array_filter(explode(',',$data['target'])));
        $fillable['ip'] = $_SERVER['REMOTE_ADDR'];
        $fillable['status'] = 'wait';
        $fillable['created_at'] =  date('Y-m-d H:i:s',time());
        if($data['type'] ==1){
            $fillable['recomm_special'] = $data['target'];
        }else{
            $fillable['recomm_animals'] = $data['target'];
        }

        return $fillable;
    }

    /**
     * @param $status
     * @return string
     */
    public function tranStatusString($status) {
        switch($status) {
            case 'wait' :
               $res = '待开奖';
                break;
            case 'prize':
                $res =  '已中奖';
                break;
            case 'no-prize':
                $res = '未中奖';
                break;
        }
        return $res;
    }

    /**
     * @param $status
     * @return int
     */
    public function tranStatusInt($status) {
        switch($status) {
            case 'wait' :
                $res = 0;
                break;
            case 'prize':
                $res = 1;
                break;
            case 'no-prize':
                $res = 2;
                break;
            default :
                $res =0;
        }
        return $res;
    }

    /**
     * @param $animals
     * @return array
     */
    public  function formatAnimals($animals,$type=2) {
        $result = [];
        foreach ($animals as $key=>$val) {
            $result[$key]['BetDate'] = date('Y-m-d',strtotime($animals[$key]['created_at']));
            $result[$key]['BetTarget'] =  ($type=='2') ? $animals[$key]['recomm_animals'] : $animals[$key]['recomm_special'];
            $result[$key]['MemberId'] = $animals[$key]['user_id'];
            $result[$key]['BetTime'] = date('H:i',strtotime($animals[$key]['created_at']));
            $result[$key]['MemberName'] = $animals[$key]['username'];
            $result[$key]['Period'] = $animals[$key]['periods'];
            $result[$key]['Result'] = $this->tranStatusInt($animals[$key]['status']);
            $result[$key]['ResultText'] = $this->tranStatusString($animals[$key]['status']) ;
            $result[$key]['QuarterBetCount'] = $animals[$key]['part_periods'];
            $result[$key]['QuarterRanking'] = $animals[$key]['all_ranking'];
            $result[$key]['QuarterWinCount'] = $animals[$key]['reword_periods'];
            $result[$key]['WinRateOfYear'] = number_format(($animals[$key]['reword_periods']/ $animals[$key]['part_periods'])*100,2).'%';
        }
        return $result;


    }

}