<?php
namespace Home\Controller;

//use Think\Controller;

class IndexController extends BaseController
{


    protected  $zhutie;
    protected  $animal_record;
    protected  $special_record;
    public function __construct()
    {
        parent::__construct();
        $this->zhutie = D('discuss_zhutie');
        $this->animal_record = D('animal_record');
        $this->special_record = D('special_record');
    }

    /**
     * http://laravelacademy.org/post/3184.html
     * http://windows.php.net/downloads/pecl/releases/
     * https://github.com/phpredis/phpredis/downloads
     */
    public function index()
    {

        //论坛
        $discuzRecomm =  S('discuzRecomm');

        if(empty($discuzRecomm)) {
            $discuzRecomm  =  $this->zhutie->where(['display'=>'1'])
                                ->order('id desc')->field('id,title')
                                ->limit(9)->select();
            S('discuzRecomm',$discuzRecomm,C('CACHE_TIME')['middle']);
        }

        //生肖
        $animal_record =  S('animal_record');
        if(empty($animal_record)) {
            $animal_record  =  $this->animal_record
                                    ->join('__USERS__ ON __ANIMAL_RECORD__.user_id = __USERS__.id')
                                    ->field('6hc_users.id,username,recomm_sum,periods_id')
                                    ->limit(33)
                                    ->order('id desc')
                                    ->select();
            S('animal_record',$animal_record,C('CACHE_TIME')['middle']);
        }

        // 特码
        $special_record = S('special_record');
        if(empty($special_record)) {
            $special_record  =  $this->special_record
                ->join('__USERS__ ON __SPECIAL_RECORD__.user_id = __USERS__.id')
                ->field('6hc_users.id,username,recomm_sum,periods_id')
                ->limit(33)
                ->order('id desc')
                ->select();
            S('special_record',$special_record,C('CACHE_TIME')['middle']);
        }

        //周榜
        $weekAnimals  = S('weekAnimals');

        if(empty($weekAnimals)) {
            $weekAnimals = $this->special_record
                ->where(['week' => date('W')-1,'6hc_special_record.status'=>'prize'])
                ->join('__USERS__ ON __SPECIAL_RECORD__.user_id = __USERS__.id')
                ->group('user_id')
                ->order('count(user_id) desc')
                ->field('count(user_id) as sum,6hc_users.id,username')
                ->select();
            S('weekAnimals',$weekAnimals,C('CACHE_TIME')['base']);
        }

        // 周榜总数
        $weekAnimalsSum = S('weekAnimalsSum');
        if(empty($weekAnimalsSum)) {
            $weekAnimalsSum = $this->special_record->where(['week' => date('W')-1])->count();
            S('weekAnimalsSum',$weekAnimalsSum,C('CACHE_TIME')['base']);
        }


        $this->assign('discuzRecomm',$discuzRecomm);
        $this->assign('animal_record',$animal_record);
        $this->assign('special_record',$special_record);
        $this->assign('weekAnimals',$weekAnimals);
        $this->assign('weekAnimalsSum',$weekAnimalsSum);
        $this->display();

    }
}