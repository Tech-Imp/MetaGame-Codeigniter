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
		$this->load->model('SectionAuth_model');
		$this->load->model('Dataprep_model');
		$data['currentLocation']="<div class='navbar-brand'>Tools Dashboard</div>";
		$this->load->view('templates/header', $data);
		$this->load->view('inc/dash_header', $data);
		
		//Logging of recent items
		$data['recentChanges']=$this->getSectionLogs();
		$data['myRole']="FIX ME";//$this->getMyRoles();
		$data['personList']=$this->getUnderlings();
		
		$data['sectionList']=$this->getSections();
		//People you have assigned
		$data['sectionAccess']=$this->getWhoAssigned();
		
		$this->load->view('sys/tools', $data);
		$this->load->view('inc/dash_footer', $data);
		$this->load->view('templates/footer', $data);
	}
//------------------------------------------------------------------------------------------------------------------
//helper functions
//------------------------------------------------------------------------------------------------------------------
	private function getUnderlings(){
		$this->load->model('Admin_model');
		$myUnderlings=$this->Admin_model->getByMinRank($this->config->item('contributor'));
		$name=$id=array();
		if(count($myUnderlings)){
			foreach($myUnderlings as $person){
				array_push($name, $person->name." (".$person->email.")");
				array_push($id, $person->id);
			}
			return $this->dropdownOptions(NULL, $name, $id);
		}
		else {
			return "<option value='0'>Nobody</option>";
		}
	}
	private function getSections(){
		$this->load->model('SectionAuth_model');
		$sections=$this->SectionAuth_model->getValidSections();
		$name=$id=array();
		if(count($sections)){
			foreach($sections as $area){
				array_push($name, $area->sub_name);
				array_push($id, $area->sub_dir);
			}
			return $this->dropdownOptions(NULL, $name, $id);
		}
		return "<option value='void'>The Void</option>";
	}
	private function getSectionLogs(){
		$this->load->model('Logging_model');
		$types=array("aSec", "dSec", "uAdd", "uDel");
		$logs=$this->Logging_model->getTypeLogs($types,15,0);
		if(count($logs)){
			$logOutput='<div><h4>Recent activity:</h4><br><ul>';
			foreach ($logs as $row) {
				$logOutput.='<li>'.$row->change.'</li>';	
			}
			$logOutput.='</ul></div>';
			return $logOutput;
		}
		else{
			return "<div><h4>Recent Section Activity:</h4><br>No recent section activity to report.</div>";
		}
	}
	private function getWhoAssigned(){
		$this->load->model('SectionAuth_model');
		$assignments=$this->SectionAuth_model->whoIAssigned();
		$delegation="";
		if(count($assignments)){
			foreach($assignments as $area){
				array_push($name, $area->sub_name);
				array_push($id, $area->sub_dir);
			}
			return "<ul>".$delegation."</ul>";
		}
		return "<ul><li>You havent granted permissions</li></ul>";
	}
	private function getMyRoles(){
		$this->load->model('SectionAuth_model');
		$sections=$this->SectionAuth_model->whereImAssigned();
		if(count($sections)){
			$logOutput='<div><h4>Assigned to:</h4><br><ul>';
			foreach ($sections as $row) {
				$logOutput.='<li>'.$row->sub_name.'</li>';	
			}
			$logOutput.='</ul></div>';
			return $logOutput;
		}
		else{
			return "<div><h4>Assigned to:</h4><br>You are not in any special sections.</div>";
		}
	}
	
}