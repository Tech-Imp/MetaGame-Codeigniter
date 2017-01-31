<?

class Securepost extends MY_Controller{

	function __construct(){
		parent::__construct();
	}

//-----------------------------------------------------------------------------------------------------
//SECTION RELATED FEATURES
//-------------------------------------------------------------------------------------------------------	
	function addSection(){
		header('content-type: text/javascript');
		$myRole=$_SESSION['role'];
		$myName=$_SESSION['name'];
		$myEmail=$_SESSION['email'];
		$author = $_SESSION['id'];
		
		$section = $this->simplePurify($this->input->post('section'));
		$sub_dir = $this->simplePurify($this->input->post('sub_dir'));
          $vis = intval($this->input->post('visibility'));
          $bindToParent = $this->simplePurify($this->input->post('where'));
		$uncleanUsage = $this->input->post('usage');
		
		if($myRole< $this->config->item('sectionAdmin')){
			$data=array('error' => "Insufficient privledges");
			$this->load->model("Errorlog_model");
			$this->Errorlog_model->newLog(-1, 'aSec', 'Failed to create section. Insufficient privledges. User '.$myName.' ('.$myEmail.') role '.$myRole);  
      		echo json_encode($data);
      		exit; 
		}
		
		$this->load->helper('htmlpurifier');
		$clean_html = html_purify($uncleanUsage);
		
		if(empty($clean_html)||empty($section)||empty($sub_dir)){
			$data=array('error' => "Required text field is empty");
			$this->load->model("Errorlog_model");
			$this->Errorlog_model->newLog(-1, 'aSec', 'Failed to create section. Required field empty by User '.$myName.' ('.$myEmail.')');  
      		echo json_encode($data);
      		exit; 
		}
		
		
		$this->load->model("SectionAuth_model");
          //Determine parent that holds the link is valid
          if(!$this->verifySection($bindToParent)){
               $data=array('error' => "Invalid section ");
               $this->load->model("Errorlog_model");
               $this->Errorlog_model->newLog(-1, 'aSec', 'Failed to create section. Parent section invalid. User '.$myName.' ('.$myEmail.') role '.$myRole.' Section '.$bindToParent);  
               echo json_encode($data);
               exit; 
          } 
          $result=$this->SectionAuth_model->saveSubsection($sub_dir, $section, $clean_html, $vis, $bindToParent);
		$this->SectionAuth_model->addUserToSection($author, $sub_dir);
		
		$this->load->model("Logging_model");
		$this->Logging_model->newLog($result, 'aSec', 'Section '.$section.' ('.$sub_dir.') created by '.$myName.'('.$myEmail.')');  
		//Preload basic template settings for a new section
		$this->load->model("Sectionexposure_model");
      	if($this->Sectionexposure_model->sectionAddBasic($sub_dir)){
       		$this->Logging_model->newLog($result, 'aVis', 'Section '.$section.' ('.$sub_dir.') had a basic visibility template created');  
      	}
      	else{
           	$this->load->model("Errorlog_model");
           	$this->Errorlog_model->newLog($result, 'aVis', 'Section '.$section.' ('.$sub_dir.') did NOT have basic visibility or routing created!! by User '.$myName.' ('.$myEmail.')'); 
      	}
          
		$data=array('success' => $result); 
      	echo json_encode($data);
      	exit; 
	}	
	
