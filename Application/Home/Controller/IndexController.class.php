<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
      	
        echo '电脑';die;
      	if($id ==1){

      		$this->theme('mobile')->display();
      	}else{
      		$this->theme('pc')->display();
      	}

    }
}