<?

class Postmedia extends MY_Controller{

	function __construct(){
		parent::__construct();
	}


//--------------------------------------------------------------------------------------------------
// MEDIA RELATED FEATURES
//
//-------------------------------------------------------------------------------------------------
	function CIUpload(){
		
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		$myRole=$_SESSION['role'];
		$myID=$_SESSION['id'];
		$myName=$_SESSION['name'];
		$myEmail=$_SESSION['email'];
		$section = $this->simplePurify($this->input->post('section')); 
          
          if(!$this->verifySection($section)){
               $data=array('error' => "Section invalid or does not exist");
               $this->load->model("Errorlog_model");
               $this->Errorlog_model->newLog(-1, 'aMed', 'Media item failed to upload. Section ('.$section.') invalid. User role '.$myRole);  
               echo json_encode($data);
               exit; 
          }
          
          
          
		/* 
		// Support CORS
		header("Access-Control-Allow-Origin: *");
		// other CORS headers if any...
		if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
			exit; // finish preflight CORS requests here
		}
		*/
		
		// 5 minutes execution time
		@set_time_limit(5 * 60);
		
		// Uncomment this one to fake upload time
		// usleep(5000);
		
		// Settings
		// $targetDir = ini_get("upload_tmp_dir") . DIRECTORY_SEPARATOR . "plupload";
		$uploadDir= 'uploads';
		$targetDir = './'.$uploadDir.'/';
		$cleanupTargetDir = true; // Remove old files
		$maxFileAge = 5 * 3600; // Temp file age in seconds
		
		
		// Create target dir
		if (!file_exists($targetDir)) {
			@mkdir($targetDir);
		}
		
		// Get a file name
		if (isset($_REQUEST["name"])) {
			$fileName = $_REQUEST["name"];
		} elseif (!empty($_FILES)) {
			$fileName = $_FILES["file"]["name"];
		} else {
			$fileName = uniqid("file_");
		}
		
		$filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;
		
		// Chunking might be enabled
		$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
		$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;
		
		
		// Remove old temp files	
		if ($cleanupTargetDir) {
			if (!is_dir($targetDir) || !$dir = opendir($targetDir)) {
				$data=array('error' => "Failed to open temp directory."); 
				$this->load->model("Errorlog_model");
				$this->Errorlog_model->newLog(-1, 'aMed', 'Media item failed to upload. Temp directory error'); 
      			echo json_encode($data);
      			exit; 
			}
		
			while (($file = readdir($dir)) !== false) {
				$tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;
		
				// If temp file is current file proceed to the next
				if ($tmpfilePath == "{$filePath}.part") {
					continue;
				}
		
				// Remove temp file if it is older than the max age and is not the current file
				if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge)) {
					@unlink($tmpfilePath);
				}
			}
			closedir($dir);
		}	
		
		
		// Open temp file
		if (!$out = @fopen("{$filePath}.part", $chunks ? "ab" : "wb")) {
			$data=array('error' => "Failed to open output stream."); 
			$this->load->model("Errorlog_model");
			$this->Errorlog_model->newLog(-1, 'aMed', 'Media item failed to upload. Output stream error'); 
  			echo json_encode($data);
  			exit;
		}
		
		if (!empty($_FILES)) {
			if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
				$data=array('error' => "Failed to move uploaded file."); 
				$this->load->model("Errorlog_model");
				$this->Errorlog_model->newLog(-1, 'aMed', 'Media item failed to upload. Move uploaded file error'); 
  				echo json_encode($data);
  				exit;
			}
		
			// Read binary input stream and append it to temp file
			if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
				$data=array('error' => "Failed to open input stream."); 
				$this->load->model("Errorlog_model");
				$this->Errorlog_model->newLog(-1, 'aMed', 'Media item failed to upload. Input stream error'); 
  				echo json_encode($data);
  				exit;
			}
		} else {	
			if (!$in = @fopen("php://input", "rb")) {
				$data=array('error' => "Failed to open input stream.");
				$this->load->model("Errorlog_model");
				$this->Errorlog_model->newLog(-1, 'aMed', 'Media item failed to upload. Input stream error'); 
  				echo json_encode($data);
  				exit;
			}
		}
		
		while ($buff = fread($in, 4096)) {
			fwrite($out, $buff);
		}
		
		@fclose($out);
		@fclose($in);
		
		// Check if file has been uploaded
		if (!$chunks || $chunk == $chunks - 1) {
			// Strip the temp .part suffix off 
			rename("{$filePath}.part", $filePath);
		}
		
		if ($chunk==0){
			$fileMD5hash=md5_file($filePath);
			$title = $this->simplePurify($this->input->post('title')); 
			$stub = $this->simplePurify($this->input->post('stub')); 
      		$visibleWhen = $this->simplePurify($this->input->post('visibleWhen'));
			$loggedOnly = $this->simplePurify(intval($this->input->post('loggedOnly')));
			$exFlag = $this->simplePurify($this->input->post('exFlag'));
			$mediaType=$this->simplePurify($this->input->post('mediaType'));
			$this->load->model("Media_model");
			$loc=base_url().$uploadDir.'/'.$fileName;
			//TODO need to prevent duplicates
        	$result=$this->Media_model->uploadMedia($loc, NULL, $mediaType, $fileMD5hash, $visibleWhen, $title, $stub, $loggedOnly, $exFlag, $section);
		}
		
		
		$data=array('success' => "Upload successful", 'chunks'=>$loggedOnly); 
		$this->load->model("Logging_model");
		$this->Logging_model->newLog($result, 'aMed', 'Media item '.$fileName.' ('.$result.') was added by user '.$myName.'('.$myEmail.') ');
		echo json_encode($data);
		exit;
		
		
	}
	
	//---------------------------------------------------------------------------	
	//
	//---------------------------------------------------------------------------
	function addEmbedMedia(){
		header('content-type: text/javascript');
		$myRole=$_SESSION['role'];
		$myName=$_SESSION['name'];
		$myEmail=$_SESSION['email'];
		$title = $this->simplePurify($this->input->post('title')); 
		$stub = $this->simplePurify($this->input->post('stub')); 
		$mediaType = $this->simplePurify($this->input->post('mediaType')); 
		$loggedOnly = $this->simplePurify(intval($this->input->post('loggedOnly')));
      	$visibleWhen = $this->simplePurify($this->input->post('visibleWhen'));
		$uncleanText = $this->input->post('embed');
		$section = $this->simplePurify($this->input->post('section')); 
		$exFlag = $this->simplePurify($this->input->post('exFlag')); 
		
		if($myRole<$this->config->item('contributor')){
			$data=array('error' => "Insufficient privledges"); 
			$this->load->model("Errorlog_model");
			$this->Errorlog_model->newLog(-1, 'aEmb', 'Embed item failed to upload. Insufficient privledges. User role '.$myRole); 
      		echo json_encode($data);
      		exit; 
		}
		if(!$this->verifySection($section)){
               $data=array('error' => "Section invalid or does not exist");
               $this->load->model("Errorlog_model");
               $this->Errorlog_model->newLog(-1, 'aEmb', 'Embed item failed to upload. Section ('.$section.') invalid. User role '.$myRole);  
               echo json_encode($data);
               exit; 
          }
		
		// $this->load->helper('htmlpurifier');
		// $clean_html = html_purify($uncleanText);
		
		if(empty($uncleanText) || empty($title) || empty($stub) || empty($mediaType)){
			$data=array('error' => "Required text field is empty"); 
			$this->load->model("Errorlog_model");
			$this->Errorlog_model->newLog(-1, 'aEmb', 'Embed item failed to upload. Required field empty'); 
      		echo json_encode($data);
      		exit; 
		}
		//Base case
		$medias=$this->config->item('recognizedMedia');
		if(in_array($mediaType, $medias)===TRUE){
			$md5=md5($uncleanText);
			$this->load->model("Media_model");
			$result=$this->Media_model->uploadMedia(NULL, $uncleanText, $mediaType, $md5, $visibleWhen, $title, $stub, $loggedOnly, $exFlag, $section);
			//Log a good entry		
			$this->load->model("Logging_model");
			$this->Logging_model->newLog($result, 'aEmb', 'Embed item '.$title.' ('.$result.') uploaded successfully by '.$myName.'('.$myEmail.')'); 
			$data=array('success' => $result); 
      		echo json_encode($data);
      		exit;
		} 
		//Missing media type or someone attempts to rename it
		else{
			$data=array('error' => "Media type was unexpected"); 
			$this->load->model("Errorlog_model");
			$this->Errorlog_model->newLog(-1, 'aEmb', 'Media type was unexpected. Attempted to store: '.$mediaType); 
      		echo json_encode($data);
      		exit; 
		}
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	//Save both embed and photo based edits. Doesnt allow the main content to change
	//---------------------------------------------------------------------------------------------------------------
	function saveMediaEdit(){
		header('content-type: text/javascript');
		$myRole=$_SESSION['role'];
		$myID=$_SESSION['id'];
		$myName=$_SESSION['name'];
		$myEmail=$_SESSION['email'];
		$mediaID = intval($this->simplePurify($this->input->post('mediaID'))); 
		$title = $this->simplePurify($this->input->post('title')); 
		$stub = $this->simplePurify($this->input->post('stub')); 
		$mediaType = $this->simplePurify($this->input->post('mediaType')); 
      	$visibleWhen = $this->simplePurify($this->input->post('visibleWhen'));
		$loggedOnly = $this->simplePurify(intval($this->input->post('loggedOnly')));
		$vintage = $this->simplePurify(intval($this->input->post('vintage'))); 
		$section = $this->simplePurify($this->input->post('section')); 
		$exFlag = $this->simplePurify($this->input->post('exFlag')); 
		
		if(empty($mediaID)){
			$data=array('error' => "Error retrieving MediaID"); 
			$this->load->model("Errorlog_model");
			$this->Errorlog_model->newLog(-1, 'eMed', 'Media item failed to be reuploaded. Error retrieving mediaID'); 
      		echo json_encode($data);
      		exit; 
		} 
		if($myRole<$this->config->item('contributor')){
			$data=array('error' => "Insufficient privledges"); 
      		echo json_encode($data);
			$this->load->model("Errorlog_model");
			$this->Errorlog_model->newLog($mediaID, 'eMed', 'Media item '.$title.' ('.$mediaID.') failed to be reuploaded. Insufficient privledges. User '.$myID.' role '.$myRole); 
      		exit; 
		}
          if(!$this->verifySection($section)){
               $data=array('error' => "Section invalid or does not exist");
               $this->load->model("Errorlog_model");
               $this->Errorlog_model->newLog($mediaID, 'eMed', 'Media item failed to reupload. Section ('.$section.') invalid. User role '.$myRole);  
               echo json_encode($data);
               exit; 
          }
		
		$medias=$this->config->item('recognizedMedia');
		if(in_array($mediaType, $medias)===FALSE){
			$data=array('error' => "Media type was unexpected"); 
			$this->load->model("Errorlog_model");
			$this->Errorlog_model->newLog($mediaID, 'eMed', 'Media type for'.$title.' ('.$mediaID.') was unexpected. Attempted to store: '.$mediaType); 
      		echo json_encode($data);
      		exit; 
		}
		
		$this->load->model("Media_model");
		
		// Verify user has rights to media
		$verify=$this->Media_model->get($mediaID, TRUE);
		if($verify->author_id==$myID || $myRole>$this->config->item('sectionAdmin')){
			$result=$this->Media_model->uploadMedia(NULL, NULL, $mediaType, NULL, $visibleWhen, $title, $stub, $loggedOnly, $exFlag, $section, $mediaID, $vintage);
			$data=array('success' => $result);
			$this->load->model("Logging_model");
			$this->Logging_model->newLog($mediaID, 'eMed', 'Media item '.$title.' ('.$mediaID.') edit saved successfully by '.$myName.'('.$myEmail.')'); 
		}
		else{
			$data=array('error' => 'Cannot Edit that item');
			$this->load->model("Errorlog_model");
			$this->Errorlog_model->newLog($mediaID, 'eMed', 'Media item '.$title.' ('.$mediaID.') edit failed. Not permitted to edit that item.'); 
		}
		 
      	echo json_encode($data);
      	exit; 
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	//Delete media item from database (and if file exists, that as well)
	//----------------------------------------------------------------------------------------------------------------
	function deleteSpecificMedia(){
		header('content-type: text/javascript');
		$myRole=$_SESSION['role'];
		$myID=$_SESSION['id'];
		$myName=$_SESSION['name'];
		$myEmail=$_SESSION['email'];
		$mediaID = intval($this->input->post('mediaID')); 
		
		if(empty($mediaID)){
			$data=array('error' => "Error retrieving MediaID"); 
      		echo json_encode($data);
      		exit; 
		}
		if($myRole< $this->config->item('contributor')){
			$data=array('error' => "Insufficient privledges"); 
      		$this->load->model("Errorlog_model");
			$this->Errorlog_model->newLog($mediaID, 'dMed', 'Media item '.$mediaID.' delete failed. Insufficient privledges. User '.$myID.' role '.$myRole); 
      		echo json_encode($data);
      		exit; 
		}
		 
		
		$this->load->model("Media_model");
		
		// Verify user has rights to media
		$verify=$this->Media_model->get($mediaID, TRUE);
		$location=NULL;
		if($verify->author_id==$myID || $myRole>$this->config->item('sectionAdmin')){
			if(!empty($verify->fileLoc)){
				$location=explode(base_url(),$verify->fileLoc);
				if(file_exists($location[1])){
					unlink($location[1]);
				}
				//TODO Potential spot for error log for file did not exist
			}
			$result=$this->Media_model->delete($mediaID);
			$data=array('success' => $mediaID);
			$this->load->model("Logging_model");
			if($location===NULL){
				$this->Logging_model->newLog($mediaID, 'dMed', 'Media item embed'.$verify->title.' delete successful by '.$myName.'('.$myEmail.')'); 
			}
			else{
				$this->Logging_model->newLog($mediaID, 'dMed', 'Media item '.$location[1].' delete successful by '.$myName.'('.$myEmail.')'); 
			}
			
		}
		else{
			$data=array('error' => 'Cannot Edit that item');
			$this->load->model("Errorlog_model");
			if($location===NULL){
				$this->Logging_model->newLog($mediaID, 'dMed', 'Media item embed'.$verify->title.' delete failed. Not permitted to edit that item.'); 
			}
			else{
				$this->Errorlog_model->newLog($mediaID, 'dMed', 'Media item '.$location[1].' delete failed. Not permitted to edit that item.'); 
			}
			
		}
		 
      	echo json_encode($data);
      	exit; 
	}
//---------------------------------------------------------------------------------
//shared fucntions
//---------------------------------------------------------------------------------
	
	 
}