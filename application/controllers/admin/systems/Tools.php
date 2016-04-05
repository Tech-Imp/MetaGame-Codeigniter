<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tools extends Dash_backend{
	
	function __construct(){
		parent::__construct();
	}
	
//--------------------------------------------------------------------------------------------------
//Base Page
//--------------------------------------------------------------------------------------------------
	public function index()
	{
		$data=$this->adminHeader();
		$this->load->model('Logging_model');
		$this->load->model('SectionAuth_model');
		$this->load->model('Dataprep_model');
		$data['currentLocation']="<div class='navbar-brand'>Tools Dashboard</div>";
		$this->load->view('templates/header', $data);
		$this->load->view('inc/dash_header', $data);
		
		//Logging of recent items
		$logOutput="<div><h4>Recent Section Activity:</h4><br>No recent section activity to report.</div>";
		$types=array("aSec", "dSec", "uAdd", "uDel");
		$logs=$this->Logging_model->getTypeLogs($types,15,0);
		if(count($logs)){
			$logOutput='<div><h4>Recent activity:</h4><br><ul>';
			foreach ($logs as $row) {
				$logOutput.='<li>'.$row->change.'</li>';	
			}
			$logOutput.='</ul></div>';
		}
		$data['recentChanges']=$logOutput;
		//Show subsections you are part of
		$mySubs=$this->SectionAuth_model->getValidSections();
		
		$data['mediaTable']="";//$this->Dataprep_model->gatherItemsAdmin($myMedia, "media", "media_id", "multimedia/editMedia", 6, 6);
		
		$this->load->view('dashboard', $data);
		$this->load->view('inc/dash_footer', $data);
		$this->load->view('templates/footer', $data);
	}
	
	
}