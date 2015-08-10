<?php
class MY_Controller extends CI_Controller{
		
	//public $data=array();
	function __construct(){
		parent::__construct();
		$data['errors']=array();
		// $data['site_name']=config_item('site_name');
	}
}
