<?php
/**
 *  后台用户管理
 */
namespace Admin\Controller;

class AgentController extends BaseController {

    protected  $adminModel;
    protected  $groupModel;
    protected  $accessModel;
    public function  __construct()
    {
        parent::__construct();
        $this->adminModel = M('admin');
        $this->groupModel = M('think_auth_group');
        $this->accessModel = M('think_auth_group_access');
    }
    /**
     * 后台管理列表
     */
    public function index()
    {
        // 获取
        $admins = D('admin')->order('id desc')
                            ->join('__THINK_AUTH_GROUP__ ON __ADMIN__.role = __THINK_AUTH_GROUP__.id')
                            ->field('6hc_think_auth_group.title,6hc_admin.*')
                            ->select();
        $count  = D('admin')->join('__THINK_AUTH_GROUP__ ON __ADMIN__.role = __THINK_AUTH_GROUP__.id')->count();
        $disableCount  = D('admin')->where('status != "active"')->count();

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
        $roles = $this->groupModel->field('id,title')->select();
        $this->assign('roles',$roles);
        $this->display();
    }

    public function  doAdd()
    {
        if(IS_POST){

            $data =  I();
            $data['created_at'] = date('Y-m-d h:i:s',time());
            $data['status'] ='active';
            $data['password'] = md5($data['pwd']);
            $data['phone'] = '1232'.rand(10000,99999);
            $res  = D('admin')->add($data);

            if($res) {
                $this->accessModel->add(array('uid'=>$res,'group_id'=>$data['role']));
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
          $obj =    $this->adminModel->find(I('get.id'));

          if($obj) {
              $roles = $this->groupModel->field('id,title')->select();
              $this->assign('roles',$roles);
              $this->assign('obj',$obj);
              $this->display();
          }else{
              $this->error('该管理员账户不存在','/Admin/Agent',3);
          }
    }

    public function doEdit()
    {
        if(IS_POST) {
            $data = I();

            $res = $this->adminModel->save($data);
            $access =  $this->accessModel->where("uid = ".$data['id'])->save(array('group_id'=>$data['role']));

            if($res || $access){
                $this->ajaxReturn(array('status'=>'ok'));
            }else{
                $this->ajaxReturn(array('status'=>'error'));
            }
        }

    }

    public function  delete()
    {
        $data = I();
        $res = $this->adminModel->where('id='.$data['id'])->delete();
        $access=  $this->accessModel->where("uid = ".$data['id'])->delete();
        if($res && $access) {
            $this->ajaxReturn(array('status'=>'ok'));
        }else{
            $this->ajaxReturn(array('status'=>'error'));
        }


    }

    public function  checkEmail()
    {
        $Admin =  D('admin');

        $info  = $Admin->where(I())->find();

        if(empty($info)){
            $this->ajaxReturn(array('ok'=>'验证通过'));
        }else{
            $this->ajaxReturn(array('error'=>'该邮箱已被使用'));
        }

    }
}