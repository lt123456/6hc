<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller{


	protected  $lotteryRecord;
	public function __construct()
	{
		parent::__construct();
		$this->lotteryRecord = D('lottery_record');

	}

    /**
     * http://windows.php.net/downloads/pecl/releases/
     * https://github.com/phpredis/phpredis/downloads
     */
    public function index(){

        $currentRecord = S('currentRecord');

        if(empty($currentRecord)) {

            $currentRecord = $this->lotteryRecord->order('id desc')->limit(6)->select();
            S('currentRecord',$currentRecord,10);
        }
//        var_dump($currentRecord);die;
        $this->assign('currentRecord',$currentRecord);
        $this->display();

    }
}