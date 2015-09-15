<?

class paging extends CI_Controller{

	function __construct(){
		parent::__construct();
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
	
	
	private function getImage($paging=0){
		$this->load->model('Media_model');
		$this->load->model('Dataprep_model');
		$maxLimit=7;
		$offset=$paging*$maxLimit;
		
		$articles=$this->Media_model->getPhotos(NULL, $maxLimit, $offset);
		$maxItems=$this->Media_model->getPhotoCount();
		
	
		if(count($articles)){
			return $this->Dataprep_model->gatherItems($articles, "news", "news_id", "news", 1, $maxItems, $maxLimit, $paging);
		}
		else{
			return "<div><h4>That article does not exist.</h4></div>";
		}
	}
	//------------------------------------------------------------------------
	//Video paging
	//-----------------------------------------------------------------------
	function nextVideo(){
		header('content-type: text/javascript');
		$offset = intval($this->input->get_post('offset')); 
		$offset++;
		$result=$this->getNews($offset);
		$data=array('success' => $result); 
      	echo json_encode($data);
      	exit; 
	}
	function prevVideo(){
		
	}
	function specificVideo(){
		
	}
	//--------------------------------------------------------------------
	//Paging abstraction
	//--------------------------------------------------------------------
	function nextPage(){
		header('content-type: text/javascript');
		$offset = intval($this->input->get_post('offset')); 
		$database = $this->input->get_post('database'); 
		$type = $this->input->get_post('type');
		if(empty($offset)||empty($database)||empty($type)){
			$data=array('error' => "Error in intial data");
		} 
		else{ 
			$offset++;
			$result=$this->determineData();
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
		
		if(empty($offset)||empty($database)||empty($type)){
			$data=array('error' => "Error in intial data");
		} 
		else{
			if($offset>0){
				$offset--;
			}	
			$result=$this->determineData();
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
	
	
	
	
	
}