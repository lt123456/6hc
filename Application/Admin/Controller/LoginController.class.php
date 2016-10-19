<?php

/**
 *  auth : liutao
 */
namespace Admin\Controller;
use Think\Controller;
use Common\Common\Verify;
class LoginController  extends  Controller
{

    /**
     * 登录
     */
    public function  login()
    {
        $this->display();

    }

    /**
     * 处理登陆
     */
    public function dologin()
    {
       $data = I();

        // 验证码匹配
//        $this->checkVeify($data['verify']);

        // 验证用户名密码
        $this->checkAdmin($data);

        // 是否登陆
        if( $this->is_login()){
            return $this->success('正在加速登陆','/Admin/Index/index',3);
        }
        $this->redirect('/Admin/Login/login');

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
            return $this->error('验证码错误','/Admin/Login/login',3);
        }
    }

    /**
     * 验证用户名 和密码
     * @param $data
     */
    public function checkAdmin($data)
    {
        $Admin  = D('admin');

        $result = $Admin->where(array('email'=>$data['email']))->find();

        if(empty($result)) {
            return $this->error('账户不存在','/Admin/Login/login',3);
        }

        if( md5($data['password']) == $result['password'] ) {

            if($result['status'] !='active') {
                return $this->error('您暂时没有权限','/Admin/Login/login',3);
            }
            $login['last_login_ip']   =  get_client_ip();
            $login['last_login_time'] = date('Y-m-d h:i:s',time());
            $login['id'] = $Admin->id;


            $Admin->save($login);
            session('admin_id',$result['id']);

        } else {
            return $this->error('密码错误','/Admin/Login/login',3);
        }

    }

    /**
     * @return mixed
     */
    public function  is_login()
    {
       return session('admin_id');
    }

    public function logout()
    {
        session('admin_id', null);
        $this->success('退出成功', U('Login/login'));
    }










}




