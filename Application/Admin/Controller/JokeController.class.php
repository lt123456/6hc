<?php
/**
 * 幽默猜测 (视频 和 图库两种)
 */
namespace Admin\Controller;

class JokeController extends BaseController {

    public function index()
    {
        $joke=D('joke')->join('__ADMIN__ ON __JOKE__.admin_id = __ADMIN__.id')
                       ->join('__LOTTERY_RECORD__ ON __JOKE__.periods_id = __LOTTERY_RECORD__.id')
                       ->field('6hc_admin.username,6hc_joke.*,6hc_lottery_record.periods')
                       ->select();

        $this->assign('joke',$joke);
        $this->display();

    }

    public function  add()
    {
        $periods = D('lottery_record')->field('id,periods')->order('id desc')->select();

        $this->assign('periods',$periods);
        $this->display();
    }

    public function  doadd()
    {
        $data=I();

        $res  = D('joke')->add($this->filterDate($data));

        if($res){
            $this->success('添加成功','./index',3);
        }else{
            $this->error('添加失败','./add',3);
        }
    }

    public function  filterDate($data)
    {
        if($data['type'] =='pic')
        {
            if($_FILES['pic']['error']!=4){
                $data['pic'] = $this->up();
            }else{
                $this->error('请上传图片文件','./add',3);
            }
        }else{
            if(empty($data['movice'])){
                $this->error('请上传视屏链接','./add',3);
            }
        }
        $data['admin_id'] = session('admin_id');
        $data['created_at'] = date('Y-m-d H:i:s',time());
        $data['updated_at'] = date('Y-m-d H:i:s',time());

        return $data;
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

        if($joke) {
            //分配
            $periods = D('lottery_record')->field('id,periods')->order('id desc')->select();
            $this->assign('periods',$periods);
            $this->assign('joke',$joke);
            $this->assign('id',$id);

            $this->display();
        }else{
            $this->error('该幽默猜测不存在','Admin/Joke/index',2);
        }


    }

    public function doEdit()
    {

        $data=I();

        $res = D('joke')->save($data);
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
        $upload->rootPath  =     './Public/Upload/joke/origin/'; // 设置附件上传根目录
        $upload->savePath  =     ''; // 设置附件上传（子）目录
        $dir = './Public/Upload/joke/origin/';
        $mini_dir = './Public/Upload/joke/mini';

        //创建上传文件目录
        if(!is_dir($dir)){
            mkdir($dir,0777,true);
        }

        // 上传文件
        $info   =   $upload->upload();

        //创建缩略图文件目录
        if(!is_dir($mini_dir.$info['pic']['savepath'])){
            mkdir($mini_dir.$info['pic']['savepath'],0777,true);
        }

        //生成缩略图
        $image  = new \Think\Image();
        $image->open($dir.$info['pic']['savepath'].$info['pic']['savename']);
        $image->thumb(100, 100)->save($mini_dir.$info['pic']['savepath'].$info['pic']['savename']);

        if(!$info){// 上传错误提示错误信息
            $this->error($upload->getError());
        }else{// 上传成功

            $json = array();
            $json['big'] = trim($dir.$info['pic']['savepath'].$info['pic']['savename'],'.');
            $json['mini'] =trim($mini_dir.$info['pic']['savepath'].$info['pic']['savename'],'.');

            return json_encode($json);
        }
    }
}