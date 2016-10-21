<?php
namespace Mobile\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
      	
        echo '手机';die;
      	if($id ==1){

      		$this->theme('mobile')->display();
      	}else{
      		$this->theme('pc')->display();
      	}

    }
}