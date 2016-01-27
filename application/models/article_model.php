<?
class Article_model extends MY_Model{
    	
    protected $_table_name='news_database';
	protected $_primary_key='news_id';
	protected $_primary_filter='intval';
	protected $_order_by='news_id DESC';
	public $rules=array();
	protected $_timestamp=TRUE;
	
	function __construct(){
		parent::__construct();
	}
	
	//-------------------------------------------------------------------------------------------------------
	//Get articles from database
	//-------------------------------------------------------------------------------------------------------
	public function getArticles($id=NULL, $resultLimit=NULL, $offset=NULL){
		$myRole=$this->session->userdata('role');
		$myID=$this->session->userdata('id');
	
		
		//Only limit view if not superadmin
		if($myRole<$this->config->item('superAdmin')){
			$this->db->where('author_id =', $myID);
		}
		
		if($resultLimit !== NULL){
			if($offset!==NULL && intval($offset)>0){
				$this->db->limit(intval($resultLimit), intval($offset));
			}
			else {
				$this->db->limit(intval($resultLimit));
			}
						
		}
		return $this->get($id);
		
	}
	
	//-------------------------------------------------------------------------------------------------------
	//Save Articles to database
	//-------------------------------------------------------------------------------------------------------
	public function postArticles($author, $visibleWhen, $title, $stub, $body, $id=NULL, $exFlag, $section){
		$data=array(
			'title'=>$title,
			'stub'=>$stub,
			'author_id'=>$author,
			'body'=>$body,
			'exclusiveSection'=>$exFlag,
			'forSection'=>$section,
			'visibleWhen'=>$visibleWhen,
		); 
		$newsId=$this->save($data, $id);
		
		return $newsId;
	}
	
	//-------------------------------------------------------------------------------------------------
	//News/Articles wrappers
	//-------------------------------------------------------------------------------------------------
	public function getNewsPublic($id=NULL, $resultLimit=NULL, $offset=NULL, $here=null){
		return $this->getWrittenPublic(id, $resultLimit, $offset, $here, "news");
	}
	public function getArticlesPublic($id=NULL, $resultLimit=NULL, $offset=NULL, $here=null){
		return $this->getWrittenPublic(id, $resultLimit, $offset, $here, "articles");
	}
	
	//--------------------------------------------------------------------------------------------------
	//Get written media in a limit/offset way only for valid timestamped articles
	//---------------------------------------------------------------------------------------------------
	public function getWrittenPublic($id=NULL, $resultLimit=NULL, $offset=NULL, $here=null, $type=NULL){
		
		$now=date('Y-m-d H:i:s');	
		$visArr=array('visibleWhen <='=> $now, 'visibleWhen !=' => '0000-00-00 00:00:00');
		
		//Whole section dedicated to making sure that items can be separated based on 
		//exclusive areas.
		$this->restrictSect($here);
		
		// End content Exclusion
		
		$this->db->where('body !=', '');
		$this->db->where($visArr);
		if ($type!==NULL) {
			$this->db->where('type ==', $type);
		}
		
		if($resultLimit !== NULL){
			if($offset!==NULL && intval($offset)>0){
				$this->db->limit(intval($resultLimit), intval($offset));
			}
			else {
				$this->db->limit(intval($resultLimit));
			}
						
		}
		
		return $this->get($id);
	}
	//-------------------------------------------------------------------------------------------------------
	//Get count of valid news/article wrappers
	//-------------------------------------------------------------------------------------------------------
	public function getNewsCount($findViaTime=true, $here=null){
		return $this->getWrittenCount($findViaTime,$here, "news");
	}
	
	public function getArticleCount($findViaTime=true, $here=null){
		return $this->getWrittenCount($findViaTime,$here, "article");
	}
	//----------------------------------------------------------------------------
	//Generic function for all written content
	//----------------------------------------------------------------------------
	public function getWrittenCount($findViaTime=true, $here=null, $type=NULL){
			
		if($type!==NULL){
			$this->db->where('type ==', $type);
		}
		
		if($findViaTime){
			$now=date('Y-m-d H:i:s');	
			$visArr=array('visibleWhen <='=> $now, 'visibleWhen !=' => '0000-00-00 00:00:00');
			$this->db->where('body !=', '');
			$this->db->where($visArr);
		}
		$this->restrictSect($here);
		return $this->db->count_all_results($this->_table_name);
	}
	
	
}
