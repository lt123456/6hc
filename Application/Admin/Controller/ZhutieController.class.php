<?php
/**
 * 主贴管理
 */
namespace Admin\Controller;

class ZhutieController extends BaseController {
    
    protected  $discussZhutieModel;

    public function  __construct()
    {
        parent::__construct();
        $this->discussZhutieModel = M('discuss_zhutie');
    }

    public function index()
    {

        //获取用户查询信息
        $map =I();
       

        //获取分类名称
        $discussCategorys = D('discuss_category')->field('name,id')->order('id desc')->limit()->select();

        // 获取
        $count  = D('discuss_zhutie')->join('__USERS__ ON __DISCUSS_ZHUTIE__.user_id = __USERS__.id')->where($this->serach($map))->count();
        $page = new  \Think\Page($count, C('PAGE'));
        if($this->serach($map)){
            foreach($this->serach($map) as $key=>$val) {
                $Page->parameter[$key]   =   urlencode($val);
            }
        }
        $discussZhuties = D('discuss_zhutie')->join('__USERS__ ON __DISCUSS_ZHUTIE__.user_id = __USERS__.id')
                                        ->join('__DISCUSS_CATEGORY__ ON __DISCUSS_ZHUTIE__.category_id = __DISCUSS_CATEGORY__.id')
                                        ->order('6hc_discuss_zhutie.id desc')
                                        ->field('6hc_discuss_category.name,6hc_users.username,6hc_discuss_zhutie.*')
                                        ->where($this->serach($map))
                                        ->limit($page->firstRow.','.$page->listRows);
        $discussZhuties = $discussZhuties->select();
        $show = $page->show();
        // var_dump($discussCategorys);die;
        //分配
        $this->assign('page',$show);
        $this->assign('map',$map);
        $this->assign('discussZhuties',$discussZhuties);
        $this->assign('discussCategorys',$discussCategorys);
        $this->assign('count',$count);

        // 视图
        $this->display();
    }


    //后台用户不添加帖子
    // public function  add()
    // {
    //     $this->display();
    // }

    // public function  doadd()
    // {

    // }

    public function  disabled()
    {

    }

    public function edit()
    {
         //获取分类名称
        $discussCategorys = D('discuss_category')->field('name,id')->order('id desc')->limit()->select();

        $obj =    $this->discussZhutieModel->find(I('get.id'));

          if($obj) {
              $this->assign('obj',$obj);
              $this->assign('discussCategorys',$discussCategorys);
              $this->display();
          }else{
              $this->error('该帖子不存在','/Admin/Zhutie',3);
          }
    }

    public function doEdit()
    {
        if(IS_POST) {
            $data = I();

            $res = $this->discussZhutieModel->save($data);

            if($res){
                $this->ajaxReturn(array('status'=>'ok'));
            }else{
                $this->ajaxReturn(array('status'=>'error'));
            }
        }
    }

    public function  delete()
    {
        $data = I();
        $res = $this->discussZhutieModel->where('id='.$data['id'])->delete();

        if($res) {
            $this->ajaxReturn(array('status'=>'ok'));
        }else{
            $this->ajaxReturn(array('status'=>'error'));
        }
    }

    //查询条件
    public function  serach($map)
    {

        if(!empty($map['category_id'])){
           $where['category_id'] = array('eq',$map['category_id']);
        }
        if(!empty($map['is_add'])){
            $where['is_add'] = array('eq',$map['is_add']);
        }
        if(!empty($map['is_expert'])){
            $where['is_expert'] = array('eq',$map['is_expert']);
        }
        if(!empty($map['is_comment'])){
            $where['is_comment'] = array('eq',$map['is_comment']);
        }
        if(!empty($map['is_top'])){
            $where['is_top'] = array('eq',$map['is_top']);
        }
        if(!empty($map['is_display'])){
            $where['is_display'] = array('eq',$map['is_display']);
        }
        if(!empty($map['type'])){
            if($map['type'] == 'title' && !empty($map['scontent'])){
                 $where['title'] = array('like','%'.$map['scontent'].'%');
            }

            if($map['type'] == 'user_id' && !empty($map['scontent'])){
                $where['username'] = array('like','%'.$map['scontent'].'%');

            }
        }
        return $where;
    }
}