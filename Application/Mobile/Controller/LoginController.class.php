<?php
namespace Home\Controller;
use Think\Controller;
use Common\Common\Verify;
class LoginController extends Controller {


    protected  $user;
    public function  __construct()
    {
        $this->user  = D('users');
    }


    /**
     * @param string $captcha
     */
    public function verify($captcha='')
    {
        $verify = new verify();
        return $verify::verify();
    }

    /**
     * 登录
     */
    public function do_login()
    {
        $name = I('name');
        $pass = I('pass');
        $verify = I('verify');

        //验证码
        $this->checkVeify($verify);
        // 判断 登录
        $result  = $this->allowLogin($name,$pass);
        // 保存 数据 到 Session

        $sessionArr  = $this->keepSession($result);
        if($sessionArr['uid']){
            $this->ajaxReturn(array('flog'=>1,'msg'=>'登录成功'));
        }
        $this->ajaxReturn(array('flog'=>6,'msg'=>'系统异常,请重新尝试'));
    }

    /***
     * 注册
     */
    public function signup()
    {
        $name = I('name');
        $pass = I('pass');
        $verify = I('verify');
        // 验证密码
        $this->checkVeify($verify);
        // 检测是否 用户名 被使用
        $this->checkUsername($name);
        // 创建用户
        $fillable = $this->createUser($name,$pass);
        $id   = $this->user->add($fillable);
        // 保存Session 登录
        $result = $this->user->find($id);
        $sessionArr  = $this->keepSession($result);
        if($sessionArr['uid']){
            $this->ajaxReturn(array('flog'=>1,'msg'=>'登录成功'));
        }
        $this->ajaxReturn(array('flog'=>6,'msg'=>'系统异常,请重新尝试'));
    }

    /**
     * 退出
     */
    public function logout()
    {
        $_SESSION = array(); //清除SESSION值.
        if(isset($_COOKIE[session_name()])){  //判断客户端的cookie文件是否存在,存在的话将其设置为过期.
            setcookie(session_name(),'',time()-1,'/');
        }
        session_destroy();  //清除服务器的sesion文件
        $this->ajaxReturn(array('flog'=>1,'msg'=>'退出成功'));
    }

    /**
     * 登录 条件
     * @param $name
     * @param $pass
     * @return mixed
     */
    public function  allowLogin($name,$pass)
    {
        $result = $this->user->where(['username'=>$name])->find();
        if(empty($result)){
            $this->ajaxReturn(array('flog'=>3,'msg'=>'账号错误'));
        }

        if(md5(md5($pass))!=$result['password']){
            $this->ajaxReturn(array('flog'=>4,'msg'=>'密码错误'));
        }
        return $result;
    }

    /**
     * 验证码匹配
     * @param $verify
     */
    public function checkVeify($verify)
    {
        $Verify = new \Think\Verify();
        $res = $Verify->check($verify);

        if (!$res) {
            $this->ajaxReturn(array('flog'=>2,'msg'=>'验证码错误'));
        }
    }

    /**
     * 创建用户
     * @param $name
     * @param $pass
     * @return array
     */
    public function createUser($name,$pass)
    {
        $fillablle  = array(
                'username' =>  $name,
                'password' =>  md5(md5($pass)),
                'email' => $this->rand(6),
                'phone' =>  mt_rand(100000,99999999),
                'status' => 'active',
                'role'   => 'god',
                'avatar' => '/pulic/avatar/default_avatar.png',
                'token' => md5(md5($name.$pass)),
                'register_ip' => $_SERVER['REMOTE_ADDR'],
                'last_login_ip' => $_SERVER['REMOTE_ADDR'],
                'last_login_time'=> date('Y-m-d H-i-s'),
                'created_at' => date('Y-m-d H-i-s'),
                'updated_at'  => date('Y-m-d H-i-s'),
        );

        return $fillablle;
    }

    /**
     * 用户名唯一
     * @param $name
     */
    public function  checkUsername($name)
    {
        $_res =  $this->user->where(array('username'=>$name))->find();
        if(!empty($_res)) {
          $this->ajaxReturn(array('flog'=>3,'msg'=>'该用户名已注册,请更换用户名完成注册'));
        }
    }


    /**
     * 随机数生成
     * @param string $length
     * @return string
     */
    public function  rand($length="6")
    {
        $rand = '';

        for($i= 0 ; $i<= $length;$i++) {
            $rand .= chr(mt_rand(33,126));
        }
        $rand .= mt_rand(10000,99999);
        return $rand;
    }


    /**
     * 保存session
     * @param $user
     * @return mixed
     */
    protected function  keepSession($user)
    {

        $login = array(
            'uid'      => $user['id'],
            'username' => $user['username'],
            'avatar'   => $user['avatar']
        );
        session('login', $login);

        return session('login');
    }




}