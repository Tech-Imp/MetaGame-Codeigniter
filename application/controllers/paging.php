<?

class paging extends CI_Controller{

	function __construct(){
		parent::__construct();
	}

	//----------------------------------------------------------------------------
	//News paging
	//--------------------------------------------------------------------------
	function nextNews(){
		header('content-type: text/javascript');
		$offset = intval($this->input->get_post('offset')); 
		$offset++;
		$result=getNews($offset);
		$data=array('success' => $result); 
      	echo json_encode($data);
      	exit; 
	}
	
	function prevNews(){
		header('content-type: text/javascript');
		$offset = intval($this->input->get_post('offset'));
		if($offset>0){
			$offset--;
		} 
		$result=getNews($offset);
		$data=array('success' => $result); 
      	echo json_encode($data);
      	exit; 
	} 
	
	function specificPage(){
		header('content-type: text/javascript');
		$offset = intval($this->input->get_post('offset')); 
		$result=getNews($offset);
		$data=array('success' => $result); 
      	echo json_encode($data);
      	exit; 
	}
	
	private function getNews($offset=0){
		$this->load->model('Article_model');
		$this->load->model('Dataprep_model');
		$maxLimit=7;
		$offset=$offset*$maxLimit;
		
		$articles=$this->Article_model->getNewsPublic(NULL, $maxLimit, $offset);
		$maxItems=$this->Article_model->getNewsCount();
		
	
		if(count($articles)){
			return $this->Dataprep_model->gatherItems($articles, "news", "news_id", "news", 1, $maxItems, $maxLimit, $offset);
		}
		else{
			return "<div><h4>That article does not exist.</h4></div>";
		}
	}
	//-------------------------------------------------------------------------
	//Image paging
	//-----------------------------------------------------------------------
	function nextImage(){
		
	}
	function prevImage(){
		
	}
	function specificImage(){
		
	}
	
	 
}