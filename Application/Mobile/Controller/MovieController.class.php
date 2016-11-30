<?php
namespace Home\Controller;

//use Think\Controller;

class MovieController extends BaseController
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
