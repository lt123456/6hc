<?php
/**
 *  后台用户管理
 */
namespace Admin\Controller;

class PowerController extends BaseController
{

    protected $ruleModel;
    protected $groupModel;

    public function  __construct()
    {
        parent::__construct();
        $this->ruleModel = M('think_auth_rule');
        $this->groupModel = M('think_auth_group');
    }

    /**
     * 后台管理列表
     */
    public function index()
    {
        // 视图
        $this->display();
    }

    /**
     *  规则
     */
    public function rule()
    {

        $rules = $this->ruleModel->order('id desc')->select();
        $count = $this->ruleModel->count();
        $this->assign('rules', $rules);
        $this->assign('count', $count);
        $this->display();
    }


    /**
     *  添加 规则 视图
     */
    public function  ruleAdd()
    {
        $this->display();
    }

    /**
     *  添加规则
     */
    public function  doRuleAdd()
    {
        if (IS_POST) {

            $data = I();
            $data['created_at'] = date('Y-m-d h:i:s', time());

            $this->vaildRuleField($data);
            $res = $this->ruleModel->add($data);

            if ($res) {
                $this->ajaxReturn(array('status' => 'ok'));
            } else {
                $this->ajaxReturn(array('status' => 'error', 'message' => '发生异常稍后再试'));
            }
        }
    }

    /**
     * @param $data
     */
    public function vaildRuleField($data)
    {
        if (!isset($data['titles'])) {
            $this->ajaxReturn(array('status' => 'error', 'message' => '请输入标题'));
        }
        $nameArray = explode('/', $data['name']);
        if (count($nameArray) != 3) {
            $this->ajaxReturn(array('status' => 'error', 'message' => '请输入正确的规则字段'));
        }
    }

    /**
     * 规则 视图
     */
    public function  ruleEdit()
    {
        $obj = $this->ruleModel->find(I('get.id'));

        if ($obj) {
            $this->assign('obj', $obj);
            $this->display();
        } else {
            $this->error('权限规则不存在', '/Admin/Power/Rule', 3);
        }
    }

    /**
     *  处理编辑规则
     */
    public function doRuleEdit()
    {
        if (IS_POST) {
            $data = I();
            $this->vaildRuleField($data);
            $res = $this->ruleModel->save($data);
            if ($res) {
                $this->ajaxReturn(array('status' => 'ok'));
            } else {
                $this->ajaxReturn(array('status' => 'error'));
            }
        }
    }

    public function  deleteRule()
    {
        $data = I();
        $res = $this->ruleModel->where('id=' . $data['id'])->delete();
        if ($res) {
            $this->ajaxReturn(array('status' => 'ok'));
        } else {
            $this->ajaxReturn(array('status' => 'error'));
        }


    }

    /***
     *  角色 展示
     */
    public function group()
    {
        $groups = $this->groupModel->order('id desc')->select();
        $count = $this->groupModel->count();
        $this->assign('groups', $groups);
        $this->assign('count', $count);
        $this->display();
    }

    /**
     *  添加视图
     */
    public function  groupAdd()
    {
        $rules = $this->ruleModel->field('id,titles')->select();
        $this->assign('rules', $rules);
        $this->display();
    }

    /**
     * @param $data
     */
    public function vaildGroupField($data)
    {
        if (!isset($data['title'])) {
            $this->ajaxReturn(array('status' => 'error', 'message' => '请输入标题'));
        }

        if (count($data['rules']) < 2) {
            $this->ajaxReturn(array('status' => 'error', 'message' => '请输入正确的规则字段'));
        }
        $data['rules'] = implode(',', $data['rules']);

        return $data;
    }

    /*
     *添加组管理
     */
    public function doGroupAdd()
    {
        if (IS_POST) {
            $data = I();

            $data = $this->vaildGroupField($data);
            $res = $this->groupModel->add($data);

            if ($res) {
                $this->ajaxReturn(array('status' => 'ok'));
            } else {
                $this->ajaxReturn(array('status' => 'error', 'message' => '发生异常稍后再试'));
            }
        }
    }

    /**
     *  编辑组
     */
    public function groupEdit()
    {
        $obj = $this->groupModel->find(I('get.id'));

        if ($obj) {

            $rules = $this->ruleModel->field('id,titles')->select();
            $this->assign('rules', $rules);
            $this->assign('obj', $obj);
            $this->display();
        } else {
            $this->error('该管理组不存在', '/Admin/Power/group', 3);
        }
    }

    public function doGroupEdit()
    {
        if (IS_POST) {
            $data = I();
            $data = $this->vaildGroupField($data);
            $res = $this->groupModel->save($data);

            if ($res) {
                $this->ajaxReturn(array('status' => 'ok'));
            } else {
                $this->ajaxReturn(array('status' => 'error'));
            }
        }

    }

    public function  deleteGroup()
    {
        $data = I();

        $res = D('admin')->where('role =' . $data['id'])->select();

        if (!empty($res)) {
            $this->ajaxReturn(array('status' => 'error', 'message' => '请先更改使用该组的后台用户角色'));
        }
        $res = $this->groupModel->where('id=' . $data['id'])->delete();

        if ($res) {
            $this->ajaxReturn(array('status' => 'ok'));
        } else {
            $this->ajaxReturn(array('status' => 'error', 'message' => '服务器错误请稍后再试'));
        }


    }

    public function  checkName()
    {

        $info = $this->ruleModel->where(I())->find();

        if (empty($info)) {
            $this->ajaxReturn(array('ok' => '验证通过'));
        } else {
            $this->ajaxReturn(array('error' => '该邮箱已被使用'));
        }

    }
}