<?php
/**
 * 幽默猜测 (视频 和 图库两种)
 */
namespace Admin\Controller;

class JokeController extends BaseController {
    public function index()
    {
        $joke=D('joke')->select();

        foreach($joke as $key=>$val){
            $admin = D('admin')->where('id='.$val['admin_id'])->find();
            $joke[$key]['admin_name'] = $admin['username'];
        }

        $this->assign('joke',$joke);

        $this->display();

    }

    public function  add()
    {
        $this->display();
    }

    public function  doadd()
    {
        $data=I();
 
        $data['admin_id'] = session('admin_id');

        if($_FILES['pic']['error']!=4){
            $data['pic'] = $this->up();
        }

        $data['created_at'] = date('Y-m-d H:i:s',time());

        $res  = D('joke')->add($data);

        if($res){
            $this->success('添加成功','./index',3);
        }else{
            $this->error('添加失败','./add',3);
        }
    }

    public function  disabled()
    {

        if(IS_POST){
            $status =  I('status');
            $id =  I('id');

            $save = array(
                    'status'=>$status,
                );

            $res = D('joke')->where('id='.$id)->save($save);

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
        
        $joke=D('joke')->where('id='.$id)->find();


        //分配
        $this->assign('joke',$joke);
        $this->assign('id',$id);

        $this->display();

    }

    public function doEdit()
    {

        $data=I();

        $id= $data['id'];

        $data['admin_id'] = session('admin_id');
        
        if($_FILES['pic']['error']!=4){
            $data['pic'] = $this->up();
        }

        $data['updated_at'] = date('Y-m-d H:i:s',time());
       
        
        $res = D('joke')->where('id='.$id)->save($data);

        if($res){
            $this->success('修改成功','./index',3);
        }else{
            $this->error('修改失败','./index',3);
        }

    }

    public function  delete()
    {
        $id=I('id');
        $res = D('joke')->where('id='.$id)->delete();

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
        $upload->rootPath  =     './Public/Upload/big/'; // 设置附件上传根目录
        $upload->savePath  =     ''; // 设置附件上传（子）目录
        $dir = './Public/Upload/big/';
        $mini_dir = './Public/Upload/mini/';

        //创建上传文件目录
        if(!is_dir($dir)){
            mkdir($dir);
        }

        // 上传文件 
        $info   =   $upload->upload();

        //创建缩略图文件目录
        if(!is_dir($mini_dir.$info['pic']['savepath'])){
            mkdir($mini_dir.$info['pic']['savepath']);
        }

        //生成缩略图
        $image  = new \Think\Image();
        $image->open($dir.$info['pic']['savepath'].$info['pic']['savename']);
        $image->thumb(100, 100)->save($mini_dir.$info['pic']['savepath'].$info['pic']['savename']);

        if(!$info){// 上传错误提示错误信息
            $this->error($upload->getError());
        }else{// 上传成功

            $json = array();
            $json['big'] = $dir.$info['pic']['savepath'].$info['pic']['savename'];
            $json['mini'] = $mini_dir.$info['pic']['savepath'].$info['pic']['savename'];

            return json_encode($json);
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
            return $upload->rootPath.$info['pic']['savepath'].$info['pic']['savename'];
        }
    }
}