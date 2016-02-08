<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contact extends common_frontend {
	
	function __construct(){
		parent::__construct();
		
	}

	public function index()
	{
		$this->load->model('Staticpages_model');
		$this->load->model('Dataprep_model');	
		$data=$this->commonHeader();
		$data['css'][2]="frontend/contact.css";
		// $data['js'][0]='bookObject.js';
		// $data['js'][1]='bookIndex.js';
		$data['title']="Contact";
		
		$contact=$this->Staticpages_model->getSpecificContact();
		$data['singularContent']=$this->Dataprep_model->gatherItems($contact, 'contact info', "static_id");
		
		
		$this->load->view('templates/header', $data);
		$this->load->view('mainLayout', $data);
		// $this->load->view('inc/modals');
		$this->load->view('templates/footer', $data);
		
		
	}

	
}

