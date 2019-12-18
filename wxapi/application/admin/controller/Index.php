<?php
namespace app\admin\controller;
use app\admin\Controller\Common;

class Index extends Common{

	public function index(){
        
		return $this->fetch('index');
	}
	

}

