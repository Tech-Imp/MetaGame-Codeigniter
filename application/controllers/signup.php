<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Signup extends frontend_controller {

	function __construct(){
		parent::__construct();
	}
	
	public function index()
	{
			
		$data['css'][0]="main.css";
		$data['title']="Sign Up";
		$data['site_name']=config_item('site_name');
		$dashboard='login';
		$data['recaptcha']=$this->config->item('recaptcha-site-key');
		// if ($this->Login_model->loggedin() == TRUE){
			// redirect($dashboard);
		// }
		
		// $this->load->library('curl'); 
		
		$this->load->model("Admin_model");
		$this->load->library('curl');
		
		$rules=$this->Admin_model->signUpRules;
		$this->form_validation->set_rules($rules);
		if($this->form_validation->run() == TRUE){
			//Login
			$recaptchaResponse = trim($this->input->post('g-recaptcha-response'));
  			$userIp=$this->input->ip_address();
  			$secret=$this->config->item('recaptcha-secret-key');
			$base="https://www.google.com/recaptcha/api/siteverify";
			
			
			$verification=array(
				"secret"=>$this->config->item('recaptcha-secret-key'),
				"response"=>$recaptchaResponse,
				"remoteip"=>$userIp,
			);
			$response=$this->curl->simple_get($base.'?'.http_build_query($verification));
			// $response=@file_get_contents($base.'?'.http_build_query($verification));
			if($response===FALSE){
				$this->session->set_flashdata('error', 'Server busy. Please try again.' );
				redirect('signup', 'refresh');
			}
			$status= json_decode($response, true);
			// var_dump($status);
			if($status["success"]===TRUE){
				if($this->Admin_model->signUp()==TRUE){
					redirect($dashboard);
				}
				else{
					$this->session->set_flashdata('error', 'Error with setting up account. Please try later.');
					redirect('signup', 'refresh');
				}
				$this->session->set_flashdata('error', 'SUCCESS');
			}
			else {
				// var_dump("FAILED");
				$this->session->set_flashdata('error', 'Incorrect response to captcha. Please try again.');
				redirect('signup', 'refresh');
			}
			
		}
		
		$this->load->view('templates/header', $data);
		$this->load->view('inc/signup_user');
		$this->load->view('templates/footer', $data);
		
		
		// $this->load->view('default', $this->data);
	}
}
