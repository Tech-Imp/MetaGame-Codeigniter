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
          $this->load->model('Errorlog_model');
          $this->load->model('Adminprep_model');
		$data=$this->adminHeader();
		$data['currentLocation']="<div class='navbar-brand'>Site Logs</div>";
		$this->load->view('templates/header', $data);
		$this->load->view('inc/dash_header', $data);
          
          $logs=$this->Errorlog_model->getQuickLogs(20,0);
          
          $data['recentChanges']=$this->Adminprep_model->getSectionLogs($logs);
          
          // Secondary logs
          $types1=array("dVis", "dNew", "dSoc", "dMed", "dCon","dSec");
          $log1=array($this->Errorlog_model->getTypeLogs($types1,15,0), 'Deletion Actions');
          $types2=array("aEmb", "aMed", "aSoc", "aUsr", "aVis", "aCon", "aNew", "aSec");
          $log2=array($this->Errorlog_model->getTypeLogs($types2,15,0), "Creation Actions");
          $types3=array("eNew", "eMed", "eCon", "eSoc", "eSec", "eVis");
          $log3=array($this->Errorlog_model->getTypeLogs($types3,15,0), "Edit Actions");
          $types4=array("oPas", "sEma", "sPas");
          $log4=array($this->Errorlog_model->getTypeLogs($types4,15,0), "System/admin Actions");
          
          
          $multi=array($log1, $log2, $log3, $log4);
          
          $data['addLogs']=$this->Adminprep_model->multiLogs($multi, true);
          
          
          $this->load->view('sys/logs', $data);
		// $this->load->view('dash/errorInfo');
		$this->load->view('inc/dash_footer', $data);
		$this->load->view('templates/footer', $data);
	}
	
}