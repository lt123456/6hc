<?php
namespace Home\Controller;
use Think\Controller;
use Common\Common\Verify;
class DiscussController extends Controller {
    
     public function  __construct()
    {
        parent::__construct();
        $this->discussZhutieModel = M('discuss_zhutie');
        $this->discussHuifuModel = M('discuss_huifu');
        $this->discussCategoryModel = M('discuss_category');
    }

    public function index(){
      	
      	//获取用户查询信息
        $map =I();

		//帖子所属分类
		$discussCategorys = M('discuss_category')->field('id,name')->order('id desc')->select();

		//三个数量统计
		$countnum = array();
		$countnum['zhutie'] = D('discuss_zhutie')->count();
		$countnum['huifu'] = D('discuss_huifu')->count();
		$countnum['users'] = D('users')->count();

		//查询版主
		$moderators = D('users')->field('id,username')->order('id')->where('role = "post_admin"')->select();

		//热门帖子
		$discussZhutiesHot = D('discuss_zhutie')->field('id,title')->order('replay_nums desc')->limit(0,10)->select();

		//专家小组
		$expert = D('users')->field('id,username,avatar')->order('id')->where('role = "expert"')->select();

		//热门用户
		$godUserHot = D('users')->field('id,username')->order()->where('role = "god"')->select();

		//置顶帖子
		$discussZhutiesTop = D('discuss_zhutie')->join('__USERS__ ON __DISCUSS_ZHUTIE__.user_id = __USERS__.id')
												->join('__DISCUSS_CATEGORY__ ON __DISCUSS_ZHUTIE__.category_id = __DISCUSS_CATEGORY__.id')
												->field('6hc_users.username,6hc_discuss_zhutie.id,title,6hc_discuss_zhutie.user_id,6hc_discuss_zhutie.created_at,6hc_discuss_zhutie.replay_nums,6hc_discuss_zhutie.read_nums,6hc_discuss_category.name')
												->order('6hc_discuss_zhutie.id desc')
												->limit(0,10)
												->where(array('is_top' => '0' , 'is_display' => '1'))
												->select();

		//非置顶帖子
		//传参排序
		if(!empty($map['order'])){
    		if($map['order'] == 'latest'){
    			 $order['created_at'] = '';
    		}
    		if($map['order'] == 'rank'){
    			 $order['replay_nums'] = 'desc';
    		}
    	}else{
    		 $order['6hc_discuss_zhutie.id'] = 'desc';
    	}

		$count  = D('discuss_zhutie')->join('__USERS__ ON __DISCUSS_ZHUTIE__.user_id = __USERS__.id')->where($this->serach($map))->count();
		$page = new  \Think\Page($count, 6);
		$discussZhuties = D('discuss_zhutie')->join('__USERS__ ON __DISCUSS_ZHUTIE__.user_id = __USERS__.id')
											  ->join('__DISCUSS_CATEGORY__ ON __DISCUSS_ZHUTIE__.category_id = __DISCUSS_CATEGORY__.id')
    										->field('6hc_users.username,6hc_discuss_zhutie.id,title,6hc_discuss_zhutie.user_id,6hc_discuss_zhutie.created_at,6hc_discuss_zhutie.replay_nums,6hc_discuss_zhutie.read_nums,6hc_discuss_category.name')
    										->order($order)
    										->where($this->serach($map))
    										->limit($page->firstRow.','.$page->listRows)
		                    ->select();

		$page->lastSuffix = 'false'; 
		$page->setConfig('first', '首页');
		$page->setConfig('last', '尾页');
		$page->setConfig('prev', '上一页');
		$page->setConfig('next', '下一页');
		$page->rollPage = '11';

		$show = $page->show();

		$this->assign('page',$show);
		$this->assign('count',$count);
		$this->assign('discussCategorys',$discussCategorys);
		$this->assign('discussZhutiesTop',$discussZhutiesTop);
		$this->assign('discussZhutiesHot',$discussZhutiesHot);
		$this->assign('discussZhuties',$discussZhuties);
		$this->assign('countnum',$countnum);
		$this->assign('moderators',$moderators);
		$this->assign('godUserHot',$godUserHot);
		$this->assign('expert',$expert);
		$this->assign('urlparameter',$urlparameter);
		$this->display();

    }

