<?php
/**
 * 开奖平台管理
 */
namespace Admin\Controller;

use Common\Common\Nature;

class LotteryRecordController extends BaseController {

    protected  $natureHelper;

    protected  $lotteryModel;

    public function __construct()
    {
        parent::__construct();
        $this->natureHelper = new Nature();
        $this->lotteryModel = D('lottery_record');
    }


    public function index()
    {
         $map =I();
        // 分页
        $count = $this ->lotteryModel->where($this->serach($map))->count();
        $Page  = new \Think\Page($count,2);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        if($this->serach($map)){

            foreach($this->serach($map) as $key=>$val) {
                $Page->parameter[$key]   =   urlencode($val);
            }
        }
        $show  = $Page->show();
        $periodsRes =  $this ->lotteryModel
                                ->where($this->serach($map))
                                ->join('__ADMIN__ ON __LOTTERY_RECORD__.admin_id = __ADMIN__.id')
                                ->limit($Page->firstRow.','.$Page->listRows)
                                ->field('6hc_admin.id,6hc_admin.username,6hc_lottery_record.*');
        if(isset($map['data'])){
             $periodsRes->order('lottery_time '.$map['data']);
        }else{
            $periodsRes->order('created_at  desc');
        }
        $periodsRes = $periodsRes->select();

        $periods = $this->lotteryModel->field('id')->order('id desc')->select();

        $this->assign('periodsRes', $periodsRes);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->assign('map',$map);
        $this->assign('periods',$periods);
        $this->display();
    }
    public function  serach($map)
    {

        if(isset($map['periods'])){
           $where['periods'] = array('eq',$map['periods']);
        }
        if(isset($map['year'])){
            $where['lottery_time'] = array('like','%'.$map['year'].'%');
        }
        if(isset($map['week'])){
            $where['week'] = array('eq',$map['week']);
        }
        if(isset($map['periods'])){
            $where['periods'] = array('eq',$map['periods']);
        }
        if(isset($map['singleDouble'])){
            $where['special_one_two'] = array('eq',$map['singleDouble']);
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

        $obj =    $this->lotteryModel->find(I('get.id'));

        if($obj) {
            $this->assign('obj',$obj);
            $this->display();
        }else{
            $this->error('该期开奖不存在','/Admin/Agent',3);
        }
    }

    public function doEdit()
    {
        if(IS_POST) {
            $data = I();
            // 处理数据
            $filleable =  $this->filterDate(I());
            // 保存数据库
            $res = $this->lotteryModel->save($filleable);
            if($res){
                $this->ajaxReturn(['status'=>'ok']);
            }else{
                $this->ajaxReturn(['status'=>'error']);
            }
        }

    }

    public function  filterDate($date)
    {
        $fillable = array();
        $animals =  explode(',',$date['lottery_animals']);
        $animals = array_unique(array_filter($animals));
        if(is_array($animals) && count($animals)==6){
            foreach($animals as $key=>$val){
                $num = intval($val);
                if(is_numeric($val) && $num>0 && $num<50 ) {
                    $animals[$key] = $num;
                } else{
                    $this->ajaxReturn(array('status'=>'warning','message'=>'请按要求输入正码'));
                }
            }
        }else{
            $this->ajaxReturn(array('status'=>'warning','message'=>'请按要求输入正码'));
        }

        $fillable['periods']    = $date['periods'];
        $fillable['id']    = $date['id'];
        $fillable['admin_id']   = session('admin_id');
        $fillable['just_collect']  = $animals[0]+$animals[1]+$animals[2]+$animals[3]+$animals[4]+$animals[5];
        $fillable['special_number']    = intval($date['special_number']);
        $fillable['special_one_two']    = $fillable['special_number']%2;
        $fillable['lottery_time']    = $date['lottery_time'];
        $fillable['update_at']    =  date('Y-m-d h:i:s',time());
        $fillable['just_one']   = $animals[0];
        $fillable['just_two']   = $animals[1];
        $fillable['just_three'] = $animals[2];
        $fillable['just_four']  = $animals[3];
        $fillable['just_five']  = $animals[4];
        $fillable['just_six']   = $animals[5];
        $fillable['just_string']   = $date['lottery_animals'];

        return $fillable;
    }

    public function  delete()
    {
        $data = I();
        $res = $this->lotteryModel->where('id='.$data['id'])->delete();

        if($res) {
            $this->ajaxReturn(['status'=>'ok']);
        }else{
            $this->ajaxReturn(['status'=>'error']);
        }
    }

    public function publish()
    {
        $data = I();
        $res = $this->lotteryModel->where('id='.$data['id'])->find();
        if($res){
            $data['status'] = 'active';
            $data['id'] = $res['id'];
             $updateRes = $this->lotteryModel->save($data);

            $fillable['perious'] = $res['periods']+1;
            $fillable['admin_id'] = session('admin_id');
            $fillable['status'] = 'wait';
            $fillable['created_at'] = date('Y-m-d H:i:s',time());
            $fillable['week'] = date('W',time());
            $addRes =  $this->lotteryModel->add($fillable);

            if($updateRes && $addRes){
                $this->ajaxReturn(array('status'=>'ok'));
            }else{
                $this->ajaxReturn(array('status'=>'error'));
            }

        }else{
            $this->ajaxReturn(array('status'=>'error'));
        }
    }

}