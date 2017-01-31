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
		$this->load->model('User_model');
          $this->load->model('Logging_model');
          $this->load->model('Adminprep_model');
          
		$data['currentLocation']="<div class='navbar-brand'>Tools Dashboard</div>";
		$data['js'][0]= 'tinymce/jquery.tinymce.min.js';
		$data['js'][1]= 'dash/dashboardIndex.js';
		$data['js'][2]= 'dash/sys/adminTools.js';
		$data['js'][3]='commonShared.js';
		
		$this->load->view('templates/header', $data);
		$this->load->view('inc/dash_header', $data);
		
          $data['createUserLink']="";
		if($_SESSION['role'] >= $this->config->item('sectionAdmin')){
               $data['createUserLink']=anchor('admin/systems/tools/createuser',"<span class='glyphicon glyphicon-user'></span> Create User", array('class'=>'btn btn-success btn-lg btn-block', 'id'=>'cNewUser'));
          }
		//Logging of recent items
		$types=array("aSec", "dSec", "uAdd", "uDel");
          $logs=$this->Logging_model->getTypeLogs($types,15,0);
		$data['recentChanges']=$this->Adminprep_model->getSectionLogs($logs);
		//My Current Roles tab
		$secRoles=$this->SectionAuth_model->whereImAssigned();
		$data['myRole']=$this->Adminprep_model->getMyRoles($secRoles);
		//Add new Roles Section
		//--who you can assign
		$myUnderlings=$this->User_model->getByMinRank($this->config->item('contributor'));
		$data['personList']=$this->getUnderlings($myUnderlings);
          //--where you can assign
		$data['sectionList']=$this->dropdownSections("void", "The Void");
          //--what you have already assigned
          $assignments=$this->SectionAuth_model->whoIAssigned();
		$data['sectionAccess']=$this->Adminprep_model->getWhoAssigned($assignments);
		//Add new section tab
		$controlled=$this->SectionAuth_model->getSectionControl();
		$data['sectionTable']=$this->Adminprep_model->getSectionControlled($controlled);
		
          //Generate Yes/no for visibility
          $data["linkVisibility"]=$this->dropdownOptions(NULL, array("Yes", "No"), array(1,0));
          // var_dump($this->SectionAuth_model->getQuicklinks());
		$this->load->view('sys/tools', $data);
		$this->load->view('inc/dash_footer', $data);
		$this->load->view('templates/footer', $data);
	}
     public function removeuser($id=NULL)
     {
          $data=$this->adminHeader();
          $this->load->model('SectionAuth_model');
          $this->load->model('User_model');
          $this->load->model('Logging_model');
          $this->load->model('Adminprep_model');
          
          $data['currentLocation']="<div class='navbar-brand'>Remove User?</div>";
          $data['js'][0]= 'tinymce/jquery.tinymce.min.js';
          $data['js'][1]= 'dash/dashboardIndex.js';
          $data['js'][2]= 'dash/sys/adminUserDelete.js';
          $data['js'][3]='commonShared.js';
          
          $this->load->view('templates/header', $data);
          $this->load->view('inc/dash_header', $data);
          
          if($id===NULL){
               $this->load->view('dash/errorInfo');
          }
          else {
               $allData=$this->SectionAuth_model->getAuthInfo(intval($id));
               if(count($allData)){
                    $data['assocID']=$id;
                    $data['userEmailAssoc']=$allData->email;
                    $data['groupName']=$allData->sub_name;
                    $data['userNameAssoc']=$allData->name;
                    
                    $this->load->view('sys/removeUser', $data);
               }
               else{
                    $this->load->view('dash/errorInfo');
               }    
          }
          
          $this->load->view('inc/dash_footer', $data);
          $this->load->view('templates/footer', $data);
     }
     public function removesection($id=NULL)
     {
          $data=$this->adminHeader();
          $this->load->model('SectionAuth_model');
          $this->load->model('User_model');
          $this->load->model('Logging_model');
          $this->load->model('Adminprep_model');
          
          $data['currentLocation']="<div class='navbar-brand'>Remove Section?</div>";
          $data['js'][0]= 'tinymce/jquery.tinymce.min.js';
          $data['js'][1]= 'dash/dashboardIndex.js';
          $data['js'][2]= 'dash/sys/adminSectionDelete.js';
          $data['js'][3]='commonShared.js';

          
          $this->load->view('templates/header', $data);
          $this->load->view('inc/dash_header', $data);
          
          if($id===NULL){
               $this->load->view('dash/errorInfo');
          }
          else {
               $allData=$this->SectionAuth_model->getSectionControl(intval($id));
               if(count($allData)){
                    //Group
                    $data['assocID']=$id;
                    $data['groupURL']=$allData->sub_dir;
                    $data['groupName']=$allData->sub_name;
                    $data['groupUsage']=$allData->usage;
                    //Creator
                    $data['creationDate']=$allData->created;
                    $data['creator']=$allData->name;
                    $data['creatorEmail']=$allData->email;
                    //User in group
                    $people=$this->SectionAuth_model->getUsersBySection($allData->sub_dir);
                    $data['sectionAccess']=$this->Adminprep_model->getWhoAssigned($people);
                    $this->load->view('sys/removeGroup', $data);
               }
               else{
                    $this->load->view('dash/errorInfo');
               }    
          }
          
          $this->load->view('inc/dash_footer', $data);
          $this->load->view('templates/footer', $data);
     }

	public function editsection($id=NULL)
     {
          $data=$this->adminHeader();
          $this->load->model('SectionAuth_model');
          $this->load->model('User_model');
          $this->load->model('Logging_model');
          $this->load->model('Adminprep_model');
          
          $data['currentLocation']="<div class='navbar-brand'>Edit Section?</div>";
          $data['js'][0]= 'tinymce/jquery.tinymce.min.js';
          $data['js'][1]= 'dash/dashboardIndex.js';
		  $data['js'][2]= 'dash/sys/adminSectionEdit.js';
		  $data['js'][3]='commonShared.js';

          
          $this->load->view('templates/header', $data);
          $this->load->view('inc/dash_header', $data);
          
          if($id===NULL){
               $this->load->view('dash/errorInfo');
          }
          else {
               $allData=$this->SectionAuth_model->getSectionControl(intval($id));
               if(count($allData)){
                    //Group
                    $data['assocID']=$id;
                    $data['groupURL']=$allData->sub_dir;
                    $data['groupName']=$allData->sub_name;
                    $data['groupUsage']=$allData->usage;
					$data["linkVisibility"]=$this->dropdownOptions($allData->visible, array("Yes", "No"), array(1,0));
					$data['sectionList']=$this->dropdownSections("void", "The Void", $allData->forSection, $allData->sub_dir);
                    //Creator
                    $data['creationDate']=$allData->created;
                    $data['creator']=$allData->name;
                    $data['creatorEmail']=$allData->email;
                    //User in group (may revisit in future)
                    // $people=$this->SectionAuth_model->getUsersBySection($allData->sub_dir);
                    // $data['sectionAccess']=$this->Adminprep_model->getWhoAssigned($people);
                    $this->load->view('sys/editGroup', $data);
               }
               else{
                    $this->load->view('dash/errorInfo');
               }    
          }
          
          $this->load->view('inc/dash_footer', $data);
          $this->load->view('templates/footer', $data);
     }
     public function createUser(){
          $data=$this->adminHeader();
          $dashboard='admin/systems/tools';
          $this->load->model("Admin_model");
          
          $rules=$this->Admin_model->adminRules;
          $this->form_validation->set_rules($rules);
          if($this->form_validation->run() == TRUE){
               // $userIp=$this->input->ip_address();
               if($this->Admin_model->adminSignUp()==TRUE){
                    redirect($dashboard);
               }
               else{
                    $this->session->set_flashdata('error', 'Error with setting up account. Please try later.');
                    redirect('signup', 'refresh');
               }
               $this->session->set_flashdata('error', 'SUCCESS');
          }
          $this->load->view('templates/header', $data);
          $this->load->view('sys/signup_user');
          $this->load->view('templates/footer', $data);
     }

//--------------------------------------------------------------------------------------
//Helper functions
//--------------------------------------------------------------------------------------
     private function getUnderlings($myUnderlings){
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

}