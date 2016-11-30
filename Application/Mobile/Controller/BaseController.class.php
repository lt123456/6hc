<?php
namespace Mobile\Controller;

use Think\Controller;

class BaseController extends Controller
{


    protected $lotteryRecord;

    public function __construct()
    {
        parent::__construct();
        $this->lotteryRecord = D('lottery_record');
        $this->friend = D('friend');
        $currentRecord = S('currentRecord');
        if (empty($currentRecord)) {
            $currentRecord = $this->lotteryRecord->order('id desc')->limit(6)->select();
            S('currentRecord', $currentRecord, C('CACHE_TIME')['smart']);
        }
//        var_dump($currentRecord);die;
        $friends = S('friends');
        if(empty($friends)) {
            $friends = $this->friend->where(['is_display'=>'1'])->field('titel','url')->select();
            S('friends',$friends,C('CACHE_TIME')['base']);
        }

        $this->assign('currentRecord', $currentRecord);
        $this->assign('friends', $friends);
    }

}