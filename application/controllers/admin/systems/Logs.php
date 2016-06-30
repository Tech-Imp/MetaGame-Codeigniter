<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logs extends Dash_backend{
	
	function __construct(){
		parent::__construct();
	}
//-------------------------------------------------------------------------------------------------------------------------------------
//View google analytics and various odds and ends
//--------------------------------------------------------------------------------------------------------------------------------------
	
	public function index(){
	     $this->secureArea();
		$this->load->model('Admin_model');
		$data=$this->adminHeader();
		$data['currentLocation']="<div class='navbar-brand'>Site Logs (NYI)</div>";
		$this->load->view('templates/header', $data);
		$this->load->view('inc/dash_header', $data);
		$this->load->view('dash/errorInfo');
		$this->load->view('inc/dash_footer', $data);
		$this->load->view('templates/footer', $data);
	}
	
}