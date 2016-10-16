<?php
/**
 * 图库管理
 */
namespace Admin\Controller;

class PaperListController extends BaseController {

    //图库列表
    public function index()
    {
        // 获取
        $papers = D('paper_list')->order('id desc')->select();

        //分配
        $this->assign('papers',$papers);

        $this->display();
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
            $data['name'] = $data['name'];
            $data['spell'] = $data['spell'];
            $data['is_del'] = $data['is_del'];

            $res  = D('paper_list')->add($data);

            if($res) {
               $this->ajaxReturn(['status'=>'ok']);
            }else{
                $this->ajaxReturn(['status'=>'error','message'=>'发生异常稍后再试']);
            }
        }
    }

    public function  disabled()
    {
        if(IS_POST){
            $is_hide =  I('is_hide');
            $id =  I('id');

            $save = array(
                    'is_hide'=>$is_hide,
                );

            $res = D('paper_list')->where('id='.$id)->save($save);

            if($res) {
               $this->ajaxReturn(['status'=>'ok']);
            }else{
                $this->ajaxReturn(['status'=>'error','message'=>'发生异常稍后再试']);
            }
        }
    }

    public function edit()
    {
        $id=I('id');

        $paper=D('paper_list')->where('id='.$id)->find();

        //分配
        $this->assign('paper',$paper);
        $this->assign('id',$id);

        $this->display();
    }

    public function doEdit()
    {
        if(IS_POST){
            $is_hide =  I('is_hide');
            $name =  I('name');
            $spell =  I('spell');
            $id =  I('id');

            $save = array(
                    'is_hide'=>$is_hide,
                    'spell'=>$spell,
                    'name'=>$name,
                    'updated_at'=>date('Y-m-d H:i:s',time()),
                );

            $res = D('paper_list')->where('id='.$id)->save($save);

            if($res) {
               $this->ajaxReturn(['status'=>'ok']);
            }else{
                $this->ajaxReturn(['status'=>'error','message'=>'发生异常稍后再试']);
            }
        }

    }

    public function  delete()
    {
        $id=I('id');
        $res = D('paper_list')->where('id='.$id)->delete();

        if($res) {
            $this->ajaxReturn(['status'=>'ok']);
        }else{
            $this->ajaxReturn(['status'=>'error']);
        }
    }

    public function  checkName()
    {
        $Paper =  D('paper_list');

        $info  = $Paper->where(I())->find();
        if(empty($info)){
            $this->ajaxReturn(['ok'=>'验证通过']);
        }else{
            $this->ajaxReturn(['error'=>'该名称已存在']);
        }

    }
}