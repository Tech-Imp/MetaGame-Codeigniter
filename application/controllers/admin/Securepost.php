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
	
	function editProfile(){
		header('content-type: text/javascript');
		$myRole=$this->session->userdata('role');
		$myName=$this->session->userdata('name');
		$myEmail=$this->session->userdata('email');
		
		$profileID = $this->simplePurify($this->input->post('profileID'));
		$avatarID = $this->simplePurify($this->input->post('avatarID'));
		$profileName = $this->simplePurify($this->input->post('profileName'));
		$title = $this->simplePurify($this->input->post('title'));
		$uncleanText = $this->input->post('bodyText');
		$section = $this->simplePurify($this->input->post('section')); 
		$exFlag = $this->simplePurify($this->input->post('exFlag')); 
		
		$author = $this->session->userdata('id');
		
		if($myRole< $this->config->item('contributor')){
			$data=array('error' => "Insufficient privledges");
			$this->load->model("Errorlog_model");
			$this->Errorlog_model->newLog(-1, 'aCon', 'Profile item failed to upload. Insufficient privledges. User role '.$myRole);  
      		echo json_encode($data);
      		exit; 
		}
		if(empty($profileID)){
			$data=array('error' => "Error retrieving staticID"); 
			$this->load->model("Errorlog_model");
			$this->Errorlog_model->newLog(-1, 'eCon', 'Profile item failed to be reuploaded. Error retrieving profileID');  
      		echo json_encode($data);
      		exit; 
		} 
		
		$this->load->helper('htmlpurifier');
		$clean_html = html_purify($uncleanText);
		
		if(empty($clean_html)||empty($profileName)||empty($title)){
			$data=array('error' => "Required text field is empty");
			$this->load->model("Errorlog_model");
			$this->Errorlog_model->newLog(-1, 'aCon', 'Profile item failed to upload. Required field empty ');  
      		echo json_encode($data);
      		exit; 
		} 
		if(empty($avatarID)){
			$avatarID=$this->config->item('defaultAvatarID');
		}
		
		$this->load->model("Profilepages_model");
        $result=$this->Profilepages_model->saveProfile($title, $profileName, $clean_html, $exFlag, $section, $avatarID, $profileID);
		$this->load->model("Logging_model");
		$this->Logging_model->newLog($result, 'aCon', 'Profile item ('.$result.') updated successfully by '.$myName.'('.$myEmail.')');  
		
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
		$profileID = intval($this->input->post('profileID')); 
		
		if($myRole< $this->config->item('superAdmin')){
			$data=array('error' => "Insufficient privledges"); 
			$this->load->model("Errorlog_model");
			$this->Errorlog_model->newLog($profileID, 'dCon', 'Section delete failed. Insufficient permissions. User '.$myID.' role '.$myRole);
      		echo json_encode($data);
      		exit; 
		}
		if(empty($profileID)){
			$data=array('error' => "Error retrieving section ID"); 
      		echo json_encode($data);
      		exit; 
		} 
		
		$this->load->model("Profilepages_model");
		
		// Verify user has rights to media
		$verify=$this->Profilepages_model->get($profileID, TRUE);
		if($verify->author_id==$myID || $myRole> $this->config->item('sectionAdmin')){
			$result=$this->Profilepages_model->delete($profileID);
			$data=array('success' => $profileID);
			$this->load->model("Logging_model");
			$this->Logging_model->newLog($profileID, 'dCon', 'Profile item '.$verify->title.' ('.$result.') was deleted by user '.$myName.'('.$myEmail.') ');
		}
		else{
			$data=array('error' => 'Cannot delete that item');
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
		
		$section = $this->simplePurify($this->input->post('section'));
		$sub_dir = $this->simplePurify($this->input->post('sub_dir'));
		$uncleanUsage = $this->simplePurify($this->input->post('profileName'));
		
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
		
		
		$this->load->model("Logging_model");
		$this->Logging_model->newLog($result, 'aSec', 'Section '.$section.' ('.$sub_dir.') created by '.$myName.'('.$myEmail.')');  
		
		$data=array('success' => $result); 
      	echo json_encode($data);
      	exit; 
	}	

//---------------------------------------------------------------------------------
//shared functions
//---------------------------------------------------------------------------------
	
	 
}