	function deleteSection(){
		header('content-type: text/javascript');
		$myRole=$_SESSION['role'];
		$myID=$_SESSION['id'];
		$myName=$_SESSION['name'];
		$myEmail=$_SESSION['email'];
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
                    // Remove basic visibility template explicitly- DO NOT HANDLE SPECIFIC custom ones here, handle that with specific page subsection addition/deletion
                    $this->load->model("Sectionexposure_model");
                    $visSuccess=$this->Sectionexposure_model->sectionRemoveBasic($verify->sub_dir);
                    if($visSuccess=="success"){
                         $this->Logging_model->newLog($result, 'dVis', 'Deleted Section '.$verify->sub_name.' ('.$verify->sub_dir.') basic visibility template successfully by User '.$myName.'('.$myEmail.')');  
                    }
                    else{
                         $this->load->model("Errorlog_model");
                         $this->Errorlog_model->newLog($result, 'dVis', 'Delete Section '.$verify->sub_name.' ('.$verify->sub_dir.') basic visibility FAILED!! Remaining vis:'.$visSuccess.' by User '.$myName.' ('.$myEmail.')'); 
                    }
     		}
     		else{
     			$data=array('error' => "Insufficient privledges"); 
                    $this->load->model("Errorlog_model");
                    $this->Errorlog_model->newLog($sectionID, 'dSec', 'Section '.$verify->sub_name.'('.$verify->subsite_id.') delete failed. Insufficient permissions. User '.$myName.'('.$myEmail.') role '.$myRole);
     		} 
          }
          else{
               $data=array('error' => "Section does not exist"); 
               $this->load->model("Errorlog_model");
               $this->Errorlog_model->newLog($sectionID, 'dSec', 'Section ('.$sectionID.') delete failed. ID does not exist. User '.$myName.'('.$myEmail.') role '.$myRole);
          }
		
		 
      	echo json_encode($data);
      	exit; 
	}
	function editSection(){
			header('content-type: text/javascript');
          	$myRole=$_SESSION['role'];
          	$myID=$_SESSION['id'];
          	$myName=$_SESSION['name'];
          	$myEmail=$_SESSION['email'];
          	$sectionID = intval($this->input->post('id')); 
		  	$vis = intval($this->input->post('visibility'));		
		  	$bindToParent = $this->simplePurify($this->input->post('where'));	
		  	$uncleanUsage = $this->input->post('usage');	
		  	//verify we have an id 
          	if(empty($sectionID) || !is_int($sectionID)){
           		$data=array('error' => "Error retrieving user ID"); 
               	echo json_encode($data);
               	exit; 
          	} 
          
		  	//Determine parent that holds the link is valid
          	if(!$this->verifySection($bindToParent)){
           		$data=array('error' => "Invalid section ");
               	$this->load->model("Errorlog_model");
               	$this->Errorlog_model->newLog(-1, 'eSec', 'Failed to edit section. Parent section invalid. User '.$myName.' ('.$myEmail.') role '.$myRole.' Section '.$bindToParent);  
               	echo json_encode($data);
               	exit; 
          	} 
		  
			$this->load->helper('htmlpurifier');
			$clean_html = html_purify($uncleanUsage);
			
			if(empty($clean_html)){
				$data=array('error' => "Required text field is empty");
				$this->load->model("Errorlog_model");
				$this->Errorlog_model->newLog(-1, 'eSec', 'Failed to edit section. Required field empty by User '.$myName.' ('.$myEmail.')');  
	      		echo json_encode($data);
	      		exit; 
			}  
		  
  			$this->load->model("SectionAuth_model");
		  	// Verify user has rights to section and section exists
		  	$verify=$this->SectionAuth_model->getSectionControl($sectionID);
          	if(count($verify)){
              	if(($verify->author_id==$myID && $myRole>= $this->config->item('sectionAdmin')) || $myRole> $this->config->item('sectionAdmin')){
 					$result=$this->SectionAuth_model->saveSectionEdits($sectionID, $clean_html, $vis, $bindToParent);
     				$data=array('success' => $result);
     				$this->load->model("Logging_model");
     				$this->Logging_model->newLog($sectionID, 'eSec', $result.' by user '.$myName.'('.$myEmail.') ');
	 			}
	 			else{
	     			$data=array('error' => "Insufficient privledges"); 
	                $this->load->model("Errorlog_model");
	                $this->Errorlog_model->newLog($sectionID, 'eSec', 'Section '.$verify->sub_name.'('.$verify->subsite_id.') edit failed. Insufficient permissions. User '.$myName.'('.$myEmail.') role '.$myRole);
	 			} 
			}
      		else{
   				$data=array('error' => "Section does not exist"); 
           		$this->load->model("Errorlog_model");
           		$this->Errorlog_model->newLog($sectionID, 'eSec', 'Section ('.$sectionID.') delete failed. ID does not exist. User '.$myName.'('.$myEmail.') role '.$myRole);
      		}
          	echo json_encode($data);
          	exit; 
     }

