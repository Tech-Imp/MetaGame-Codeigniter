<?

class Postprofile extends MY_Controller{

	function __construct(){
		parent::__construct();
	}

//-----------------------------------------------------------------------------------------------------
//PROFILE RELATED FEATURES
//-------------------------------------------------------------------------------------------------------	
	function addProfile(){
		header('content-type: text/javascript');
		$myRole=$_SESSION['role'];
		$myName=$_SESSION['name'];
		$myEmail=$_SESSION['email'];
		$avatarID = intval($this->simplePurify($this->input->post('avatarID')));
		$profileName = $this->simplePurify($this->input->post('profileName'));
		$title = $this->simplePurify($this->input->post('title'));
		$uncleanText = $this->input->post('bodyText');
		$section = $this->simplePurify($this->input->post('section')); 
		$exFlag = $this->simplePurify($this->input->post('exFlag')); 
		
		$author = $_SESSION['id'];

		if($myRole< $this->config->item('contributor')){
			$data=array('error' => "Insufficient privledges");
			$this->load->model("Errorlog_model");
			$this->Errorlog_model->newLog(-1, 'aCon', 'Profile item failed to upload. Insufficient privledges. User role '.$myRole);  
      		echo json_encode($data);
      		exit; 
		}
		if(!$this->verifySection($section)){
               $data=array('error' => "Section invalid or does not exist");
               $this->load->model("Errorlog_model");
               $this->Errorlog_model->newLog(-1, 'aCon', 'Profile item failed to upload. Section ('.$section.') invalid. User role '.$myRole);  
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
        $result=$this->Profilepages_model->saveProfile($title, $profileName, $clean_html, $exFlag, $section, $avatarID);
		$this->load->model("Logging_model");
		$this->Logging_model->newLog($result, 'aCon', 'Profile item '.$title.' ('.$result.') uploaded successfully by '.$myName.'('.$myEmail.')');  
		
		$data=array('success' => $result); 
      	echo json_encode($data);
      	exit; 
	}	
	
	function editProfile(){
		header('content-type: text/javascript');
		$myRole=$_SESSION['role'];
		$myName=$_SESSION['name'];
		$myEmail=$_SESSION['email'];
		
		$profileID = intval($this->simplePurify($this->input->post('profileID')));
		$avatarID = intval($this->simplePurify($this->input->post('avatarID')));
		$profileName = $this->simplePurify($this->input->post('profileName'));
		$title = $this->simplePurify($this->input->post('title'));
		$uncleanText = $this->input->post('bodyText');
		$section = $this->simplePurify($this->input->post('section')); 
		$exFlag = $this->simplePurify($this->input->post('exFlag')); 
		
		$author = $_SESSION['id'];
		
		if(empty($profileID)){
			$data=array('error' => "Error retrieving staticID"); 
			$this->load->model("Errorlog_model");
			$this->Errorlog_model->newLog(-1, 'eCon', 'Profile item failed to be reuploaded. Error retrieving profileID');  
      		echo json_encode($data);
      		exit; 
		}
		if($myRole< $this->config->item('contributor')){
			$data=array('error' => "Insufficient privledges");
			$this->load->model("Errorlog_model");
			$this->Errorlog_model->newLog($profileID, 'eCon', 'Profile item failed to upload. Insufficient privledges. User role '.$myRole);  
      		echo json_encode($data);
      		exit; 
		}
		if(!$this->verifySection($section)){
               $data=array('error' => "Section invalid or does not exist");
               $this->load->model("Errorlog_model");
               $this->Errorlog_model->newLog($profileID, 'eCon', 'Profile item failed to upload. Section ('.$section.') invalid. User role '.$myRole);  
               echo json_encode($data);
               exit; 
          }
		 
		
		$this->load->helper('htmlpurifier');
		$clean_html = html_purify($uncleanText);
		
		if(empty($clean_html)||empty($profileName)||empty($title)){
			$data=array('error' => "Required text field is empty");
			$this->load->model("Errorlog_model");
			$this->Errorlog_model->newLog($profileID, 'eCon', 'Profile item failed to upload. Required field empty ');  
      		echo json_encode($data);
      		exit; 
		} 
		if(empty($avatarID)){
			$avatarID=$this->config->item('defaultAvatarID');
		}
		
		$this->load->model("Profilepages_model");
        $result=$this->Profilepages_model->saveProfile($title, $profileName, $clean_html, $exFlag, $section, $avatarID, $profileID);
		$this->load->model("Logging_model");
		$this->Logging_model->newLog($result, 'eCon', 'Profile item '.$title.' ('.$result.') updated successfully by '.$myName.'('.$myEmail.')');  
		
		$data=array('success' => $result); 
      	echo json_encode($data);
      	exit; 
	}
	
	function deleteProfile(){
		header('content-type: text/javascript');
		$myRole=$_SESSION['role'];
		$myID=$_SESSION['id'];
		$myName=$_SESSION['name'];
		$myEmail=$_SESSION['email'];
		$profileID = intval($this->input->post('profileID')); 
		
		if(empty($profileID)){
			$data=array('error' => "Error retrieving profileID"); 
      		echo json_encode($data);
      		exit; 
		} 
		if($myRole< $this->config->item('contributor')){
			$data=array('error' => "Insufficient privledges"); 
			$this->load->model("Errorlog_model");
			$this->Errorlog_model->newLog($profileID, 'dCon', 'Profile delete failed. Insufficient permissions. User '.$myID.' role '.$myRole);
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
//-------------------------------------------------------------------------------------------------------------------
//SOCIAL RELATED FEATURES
//-------------------------------------------------------------------------------------------------------------------	
	    function addSocial(){
          header('content-type: text/javascript');
          $myRole=$_SESSION['role'];
          $myName=$_SESSION['name'];
          $myEmail=$_SESSION['email'];
          $author = $_SESSION['id'];
          
          $logoID = intval($this->simplePurify($this->input->post('logoID')));
          $target = $this->simplePurify($this->input->post('target'));
          $facebook = $this->simplePurify($this->input->post('facebook'));
          $youtube = $this->simplePurify($this->input->post('youtube'));
          $twitter = $this->simplePurify($this->input->post('twitter'));
          $tumblr = $this->simplePurify($this->input->post('tumblr'));
          $email = $this->simplePurify($this->input->post('email'));
          $twitch = $this->simplePurify($this->input->post('twitch'));
          $uncleanText = $this->input->post('bodyText');
          
          $section = $this->simplePurify($this->input->post('section')); 
          $exFlag = $this->simplePurify($this->input->post('exFlag')); 
          
          

          if($myRole< $this->config->item('contributor')){
               $data=array('error' => "Insufficient privledges");
               $this->load->model("Errorlog_model");
               $this->Errorlog_model->newLog(-1, 'aSoc', 'Social item failed to upload. Insufficient privledges. User role '.$myRole);  
               echo json_encode($data);
               exit; 
          }
          if(!$this->verifyUnique($target)){
               $data=array('error' => "Another entry already exists. Edit that instead.");
               $this->load->model("Errorlog_model");
               $this->Errorlog_model->newLog(-1, 'aSoc', 'Social item failed to upload. Item for "'.$target.'"aready exists. User role '.$myRole);  
               echo json_encode($data);
               exit; 
          }
          if(!$this->verifySection($section)){
               $data=array('error' => "Section invalid or does not exist");
               $this->load->model("Errorlog_model");
               $this->Errorlog_model->newLog(-1, 'aSoc', 'Social item failed to upload. Section ('.$section.') invalid. User role '.$myRole);  
               echo json_encode($data);
               exit; 
          }
          if(empty($target)){
               $data=array('error' => "Required text field is empty");
               $this->load->model("Errorlog_model");
               $this->Errorlog_model->newLog(-1, 'aSoc', 'Social item failed to upload. Required field empty ');  
               echo json_encode($data);
               exit; 
          }
          $this->load->helper('htmlpurifier');
          $clean_html = html_purify($uncleanText);
          
          
          $this->load->model("Subpages_model");
          $result=$this->Subpages_model->saveSocial($target, $logoID, $facebook, $youtube, $twitter, $tumblr, $twitch, $email, $clean_html, $exFlag, $section);
          $this->load->model("Logging_model");
          $this->Logging_model->newLog($result, 'aSoc', 'Social item '.$target.' ('.$result.') uploaded successfully by '.$myName.'('.$myEmail.')');  
          
          $data=array('success' => $result); 
          echo json_encode($data);
          exit; 
     }    
     
     function editSocial(){
          header('content-type: text/javascript');
          $myRole=$_SESSION['role'];
          $myName=$_SESSION['name'];
          $myEmail=$_SESSION['email'];
          $author = $_SESSION['id'];
          
          $info_id=intval($this->simplePurify($this->input->post('infoID')));
          $logoID = intval($this->simplePurify($this->input->post('logoID')));
          $facebook = $this->simplePurify($this->input->post('facebook'));
          $youtube = $this->simplePurify($this->input->post('youtube'));
          $twitter = $this->simplePurify($this->input->post('twitter'));
          $tumblr = $this->simplePurify($this->input->post('tumblr'));
          $email = $this->simplePurify($this->input->post('email'));
          $twitch = $this->simplePurify($this->input->post('twitch'));
          $uncleanText = $this->input->post('bodyText');
          
          $section = $this->simplePurify($this->input->post('section')); 
          $exFlag = $this->simplePurify($this->input->post('exFlag')); 
          
          
          if(empty($info_id)){
               $data=array('error' => "Error retrieving socialID"); 
               $this->load->model("Errorlog_model");
               $this->Errorlog_model->newLog(-1, 'eSoc', 'Social item failed to be reuploaded. Error retrieving socialID');  
               echo json_encode($data);
               exit; 
          }
          if($myRole< $this->config->item('contributor')){
               $data=array('error' => "Insufficient privledges");
               $this->load->model("Errorlog_model");
               $this->Errorlog_model->newLog($info_id, 'eSoc', 'Social item failed to upload. Insufficient privledges. User role '.$myRole);  
               echo json_encode($data);
               exit; 
          }
          if(!$this->verifySection($section)){
               $data=array('error' => "Section invalid or does not exist");
               $this->load->model("Errorlog_model");
               $this->Errorlog_model->newLog($info_id, 'eSoc', 'Social item failed to upload. Section ('.$section.') invalid. User role '.$myRole);  
               echo json_encode($data);
               exit; 
          }
           
          
          $this->load->helper('htmlpurifier');
          $clean_html = html_purify($uncleanText);
          
          $this->load->model("Subpages_model");
          $result=$this->Subpages_model->saveSocial(NULL, $logoID, $facebook, $youtube, $twitter, $tumblr, $twitch, $email, $clean_html, $exFlag, $section, $info_id);
          $this->load->model("Logging_model");
          $this->Logging_model->newLog($result, 'eSoc', 'Social item '.$info_id.' ('.$result.') updated successfully by '.$myName.'('.$myEmail.')');  
          
          $data=array('success' => $result); 
          echo json_encode($data);
          exit; 
     }
     
     function deleteSocial(){
          header('content-type: text/javascript');
          $myRole=$_SESSION['role'];
          $myID=$_SESSION['id'];
          $myName=$_SESSION['name'];
          $myEmail=$_SESSION['email'];
          $profileID = intval($this->input->post('infoID')); 
          
          if(empty($profileID)){
               $data=array('error' => "Error retrieving SocialID"); 
               echo json_encode($data);
               exit; 
          } 
          if($myRole< $this->config->item('contributor')){
               $data=array('error' => "Insufficient privledges"); 
               $this->load->model("Errorlog_model");
               $this->Errorlog_model->newLog($profileID, 'dSoc', 'Social delete failed. Insufficient permissions. User '.$myID.' role '.$myRole);
               echo json_encode($data);
               exit; 
          }
          
          
          $this->load->model("Subpages_model");
          // Verify user has rights to media
          $verify=$this->Subpages_model->get($profileID, TRUE);
          if($verify->author_id==$myID || $myRole> $this->config->item('sectionAdmin')){
               $result=$this->Subpages_model->delete($profileID);
               $data=array('success' => $profileID);
               $this->load->model("Logging_model");
               $this->Logging_model->newLog($profileID, 'dSoc', 'Social item for '.$verify->sub_dir.' ('.$result.') was deleted by user '.$myName.'('.$myEmail.') ');
          }
          else{
               $data=array('error' => 'Cannot delete that item');
          }
           
          echo json_encode($data);
          exit; 
     }

     private function verifyUnique($target=""){
          $target=preg_replace('/\s+/', '', $target);
          if($target==null){$target="";}
          $this->load->model("Subpages_model");
          if($target=="self"){
               return $this->Subpages_model->uniqueSelf();
          }
          elseif ($target==""){
              return false;
          }
          return $this->Subpages_model->uniqueSelf($target, true);
     }


}