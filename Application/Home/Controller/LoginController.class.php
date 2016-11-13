<?php
namespace Home\Controller;
use Think\Controller;
use Common\Common\Verify;
class LoginController extends Controller {

    public function index(){  	
        $this->display();
    }

    //验证码
    public function verify($captcha='')
    {
        $verify = new verify();
        return $verify::verify();
    }

    public function do_login(){
    	$name = I('name');
    	$pass = I('pass');

    	$pattern = "/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i";
    	if(!preg_match($pattern,$name)){
    		echo json_encode(array('flog'=>2,'msg'=>'登录账号错误'));
    		die;
    	}

    	$result = D('users')->where('email="'.$name.'"')->find();
    	if(empty($result)){
    		echo json_encode(array('flog'=>3,'msg'=>'账号错误'));
    		die;
    	}

    	if($pass!=$result['password']){
			echo json_encode(array('flog'=>4,'msg'=>'密码错误'));
    		die;
    	}

    	$login = array(
    			'uid'=>$result['id'],
    			'email'=>$result['email'],
    			'username'=>$result['username'],
    		);


    	session('login', $login);        

		echo json_encode(array('flog'=>1,'msg'=>'登录成功'));
		die;
    }
}