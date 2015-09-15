<?

class paging extends CI_Controller{

	function __construct(){
		parent::__construct();
	}
	//--------------------------------------------------------------------
	//Paging abstraction
	//--------------------------------------------------------------------
	function nextPage(){
		header('content-type: text/javascript');
		$offset = intval($this->input->get_post('offset')); 
		$database = $this->input->get_post('database'); 
		$type = $this->input->get_post('type');
		if(!(isset($offset))||!(isset($database))||!(isset($type))){
			$data=array('error' => "Error in intial data");
		} 
		else{ 
			$offset++;
			$result=$this->determineData($database, $type, $offset);
			if($result!=false){
				$data=array('success' => $result); 
			}
			else {
				$data=array('failure' => "Error retrieving data"); 
			}
		}
      	echo json_encode($data);
      	exit; 
	}
	
	function prevPage(){
		header('content-type: text/javascript');
		$offset = intval($this->input->get_post('offset')); 
		$database = $this->input->get_post('database'); 
		$type = $this->input->get_post('type'); 
		
		if(!(isset($offset))||!(isset($database))||!(isset($type))){
			$data=array('error' => "Error in intial data");
		} 
		else{
			if($offset>0){
				$offset--;
			}	
			$result=$this->determineData($database, $type, $offset);
			if($result!=false){
				$data=array('success' => $result); 
			}
			else {
				$data=array('failure' => "Error retrieving data"); 
			}
		}
      	echo json_encode($data);
      	exit; 
	}
	// Figure out which datatable to load
	private function determineData($database, $type, $offset){
		switch ($database) {
			case 'video':
				$results=$this->getVideo($offset, $type);
				break;
			case 'image':
				$results=$this->getImage($offset, $type);
				break;
			case 'news':
				$results=$this->getNews($offset);
				break;
			default:
				$results=false;
				break;
		}
		return $results;
	}
	//Used in cases where there is a vintage tab
	private function determineVintage($type){
		switch ($type) {
			case 'primary':
				$result=0;
				break;
			
			default:
				$result=1;
				break;
		}
		return $result;
	}
	//----------------------------------------------------------------------------
	//News paging
	//--------------------------------------------------------------------------

	
	// TODO Need to adjust this for any auto jump to page
	function specificPage(){
		header('content-type: text/javascript');
		$offset = abs(intval($this->input->get_post('offset'))); 
		$result=$this->getNews($offset);
		$data=array('success' => $result); 
      	echo json_encode($data);
      	exit; 
	}
	
	private function getNews($paging=0){
		$this->load->model('Article_model');
		$this->load->model('Dataprep_model');
		$maxLimit=7;
		$offset=$paging*$maxLimit;
		
		$articles=$this->Article_model->getNewsPublic(NULL, $maxLimit, $offset);
		$maxItems=$this->Article_model->getNewsCount();
		
	
		if(count($articles)){
			return $this->Dataprep_model->gatherItems($articles, "news", "news_id", "news", 1, $maxItems, $maxLimit, $paging);
		}
		else{
			return "<div><h4>That article does not exist.</h4></div>";
		}
	}
	//-------------------------------------------------------------------------
	//Image paging
	//-----------------------------------------------------------------------
	
	
	private function getImage($paging=0, $type){
		$this->load->model('Media_model');
		$this->load->model('Dataprep_model');
		$maxLimit=7;
		$offset=$paging*$maxLimit;
		
		$vintage=$this->determineVintage($type);
		
		$currentRole=$this->session->userdata('role');
		if($currentRole===FALSE){
			$currentRole=0;
		}
		
		if($currentRole>0){
		// Show only images that do not require logging in
			$myMedia=$this->Media_model->getPhotos(NULL, $vintage, NULL, $maxLimit, $offset);
			$maxItems=$this->Media_model->getPhotoCount($vintage, NULL);
		}
		else{
		// Show media for logged in users	
			$myMedia=$this->Media_model->getPhotos(NULL, $vintage, 0, $maxLimit, $offset);
			$maxItems=$this->Media_model->getPhotoCount($vintage, 0);	
			
		}
	
		if(count($myMedia)){
			return $this->Dataprep_model->gatherItems($myMedia, "media", "media_id", "photos", 3, $maxItems, $maxLimit, $paging, $type);
		}
		else{
			return "<div><h4>That image does not exist.</h4></div>";
		}
	}
	//------------------------------------------------------------------------
	//Video paging
	//-----------------------------------------------------------------------
	
	private function getVideo($paging=0, $type){
		$this->load->model('Media_model');
		$this->load->model('Dataprep_model');
		$maxLimit=7;
		$offset=$paging*$maxLimit;
		
		$vintage=$this->determineVintage($type);
		
		$currentRole=$this->session->userdata('role');
		if($currentRole===FALSE){
			$currentRole=0;
		}
		
		if($currentRole>0){
			// show only embeded media that do not require login
			$myMedia=$this->Media_model->getEmbeds(NULL, $vintage, NULL, $maxLimit, $offset);
			$maxItems=$this->Media_model->getEmbedCount($vintage,NULL);
			
		}
		else{
			// Show all embedded media for logged in users
			$myMedia=$this->Media_model->getEmbeds(NULL, $vintage, 0, $maxLimit, $offset);
			$maxItems=$this->Media_model->getEmbedCount($vintage, 0);
		}
	
		if(count($myMedia)){
			return $this->Dataprep_model->gatherItems($myMedia, "media", "media_id", "videos", 1, $maxItems, $maxLimit, $paging, $type);
		}
		else{
			return "<div><h4>That video does not exist.</h4></div>";
		}
	} 
	
	
	
	
	
}