    //帖子详情页面
    public function details(){

      $id = I()['id'];

      if(empty($id)){
        E('页面错误');
      }
      $discussZhutie = $this->discussZhutieModel->join('__DISCUSS_CATEGORY__ ON __DISCUSS_ZHUTIE__.category_id = __DISCUSS_CATEGORY__.id')
                                                ->join('__USERS__ ON __DISCUSS_ZHUTIE__.user_id = __USERS__.id')
                                                ->field('6hc_discuss_zhutie.id as z_id,6hc_discuss_zhutie.user_id,title,read_nums,replay_nums,description,6hc_discuss_zhutie.created_at,name,6hc_users.username,6hc_discuss_zhutie.agree_nums')
                                                ->where(array('6hc_discuss_zhutie.id' => $id , 'is_display' => '1'))
                                                ->find();
      if(empty($discussZhutie)){

         E('页面错误');
      }

      //其他页面title及链接
      $sixs = $this->discussZhutieModel->field('id,title')->where(array('is_display' => '1'))->order('id desc')->limit(0,6)->select();

      //查询回复
      $count = $this->discussHuifuModel->join('__USERS__ ON __DISCUSS_HUIFU__.user_id = __USERS__.id')->where(array('6hc_discuss_huifu.zhutie_id' => $id, 'is_display' => '1', 'pid' => '0'))->count();
      $page = new  \Think\Page($count, 6);
      $discussHuifus = $this->discussHuifuModel->join('__USERS__ ON __DISCUSS_HUIFU__.user_id = __USERS__.id')
                                              ->field('6hc_discuss_huifu.*,6hc_users.username')
                                              ->where(array('6hc_discuss_huifu.zhutie_id' => $id, 'is_display' => '1', 'pid' => '0'))
                                              ->order('6hc_discuss_huifu.created_at desc')
                                              ->limit($page->firstRow.','.$page->listRows)
                                              ->select();

      //进入页面数值加一
      $this->discussZhutieModel->where('id='.$id)->setInc('read_nums');

      $page->lastSuffix = 'false'; 
      $page->setConfig('first', '首页');
      $page->setConfig('last', '尾页');
      $page->setConfig('prev', '上一页');
      $page->setConfig('next', '下一页');
      $page->rollPage = '11';

      $show = $page->show();
      $this->assign('page',$show);
      $this->assign('count',$count);
      $this->assign('sixs',$sixs);
      $this->assign('discussHuifus',$discussHuifus);
      $this->assign('discussZhutie',$discussZhutie);
      $this->display();
    }

    //个人帖子页面
    public function space(){
        //获取用户id
        $map =I();
        if(empty($map)){
          E('该用户不存在');
        }
        $id = $map['id'];
        
        //该用户的两个信息
        $userdetail = D('users')->field('username,last_login_time')->find($id);
        if(!$userdetail){
         $this->error('用户不存在','/Discuss/index');

        }
        //该用户的帖子
        //传参排序
        if(!empty($map['order'])){
            //时间
            if($map['order'] == 'latest'){
               $order['created_at'] = '';
            }
          }else{
            //id
             $order['6hc_discuss_zhutie.id'] = 'desc';
          }

        $count  = D('discuss_zhutie')->join('__USERS__ ON __DISCUSS_ZHUTIE__.user_id = __USERS__.id')->where(array('6hc_discuss_zhutie.user_id' => $id))->count();
        $page = new  \Think\Page($count, 6);
        $discussZhuties = D('discuss_zhutie')->join('__USERS__ ON __DISCUSS_ZHUTIE__.user_id = __USERS__.id')
                            ->join('__DISCUSS_CATEGORY__ ON __DISCUSS_ZHUTIE__.category_id = __DISCUSS_CATEGORY__.id')
                            ->field('6hc_users.username,6hc_discuss_zhutie.id,title,6hc_discuss_zhutie.user_id,6hc_discuss_zhutie.created_at,6hc_discuss_zhutie.replay_nums,6hc_discuss_zhutie.read_nums,6hc_discuss_category.name')
                            ->order($order)
                            ->where(array('6hc_discuss_zhutie.user_id' => $id))
                            ->limit($page->firstRow.','.$page->listRows)
                            ->select();

        $page->lastSuffix = 'false'; 
        $page->setConfig('first', '首页');
        $page->setConfig('last', '尾页');
        $page->setConfig('prev', '上一页');
        $page->setConfig('next', '下一页');
        $page->rollPage = '11';

        $show = $page->show();

        $this->assign('page',$show);
        $this->assign('count',$count);
        $this->assign('discussZhuties',$discussZhuties);
        $this->assign('userdetail',$userdetail);
        $this->display();
    }

