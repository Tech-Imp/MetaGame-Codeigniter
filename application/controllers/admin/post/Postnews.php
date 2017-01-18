<?

class Postnews extends MY_Controller{

	function __construct(){
		parent::__construct();
	}


	
//----------------------------------------------------------------------------------------------------------------------------------------
// NEWS RELATED FEATURES
//
//---------------------------------------------------------------------------------------------------------------------------------------
	function addNews(){
		header('content-type: text/javascript');
		$myRole=$this->session->userdata('role');
		$myName=$this->session->userdata('name');
		$myEmail=$this->session->userdata('email');
		$title = $this->simplePurify($this->input->post('title')); 
		$section = $this->simplePurify($this->input->post('section')); 
		$exFlag = $this->simplePurify($this->input->post('exFlag')); 
		$stub = $this->simplePurify($this->input->post('stub')); 
      	$visibleWhen = $this->simplePurify($this->input->post('visibleWhen'));
		$uncleanText = $this->input->post('bodyText');
		// $author =$this->simplePurify( $this->session->userdata('id'));
		$type=$this->simplePurify($this->input->post('type'));
		
		if($myRole < $this->config->item('contributor')){
			$data=array('error' => "Insufficient privledges");
			$this->load->model("Errorlog_model");
			$this->Errorlog_model->newLog(-1, 'aNew', 'News item failed to upload. Insufficient privledges. User role '.$myRole);  
      		echo json_encode($data);
      		exit; 
		}
		
          if(!$this->verifySection($section)){
               $data=array('error' => "Section invalid or does not exist");
               $this->load->model("Errorlog_model");
               $this->Errorlog_model->newLog(-1, 'aNew', 'News item failed to upload. Section ('.$section.') invalid. User role '.$myRole);  
               echo json_encode($data);
               exit; 
          }
		// $section=preg_replace('/\s+/', '', $section);
		// if($section==null){$section="";}
		
		$this->load->helper('htmlpurifier');
		$clean_html = html_purify($uncleanText);
		
		if(empty($clean_html) || empty($title) || empty($stub)){
			$data=array('error' => "Required text field is empty");
			$this->load->model("Errorlog_model");
			$this->Errorlog_model->newLog(-1, 'aNew', 'News item failed to upload. Required field empty ');  
      		echo json_encode($data);
      		exit; 
		} 
		
		$this->load->model("Article_model");
          $result=$this->Article_model->postArticles($visibleWhen, $title, $stub, $clean_html, NULL, $exFlag, $section, $type);
		$this->load->model("Logging_model");
		$this->Logging_model->newLog($result, 'aNew', 'News item '.$title.' ('.$result.') uploaded successfully by '.$myName.'('.$myEmail.')');  
		
		$data=array('success' => $result); 
      	echo json_encode($data);
      	exit; 
	}

	//-----------------------------------------------------------------------------------------------------------------
	//Save news Edits
	//---------------------------------------------------------------------------------------------------------------
	function saveNewsEdit(){
		header('content-type: text/javascript');
		$myRole=$this->session->userdata('role');
		$myID=$this->session->userdata('id');
		$myName=$this->session->userdata('name');
		$myEmail=$this->session->userdata('email');
		$type=$this->simplePurify($this->input->post('type'));
		$newsID = intval($this->simplePurify($this->input->post('newsID'))); 
		$title = $this->simplePurify($this->input->post('title'));
		$section = $this->simplePurify($this->input->post('section')); 
		$exFlag = $this->simplePurify($this->input->post('exFlag')); 
		$stub = $this->simplePurify($this->input->post('stub')); 
      	$visibleWhen = $this->simplePurify($this->input->post('visibleWhen'));
		$uncleanText = $this->input->post('body');

		$section=preg_replace('/\s+/', '', $section);
		if($section==null){$section="";}
		
		
		if(empty($newsID)){
			$data=array('error' => "Error retrieving NewsID"); 
			$this->load->model("Errorlog_model");
			$this->Errorlog_model->newLog(-1, 'eNew', 'News item failed to be reuploaded. Error retrieving newsID');  
      		echo json_encode($data);
      		exit; 
		}
		if($myRole < $this->config->item('contributor')){
			$data=array('error' => "Insufficient privledges");
			$this->load->model("Errorlog_model");
			$this->Errorlog_model->newLog($newsID, 'eNew', 'News item '.$newsID.' failed to be reuploaded. Insufficient privledges. User '.$myID.' role '.$myRole);   
      		echo json_encode($data);
      		exit; 
		}
          if(!$this->verifySection($section)){
               $data=array('error' => "Section invalid or does not exist");
               $this->load->model("Errorlog_model");
               $this->Errorlog_model->newLog($newsID, 'eNew', 'News item failed to reupload. Section ('.$section.') invalid. User role '.$myRole);  
               echo json_encode($data);
               exit; 
          }
		 
		
		$this->load->helper('htmlpurifier');
		$clean_html = html_purify($uncleanText);
		
		if(empty($clean_html) || empty($title) || empty($stub)){
			$data=array('error' => "Required text field is empty"); 
			$this->load->model("Errorlog_model");
			$this->Errorlog_model->newLog($newsID, 'eNew', 'News item failed to upload. Required field empty ');  
      		echo json_encode($data);
      		exit; 
		} 
		
		$this->load->model("Article_model");
		
		// Verify user has rights to media
		$verify=$this->Article_model->get($newsID, TRUE);
		if($verify->author_id==$myID || $myRole>$this->config->item('sectionAdmin')){
			$result=$this->Article_model->postArticles($visibleWhen, $title, $stub, $clean_html, $newsID, $exFlag, $section, $type);
			$this->load->model("Logging_model");
			$this->Logging_model->newLog($result, 'eNew', 'News item '.$title.' ('.$result.') edit saved successfully by '.$myName.'('.$myEmail.')');  
			$data=array('success' => $result);
		}
		else{
			$data=array('error' => 'Cannot Edit that item');
		}
      	echo json_encode($data);
      	exit; 
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	//Delete media item from database (and if file exists, that as well)
	//----------------------------------------------------------------------------------------------------------------
	function deleteSpecificNews(){
		header('content-type: text/javascript');
		$myRole=$this->session->userdata('role');
		$myID=$this->session->userdata('id');
		$myName=$this->session->userdata('name');
		$myEmail=$this->session->userdata('email');
		$newsID = intval($this->input->post('newsID')); 
		
		if(empty($newsID)){
			$data=array('error' => "Error retrieving NewsID"); 
      		echo json_encode($data);
      		exit; 
		} 

		if($myRole< $this->config->item('contributor')){
			$data=array('error' => "Insufficient privledges"); 
			$this->load->model("Errorlog_model");
			$this->Errorlog_model->newLog($newsID, 'dNews', 'News item delete failed. Insufficient permissions. User '.$myID.' role '.$myRole);
      		echo json_encode($data);
      		exit; 
		}
				
		$this->load->model("Article_model");
		
		// Verify user has rights to media
		$verify=$this->Article_model->get($newsID, TRUE);
		if($verify->author_id==$myID || $myRole>$this->config->item('sectionAdmin')){
			$result=$this->Article_model->delete($newsID);
			$data=array('success' => $newsID);
			$this->load->model("Logging_model");
			$this->Logging_model->newLog($newsID, 'dNews', 'News item '.$verify->title.' ('.$result.') was deleted by user '.$myName.'('.$myEmail.') ');
		}
		else{
			$data=array('error' => 'Cannot Edit that item');
		}
		 
      	echo json_encode($data);
      	exit; 
	}
	
	 
}