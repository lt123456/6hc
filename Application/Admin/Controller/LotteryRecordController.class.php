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
        $count = $this ->lotteryModel->where($this->serach($map))->count();
        $Page  = new \Think\Page($count,2);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        if($this->serach($map)){

            foreach($this->serach($map) as $key=>$val) {
                $Page->parameter[$key]   =   urlencode($val);
            }
        }
        var_dump($this->serach($map));
        $show  = $Page->show();
        $periodsRes =  $this ->lotteryModel
                                ->where($this->serach($map))
                                ->join('__ADMIN__ ON __LOTTERY_RECORD__.admin_id = __ADMIN__.id')
                                ->limit($Page->firstRow.','.$Page->listRows)
                                ->field('6hc_admin.id,6hc_admin.username,6hc_lottery_record.*');
        if(isset($map['data'])){
             $periodsRes->order('lottery_time '.$map['data']);
        }
        $periodsRes = $periodsRes->select();
//        var_dump($periodsRes);
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

    }

    public function doEdit()
    {

    }

    public function  delete()
    {

    }
}