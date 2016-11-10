<?php
/**
 * 分数管理 . 用户的排名, 等级
 */
namespace Admin\Controller;

class ScoreController extends BaseController
{


    protected $scoreModel;

    public function __construct()
    {
        parent::__construct();
        $this->scoreModel = D('score');
    }

    /**
     *   成绩汇总
     */
    public function index()
    {
        $map = I();

        // 分页
        $count = $this->scoreModel
            ->join('__USERS__ ON __SCORE__.user_id = __USERS__.id')
            ->where($this->serach($map))
            ->count();
        $Page = new \Think\Page($count, C('PAGE'));// 实例化分页类 传入总记录数和每页显示的记录数(25)
        if ($this->serach($map)) {

            foreach ($this->serach($map) as $key => $val) {
                $Page->parameter[$key] = urlencode($val);
            }
        }
        $show = $Page->show();
        $scoreRes = $this->scoreModel
            ->where($this->serach($map))
            ->join('__USERS__ ON __SCORE__.user_id = __USERS__.id')
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->field('6hc_users.username,6hc_score.*,6hc_score.reword_periods/6hc_score.part_periods as win,  (@i:=@i + 1) AS `index`')
            ->order($this->order($map['most']));
        $scoreRes = $scoreRes->select();

        foreach ($scoreRes as $key => $val) {

            $rank[$key]['id'] = $val['id'];
            $rank[$key]['rank'] = $val['reword_periods'] / $val['part_periods'];
        }
        foreach ($rank as $key => $row) {
            $volume['$key'] = $row['rank'];
        }
        array_multisort($volume, SORT_DESC, $rank);

//        $ranking = $this->scoreModel->query("SELECT (@i:=@i + 1) AS `index`,6hc_score.* FROM `6hc_score`, (SELECT @i:=0) as it ORDER (6hc_score.reword_periods/6hc_score.part_periods) desc");

//        var_dump($this->scoreModel->_sql());
//        var_dump($scoreRes);
        $this->assign('scoreRes', $scoreRes);// 赋值数据集
        $this->assign('page', $show);// 赋值分页输出
        $this->assign('map', $map);
        $this->display();
    }

    /**搜素
     * @param $map
     * @return mixed
     */
    public function  serach($map)
    {

        if (!empty($map['stand'])) {
            $where['standing'] = array('eq', $map['stand']);
        }
        if (!empty($map['username'])) {
            $where['6hc_users.username'] = array('like', '%' . $map['username'] . '%');
        }

        return $where;
    }

    /**
     * 排序
     * @param $most
     * @return string
     */
    public function  order($most)
    {

        switch ($most) {

            case '0':
                $orderFiled = 'part_periods desc';
                break;
            case '1':
                $orderFiled = 'reword_periods';
                break;
            case '2':
                $orderFiled = '(reword_periods/part_periods) desc';
                break;
            default:
                $orderFiled = 'created_at desc';
        }
        return $orderFiled;

    }

    public function  add()
    {

    }

    public function  doadd()
    {

    }

    public function  disabled()
    {

    }

    public function edit()
    {

    }

    public function doEdit()
    {

    }

    public function  delete()
    {

    }
}