//---------------------------------------------------------------------------------
//USER->SECTION RELATED FEATURES
//---------------------------------------------------------------------------------
	function addUserToSection(){
		header('content-type: text/javascript');
		$myRole=$_SESSION['role'];
		$myName=$_SESSION['name'];
		$myEmail=$_SESSION['email'];
		$author = $_SESSION['id'];
		
		$user = $this->simplePurify($this->input->post('user'));
		$section = $this->simplePurify($this->input->post('section'));
		//Verify user is valid to add users
		if($myRole< $this->config->item('sectionAdmin')){
			$data=array('error' => "Insufficient privledges");
			$this->load->model("Errorlog_model");
			$this->Errorlog_model->newLog(-1, 'uAdd', 'Failed to add user to section. Insufficient privledges. User: '.$myName.' ('.$myEmail.') role: '.$myRole);  
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
		$userRecord=$this->User_model->getByMinRank($this->config->item('contributor'), $user);
          if(is_null($userRecord)){
               $data=array('error' => "Invalid member");
               $this->load->model("Errorlog_model");
               $this->Errorlog_model->newLog($user, 'uAdd', 'Failed to add user to section. User invalid. User: '.$myName.' ('.$myEmail.') role: '.$myRole);  
               echo json_encode($data);
               exit; 
          }
          //Verify user isnt already in list
		if(!($this->SectionAuth_model->isNewUser($user, $section))){
		    $data=array('error' => "User already a member");
               $this->load->model("Errorlog_model");
               $this->Errorlog_model->newLog($user, 'uAdd', 'Failed to add user '.$userRecord->name.' ('.$user.') to section. User already a member. User: '.$myName.' ('.$myEmail.') role: '.$myRole);  
               echo json_encode($data);
               exit;  
		}
		//Verify section exists, user has authorization for it, and if so add user to it
          if($this->SectionAuth_model->sectionExists($section) && $this->SectionAuth_model->isSelfAuthorized($section)){
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
               $this->Errorlog_model->newLog($user, 'uAdd', 'Failed to add user '.$userRecord->name.' ('.$user.') to section. Section '.$section.' invalid. User: '.$myName.' ('.$myEmail.') role: '.$myRole);  
               echo json_encode($data);
               exit; 
          } 
	}	
	
     function deleteUserFromSection(){
          header('content-type: text/javascript');
          $myRole=$_SESSION['role'];
          $myID=$_SESSION['id'];
          $myName=$_SESSION['name'];
          $myEmail=$_SESSION['email'];
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

//-------------------------------------------------------------------------------------------------------------
//USER->UserRole related functions
//-------------------------------------------------------------------------------------------------------------
	function setRole(){
          header('content-type: text/javascript');
          $myRole=$_SESSION['role'];
          $myID=$_SESSION['id'];
          $myName=$_SESSION['name'];
          $myEmail=$_SESSION['email'];
          $entryID = intval($this->input->post('userID')); 
		  $adjustRole = $this->input->post('rank');		
			
          if(empty($entryID) || !is_int($entryID)){
               $data=array('error' => "Error retrieving user ID"); 
               echo json_encode($data);
               exit; 
          } 
		  switch($adjustRole){
			case "norm":
				$setRank=$this->config->item('normUser');
				break;
			case "contrib": 
				$setRank=$this->config->item('contributor');
				break;
			case "sect":
				$setRank=$this->config->item('sectionAdmin');
				break;
			default:
				$data=array('error' => "Error retrieving role"); 
               	echo json_encode($data);
               	exit; 
		  }	
		
		
			$this->load->model('User_model');
			$userData=$this->User_model->getUsers($entryID);
		
          
          if(count($userData)){
               //only those with proper rank can affect others below them
              if($userData->role < $myRole && $myRole > $this->config->item('contributor') && $myRole > $setRank){
                    $result=$this->User_model->setRole($entryID, $setRank);
                    $data=array('success' => $result);
                    $this->load->model("Logging_model");
                    $this->Logging_model->newLog($entryID, 'uPri', 'User '.$userData->name.$result.' by user '.$myName.'('.$myEmail.') ');
               }
               else{
                    $data=array('error' => "Insufficient privledges"); 
                    $this->load->model("Errorlog_model");
                    $this->Errorlog_model->newLog($entryID, 'uPri', 'User '.$userData->name.'('.$userData->email.') role change failed. Insufficient permissions. User '.$myID.' role '.$myRole);
               } 
          }
          else{
               $data=array('error' => "Entry does not exist"); 
               $this->load->model("Errorlog_model");
               $this->Errorlog_model->newLog($entryID, 'uPri', 'User role change failed. ID does not exist. User '.$myID.' role '.$myRole);
          }
          
           
          echo json_encode($data);
          exit; 
     }







//---------------------------------------------------------------------------------
//shared functions
//---------------------------------------------------------------------------------
	
	 
}