<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {


	protected  $lotteryRecord;
	public function __construct()
	{
		parent::__construct();
		$this->lotteryRecord = D('lottery_record');

	}

    public function index(){  

    	$lotteryRecords  = $this->lotteryRecord->limit(5)->select();

        $this->assign('lotteryRecord',$lotteryRecords);
        $this->display();
        $this->display();

    }
}