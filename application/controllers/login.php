<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends admin_controller {

	function __construct(){
		parent::__construct();
	}
	
	public function index()
	{
			
		$data['css'][0]="main.css";
		$data['title']="Login";
		$data['site_name']=config_item('site_name');
		$dashboard='admin/dashboard';
		
		if ($this->Login_model->loggedin() == TRUE){
			redirect($dashboard);
		}
		
		$rules=$this->Login_model->rules;
		$this->form_validation->set_rules($rules);
		if($this->form_validation->run() == TRUE){
			//Login
			if($this->Login_model->login()==TRUE){
				redirect($dashboard);
			}
			else{
				$this->session->set_flashdata('error', 'That email/password combination does not exist');
				redirect('login', 'refresh');
			}
		}
		
		$this->load->view('templates/header', $data);
		$this->load->view('inc/login_user');
		$this->load->view('templates/footer', $data);
		
		
		// $this->load->view('default', $this->data);
	}
	public function logout()
	{
		$this->Login_model->logout();
		redirect('login');
	}
}
