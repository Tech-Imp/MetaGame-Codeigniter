<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends Dash_backend{
	
	function __construct(){
		parent::__construct();
	}
	
//-----------------------------------------------------------------------------------------------------------------
//For updating self and if role is high enough, others as well
//------------------------------------------------------------------------------------------------------------------	
	
	public function index($id=NULL)
	{
		$this->load->model('Admin_model');
		$data=$this->commonHeader();
		$data['currentLocation']="<div class='navbar-brand'>Your Settings</div>";
		
		// Completely disallow the ability of anyone with less rank than sectionAdmin from even attempting a PW change on someone else
		if($this->session->userdata('role') < $this->config->item('sectionAdmin')){$id=NULL;}
		
		// Find the name of the person to be changed
		$roleAffected=-1;
		if($id!==NULL){
			$whoArr=$this->Admin_model->get_by(array('id' => $id), true);
			if(count($whoArr)){
				$who=$whoArr->name;
				$whoAuth=$this->Admin_model->get_by(array('id'=>$id), true, 'auth_role', 'id');
				if(count($whoAuth)){ $roleAffected=$whoAuth->role; }
			}
			else{
				$who=-1;
			}
		}
		else{
			$who=$this->session->userdata('name');
		}
		$editID=NULL;
		// Verify all rules for input fields are followed
		$rules=$this->Admin_model->rules;
		$this->form_validation->set_rules($rules);
		if($this->form_validation->run() == TRUE){
			//Login
			if($id===NULL){
				$editID=$this->Admin_model->fixSelf();
			}
			elseif($this->session->userdata('role')>= $this->config->item('sectionAdmin') && $roleAffected !== -1 && $this->session->userdata('role') > $roleAffected){
				$editID=$this->Admin_model->fixOther(intval($id));
			}
			else{
				$this->load->model("Errorlog_model");
				$this->Errorlog_model->newLog($id, '!Pas', 'Password update failed. Insufficient rights for '.$this->session->userdata('id').'of role '.$this->session->userdata('role').
				' to Attempted edit '.$who.'('.$id.') role '.$roleAffected ); 
				$editID=-1;
			}
			
		}
		$progMessage='';
		if($editID!==NULL){
			if($editID!==-1){
				$progMessage="<br> <span class='text-success'>Update Successful</span>";
			}
			elseif ($this->session->userdata('role')>= $this->config->item('contributor')) {
				$progMessage="<br> <span class='text-danger'>Update failed.</span> <br>Cannot alter passwords of users of equal or higher rank than you. Cannot alter normal users at contributor rank.";
			}
			else{
				$progMessage="<br> <span class='text-danger'>Update failed.</span>";
			}
		}
		
		$data['userEdit']="Edit user ".$who.' '.$progMessage;
		
		$this->load->view('templates/header', $data);
		$this->load->view('inc/dash_header', $data);
		//If user doesnt exist
		if($who===-1){
			$this->load->view('dash/errorInfo');
		}
		// if user exceeds your role
		elseif ($this->session->userdata('role') <= $roleAffected && $this->session->userdata('role')>= $this->config->item('contributor')) {
			$this->load->view('dash/privInfo');	
		}
		// if user is acceptable for alteration
		else{
			$this->load->view('dash/updateInfo');
		}
		$this->load->view('inc/dash_footer', $data);
		$this->load->view('templates/footer', $data);
		
	}
//-----------------------------------------------------------------------------------------------------------------------
//View all users and redirect to edit user passwords. Since email is used as a login key it is not allowed for altering
//Will only display users with a lower access level than user logged in
//Will need to code a "where" function for specific user lookup in the future
//Should also link to additional databass to verify transactions the user has made
//Also pull up log entries on user
//------------------------------------------------------------------------------------------------------------------------
	
	public function listUsers(){
          $data=$this->adminHeader();
          $this->load->model('User_model');
          if($this->session->userdata('role') >= $this->config->item('sectionAdmin')){
		   $userRecords=$this->User_model->getUsers();
          }
          else{
             $userRecords=array();  
          }
		$data['currentLocation']="<div class='navbar-brand'>Users List</div>";
		$data['numUsers']=count($userRecords);
		$data['userTable']="<strong>No users to display from database that are under your authority.</strong>";
		
		if(count($userRecords)){
			$data['userTable']=$this->simpleUserInterface($userRecords);
		}
		
		$this->load->view('templates/header', $data);
		$this->load->view('inc/dash_header', $data);
		$this->load->view('dash/userInfo');
		$this->load->view('inc/dash_footer', $data);
		$this->load->view('templates/footer', $data);
	}
	
	//Used in the simple case of solely editing passwords
	private function simpleUserInterface($userRecords){
         $table="<div class='table-responsive'><table class='table table-hover table-striped'><thead>
               <tr>
                    <td>Name</td>
                    <td>Email</td>
                    <td>Edit</td>
               </tr>
          </thead>
          <tbody>";
          //Loop through the data and make a new row for each
          foreach ($userRecords as $row) {
               $table.="
               <tr><td>"
               .$row->name."</td>
               <td>".$row->email."</td>
               <td>".anchor('admin/dashboard/users/index/'.$row->id, "<span class='glyphicon glyphicon-cog'></span>");
               if($this->session->userdata('role') >= $this->config->item('sectionAdmin')){
		   			$table.="   ".anchor('admin/dashboard/users/userRole/'.$row->id, "<span class='glyphicon glyphicon-warning-sign'></span>");
          		}
               $table.="</td></tr>";
          }
          $table.="</tbody></table>";
          // $data['userTable']=var_dump($userRecords);
          return $table;
	}
//---------------------------------------------------------------------------------
//Alter user role to be elevated or lowered
//---------------------------------------------------------------------------------
	public function userRole($id=NULL){
		$data=$this->adminHeader();
		
	      $this->load->model('User_model');
	      $this->load->model('Logging_model');
	      $this->load->model('Adminprep_model');
	      
	      $data['currentLocation']="<div class='navbar-brand'>Alter Permissions</div>";
	      $data['js'][0]= 'tinymce/jquery.tinymce.min.js';
	      $data['js'][1]= 'dash/dashboardIndex.js';
	      $data['js'][2]= 'dash/sys/adminUserAdjust.js';
	      $data['js'][3]='commonShared.js';
	      
	      $this->load->view('templates/header', $data);
	      $this->load->view('inc/dash_header', $data);
	      
	      if($id===NULL){
	           $this->load->view('dash/errorInfo');
	      }
	      else {
	           $allData=$this->User_model->getUsers(intval($id));
	           if(count($allData)){
	                $data['uid']=$id;
	                $data['userEmail']=$allData->email;
	                $data['comments']=$allData->comment;
	                $data['userName']=$allData->name;
	                $data['role']=$this->determineRole($allData->role);
					$data['actions']=$this->roleActions($allData->role);
	                $this->load->view('sys/alterUser', $data);
	           }
	           else{
	                $this->load->view('dash/errorInfo');
	           }    
	      }
	      
	      $this->load->view('inc/dash_footer', $data);
	      $this->load->view('templates/footer', $data);
	}
//-----------------------------------------------------------------------------------------------------------
//Give a textual descriptor of the role
//-----------------------------------------------------------------------------------------------------------
	private function determineRole($role=0){
		switch(intval($role)){
			case $this->config->item('normUser'):
				return "Normal User";
				break;
			case $this->config->item('contributor'):
				return "Contributor";
				break;
			case $this->config->item('sectionAdmin'):
				return "Section Admin";
				break;
			case $this->config->item('superAdmin'):
				return "Admin";
				break;
			default:
				return "Unknown ( ".$role." )";
				break;
		}	
		return "ERROR";
	}	
//-----------------------------------------------------------------------------------------------------------
//Generate role promotion/demotion buttons
//-----------------------------------------------------------------------------------------------------------	
	private function roleActions($role=0){
		if($role>=$this->session->userdata('role')){
			return "<br>
					<div class='row'>
                         <div class='col-xs-12'><strong>You cannot alter this users role.</strong></div>
                    </div><br>";
		}
		else{
			$actions="
			<button class='btn btn-info' type='button' data-toggle='collapse' data-parent='#act-accordion' data-target='#setNorm'>
                    Set as Norm User
                    </button>";
                    
             $block="<br><div class='panel-group' id='act-accordion' role='tablist'>
				<div class='panel'>
             		<div class='collapse'  role='tabpanel' id='setNorm'>
                         <div class='well'>
                         <div>This will set their role to a normal user.</strong></div> 
                         <div><strong>Are you sure?</strong></div><br> 
                         <button class='btn btn-danger' type='button' data-toggle='collapse' data-target='#setNorm'>No, Cancel</button>
                         <button class='btn btn-success' type='button' id='setRoleNorm'>Yes, Norm User</button>
                         </div>
                    </div>";
			if($this->session->userdata('role')>=$this->config->item('sectionAdmin')){
				$actions.="
				<button class='btn btn-info' type='button' data-toggle='collapse' data-parent='#act-accordion' data-target='#setContrib'>
                    Set as Contributor
                    </button>";
                $block.="<div class='collapse' role='tabpanel' id='setContrib'>
                         <div class='well'>
                         <div>This will set their role to a Contributor.</strong></div> 
                         <div><strong>Are you sure?</strong></div><br> 
                         <button class='btn btn-danger' type='button' data-toggle='collapse' data-target='#setContrib'>No, Cancel</button>
                         <button class='btn btn-success' type='button' id='setRoleContrib'>Yes, Contributor</button>
                         </div>
                    </div>";
			}
			if($this->session->userdata('role')>=$this->config->item('superAdmin')){
				$actions.="
				<button class='btn btn-info' type='button' data-toggle='collapse' data-parent='#act-accordion' data-target='#setSectAdmin'>
                    Set as Section Admin
                    </button>";
                $block.="<div class='collapse' role='tabpanel' id='setSectAdmin'>
                         <div class='well'>
                         <div>This will set their role to a Section Admin.</strong></div> 
                         <div><strong>Are you sure?</strong></div><br> 
                         <button class='btn btn-danger' type='button' data-toggle='collapse' data-target='#setSectAdmin' aria-expanded='false' aria-controls='setSectAdmin'>No, Cancel</button>
                         <button class='btn btn-success' type='button' id='setRoleSAdmin'>Yes, Section Admin</button>
                         </div>
                    </div>";
			}
			return $actions.$block."</div></div>";
		}
	}
}