<?php
namespace Home\Controller;
use Think\Controller;
use Think\Cache\Driver\Redis;
use Common\Common\Verify;
use \Think\Page;


class PaperController extends Controller {

	//所有图纸的spell
	public $spells = array();

    public $redis;

    public $redisMax = 4;

    public function __construct(){
        parent::__construct();
        $this->redis = new Redis();
    }

    //验证码
    public function verify($captcha='')
    {
        $verify = new verify();
        return $verify::verify();
    }

    public function index(){

    	//热门图纸
    	$hot_papers = D('paper_view')
					->order('`read` desc')
					->join('6hc_paper_list ON 6hc_paper_list.id = 6hc_paper_view.pic_name_id')
					->group('pic_name_id')
	                ->field('*')
	                ->limit('0,8')
	                ->select();

	    //所有的图纸
	    $papers = D('paper_list')
				->order('`spell`')
                ->where('is_hide="0"')
                ->field('*')
                ->select();

        //所有图纸的spell
        $this->spells = range('A','Z');

        //查看redis里面的值
        $hot_terms=$this->hot_redis();

        $this->assign('hot_papers',$hot_papers);
        $this->assign('spells',$this->spells);
        $this->assign('papers',$papers);
        $this->assign('hot_terms',$hot_terms);

        $this->display();
    }


    //搜索图库名称
    public function search(){
        $keyword=I('keyword');

    	//加入redis
    	$hot_terms=$this->hot_redis($keyword);

        //搜索所搜的图
        $search_count = D('paper_list')
                    ->where('name like "%'.$keyword.'%"')
                    ->field('*')
                    ->count();

        //查询对应分类总所有的期数(默认为搜索词汇的全部期数)
        $papers =D('paper_list')
                ->where('6hc_paper_list.name like "%'.$keyword.'%"')
                ->field('6hc_paper_list.*,6hc_paper_view.cut_pic')
                ->group('6hc_paper_list.id')
                ->select();
        $lottery=$this->lotterys(36,$keyword,'',$papers);



        $this->assign('hot_terms',$hot_terms);
        $this->assign('keyword',$keyword);
        $this->assign('search_count',$search_count);
        $this->assign('lottery',$lottery);

        $this->display();            
    }


    //图库列表页面
    public function paper_list(){
        $id = I('id');

        //加入redis
        $hot_terms=$this->hot_redis();

        $paper = D('paper_list')
                ->where('6hc_paper_list.id='.$id)
                ->field('*')
                ->where('is_hide="0"')
                ->find();

        if(empty($paper)){
            $this->error('找不到对应彩票','/paper',3);
        }

        $lottery=$this->lotterys('','',$id);
        // var_dump($lottery);die;
        $this->assign('hot_terms',$hot_terms);
        $this->assign('paper',$paper);
        $this->assign('lottery',$lottery);

        $this->display();     
    }

    public function view(){
        $id = I('id');
        $period = I('period');
        if(empty($id)){
            $this->error('找不到对应图库','/paper',3);
        }

        if(empty($period)){
            $record =D('lottery_record')
                    ->join('6hc_paper_view ON 6hc_paper_view.periods_id=6hc_lottery_record.id')
                    ->where('6hc_paper_view.pic_name_id='.$id)
                    ->order('6hc_lottery_record.periods desc')
                    ->field('6hc_lottery_record.*')
                    ->find();
        }else{
            //查询对应的期数
            $record =D('lottery_record')
                    ->where('id='.$period)
                    ->field('*')
                    ->find();
        }

        $this->paper_read($id,$record['id']);

        $paper = D('paper_list')
                ->join('6hc_paper_view ON 6hc_paper_view.pic_name_id=6hc_paper_list.id')
                ->where('6hc_paper_list.is_hide="0" and 6hc_paper_view.pic_name_id='.$id.' and 6hc_paper_view.periods_id='.$record['id'])
                ->field('6hc_paper_list.id as listid,6hc_paper_list.*,6hc_paper_view.*')
                ->find();

        if(empty($paper)){
            $this->error('没有当前期数的图库','/paper',3);
        }

        $lottery=$this->lotterys(10,'',$id);
        // var_dump($lottery);
        // var_dump($paper);die;

        //回复
        $comment_count      = D('comment')->where('origin_id="'.$id.'" and select_table="paper"')->count();
        $Page    = new Page($comment_count,4);

        $Page->setConfig('prev','上一页');
        $Page->setConfig('next','下一页');
        $Page->setConfig('last','末页');
        $Page->setConfig('first','首页');
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');

        $show = $Page->show();

        $comment = D('comment')
                ->where('origin_id="'.$id.'" and select_table="paper"')
                ->order('`id` desc')
                ->limit($Page->firstRow.','.$Page->listRows)
                ->field('*')
                ->select();

        //可能喜欢的
        $like_papers = D('paper_view')
                    ->order('`read` desc')
                    ->join('6hc_paper_list ON 6hc_paper_list.id = 6hc_paper_view.pic_name_id')
                    ->group('pic_name_id')
                    ->field('*')
                    ->limit('0,8')
                    ->select();

        $this->assign('paper',$paper);
        $this->assign('record',$record);
        $this->assign('lottery',$lottery);
        $this->assign('comment',$comment);
        $this->assign('page',$show);
        $this->assign('comment_count',$comment_count);
        $this->assign('like_papers',$like_papers);

        $this->display();
       
    }

