<?php
/**
 *  前台用户管理
 */
namespace Admin\Controller;

class MemberController extends BaseController {

    protected $MemberModel;
    public function __construct()
    {
        parent::__construct();
        $this->memberModel = D('users');
    }
    public function index()
    {

        $map =I();

        $count = $this ->memberModel
                       ->where($this->serach($map))
                       ->count();
        $Page  = new \Think\Page($count,C('PAGE'));// 实例化分页类 传入总记录数和每页显示的记录数(25)
        if($this->serach($map)){

            foreach($this->serach($map) as $key=>$val) {
                $Page->parameter[$key]   =   urlencode($val);
            }
        }
        $show  = $Page->show();
          // 获取
        $members =$this->memberModel
                       -> where($this->serach($map))
                      ->order('id desc')
                      ->select();
        //分配
        $this->assign('members',$members);
        $this->assign('page',$show);// 赋值分页输出
        $this->assign('map',$map);
        $this->display();
    }

     public function  serach($map)
    {

        if(!empty($map['role'])){
            $where['role'] = array('eq',$map['role']);
        }
        if(!empty($map['status'])){
            $where['status'] = array('eq',$map['status']);
        }
        if(!empty($map['username'])){
            $where['username'] = array('like','%'.$map['username'].'%');
        }

        return $where;
    }

    public function  add()
    {
        $this->display();

    }

    public function  doadd()
    {
        if(IS_POST){

            $data =  I();
            $data['created_at'] = date('Y-m-d h:i:s',time());
            $data['status'] ='active';
            $data['password'] = md5($data['pwd']);
            $data['phone'] = '1888'.rand(10000,99999);
            $res  = $this->memberModel->add($data);

            if($res) {
               $this->ajaxReturn(array('status'=>'ok'));
            }else {
                $this->ajaxReturn(array('status'=>'error','message'=>'发生异常稍后再试'));
            }
        }
    }

    public function  disabled()
    {

    }

    public function edit()
    {

          $obj =    $this->memberModel->find(I('get.id'));

          if($obj) {
              $this->assign('obj',$obj);
              $this->display();
          }else{
              $this->error('该用户账户不存在','/Admin/Member',3);
          }
    }

    public function doEdit()
    {
        if(IS_POST) {
            $data = I();

            $res = $this->memberModel->save($data);

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
        $res = $this->memberModel->where('id='.$data['id'])->delete();
        if($res) {
            $this->ajaxReturn(array('status'=>'ok'));
        }else{
            $this->ajaxReturn(array('status'=>'error'));
        }


    }
    public function  checkEmail()
    {
        
        $info  = $this->memberModel->where(I())->find();

        if(empty($info)){
            $this->ajaxReturn(array('ok'=>'验证通过'));
        }else{
            $this->ajaxReturn(array('error'=>'该邮箱已被使用'));
        }

    }
}