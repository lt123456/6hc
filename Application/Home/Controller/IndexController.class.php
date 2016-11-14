<?php
namespace Home\Controller;

//use Think\Controller;

class IndexController extends BaseController
{


    protected  $zhutie;
    protected  $animal_record;
    protected  $special_record;
    protected  $paper_list;
    protected  $discuss_zhutie;
    protected  $joke;
    public function __construct()
    {
        parent::__construct();
        $this->zhutie = D('discuss_zhutie');
        $this->animal_record = D('animal_record');
        $this->special_record = D('special_record');
        $this->paper_list  = D('paper_list');
        $this->discuss_zhutie = D('discuss_zhutie');
        $this->joke = D('joke');
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
                                    ->join('__LOTTERY_RECORD__ ON __ANIMAL_RECORD__.periods_id = __LOTTERY_RECORD__.id')
                                    ->field('6hc_users.id,username,recomm_sum,periods_id,6hc_lottery_record.periods as periods_id')
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
                ->join('__LOTTERY_RECORD__ ON __SPECIAL_RECORD__.periods_id = __LOTTERY_RECORD__.id')
                ->field('6hc_users.id,username,recomm_sum,periods_id,6hc_lottery_record.periods as periods_id')
                ->limit(33)
                ->order('id desc')
                ->select();
            S('special_record',$special_record,C('CACHE_TIME')['middle']);
        }

        //周榜
        $weekAnimals  = '';

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

        $weekAnimals  = '';

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
        $weekAnimalsSum ='';
        if(empty($weekAnimalsSum)) {
            $weekAnimalsSum = $this->lotteryRecord->where(['week' => date('W')-1])->count();
            $weekAnimalsSum = empty($weekAnimalsSum) ? '3' : $weekAnimalsSum;
            S('weekAnimalsSum',$weekAnimalsSum,C('CACHE_TIME')['base']);
        }


        // 当前 最新的
        $newLotteryRecord = $this->lotteryRecord->order('id desc')->getField('id');
        // 当前图纸
        $parperList =   $this->paper_list
                             ->join('__PAPER_VIEW__ on __PAPER_LIST__.id = __PAPER_VIEW__.pic_name_id')
                             ->join('__LOTTERY_RECORD__ on __PAPER_VIEW__.periods_id = __LOTTERY_RECORD__.id')
                             ->where('6hc_paper_view.periods_id',$newLotteryRecord)
                             ->field('6hc_paper_list.*,6hc_paper_view.id as v_id,6hc_paper_view.cut_pic,6hc_lottery_record.periods')
                             ->limit(15)
                             ->select();

        // 专家推荐帖子
        $expertDiscuss  = $this->discuss_zhutie
                               ->join('__USERS__ ON __DISCUSS_ZHUTIE__.user_id = __USERS__.id')
                               ->where(array('is_expert'=>'1','is_display'=>'1'))
                                ->field('6hc_discuss_zhutie.id,title,username,6hc_users.id as u_id,6hc_discuss_zhutie.created_at')
                               ->order("id desc")
                                ->limit(8)
                                ->select();
        // 图解小组
        $illustration  = $this->discuss_zhutie
                            ->join('__USERS__ ON __DISCUSS_ZHUTIE__.user_id = __USERS__.id')
                            ->where(array('category_id'=>C('ILLUSTRATION'),'is_display'=>'1'))
                            ->field('6hc_discuss_zhutie.id,title,username,6hc_users.id as u_id')
                            ->order("id desc")
                            ->limit(10)
                            ->select();

        // 特码走势图

        $specialPic =  $this->lotteryRecord
                            ->where(array('status'=>'active'))
                            ->order('id desc')
                            ->field('periods as num,special_number as count')
                            ->limit(21)
                            ->select();
        sort($specialPic);
        foreach($specialPic as  $key=>$pics) {
                $specialPics[$key]['Name'] = $specialPic[$key]['num'];
                $specialPics[$key]['Count'] = intval($specialPic[$key]['count']);
        }
        $specialPicJson = json_encode($specialPics);

        // 幽默猜测

        $joke  = $this->joke
                    ->join('__LOTTERY_RECORD__ on __JOKE__.periods_id = __LOTTERY_RECORD__.id')
                    ->order('6hc_joke.id desc')
                    ->field('periods,title,pic,type,movie,6hc_joke.id')
                    ->limit(2)
                    ->select();

        $this->assign('discuzRecomm',$discuzRecomm);
        $this->assign('animal_record',$animal_record);
        $this->assign('special_record',$special_record);
        $this->assign('weekAnimals',$weekAnimals);
        $this->assign('weekAnimalsSum',$weekAnimalsSum);
        $this->assign('paperList',$parperList);
        $this->assign('expertDiscuss',$expertDiscuss);
        $this->assign('illustration',$illustration);
        $this->assign('specialPicJson',$specialPicJson);
        $this->assign('joke',$joke);
        $this->display();

    }
}