    //提交回复
    public function sub_huifu(){
        $post_content = I('post_content');
        $captcha = I('captcha');
        $paper_id = I('paper_id');

        $data = array(
                'flag'=>'',
                'msg'=>''
            );

        // $res = $this->verify($captcha);
        $res = 1;
        if(!$res){
            $data['flag']=2;
            $data['msg']='验证码错误';
            echo json_encode($data);
            die;
        }

        //获取用户ID
        $userid = rand(1,4);

        //插入记录
        $insert = array(
                'description'=>$post_content,
                'user_id'=>$userid,
                'origin_id'=>$paper_id,
                'select_table'=>'paper',
                'created_at'=>date('Y-m-d H:i:s'),
                'updated_at'=>date('Y-m-d H:i:s'),
            );

        $comment = D('comment')->add($insert);
        if($comment){
            $data['flag']=1;
            $data['msg']='成功';
            echo json_encode($data);
            die;  
        }else{
            $data['flag']=3;
            $data['msg']='网络出现问题，刷新一下';
            echo json_encode($data);
            die;
        }

    }

    //将搜索的热词加入redis
    protected function hot_redis($search){
        $foo_key = 'search_key';
        //备用key
        $bei_key = 'ready_key';

        //存放展示的key
        $word = $this->redis->get($foo_key);

        //存放备用的key
        $bei = $this->redis->get($bei_key);

        //搜索为空的话
        if(empty($search)){

            $this->redis->close();
            if(!is_array($word)){
                return array();
            }
            arsort($word);

            return array_slice($word,0,$this->redisMax);
        }

        //判断为空 或者不满9个的时候(先补满9个)
        if(!$word || count($word)<$this->redisMax){
            //判断不是数组 或者 当前搜索的不存在的时候
            if(!is_array($word) || !array_key_exists($search,$word)){
                if(!is_array($word)){
                    $word = array();
                }
                $word = array_merge($word,array($search=>1));
            }else{
                $word[$search] += 1;
            }            
        }else{
            if(array_key_exists($search,$word)){
                $word[$search] += 1;
            }else{
                arsort($word);
                if(end($word)==1){
                    array_pop($word);
                    $word = array_merge($word,array($search=>1));
                }else{
                    //判断如果不存在word中且现在的里面没有==1的
                    if(!is_array($bei) || !array_key_exists($search,$bei)){
                        if(!is_array($bei)){
                            $bei = array($search=>1);
                        }else{
                            $bei = array_merge($bei,array($search=>1));
                        }
                    }else{
                        $bei[$search] += 1;
                    }

                    //如果最小的一个小于了备考中的  两个替换
                    if(end($word)<$bei[$search]){
                        $tihuan = array_slice($word,-1,1);
                        array_pop($word);
                        $word = array_merge($word,array($search=>$bei[$search]));
                        $bei = $tihuan;
                    }

                    $set = $this->redis->set($bei_key,json_encode($bei));
                }
            }
        }

        $json = json_encode($word);
        $set = $this->redis->set($foo_key,$json);

        arsort($word);
        $word=array_slice($word,0,$this->redisMax);

        $this->redis->close();

        return $word;
    }

    /**
    * @desc 查询每个图库的开奖期数
    * @param papers array 关联的数组
    * @param max string  要获取的多少的期数 默认为0,则获取全部
    * @param paperId int 图库的ID
    */
    protected function lotterys($max='',$keyword,$paperId='',&$papers=array()){

        $fields = '6hc_paper_list.id as listid,6hc_lottery_record.id,6hc_lottery_record.periods';

        $paper_view = D('paper_list')->field($fields)
                     ->join('6hc_paper_view ON 6hc_paper_list.id = 6hc_paper_view.pic_name_id')
                     ->join('6hc_lottery_record ON 6hc_paper_view.periods_id=6hc_lottery_record.id')
                     ->order('6hc_lottery_record.periods desc')
                     ->where('6hc_paper_list.is_hide="0"');

        //可以搜索的词汇
        if(!empty($keyword)){
            $paper_view->where('6hc_paper_list.name like "%'.$keyword.'%"');
        }
        //可以搜索对应的ID
        if(!empty($paperId)){
            $paper_view->where('6hc_paper_list.id='.$paperId);
        }
        //如果不传最大搜索的数量 则搜索全部期数
        if(!empty($max)){
            $paper_view->limit('0,'.$max);
        }      
            $view=$paper_view->select();

        //拼接
        if(!empty($papers)){
            foreach($papers as $key=>$val){
                if(!empty($keyword)){
                    $papers[$key]['span_name'] = str_replace($keyword,'<span>'.$keyword.'</span>',$val['name']);
                }
                $count = 0;
                foreach($view as $k=>$v){
                    if($val['id']==$v['listid']){
                        $papers[$key]['periods'][]=$v;
                        $count++;
                        $papers[$key]['period_count']=$count;
                    }
                }
            }
        }else{
            $papers = $view;
        }

        return $papers;
    }

    /**
    * @desc 没查看一次图库阅读量加1
    * @param id 图库ID
    * @param period 期数
    */
    protected function paper_read($id,$period){
        $paper = D('paper_view')
                ->where('6hc_paper_view.pic_name_id='.$id.' and 6hc_paper_view.periods_id='.$period)
                ->find();

        $read = ++$paper['read'];

        D('paper_view')->where('6hc_paper_view.pic_name_id='.$id.' and 6hc_paper_view.periods_id='.$period)->save(array('read'=>$read));
    }


}