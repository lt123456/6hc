<?php
namespace Home\Controller;
use Think\Controller;
use \Think\Page;
use Common\Common\Verify;


class JokeController extends Controller {

	public $zodiac = array('rat'=>'鼠','ox'=>'牛','tiger'=>'虎','hare'=>'兔','dragon'=>'龙','snake'=>'蛇','horse'=>'马','sheep'=>'羊','monkey'=>'猴','cock'=>'鸡','dog'=>'狗','boar'=>'猪');

	//幽默猜测首页
	public function index(){
		//图片猜测
		$jokePic = D('joke')->order('`id` desc')
	                ->field('*')
	                ->where('type="pic"')
	                ->limit('0,12')
	                ->select();

		//视频猜测
		$jokeMovie = D('joke')->order('`id` desc')
	                ->field('*')
	                ->where('type="movie"')
	                ->limit('0,12')
	                ->select();

	    $this->joke_Mosaic($jokePic);
	    $this->joke_Mosaic($jokeMovie);

	    // var_dump($jokePic);
	    // var_dump($jokeMovie);die;
        $this->assign('jokePic',$jokePic);
        $this->assign('jokeMovie',$jokeMovie);

		$this->display();
	}

	//幽默猜测列表
	public function joke_list(){
		$type = I('type');

		if(empty($type)){
            $this->error('对应的幽默猜测找不到','/joke',3);
		}

		$count      = D('joke')->where('type="'.$type.'"')->count();

		$Page       = new Page($count,24);

		//修改分页配置

		// $Page->setConfig('header','<span class="total">共<b>%TOTAL_ROW%</b>条数据，第<b>%NOW_PAGE%</b>页/共<b>%TOTAL_PAGE%</b>页</span>');

		$Page->setConfig('prev','上一页');
		$Page->setConfig('next','下一页');
		$Page->setConfig('last','末页');
		$Page->setConfig('first','首页');
		$Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');

		$show = $Page->show();

		$jokes = D('joke')
				->where('type="'.$type.'"')
				->order('`id` desc')
				->limit($Page->firstRow.','.$Page->listRows)
				->select();

	    $this->joke_Mosaic($jokes);
		// var_dump($jokes);die;

		$this->assign('jokes',$jokes);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出

		$this->display();
	}


	//详情页面
	public function info(){
		$jokeid = I('jokeid');


	 	$jokes = D('joke')
            ->where('6hc_joke.is_delete="0" and status="allow" and 6hc_joke.id='.$jokeid)
            ->field('6hc_joke.id as jokeid,6hc_joke.*')
            ->find();

        //拼接数据
       	$this->joke_Mosaic($jokes,1);

		if(empty($jokes)){
            $this->error('找不到对应幽默猜测','/joke',3);
        }

        //阅读量自增一
		$this->joke_read($jokeid);

		//推荐
		$recommend = array();
	   	$this->animals($jokeid,$recommend);

	   	// var_dump($recommend);die;
		//相邻两期
		$adjacent = array();
		$this->joke_adjacent($jokeid,$adjacent);


		//是否可以推荐
		$is_recommend = false;
    	$ip = $_SERVER["REMOTE_ADDR"];

    	//图纸推荐
    	$comm_papers = D('paper_view')
					->join('6hc_paper_list ON 6hc_paper_list.id = 6hc_paper_view.pic_name_id')
                    ->join('6hc_lottery_record ON 6hc_paper_view.periods_id=6hc_lottery_record.id')
					->group('pic_name_id')
					->order('6hc_lottery_record.periods desc,6hc_paper_view.`read` desc')
	                ->field('*')
	                ->limit('0,3')
	                ->select();

    	$commend = D('recomm_animals')
		            ->where('6hc_recomm_animals.joke_id='.$jokeid.' and 6hc_recomm_animals.comm_ip="'.$ip.'"')
		            ->field('6hc_recomm_animals.id')
		            ->find();

    	if(empty($adjacent['next'])&&empty($commend)){
			$is_recommend = true;
    	}

        //回复
        $comment_count      = D('comment')->where('origin_id="'.$jokeid.'" and select_table="jock"')->count();
        $Page    = new Page($comment_count,4);

        $Page->setConfig('prev','上一页');
        $Page->setConfig('next','下一页');
        $Page->setConfig('last','末页');
        $Page->setConfig('first','首页');
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');

        $show = $Page->show();

        $comment = D('comment')
                ->where('origin_id="'.$jokeid.'" and select_table="jock"')
                ->order('`id` desc')
                ->limit($Page->firstRow.','.$Page->listRows)
                ->field('*')
                ->select();


		$this->assign('adjacent',$adjacent);
  		$this->assign('jokes',$jokes);
  		$this->assign('jokeid',$jokeid);
  		$this->assign('recommend',$recommend);
  		$this->assign('is_recommend',$is_recommend);
  		$this->assign('comm_papers',$comm_papers);
  		$this->assign('page',$show);
  		$this->assign('comment',$comment);
  		$this->assign('comment_count',$comment_count);
		$this->display();
	}

