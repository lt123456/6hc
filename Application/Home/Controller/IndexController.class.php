<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
      	
      	$id = I('get.id');

      		var_dump($id);
      	if($id ==1){

      		$this->theme('mobile')->display('index');
      	}else{
      		$this->theme('pc')->display();
      	}

    }
}