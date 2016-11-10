<?php
/**
 * 贴吧 版块管理
 */
namespace Admin\Controller;

class DiscussCategoryController extends BaseController
{

    protected $discussCategoryModel;

    public function  __construct()
    {
        parent::__construct();
        $this->discussCategoryModel = M('discuss_category');
    }

    public function index()
    {
        $count = D('discuss_category')->count();

        // 获取
        $discussCategorys = D('discuss_category')->order('id desc')->limit()->select();


        //分配
        $this->assign('discussCategorys', $discussCategorys);
        $this->assign('count', $count);

        $this->display();
    }

    public function  add()
    {
        $this->display();
    }

    public function  doadd()
    {
        if (IS_POST) {

            $data = I();

            $data['created_at'] = date('Y-m-d H:i:s', time());
            $res = D('discuss_category')->add($data);

            if ($res) {
                $this->ajaxReturn(array('status' => 'ok'));
            } else {
                $this->ajaxReturn(array('status' => 'error', 'message' => '发生异常稍后再试'));
            }
        }

    }

    public function  disabled()
    {

    }

    public function edit()
    {
        $obj = $this->discussCategoryModel->find(I('get.id'));

        if ($obj) {
            $this->assign('obj', $obj);
            $this->display();
        } else {
            $this->error('该板块不存在', '/Admin/DiscussCategory', 3);
        }
    }

    public function doEdit()
    {

        if (IS_POST) {
            $data = I();

            $res = $this->discussCategoryModel->save($data);

            if ($res) {
                $this->ajaxReturn(array('status' => 'ok'));
            } else {
                $this->ajaxReturn(array('status' => 'error'));
            }
        }
    }

    public function  delete()
    {
        $data = I();
        $zhutie = M('discuss_zhutie');
        $count = $zhutie->where('category_id=' . $data['id'])->count();
        $panduan = ($count == '0') ? '1' : '0';
        if ($panduan == '1') {

            $res = $this->discussCategoryModel->where('id=' . $data['id'])->delete();

            if ($res) {
                $this->ajaxReturn(array('status' => 'ok'));
            } else {
                $this->ajaxReturn(array('status' => 'error'));
            }
        } else {
            $this->ajaxReturn(array('status' => 'error1'));
        }
    }
}