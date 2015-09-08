<?
class Media_model extends MY_Model{
    	
    protected $_table_name='media_database';
	protected $_primary_key='media_id';
	protected $_primary_filter='intval';
	protected $_order_by='media_id DESC';
	public $rules=array();
	protected $_timestamp=TRUE;
	
	function __construct(){
		parent::__construct();
	}
	
	//-------------------------------------------------------------------------------------------------------
	//Get Media from database
	//-------------------------------------------------------------------------------------------------------
	public function getMedia($id=NULL, $resultLimit=NULL, $offset=NULL){
		$myRole=$this->session->userdata('role');
		$myID=$this->session->userdata('id');
			
		//Only limit view if not superadmin
		if($myRole<9){
			$this->db->where('author_id =', $myID);
		}
		
		if($resultLimit !== NULL && $offset!==NULL){
			$this->db->limit(intval($resultLimit), intval($offset));			
		}
		
		return $this->get($id);
		
	}
	//-------------------------------------------------------------------------------------------------------
	//Save normal uploads to server
	//-----------------------------------------------------------------------------------------------------
	public function uploadMedia($location=NULL, $embed=NULL, $md5=NULL, $visibleWhen, $title, $stub, $loggedOnly, $id=NULL, $vintage=NULL){
		$myID=$this->session->userdata('id');
			
		$data=array(
			'author_id'=>$myID,
			'title'=>$title,
			'stub'=>$stub,
			'visibleWhen'=>$visibleWhen,
			'loggedOnly'=>$loggedOnly,
		); 
		// Auxiliary data
		if($md5!==NULL){
			$data['md5Hash']=$md5;
		}
		if($vintage!==NULL){
			$data['vintage']=$vintage;
		}	
		// Primary data on item added
		if($embed!==NULL){
			$data['embed']=$embed;
		}
		if($location!==NULL){
			$data['fileLoc']=$location;
		}


		$photoId=$this->save($data, $id);
		
		return $photoId;
	}
	//--------------------------------------------------------------------------------------------------------------------
	//Get only photos from database, use limit
	//--------------------------------------------------------------------------------------------------------------------
	public function getPhotos($id=NULL, $vintage=0, $logged=1, $resultLimit=NULL, $offset=NULL){
		
		$now=date('Y-m-d H:i:s');	
		$visArr=array('visibleWhen <='=> $now, 'visibleWhen !=' => '0000-00-00 00:00:00');
		$this->db->where('fileLoc !=', '');
		
		$this->db->where($visArr);
		//Only limit to vintage or nonvintage when proper value
		if($vintage !== NULL){
			$this->db->where('vintage', intval($vintage));
		}
		//Only limit when logging needs to limit
		if($logged !== NULL){
			$this->db->where('loggedOnly', intval($logged));
		}
		//Pagination
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
	
	public function getPhotoCount($vintage=0, $logged=1){
		$now=date('Y-m-d H:i:s');	
		$visArr=array('visibleWhen <='=> $now, 'visibleWhen !=' => '0000-00-00 00:00:00');
		$this->db->where('fileLoc !=', '');
		
		$this->db->where($visArr);
		if($vintage !== NULL){
			$this->db->where('vintage', intval($vintage));
		}
		if($logged !== NULL){
			$this->db->where('loggedOnly', intval($vintage));
		}
		return $this->db->count_all_results($this->_table_name);
	}
		
	//---------------------------------------------------------------------------------------------------------------------------
	//Get only embeds from database, use limit 
	//---------------------------------------------------------------------------------------------------------------------------
	public function getEmbeds($id=NULL ,$vintage=0, $logged=1, $resultLimit=NULL, $offset=NULL){
		
		$now=date('Y-m-d H:i:s');	
		$visArr=array('visibleWhen <='=> $now, 'visibleWhen !=' => '0000-00-00 00:00:00');
		
		$this->db->where('embed !=', '');
		$this->db->where($visArr);
		//Only limit to vintage or nonvintage when proper value
		if($vintage != NULL){
			$this->db->where('vintage', intval($vintage));
		}
		//Only limit when logging needs to limit
		if($logged != NULL){
			$this->db->where('loggedOnly', intval($vintage));
		}
		//Pagination
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
	public function getEmbedCount($vintage=0, $logged=1){
		$now=date('Y-m-d H:i:s');	
		$visArr=array('visibleWhen <='=> $now, 'visibleWhen !=' => '0000-00-00 00:00:00');
		$this->db->where('embed !=', '');
		
		$this->db->where($visArr);
		if($vintage != NULL){
			$this->db->where('vintage', intval($vintage));
		}
		if($logged != NULL){
			$this->db->where('loggedOnly', intval($vintage));
		}
		return $this->db->count_all_results($this->_table_name);
	}
	
	
	
}
