<?php
/**
 *  后台用户管理
 */
namespace Admin\Controller;

class AgentController extends BaseController {


    /**
     * 后台管理列表
     */
    public function index()
    {
        // 获取
        $admins = D('admin')->order('id desc')->select();
        $count  = D('admin')->count();
        $disableCount  = D('admin')->where(['status'=>'active'])->count();

        //分配
        $this->assign('admins',$admins);
        $this->assign('count',$count);
        $this->assign('disableCount',$disableCount);

        // 视图
        $this->display();
    }

    /**
     *  添加 用户
     */
    public function  add()
    {
        $this->display();
    }

    public function  doadd()
    {

    }

    public function  disabled()
    {

    }

    public function edit()
    {

    }

    public function doEdit()
    {

    }

    public function  delete()
    {

    }
}