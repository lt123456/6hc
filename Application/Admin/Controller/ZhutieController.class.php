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
        // 获取
        $discussZhuties = D('discuss_zhutie')->order('id desc')->select();
        $count  = D('discuss_zhutie')->count();
        // var_dump($count);die;
        //分配
        $this->assign('discussZhuties',$discussZhuties);
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
        $obj =    $this->discussZhutieModel->find(I('get.id'));

          if($obj) {
              $this->assign('obj',$obj);
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
}