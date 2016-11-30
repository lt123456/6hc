<?php
namespace Home\Controller;

//use Think\Controller;

class MemberController extends BaseController
{

    protected  $id;
    public function  __construct()
    {
        parent::__construct();
        if(empty($_SESSION['login'])) {
            Header('Location: /');
        }
        $this->id = $_SESSION['login']['uid'];
    }

    public function index()
    {
        $this->display();
    }

    public function info()
    {
        $this->display();
    }

    public function updateinfo()
    {

    }

    public function passsword()
    {
        $this->display();
    }

    public function updatepasssword()
    {

    }

    public function phone()
    {
        $this->display();
    }

    public function updatephone()
    {

    }

    public function email()
    {
        $this->display();
    }

    public function updateemail()
    {

    }


}