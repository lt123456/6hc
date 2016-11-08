<?php
/**
 * Ip管理
 */
namespace Admin\Controller;

class FriendController extends BaseController {

    protected  $friendModel;
    public function  __construct()
    {
        parent::__construct();
        $this->friendModel = D('friend');
    }

    /**
     *  展示 禁用的friend 地址
     */
    public function index()
    {
        $friends = $this->friendModel->order('id desc')->select();
        $count  = $this->friendModel->count();
        $this->assign('friends',$friends);
        $this->assign('count',$count);
        $this->display();
    }

    /**
     *  添加 friend 视图
     */
    public function  add()
    {
        $this->display();
    }

    /**
     *  添加friend
     */
    public function  doAdd()
    {
        if(IS_POST){

            $data =  I();
            $data['created_at'] = date('Y-m-d h:i:s',time());

            $res  =$this->friendModel->add($data);

            if($res) {
                $this->ajaxReturn(array('status'=>'ok'));
            }else{
                $this->ajaxReturn(array('status'=>'error','message'=>'发生异常稍后再试'));
            }
        }
    }
    /**
     * friend 视图
     */
    public function  edit()
    {
        $obj =    $this->friendModel->find(I('get.id'));

        if($obj) {
            $this->assign('obj',$obj);
            $this->display();
        }else{
            $this->error('该友情链接不存在','/Admin/Friend/index',3);
        }
    }

    /**
     *  处理friend
     */
    public function doEdit()
    {
        if(IS_POST) {
            $data = I();
            $res = $this->friendModel->save($data);
            if($res){
                $this->ajaxReturn(array('status'=>'ok'));
            }else{
                $this->ajaxReturn(array('status'=>'error'));
            }
        }
    }

    /**
     *  删除
     */
    public function  delete()
    {
        $data = I();
        $res = $this->friendModel->where('id='.$data['id'])->delete();
        if($res) {
            $this->ajaxReturn(array('status'=>'ok'));
        }else{
            $this->ajaxReturn(array('status'=>'error'));
        }


    }

}