<?php
namespace Home\Controller;

//use Think\Controller;

class IndexController extends BaseController
{


    protected  $zhutie;
    public function __construct()
    {
        parent::__construct();

        $this->zhutie = D('discuss_zhutie');

    }

    /**
     * http://windows.php.net/downloads/pecl/releases/
     * https://github.com/phpredis/phpredis/downloads
     */
    public function index()
    {
        $discuzRecomm =  S('discuzRecomm');

        if(empty($discuzRecomm)) {
            $discuzRecomm  =  $this->zhutie->where(['display'=>'1'])
                                ->order('id desc')->field('id,title')
                                ->limit(9)->select();
            S('discuzRecomm',$discuzRecomm,C('CACHE_TIME')['middle']);
        }

        $this->assign('discuzRecomm',$discuzRecomm);

        $this->display();

    }
}