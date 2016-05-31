<?
class Vendor_model extends MY_Model{
    	
    protected $_table_name='items_database';
	protected $_primary_key='item_id';
	protected $_primary_filter='intval';
	protected $_order_by='item_id DESC';
	public $rules=array(
		'price'=>array(
			'field'=>'price', 
			'label'=>'Price', 
			'rules'=>'trim|required|xss_clean|max_length[6]|greater_than[0]|decimal'
		),
		'title'=>array(
			'field'=>'title', 
			'label'=>'Title', 
			'rules'=>'trim|required|xss_clean'
		),
		'short_desc'=>array(
			'field'=>'short_desc', 
			'label'=>'Shorthand Description', 
			'rules'=>'trim|required|xss_clean'
		)
		
		
		
		);
	protected $_timestamp=TRUE;
	
	function __construct(){
		parent::__construct();
	}
	
	
	
	public function saveContact($body, $title='', $id=NULL){
			
		$data=array(
			'title'=>$title,
			'page'=>'contact',
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
