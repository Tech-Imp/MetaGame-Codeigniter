<?

class Securepost extends MY_Controller{

	function __construct(){
		parent::__construct();
	}

//-----------------------------------------------------------------------------------------------------
//SECTION RELATED FEATURES
//
//-------------------------------------------------------------------------------------------------------	
	function addSection(){
		header('content-type: text/javascript');
		$myRole=$this->session->userdata('role');
		$myName=$this->session->userdata('name');
		$myEmail=$this->session->userdata('email');
		$author = $this->session->userdata('id');
		
		$section = $this->simplePurify($this->input->post('section'));
		$sub_dir = $this->simplePurify($this->input->post('sub_dir'));
		$uncleanUsage = $this->input->post('usage');
		
		if($myRole< $this->config->item('sectionAdmin')){
			$data=array('error' => "Insufficient privledges");
			$this->load->model("Errorlog_model");
			$this->Errorlog_model->newLog(-1, 'aSec', 'Failed to create section. Insufficient privledges. User role '.$myRole);  
      		echo json_encode($data);
      		exit; 
		}
		
		$this->load->helper('htmlpurifier');
		$clean_html = html_purify($uncleanUsage);
		
		if(empty($clean_html)||empty($section)||empty($sub_dir)){
			$data=array('error' => "Required text field is empty");
			$this->load->model("Errorlog_model");
			$this->Errorlog_model->newLog(-1, 'aSec', 'Failed to create section. Required field empty ');  
      		echo json_encode($data);
      		exit; 
		}
		
		
		$this->load->model("SectionAuth_model");
          $result=$this->SectionAuth_model->saveSubsection($sub_dir, $section, $clean_html);
		$this->SectionAuth_model->addUserToSection($author, $sub_dir);
		
		$this->load->model("Logging_model");
		$this->Logging_model->newLog($result, 'aSec', 'Section '.$section.' ('.$sub_dir.') created by '.$myName.'('.$myEmail.')');  
		
		$data=array('success' => $result); 
      	echo json_encode($data);
      	exit; 
	}	
	