    //增加推荐
    public function add_animals(){
    	$comm = I('comm');
    	$jokeid = I('jokeid');
    	$ip = $_SERVER["REMOTE_ADDR"];

    	//是否存在
    	$me_comm = D('recomm_animals')
		            ->where('6hc_recomm_animals.joke_id='.$jokeid.' and 6hc_recomm_animals.comm_ip="'.$ip.'"')
		            ->count();
		//总的数量
        $sum_comm = D('recomm_animals')
		            ->where('6hc_recomm_animals.joke_id='.$jokeid)
		            ->count();

		if(empty($me_comm)){
			$sum = (($sum_comm)?$sum_comm:0)+1;
			$data = array(
					'joke_id'=>$jokeid,
					$comm=>1,
					'comm_ip'=>$ip,
					'sum'=>$sum,
				);
			D('recomm_animals')->add($data);
			D('recomm_animals')->where('6hc_recomm_animals.joke_id='.$jokeid)->save(array('sum'=>$sum));
			echo 1;
		}else{
			echo 2;
		}
    }


    //提交回复
    public function sub_huifu(){
        $post_content = I('post_content');
        $captcha = I('captcha');
        $joke_id = I('joke_id');

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
                'origin_id'=>$joke_id,
                'select_table'=>'jock',
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


    //验证码
    public function verify($captcha='')
    {
        $verify = new verify();
        if(empty($captcha)){
            return $verify::verify();
        }else{
            return $verify::verify($captcha);
        }


    }


	/**
	* @desc 对幽默猜测进行拼接
	* @param $joke arr 
	* @param $int  int  默认为二维数组
	*/
	protected function joke_Mosaic(&$jokes,$int=2){

		switch($int){
			case 1:
				if(!empty($jokes['pic'])){
					$jokes['image'] = json_decode($jokes['pic'],true);
				}
				if(!empty($jokes['periods_id'])){
		            $jokes['lottery'] = D('lottery_record')
						                    ->where('id='.$jokes['periods_id'])
						                    ->field('*')
						                    ->find();
				}
				if(!empty($jokes['admin_id'])){
					$jokes['admin'] = D('admin')
				                    ->where('id='.$jokes['admin_id'])
				                    ->field('*')
				                    ->find();
				}
				break;
			case 2:
				foreach($jokes as $key=>$joke){
					if(!empty($joke['pic'])){
						$jokes[$key]['image'] = json_decode($joke['pic'],true);
					}
					if(!empty($joke['periods_id'])){
			            $jokes[$key]['lottery'] = D('lottery_record')
							                    ->where('id='.$joke['periods_id'])
							                    ->field('*')
							                    ->find();
					}
					if(!empty($joke['admin_id'])){
						$jokes[$key]['admin'] = D('admin')
							                    ->where('id='.$joke['admin_id'])
							                    ->field('*')
							                    ->find();
					}
				}
				break;
		}
	}

	/**
    * @desc 没查看一次幽默猜测阅读量加1
    * @param id 图库ID
    * @param period 期数
    */
    protected function joke_read($id){
        $paper = D('joke')
                ->where('6hc_joke.id='.$id)
                ->find();

        $read = ++$paper['read'];

        D('joke')->where('6hc_joke.id='.$id)->save(array('read'=>$read));
    }

    /**
    * @desc 相邻的两个
    * @param $jokeid 当前幽默猜测的ID
    *
    */
    protected function joke_adjacent($jokeid,&$adjacent){

    	//查看对应的幽默猜测
    	$joke = D('joke')
    			->join('6hc_lottery_record ON 6hc_lottery_record.id=6hc_joke.periods_id')
            	->where('6hc_joke.is_delete="0" and 6hc_joke.id='.$jokeid)
            	->field('6hc_lottery_record.periods,6hc_joke.type')
            	->find();

        if(empty($joke)){
        	$adjacent['next'] = array();
        	$adjacent['prev'] = array();
        }

        //查看相邻的
        $period = $joke['periods'];
        $next = ++$period;

        $period = $joke['periods'];
        $prev = --$period;

        $field = '6hc_joke.id as jokeid,6hc_joke.*,6hc_lottery_record.id,6hc_lottery_record.periods';

        $adjacent['next'] = D('joke')
	    			->join('6hc_lottery_record ON 6hc_lottery_record.id=6hc_joke.periods_id')
	            	->where('6hc_joke.is_delete="0" and 6hc_joke.type="'.$joke['type'].'" and 6hc_lottery_record.periods='.$next)
	            	->field($field)
	            	->find();


        $adjacent['prev'] = D('joke')
	    			->join('6hc_lottery_record ON 6hc_lottery_record.id=6hc_joke.periods_id')
	            	->where('6hc_joke.is_delete="0" and 6hc_joke.type="'.$joke['type'].'" and 6hc_lottery_record.periods='.$prev)
	            	->field($field)
	            	->find();
        
       return $adjacent;
    }

    /**
    * @desc 查看推荐
    * 
    */
    protected function animals($jokeid,&$recommend){
    	$zodiacs = $this->zodiac;

    	//当前幽默猜测的所有推荐
    	$sum_recomm=D('recomm_animals')
			            ->where('6hc_recomm_animals.joke_id='.$jokeid)
			            ->count();

    	foreach($zodiacs as $key=>$val){
			$count=D('recomm_animals')
		            ->where('6hc_recomm_animals.'.$key.'=1 and 6hc_recomm_animals.joke_id='.$jokeid)
		            ->count();

    		$recommend[$key]['count'] = $count;
    		$recommend[$key]['just_percent'] = round((int)$count/(int)$sum_recomm,4)*100;
    		$recommend[$key]['back_percent'] = 100-(round((int)$count/(int)$sum_recomm,4)*100);
            $recommend[$key]['zn'] = $val;
            $recommend[$key]['en'] = $key;
    	}
    }

}