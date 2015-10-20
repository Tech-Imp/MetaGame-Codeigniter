<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gear extends common_frontend {
	
	function __construct(){
		parent::__construct();
		
	}

	public function index()
	{
		$this->load->model('Vendor_model');
		$this->load->model('Dataprep_model');	
		$data=$this->commonHeader();
		// $data['js'][0]='bookObject.js';
		// $data['js'][1]='bookIndex.js';
		$data['title']="Contact";
		
		
		
		// $contact=$this->Staticpages_model->getSpecificContact();
		$data['singularContent']='<div><h4>Feature coming soon: Buy stuff directly from the site.<br><br> 
		In the meantime, enjoy the videos and pictures and thanks for coming along for the ride. </h4></div>';
		
		
		$this->load->view('templates/header', $data);
		$this->load->view('mainLayout', $data);
		// $this->load->view('inc/modals');
		$this->load->view('templates/footer', $data);
		
		
	}	
	
	
}

