<?php

/**
 *  auth : liutao
 */
namespace Admin\Controller;

use Think\Controller;
use Think\Think;

class BaseController extends Controller
{
    protected $userId = null;

    public function __construct()
    {
        parent::__construct();

        if (!session('admin_id')) {
            $this->error('请先登录', U('Admin/Login/login'));
        }
//        $Auth = new \Think\Auth();
//
//        $module_name = MODULE_NAME . '/' . CONTROLLER_NAME . '/' . ACTION_NAME;
//        if (!$Auth->check($module_name, session('admin_id'))) {
//            $this->error('没有权限！');
//
//        };
    }

}