<?
class Staticpages_model extends MY_Model{
    	
    protected $_table_name='static_database';
	protected $_primary_key='static_id';
	protected $_primary_filter='intval';
	protected $_order_by='static_id DESC';
	public $rules=array();
	protected $_timestamp=TRUE;
	
	function __construct(){
		parent::__construct();
	}
	
	
	
	public function saveContact($body, $title='', $id=NULL){
		$myID=$this->session->userdata('id');
			
		$data=array(
			'title'=>$title,
			'page'=>'contact',
			'author_id'=>$myID,
			'body'=>$body,
		); 
		

		$rowId=$this->save($data, $id);
		
		return $rowId;
	}
	

	//------------------------------------------------------------------------------------------------------
	//Display limited list of recent activites
	//-----------------------------------------------------------------------------------------------------
	public function getContact($id=NULL, $limit=NULL, $offset=NULL)
	{
		
		$this->db->where('page =', 'contact');
		
		if($limit !== NULL){
			if($offset!==NULL && intval($offset)>0){
				$this->db->limit(intval($resultLimit), intval($offset));
			}
			else {
				$this->db->limit(intval($limit));
			}
						
		}
	
		return $this->get($id);
	}
	//-----------------------------------------------------------------------------------------------------------
	//
	//---------------------------------------------------------------------------------------------------------
	public function getSpecificContact($category=NULL)
	{
		$this->db->where('page =', 'contact');
		
		if($category !== NULL){
			$this->db->where('category =', $category);
		}
		$this->db->limit(1);
		return $this->get();
	}
	
}
