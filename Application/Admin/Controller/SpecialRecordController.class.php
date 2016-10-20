<?php
/**
 * 用户 推荐 特码管理
 */

namespace Admin\Controller;

class  SpecialRecordController extends BaseController {

    protected  $scoreModel;
    protected  $specialModel;
    protected  $lotteryModel;
    public function __construct()
    {
        parent::__construct();
        $this->scoreModel= D('score');
        $this->specialModel= D('special_record');
        $this->lotteryModel= D('lottery_record');

    }
    public function index()
    {
        $map =I();

        // 期数
        $periods = $this->lotteryModel->field('id,periods')->order('id desc')->select();
        // 分页
        $count = $this ->specialModel
            ->join('__USERS__ ON __SPECIAL_RECORD__.user_id = __USERS__.id')
            ->where($this->serach($map))
            ->count();
        $Page  = new \Think\Page($count,C('PAGE'));// 实例化分页类 传入总记录数和每页显示的记录数(25)
        if($this->serach($map)){

            foreach($this->serach($map) as $key=>$val) {
                $Page->parameter[$key]   =   urlencode($val);
            }
        }
        $show  = $Page->show();
        $specialRes =  $this ->specialModel
            ->where($this->serach($map))
            ->join('__USERS__ ON __SPECIAL_RECORD__.user_id = __USERS__.id')
            ->join('__SCORE__ ON __SPECIAL_RECORD__.user_id = __SCORE__.id')
            ->join('__LOTTERY_RECORD__ ON __SPECIAL_RECORD__.periods_id = __LOTTERY_RECORD__.id')
            ->limit($Page->firstRow.','.$Page->listRows)
            ->field('6hc_users.id,6hc_users.username,6hc_score.part_periods,6hc_score.reword_periods,6hc_lottery_record.periods,6hc_special_record.*')
            ->order('created_at desc');
        $specialRes = $specialRes->select();
        $this->assign('specialRes', $specialRes);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->assign('map',$map);
        $this->assign('periods',$periods);
        $this->display();
    }

    public function  serach($map)
    {

        if(!empty($map['periods'])){
            $where['periods_id'] = array('eq',$map['periods']);
        }
        if(!empty($map['status'])){
            $where['6hc_special_record.status'] = array('eq',$map['status']);
        }
        if(!empty($map['username'])){
            $where['6hc_users.username'] = array('like','%'.$map['username'].'%');
        }

        return $where;
    }

    public function  add()
    {

    }

    public function  doadd()
    {

    }

    public function  disabled()
    {

    }

    public function edit()
    {
        $obj = $this->specialModel->find(I('get.id'));

        if($obj) {

            $obj = $this->specialModel
                ->where("6hc_special_record.id =".I('get.id'))
                ->join('__USERS__ ON __SPECIAL_RECORD__.user_id = __USERS__.id')
                ->join('__LOTTERY_RECORD__ ON __SPECIAL_RECORD__.periods_id = __LOTTERY_RECORD__.id')
                ->field('6hc_special_record.id,6hc_special_record.recomm_special,6hc_lottery_record.periods,6hc_users.username')
                ->find();
            $this->assign('obj',$obj);
            $this->display();
        }else{
            $this->error('该用户生肖推荐不存在','/Admin/Power/group',3);
        }

    }

    public function doEdit()
    {
        if(IS_POST) {
            $data = I();
            $data = $this->vaildAnimalField($data);
            $res = $this->specialModel->save($data);
            if($res){
                $this->ajaxReturn(array('status'=>'ok'));
            }else{
                $this->ajaxReturn(array('status'=>'error'));
            }
        }

    }

    public function vaildAnimalField($data)
    {
        $specials = array_unique($data['specials']);
        if( count( $specials)<0 && count($specials)>7 ) {
            $this->ajaxReturn(array('status'=>'error','message'=>'请正确选择1-6个号码'));
        }
        $data['recomm_special'] = implode(',',$specials);
        $data['recomm_sum'] = count($specials);

        return $data;
    }

    public function  delete()
    {
        $data = I();

        $this->specialModel->startTrans();
        $animalRes = $this->specialModel->where('id='.$data['id'])->find();

        if($animalRes){

            $res = $this->specialModel->where('id='.$data['id'])->delete();

            $user_id= $animalRes['user_id'];

            $score = $this->scoreModel->where(array('user_id'=>$user_id))->find();

            $fillable['part_periods'] = $score['part_periods'] -1;
            $fillable['id'] = $score['id'];
            if($animalRes['status'] =='prize') {
                $fillable['reword_periods'] = $score['reword_periods'] -1;
            }
            $col = $this->scoreModel->save($fillable);
            if($res && $col ) {
                $this->specialModel->commit();
                $this->ajaxReturn(array('status'=>'ok'));
            }else{
                $this->specialModel->rollback();
                $this->ajaxReturn(array('status'=>'error','message'=>'服务器错误请稍后再试'));
            }
        }else{
            $this->ajaxReturn(array('status'=>'error','message'=>'生肖推荐不存在!'));
        }

    }
}