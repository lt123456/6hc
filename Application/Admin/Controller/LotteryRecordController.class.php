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
         $map =array_filter(I());

        // 分页
        $count = $this ->lotteryModel->where($this->serach())->count();
        $Page  = new \Think\Page($count,2);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show  = $Page->show();
        $periodsRes =  $this ->lotteryModel
                                ->where($this->serach)
                                ->join('__ADMIN__ ON __LOTTERY_RECORD__.admin_id = __ADMIN__.id')
                                ->limit($Page->firstRow.','.$Page->listRows);
        if(isset($map['data'])){
             $periodsRes->order('lottery_time '.$map['data']);
        }
        $periodsRes = $periodsRes->select();
        $periods = $this->lotteryModel->field('id')->order('id desc')->select();

        $this->assign('periodsRes', $periodsRes);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->assign('map',$map);
        $this->assign('periods',$periods);
        $this->display();
    }
    public function  serach()
    {
        if(isset($map['periods'])){
           $where['periods'] = array('gt',$map['periods']);
        }
        if(isset($map['year'])){
            $where['lottery_time'] = array('like','%'.$map['periods'].'%');
        }
        if(isset($map['week'])){
            $where['week'] = array('gt',$map['week']);
        }
        if(isset($map['periods'])){
            $where['periods'] = array('gt',$map['periods']);
        }
        if(isset($map['singleDouble'])){
            $where['special_one_two'] = array('gt',$map['singleDouble']);
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

    }

    public function doEdit()
    {

    }

    public function  delete()
    {

    }
}