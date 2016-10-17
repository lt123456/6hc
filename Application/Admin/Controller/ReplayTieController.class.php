<?php
/**
 * 回帖贴管理
 */
namespace Admin\Controller;

class ReplayTieController extends BaseController {

    protected  $discussHuifuModel;

    public function  __construct()
    {
        parent::__construct();
        $this->discussHuifuModel = M('discuss_huifu');
    }

    public function index()
    {
        //获取用户查询信息
        $map =I();
        // var_dump(I());die;
        $count  = D('discuss_huifu')->where($this->serach($map))->count();
        $page = new  \Think\Page($count, 2);
        if($this->serach($map)){

            foreach($this->serach($map) as $key=>$val) {
                $Page->parameter[$key]   =   urlencode($val);
            }
        }
        // 获取
        $discussReplayTies = D('discuss_huifu')->join('__USERS__ ON __DISCUSS_HUIFU__.user_id = __USERS__.id')
                                                ->join('__DISCUSS_ZHUTIE__ ON __DISCUSS_HUIFU__.zhutie_id = __DISCUSS_ZHUTIE__.id')
                                                ->field('6hc_discuss_zhutie.title as ztitle,6hc_users.username,6hc_discuss_huifu.*')
                                                ->order('id desc')
                                                ->where($this->serach($map))
                                                ->limit($page->firstRow.','.$page->listRows)
                                                ->select();
        $show = $page->show();
        foreach($discussReplayTies as $k=>$v){
            //获得当前逗号的个数
            $len = count(explode(',', $v['path']))-1;
            //生成相应的内容
            $path = explode(',', $v['path']);
            foreach($path as $key => $val){
                $key = $key+1;
                $paths .= '前'.$key.'个回复为id为'.$val.'的回复<br>';
            }
            // var_dump($discussReplayTies[$k]['path']);die;
            //  |-------
            $discussReplayTies[$k]['path'] = $paths;
            unset($paths);
        }
        //分配
        $this->assign('page',$show);
        $this->assign('discussReplayTies',$discussReplayTies);
        $this->assign('count',$count);

        $this->display();
    }

    //同帖子不做添加
    // public function  add()
    // {

    // }

    // public function  doadd()
    // {

    // }

    public function  disabled()
    {

    }

    //不允许编辑
    // public function edit()
    // {

    // }

    // public function doEdit()
    // {

    // }

    public function  delete()
    {
        $data = I();
        $res = $this->discussHuifuModel->where('id='.$data['id'])->delete();

        if($res) {
            $this->ajaxReturn(array('status'=>'ok'));
        }else{
            $this->ajaxReturn(array('status'=>'error'));
        }
    }

     //查询条件
    public function  serach($map)
    {
        if(isset($map['is_display'])){
            $where['6hc_discuss_huifu.is_display'] = array('eq',$map['is_display']);
        }
        if(isset($map['type'])){
            if($map['type'] == 'title' && isset($map['scontent'])){
                 $where['title'] = array('like','%'.$map['scontent'].'%');
            }
            if($map['type'] == 'user_id' && isset($map['scontent'])){
                $where['username'] = array('like','%'.$map['scontent'].'%');
            }
            if($map['type'] == 'content' && isset($map['scontent'])){
                $where['content'] = array('like','%'.$map['scontent'].'%');
            }
        }
        return $where;
    }
}