	function deleteSection(){
		header('content-type: text/javascript');
		$myRole=$this->session->userdata('role');
		$myID=$this->session->userdata('id');
		$myName=$this->session->userdata('name');
		$myEmail=$this->session->userdata('email');
		$sectionID = intval($this->input->post('sectionID')); 

		if(empty($sectionID) || !is_int($sectionID)){
			$data=array('error' => "Error retrieving section ID"); 
      		echo json_encode($data);
      		exit; 
		} 
          
		$this->load->model("SectionAuth_model");
		// Verify user has rights to section and section exists
		$verify=$this->SectionAuth_model->getSectionControl($sectionID);
          if(count($verify)){
              if(($verify->author_id==$myID && $myRole> $this->config->item('contributor')) || $myRole> $this->config->item('sectionAdmin')){
     			$result=$this->SectionAuth_model->removeSubDir($sectionID);
     			$data=array('success' => $result);
     			$this->load->model("Logging_model");
     			$this->Logging_model->newLog($sectionID, 'dSec', $result.' by user '.$myName.'('.$myEmail.') ');
     		}
     		else{
     			$data=array('error' => "Insufficient privledges"); 
                    $this->load->model("Errorlog_model");
                    $this->Errorlog_model->newLog($sectionID, 'dSec', 'Section '.$verify->sub_name.'('.$verify->subsite_id.') delete failed. Insufficient permissions. User '.$myID.' role '.$myRole);
     		} 
          }
          else{
               $data=array('error' => "Section does not exist"); 
               $this->load->model("Errorlog_model");
               $this->Errorlog_model->newLog($sectionID, 'dSec', 'Section ('.$sectionID.') delete failed. ID does not exist. User '.$myID.' role '.$myRole);
          }
		
		 
      	echo json_encode($data);
      	exit; 
	}

//---------------------------------------------------------------------------------
//USER->SECTION RELATED FEATURES
//---------------------------------------------------------------------------------
	function addUserToSection(){
		header('content-type: text/javascript');
		$myRole=$this->session->userdata('role');
		$myName=$this->session->userdata('name');
		$myEmail=$this->session->userdata('email');
		$author = $this->session->userdata('id');
		
		$user = $this->simplePurify($this->input->post('user'));
		$section = $this->simplePurify($this->input->post('section'));
		//Verify user is valid to add users
		if($myRole< $this->config->item('sectionAdmin')){
			$data=array('error' => "Insufficient privledges");
			$this->load->model("Errorlog_model");
			$this->Errorlog_model->newLog(-1, 'uAdd', 'Failed to add user to section. Insufficient privledges. User role '.$myRole);  
      		echo json_encode($data);
      		exit; 
		}
		//Determine that the items are not empty
		if(empty($user)||empty($section)){
			$data=array('error' => "Required text field is empty");
			$this->load->model("Errorlog_model");
			$this->Errorlog_model->newLog(-1, 'uAdd', 'Failed to add user to section. Required field empty ');  
      		echo json_encode($data);
      		exit; 
		}
		//Verify added member qualifications
		$this->load->model("User_model");
		$this->load->model("SectionAuth_model");
		$userRecord=$this->User_model->getUsers($user);
          if($userRecord->role < $this->config->item('contributor')){
               $data=array('error' => "Invalid member");
               $this->load->model("Errorlog_model");
               $this->Errorlog_model->newLog($user, 'uAdd', 'Failed to add user to section. User invalid. User role '.$myRole);  
               echo json_encode($data);
               exit; 
          }
          //Verify user isnt already in list
		if(!($this->SectionAuth_model->isNewUser($user, $section))){
		    $data=array('error' => "User already a member");
               $this->load->model("Errorlog_model");
               $this->Errorlog_model->newLog($user, 'uAdd', 'Failed to add user to section. User already a member. User role '.$myRole);  
               echo json_encode($data);
               exit;  
		}
		//Verify section exists and if so add user to it
          if($this->SectionAuth_model->sectionExists($section)){
               $result=$this->SectionAuth_model->addUserToSection($user, $section);
               $this->load->model("Logging_model");
               $this->Logging_model->newLog($result, 'uAdd', 'User '.$userRecord->name.' ('.$user.') added to section '.$section.'  by '.$myName.'('.$myEmail.')');
               $data=array('success' => $result); 
               echo json_encode($data);
               exit;
          }
          else{
               $data=array('error' => "Invalid section ");
               $this->load->model("Errorlog_model");
               $this->Errorlog_model->newLog($user, 'uAdd', 'Failed to add user to section. Section invalid. User role '.$myRole.' Section '.$section);  
               echo json_encode($data);
               exit; 
          } 
	}	

     function deleteUserFromSection(){
          header('content-type: text/javascript');
          $myRole=$this->session->userdata('role');
          $myID=$this->session->userdata('id');
          $myName=$this->session->userdata('name');
          $myEmail=$this->session->userdata('email');
          $entryID = intval($this->input->post('entryID')); 

          if(empty($entryID) || !is_int($entryID)){
               $data=array('error' => "Error retrieving entry ID"); 
               echo json_encode($data);
               exit; 
          } 
          
          $this->load->model("SectionAuth_model");
          // Verify user has rights to section and section exists
          $verify=$this->SectionAuth_model->getAuthInfo($entryID);
          
          if(count($verify)){
               //only the original assigner or someone with greater rank than section admin can remove user
              if(($verify->author_id==$myID && $myRole> $this->config->item('contributor')) || $myRole> $this->config->item('sectionAdmin')){
                         
                    $result=$this->SectionAuth_model->removeUserFromSection($entryID);
                    $data=array('success' => $result);
                    $this->load->model("Logging_model");
                    $this->Logging_model->newLog($entryID, 'uDel', $result.' by user '.$myName.'('.$myEmail.') ');
               }
               else{
                    $data=array('error' => "Insufficient privledges"); 
                    $this->load->model("Errorlog_model");
                    $this->Errorlog_model->newLog($entryID, 'uDel', 'User '.$verify->name.'('.$verify->user_id.') deallocation failed. Insufficient permissions. User '.$myID.' role '.$myRole);
               } 
          }
          else{
               $data=array('error' => "Entry does not exist"); 
               $this->load->model("Errorlog_model");
               $this->Errorlog_model->newLog($entryID, 'uDel', 'User ('.$sectionID.') deallocation failed. ID does not exist. User '.$myID.' role '.$myRole);
          }
          
           
          echo json_encode($data);
          exit; 
     }

//---------------------------------------------------------------------------------
//shared functions
//---------------------------------------------------------------------------------
	
	 
}