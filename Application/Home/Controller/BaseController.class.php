<?php
namespace Home\Controller;

use Think\Controller;

class BaseController extends Controller
{


    protected $lotteryRecord;

    public function __construct()
    {
        parent::__construct();
        $this->lotteryRecord = D('lottery_record');
        $currentRecord = '';
        if (empty($currentRecord)) {
            $currentRecord = $this->lotteryRecord->order('id desc')->limit(6)->select();
            S('currentRecord', $currentRecord, C('CACHE_TIME')['smart']);
        }
        $this->assign('currentRecord', $currentRecord);
    }

}