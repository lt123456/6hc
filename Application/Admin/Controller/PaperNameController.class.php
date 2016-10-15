<?php
/**
 * 图库名称管理
 */
namespace Admin\Controller;

class PaperNameController extends BaseController {

    public function index()
    {
        $papers = D('paper_view')->select();

        foreach($papers as $key=>$paper){
            $list = D('paper_list')->where('id='.$paper['pic_name_id'])->find();
            $papers[$key]['paper_name']=$list['name'];
            $papers[$key]['spell']=$list['spell'];
            $papers[$key]['is_hide']=$list['is_hide'];
            $admin = D('admin')->where('id='.$paper['admin_id'])->find();
            $papers[$key]['admin_name'] = $admin['username'];
        }

        //分配
        $this->assign('papers',$papers);

        $this->display();
    }

    public function  add()
    {
        // 获取
        $papers = D('paper_list')->where('is_hide=0')->order('id desc')->select();

        //分配
        $this->assign('papers',$papers);

        $this->display();
    }

    public function  doadd()
    {
        $data=I();

        $data['admin_id'] = session('admin_id');

        if(!empty($_FILES)){
            $data['origin'] = $this->up();
        }

        $data['created_at'] = date('Y-m-d H:i:s',time());

        $res  = D('paper_view')->add($data);

        if($res){
            $this->success('添加成功','./index',3);
        }else{
            $this->error('添加失败','./add',3);
        }
    }

    public function  disabled()
    {

    }

    public function edit()
    {
        $id=I('id');
        
        $paper=D('paper_view')->where('id='.$id)->find();

        // 获取
        $papers = D('paper_list')->order('id desc')->select();


        //分配
        $this->assign('papers',$papers);
        $this->assign('paper',$paper);
        $this->assign('id',$id);

        $this->display();
    }

    public function doEdit()
    {

        $data=I();

        $id= $data['id'];

        $data['admin_id'] = session('admin_id');
        
        if($_FILES['origin']['error']!=4){
            $data['origin'] = $this->up();
        }

        $data['updated_at'] = date('Y-m-d H:i:s',time());
        // var_dump($data);die;
        $res = D('paper_view')->where('id='.$id)->save($data);

        if($res){
            $this->success('修改成功','./index',3);
        }else{
            $this->error('修改失败','./index',3);
        }
    }

    public function  delete()
    {
        $id=I('id');
        $res = D('paper_view')->where('id='.$id)->delete();

        if($res) {
            $this->ajaxReturn(['status'=>'ok']);
        }else{
            $this->ajaxReturn(['status'=>'error']);
        }
    }


    private function up(){
        //完成与thinkphp相关的，文件上传类的调用  
        import('@.Org.UploadFile');//将上传类UploadFile.class.php拷到Lib/Org文件夹下

        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =     './Public/Upload/'; // 设置附件上传根目录
        $upload->savePath  =     ''; // 设置附件上传（子）目录

        // 上传文件 
        $info   =   $upload->upload();


        //生成缩略图
        // $img = new \Think\Image();
        // $img->open($path);
        // $a=$img->thumb(10,10)->save($path_mini);


        if(!$info){// 上传错误提示错误信息
            $this->error($upload->getError());
        }else{// 上传成功
            return $upload->rootPath.$info['origin']['savepath'].$info['origin']['savename'];
        }
    }
}