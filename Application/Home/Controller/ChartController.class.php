<?php
namespace Home\Controller;

//use Think\Controller;

class ChartController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function  index()
    {
        $this->display();
    }
}
