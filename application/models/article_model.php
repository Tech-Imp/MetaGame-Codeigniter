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
		if($myRole<9){
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
	public function postArticles($author, $visibleWhen, $title, $stub, $body, $id=NULL){
		$data=array(
			'title'=>$title,
			'stub'=>$stub,
			'author_id'=>$author,
			'body'=>$body,
			'visibleWhen'=>$visibleWhen,
		); 
		$newsId=$this->save($data, $id);
		
		return $newsId;
	}
	//--------------------------------------------------------------------------------------------------
	//Get articles in a limit/offest way only for valid timestamped articles
	//---------------------------------------------------------------------------------------------------
	public function getNewsPublic($id=NULL, $resultLimit=NULL, $offset=NULL){
		
		$now=date('Y-m-d H:i:s');	
		$visArr=array('visibleWhen <='=> $now, 'visibleWhen !=' => '0000-00-00 00:00:00');
		
		//Whole section dedicated to making sure that items can be separated based on 
		//exclusive areas.
		//TODO Test abstract this to my_model
		$excludeLoc=array(
			'main',
			'admin'
		);
		
		$here=explode('/', uri_string());
		if (in_array($here[1], $excludeLoc)==FALSE){
			$this->db->where('forSection ==', $here[1]);
		}
		else{
			$this->db->where('exclusiveSection ==', 0);
		}
		// End content Exclusion
		
		$this->db->where('body !=', '');
		$this->db->where($visArr);
		
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
	//Get count of valid articles
	//-------------------------------------------------------------------------------------------------------
	public function getNewsCount($findViaTime=true){
		
		if($findViaTime){
			$now=date('Y-m-d H:i:s');	
			$visArr=array('visibleWhen <='=> $now, 'visibleWhen !=' => '0000-00-00 00:00:00');
			$this->db->where('body !=', '');
			$this->db->where($visArr);
		}
		
		return $this->db->count_all_results($this->_table_name);
	}
	
	
}
