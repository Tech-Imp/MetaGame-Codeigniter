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
		$data=$this->adminHeader();
		$data['currentLocation']="<div class='navbar-brand'>Site Logs (NYI)</div>";
		$this->load->view('templates/header', $data);
		$this->load->view('inc/dash_header', $data);
          
          $logOutput="<div><h4>Recent activity:</h4><br>No recent activity to report.</div>";
          $logs=$this->Errorlog_model->getQuickLogs(15,0);
          if(count($logs)){
               $logOutput='<div><h4>Recent activity:</h4><br><ul>';
               foreach ($logs as $row) {
                    $logOutput.='<li>'.$row->change.'</li>';     
               }
               $logOutput.='</ul></div>';
          }
          $data['recentChanges']=$logOutput;
          
          
          
          
          $this->load->view('dashboard', $data);
		// $this->load->view('dash/errorInfo');
		$this->load->view('inc/dash_footer', $data);
		$this->load->view('templates/footer', $data);
	}
	
}