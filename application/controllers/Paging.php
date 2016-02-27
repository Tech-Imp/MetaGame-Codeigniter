<?

class Paging extends CI_Controller{

	function __construct(){
		parent::__construct();
	}
	//--------------------------------------------------------------------
	//Paging abstraction
	//--------------------------------------------------------------------
	function nextPage(){
		header('content-type: text/javascript');
		$offset = intval($this->input->post('offset')); 
		$database = $this->input->post('database'); 
		$type = $this->input->post('type');
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
		$offset = intval($this->input->post('offset')); 
		$database = $this->input->post('database'); 
		$type = $this->input->post('type'); 
		
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
		$loc=explode("/", $database);
		// var_dump($loc);
		switch (end($loc)) {
			case 'video':
				$results=$this->getVideo($offset, $type, $loc[1], $loc[0]);
				break;
			case 'photos':
				$results=$this->getImage($offset, $type, $loc[1], $loc[0]);
				break;
			case 'news':
				$results=$this->getNews($offset, $loc[1], $loc[0]);
				break;
			case 'multimedia':
				$results=$this->getMedia($offset, $loc[1]);
				break;
			case 'media':
				$whichType=explode(".", $type);
				if(end($whichType)=="videos"){
					$results=$this->getVideo($offset, $type, $loc[1], $loc[0]);
				}
				elseif (end($whichType)=="photos") {
					$results=$this->getImage($offset, $type, $loc[1], $loc[0]);
				}
				else{
					$results=false;
				}
				break;
			case 'articles':
				$results=$this->getArticles($offset, $loc[1], $loc[0]);
				break;
			case 'written':
				$results=$this->getWritten($offset, $loc[1]);
				break;
			default:
				$results=false;
				break;
		}
		return $results;
	}
	//Used in cases where there is a vintage tab
	private function determineVintage($type){
		$vinType=explode(".", $type);	
			
		switch ($vinType[0]) {
			case 'primary':
				$result=0;
				break;
			case 'all':
				$result=NULL;
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
		$offset = intval($this->input->post('offset')); 
		$database = $this->input->post('database'); 
		$type = $this->input->post('type'); 
		
		if(!(isset($offset))||!(isset($database))||!(isset($type))){
			$data=array('error' => "Error in intial data");
		} 
		$result=$this->determineData($database, $type, $offset);
		if($result!=false){
			$data=array('success' => $result); 
		}
		else {
			$data=array('failure' => "Error retrieving data"); 
		}
		
      	echo json_encode($data);
      	exit; 
	}
	
	
	//-------------------------------------------------------------------------
	//Image paging
	//-----------------------------------------------------------------------
	
	
	private function getImage($paging=0, $type, $currentLoc=null, $parent=NULL){
		$this->load->model('Media_model');
		$this->load->model('Dataprep_model');
		$maxLimit=$this->config->item('maxSMedia');
		$offset=$paging*$maxLimit;
		
		$vintage=$this->determineVintage($type);
		
		// $vintage=NULL; //DEBUG LINE
		
		$currentRole=$this->session->userdata('role');
		if($currentRole===FALSE){
			$currentRole=0;
		}
		
		if($currentRole>0){
		// Show only images that do not require logging in
			$myMedia=$this->Media_model->getPhotos(NULL, $vintage, NULL, $maxLimit, $offset, $currentLoc);
			$maxItems=$this->Media_model->getPhotoCount($vintage, NULL, $currentLoc);
		}
		else{
		// Show media for logged in users	
			$myMedia=$this->Media_model->getPhotos(NULL, $vintage, 0, $maxLimit, $offset, $currentLoc);
			$maxItems=$this->Media_model->getPhotoCount($vintage, 0, $currentLoc);	
			
		}
	
		if(count($myMedia)){
			return $this->Dataprep_model->gatherItemsRedirect($myMedia, "media", "media_id", "photos", 3, $maxItems, $maxLimit, $paging, $type, $parent);
		}
		else{
			return "<div><h4>That image does not exist.</h4></div>";
		}
	}
	//------------------------------------------------------------------------
	//Video paging
	//-----------------------------------------------------------------------
	
	private function getVideo($paging=0, $type, $currentLoc=null, $parent=NULL ){
		$this->load->model('Media_model');
		$this->load->model('Dataprep_model');
		$maxLimit=$this->config->item('maxMMedia');
		$offset=$paging*$maxLimit;
		
		$vintage=$this->determineVintage($type);
		// $vintage=NULL; //DEBUG LINE
		
		$currentRole=$this->session->userdata('role');
		if($currentRole===FALSE){
			$currentRole=0;
		}
		
		if($currentRole>0){
			// show only embeded media that do not require login
			$myMedia=$this->Media_model->getEmbeds(NULL, $vintage, NULL, $maxLimit, $offset, $currentLoc);
			$maxItems=$this->Media_model->getEmbedCount($vintage,NULL, $currentLoc);
			
		}
		else{
			// Show all embedded media for logged in users
			$myMedia=$this->Media_model->getEmbeds(NULL, $vintage, 0, $maxLimit, $offset, $currentLoc);
			$maxItems=$this->Media_model->getEmbedCount($vintage, 0, $currentLoc);
		}
	
		if(count($myMedia)){
			return $this->Dataprep_model->gatherItemsRedirect($myMedia, "media", "media_id", "video", 3, $maxItems, $maxLimit, $paging, $type, $parent);
		}
		else{
			return "<div><h4>That video does not exist.</h4></div>";
		}
	} 
	//------------------------------------------------------------------------------
	//Written areas paging
	//------------------------------------------------------------------------------
	private function getArticles($paging=0, $currentLoc=null, $parent=NULL){
		$this->load->model('Article_model');
		$this->load->model('Dataprep_model');
		$maxLimit=$this->config->item('maxArticles');
		$offset=$paging*$maxLimit;
		$articles=$this->Article_model->getArticlesPublic(NULL, $maxLimit, $offset, $currentLoc);
		$maxNewsCount=$this->Article_model->getArticlesCount(true, $currentLoc);
		
		if(count($articles)){
			return $this->Dataprep_model->gatherItemsRedirect($articles, "article", "news_id", "articles", 1, $maxNewsCount, $maxLimit, $paging, $parent);
		}
		else{
			return "<div><h4>That article does not exist.</h4></div>";
		}
	}

	private function getNews($paging=0, $currentLoc=null, $parent=NULL){
		$this->load->model('Article_model');
		$this->load->model('Dataprep_model');
		$maxLimit=$this->config->item('maxArticles');;
		$offset=$paging*$maxLimit;
		
		$articles=$this->Article_model->getNewsPublic(NULL, $maxLimit, $offset, $currentLoc);
		$maxItems=$this->Article_model->getNewsCount(true, $currentLoc);
		
	
		if(count($articles)){
			return $this->Dataprep_model->gatherItemsRedirect($articles, "news", "news_id", "news", 1, $maxItems, $maxLimit, $paging, $parent);
		}
		else{
			return "<div><h4>That article does not exist.</h4></div>";
		}
	}


	//---------------------------------------------------------------------------
	//Articles paging on the dashboard
	//--------------------------------------------------------------------------
	private function getWritten($paging=0, $currentLoc=null){
		$this->load->model('Article_model');
		$this->load->model('Dataprep_model');
		$maxLimit=$this->config->item('maxAdmin');
		$offset=$paging*$maxLimit;
		
		$articles=$this->Article_model->getArticles(NULL, $maxLimit, $offset, $currentLoc);
		$maxNewsCount=$this->Article_model->getWrittenCount(false, $currentLoc);
		
	
		if(count($articles)){
			return $this->Dataprep_model->gatherItemsAdmin($articles, "news", "news_id", "editNews", $maxNewsCount, $maxLimit, $paging);
		}
		else{
			return "<div><h4>That article does not exist.</h4></div>";
		}
	}
	//---------------------------------------------------------------------------
	//Media pgaing on the dashboard
	//------------------------------------------------------------------------------
	private function getMedia($paging=0, $currentLoc=null){
		$this->load->model('Media_model');
		$this->load->model('Dataprep_model');
		$maxLimit=$this->config->item('maxAdmin');
		$offset=$paging*$maxLimit;
		
		$myMedia=$this->Media_model->getMedia(NULL, $maxLimit, $offset, $currentLoc);
		$maxMediaCount=$this->Media_model->getMediaCount($currentLoc);
		
	
		if(count($myMedia)){
			return $this->Dataprep_model->gatherItemsAdmin($myMedia, "media", "media_id", "editMedia", $maxMediaCount, $maxLimit, $paging);
		}
		else{
			return "<div><h4>That video does not exist.</h4></div>";
		}
	} 
	
	
}