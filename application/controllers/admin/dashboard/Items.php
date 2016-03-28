<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Items extends Dash_backend{
	
	function __construct(){
		parent::__construct();
	}
	
//----------------------------------------------------------------------------------------------------------------------------
//Add, remove, view and edit items for sale
//Requires paypal or other sales intergration
//
//----------------------------------------------------------------------------------------------------------------------------
	public function index(){
		$this->load->model('Admin_model');
		$data=$this->commonHeader();
		$data['currentLocation']="<div class='navbar-brand'>Product Dashboard</div>";
		
		// $data['recaptcha']=$this->config->item('recaptcha-site-key');
		// if ($this->Login_model->loggedin() == TRUE){
			// redirect($dashboard);
		// }
		
		// $this->load->library('curl'); 
		
		$this->load->model("Vendor_model");
		// $this->load->library('curl');
		
		$rules=$this->Vendor_model->rules;
		$this->form_validation->set_rules($rules);
		if($this->form_validation->run() == TRUE){
			//Login
			
			
		}
		
		
		$this->load->view('templates/header', $data);
		$this->load->view('inc/dash_header', $data);
		$this->load->view('dash/errorInfo');
		$this->load->view('inc/dash_footer', $data);
		$this->load->view('templates/footer', $data);
	}
	
}