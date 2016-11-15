<?php
/**
 * 回帖贴管理
 */
namespace Admin\Controller;

class ReplayTieController extends BaseController
{

    protected $discussHuifuModel;

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
        $count  = D('discuss_huifu')->join('__USERS__ ON __DISCUSS_HUIFU__.user_id = __USERS__.id')->where($this->serach($map))->count();
        $page = new  \Think\Page($count, 2);
        if ($this->serach($map)) {

            foreach ($this->serach($map) as $key => $val) {
                $Page->parameter[$key] = urlencode($val);
            }
        }
        // 获取
        $discussReplayTies = D('discuss_huifu')->join('__USERS__ ON __DISCUSS_HUIFU__.user_id = __USERS__.id')
                                                ->join('__DISCUSS_ZHUTIE__ ON __DISCUSS_HUIFU__.zhutie_id = __DISCUSS_ZHUTIE__.id')
                                                ->field('6hc_discuss_zhutie.title as ztitle,6hc_users.username,6hc_discuss_huifu.*,concat(path,",",6hc_discuss_huifu.id) as paths')
                                                ->order('paths')
                                                ->where($this->serach($map))
                                                // ->limit($page->firstRow.','.$page->listRows)
                                                ->select();
        $show = $page->show();
        // var_dump($discussReplayTies);die;
        foreach($discussReplayTies as $k=>$v){
            $array  = explode(',',$v['path']);
            //获得当前逗号的个数
            $len = count(explode(',', $v['path']))-1;

            if ($len && is_array($array)) {

                foreach($array as $key=>$val){
                     $content = D('discuss_huifu')->find($val);
                     // var_dump($content);die;
                      $discussReplayTies[$k]['reply_text'][$val] = str_repeat('|------', $len).$content['content'];
                }
        
           }
        }
        // var_dump($discussReplayTies);die;
        //分配
        $this->assign('page', $show);
        $this->assign('discussReplayTies', $discussReplayTies);
        $this->assign('count', $count);

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

    public function edit()
    {
        $obj =    $this->discussHuifuModel->find(I('get.id'));

          if($obj) {
              $this->assign('obj',$obj);
              $this->assign('discussReplayTies',$discussReplayTies);
              $this->display();
          }else{
              $this->error('该回复不存在','/Admin/Zhutie',3);
          }
    }

    public function doEdit()
    {
        if(IS_POST) {
            $data = I();

            $res = $this->discussHuifuModel->save($data);

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
        $res = $this->discussHuifuModel->where('id=' . $data['id'])->delete();

        if ($res) {
            $this->ajaxReturn(array('status' => 'ok'));
        } else {
            $this->ajaxReturn(array('status' => 'error'));
        }
    }

    //查询条件
    public function  serach($map)
    {
        if (isset($map['is_display'])) {
            $where['6hc_discuss_huifu.is_display'] = array('eq', $map['is_display']);
        }
        if (isset($map['type'])) {
            if ($map['type'] == 'title' && isset($map['scontent'])) {
                $where['title'] = array('like', '%' . $map['scontent'] . '%');
            }

            if($map['type'] == 'user_id' && isset($map['scontent'])){
                $where['6hc_users.username'] = array('like','%'.$map['scontent'].'%');
            }
            if ($map['type'] == 'content' && isset($map['scontent'])) {
                $where['content'] = array('like', '%' . $map['scontent'] . '%');
            }
        }
       
        return $where;
    }
}