    //添加帖子
    public function addZhutie(){

    	// 验证码匹配
    	$data = I();
     	$this->checkVeify($data['verify']);

     	if(!empty($data['title']) && !empty($data['description']) && !empty($data['category_id'])){
       	if(IS_POST){
	            $data['is_add'] = '0';
	            $data['is_expert'] = '0';
	            $data['is_comment'] = '1';
	            $data['is_top'] = '0';
	            $data['is_display'] = '0';
	            $data['is_admin'] = '0';
	            $data['replay_nums'] = '0';
	            $data['created_at'] = date('Y-m-d H:i:s',time());
	            // var_dump($data);die;
	            $res  = $this->discussZhutieModel->add($data);

	            if($res){
		    		$this->ajaxreturn(array('status'=>'ok'));
		    	}else{
		    		$this->ajaxreturn(array('status'=>'false'));
		    	}
	    }else{
	    	$this->ajaxreturn(array('status'=>'method'));
	    }
  	}else{
  		$this->ajaxreturn(array('status'=>'description'));
  	}
      
    }

    //添加回复
    public function addHuifu(){

      // 验证码匹配
      $data = I();
      $this->checkVeify($data['verify']);

        if(IS_POST){
            $user_id = '1';
            $zhutie_id = '65';
            $data['title'] = '';
            $data['user_id'] = $user_id;
            $data['zhutie_id'] = $zhutie_id;
            $data['is_display'] = '1';
            $data['replay_nums'] = '0';
            $data['pid'] = '0';
            $data['created_at'] = date('Y-m-d H:i:s',time());
            $res  = $this->discussHuifuModel->add($data);
            $resz = $this->discussZhutieModel->where(array('id' => $zhutie_id))->setInc('replay_nums');

            if($res && $resz){
              $this->ajaxreturn(array('status'=>'ok'));
            }else{
            $this->ajaxreturn(array('status'=>'false'));
            }
        }else{
          $this->ajaxreturn(array('status'=>'method'));
        }
    }


    //点赞
    public function addAgree(){
        $data = I();
        if(!empty($data['id'])){

            $res = $this->discussZhutieModel->where(array('id' => $data['id']))->setInc('agree_nums');
            if($res){
                $this->ajaxreturn(array('status'=>'ok'));
            }else{
                $this->ajaxreturn(array('status'=>'error'));
            }
        }else{
          $this->ajaxreturn(array('status'=>'error'));
        }
    }


    //发表新帖子页面
    public function subject(){

    	//帖子所属分类
		$discussCategorys = D('discuss_category')->field('id,name')->order('id desc')->select();

		$this->assign('discussCategorys',$discussCategorys);
    	$this->display();
    }



    /**
     * 验证码
     */
    public function verify()
    {

        $verify=new verify();

        return $verify::verify();

    }

    /**
     *  验证码匹配
     */
    public function checkVeify($verify)
    {
        $Verify = new \Think\Verify();
        $res = $Verify->check($verify);

        if(!$res) {
            $this->ajaxreturn(array('status'=>'yzm'));
        }
    }

    //查询条件
    public function  serach($map)
    {

        if(!empty($map['category_id'])){
           $where['category_id'] = array('eq',$map['category_id']);
        }

        if(!empty($map['order']) && $map['order'] == 'add'){
        	$where['is_add'] = array('eq','1');
        }

        if(!empty($map['order']) && $map['order'] == 'expert'){
        	$where['role'] = array('eq','expert');
        }
       
        if(!empty($map['type'])){
            if($map['type'] == 'title' && !empty($map['content'])){
                 $where['title'] = array('like','%'.$map['content'].'%');
            }

            if($map['type'] == 'user_id' && !empty($map['content'])){
                $where['username'] = array('like','%'.$map['content'].'%');

            }
        }
			
        $where['is_top'] = array('eq','1');
    	$where['is_display'] = array('eq','1');
        return $where;
    }


}