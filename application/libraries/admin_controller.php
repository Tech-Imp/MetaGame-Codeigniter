<?php
class Admin_controller extends MY_Controller{
		
	public $logoutPATH='login/logout';
	
	function __construct(){
		parent::__construct();	
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('session');
		$this->load->model('Login_model');
		
		
		
		$excludeLoc=array(
			'login',
			'login/logout'
		);
		if(in_array(uri_string(), $excludeLoc)==FALSE){
			if($this->Login_model->loggedin()==FALSE){
				redirect('login');
			}
		}
		
	}	
	
}
