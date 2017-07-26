<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends Dash_backend{
	
	function __construct(){
		parent::__construct();
	}
	
//--------------------------------------------------------------------------------------------------
//Base Page
//--------------------------------------------------------------------------------------------------
	public function index()
	{
		$data=$this->commonHeader();
		$this->load->model('Logging_model');
		$this->load->model('Media_model');
		$this->load->model('Adminprep_model');
		$data['currentLocation']="<div class='navbar-brand'>Your Dashboard</div>";
		$this->load->view('templates/header', $data);
		$this->load->view('inc/dash_header', $data);
		
		//Logging of recent items
		$logs=$this->Logging_model->getQuickLogs(15,0);
		
		$data['recentChanges']=$this->Adminprep_model->getSectionLogs($logs);
		//Recent Photos
		$myMedia=$this->Media_model->getMedia(NULL, 6, 0);
		$data['mediaTable']=$this->Adminprep_model->gatherItemsAdmin($myMedia, "media", "media_id", "multimedia/editMedia", 6, 6);
		$this->load->view('dashboard', $data);
		$this->load->view('inc/dash_footer', $data);
		$this->load->view('templates/footer', $data);
	}
}