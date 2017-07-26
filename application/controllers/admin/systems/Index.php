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
		$data=$this->adminHeader();
		$this->load->model('Logging_model');
		$this->load->model('Media_model');
		$this->load->model('Adminprep_model');
		$data['currentLocation']="<div class='navbar-brand'>Your Systems</div>";
		$this->load->view('templates/header', $data);
		$this->load->view('inc/dash_header', $data);
		
		//Logging of recent items
		
		$types=array("aSec", "dSec", "uAdd", "uDel");
          $logs=$this->Logging_model->getTypeLogs($types,15,0);
          $data['recentChangesType']="User/Section logs";
          $data['recentChanges']=$this->Adminprep_model->getSectionLogs($logs);
		
          // Secondary logs
		$types1=array("dVis", "dNew", "dSoc", "dMed", "dCon");
          $log1=array($this->Logging_model->getTypeLogs($types1,15,0), 'Deletion Actions');
          $types2=array("aEmb", "aMed", "aSoc", "aUsr", "aVis", "aCon", "aNew");
          $log2=array($this->Logging_model->getTypeLogs($types2,15,0), "Creation Actions");
          $types3=array("oPas", "sEma", "sPas");
          $log3=array($this->Logging_model->getTypeLogs($types3,15,0), "System/admin Actions");
          
          $multi=array($log1, $log2, $log3);
          
          $data['addLogs']=$this->Adminprep_model->multiLogs($multi, true);
          
          
          
		$this->load->view('sys/logs', $data);
		$this->load->view('inc/dash_footer', $data);
		$this->load->view('templates/footer', $data);
	}
}