<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contact extends Common_frontend {
	
	function __construct(){
		parent::__construct();
		
	}

	public function index()
	{
		$this->load->model('beta/Profilepages_model', "Profilepages_model");
		$this->load->model('beta/Dataprep_model', "Dataprep_model");	
		$data=$this->commonHeader();
		$data['css'][2]="frontend/contact.css";
		// $data['js'][0]='bookObject.js';
		// $data['js'][1]='bookIndex.js';
		$data['title']="Contact";
		
		$contact=$this->Profilepages_model->getProfile();
		$data['singularContent']=$this->Dataprep_model->profileItems($contact, "profiles", "static_id", "contact");
		
		
		$this->load->view('templates/header', $data);
		$this->load->view('mainLayout', $data);
		// $this->load->view('inc/modals');
		$this->load->view('templates/footer', $data);
		
		
	}

	
}

