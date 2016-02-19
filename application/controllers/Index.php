<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends Frontend_controller {
	function __construct(){
		parent::__construct();
	}

	public function index()
	{
		
		$data['site_name']=config_item('site_name');
		$data['css'][0]="main.css";
		// $data['js'][0]='bookObject.js';
		// $data['js'][1]='bookIndex.js';
		$data['title']="Welcome";
		
		
		$this->load->view('templates/header', $data);
		$this->load->view('welcome');
		$this->load->view('templates/footer', $data);
		
	}
}

