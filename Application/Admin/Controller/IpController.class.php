<?php
/**
 * Ip管理
 */
namespace Admin\Controller;

class IpController extends BaseController {

    protected  $ipModel;
    public function  __construct()
    {
        parent::__construct();
        $this->ipModel = D('ip');
    }

    /**
     *  展示 禁用的ip 地址
     */
    public function index()
    {
        $ips = $this->ipModel->order('id desc')->select();
        $count  = $this->ipModel->count();
        $this->assign('ips',$ips);
        $this->assign('count',$count);
        $this->display();
    }

    /**
     *  添加 ip 视图
     */
    public function  add()
    {
        $this->display();
    }

    /**
     *  添加ip
     */
    public function  doAdd()
    {
        if(IS_POST){

            $data =  I();
            $data['created_at'] = date('Y-m-d h:i:s',time());

            $this->vaildRuleField($data);
            $res  =$this->ipModel->add($data);

            if($res) {
                $this->ajaxReturn(array('status'=>'ok'));
            }else{
                $this->ajaxReturn(array('status'=>'error','message'=>'发生异常稍后再试'));
            }
        }
    }

    /**
     * @param $data
     */
    public function vaildRuleField($data)
    {
        $ips = explode('.',$data['ip']);
        if(count($ips) != 4 ){
            $this->ajaxReturn(array('status'=>'error','message'=>'请输入正确格式ip地址'));
        }
    }

    /**
     * ip 视图
     */
    public function  edit()
    {
        $obj =    $this->ipModel->find(I('get.id'));

        if($obj) {
            $this->assign('obj',$obj);
            $this->display();
        }else{
            $this->error('禁用ip不存在','/Admin/Ip/index',3);
        }
    }

    /**
     *  处理ip
     */
    public function doEdit()
    {
        if(IS_POST) {
            $data = I();
            $this->vaildRuleField($data);
            $res = $this->ipModel->save($data);
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
        $res = $this->ipModel->where('id='.$data['id'])->delete();
        if($res) {
            $this->ajaxReturn(array('status'=>'ok'));
        }else{
            $this->ajaxReturn(array('status'=>'error'));
        }


    }

}