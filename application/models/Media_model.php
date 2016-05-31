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
	//Get Media from database, primarily used on backend, no media type distinction, except profile pic
	//-------------------------------------------------------------------------------------------------------
	public function getMedia($id=NULL, $resultLimit=NULL, $offset=NULL, $here=null){
		$myRole=$this->session->userdata('role');
		$myID=$this->session->userdata('id');
		if($id===NULL){
			$this->db->where('mediaType !=', 'avatar');
               $this->db->where('mediaType !=', 'logo');
		}
		//Only limit view if not superadmin
		if($myRole<$this->config->item('superAdmin')){
			$this->db->where('author_id =', $myID);
		}
		return $this->getCommonMedia($id, $resultLimit, $offset, $here, $timeNeed=NULL);
	}
	public function getMediaCount($here=null){
		$myRole=$this->session->userdata('role');
		$myID=$this->session->userdata('id');
		$this->db->where('mediaType !=', 'avatar');
		$this->db->where('mediaType !=', 'logo');
		//Only limit view if not superadmin
		if($myRole<$this->config->item('superAdmin')){
			$this->db->where('author_id =', $myID);
		}
		return $this->getCommonCount($here, $timeNeed=NULL);
	}
	//-------------------------------------------------------------------------------------------------------
	//Save normal uploads to server
	//-----------------------------------------------------------------------------------------------------
	public function uploadMedia($location=NULL, $embed=NULL, $mediaType, $md5=NULL, $visibleWhen, $title, $stub, $loggedOnly, $exFlag, $section, $id=NULL, $vintage=NULL){
			
		$data=array(
			'title'=>$title,
			'stub'=>$stub,
			'visibleWhen'=>$visibleWhen,
			'loggedOnly'=>$loggedOnly,
			'mediaType' => $mediaType,
			'exclusiveSection'=>$exFlag,
			'forSection'=>$section,
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
	public function getPhotos($id=NULL, $vintage=0, $logged=1, $resultLimit=NULL, $offset=NULL, $here=null){
		$this->db->where('fileLoc !=', '');
		$this->db->where('mediaType', 'picture');
		//Only limit to vintage or nonvintage when proper value
		if($vintage !== NULL){
			$this->db->where('vintage', intval($vintage));
		}
		//Only limit when logging needs to limit
		if($logged !== NULL){
			$this->db->where('loggedOnly', intval($logged));
		}
		return $this->getCommonMedia($id, $resultLimit, $offset, $here, true);
	}
	
	public function getPhotoCount($vintage=0, $logged=1, $here=null){
		$this->db->where('fileLoc !=', '');
		$this->db->where('mediaType', 'picture');
		if($vintage !== NULL){
			$this->db->where('vintage', intval($vintage));
		}
		if($logged !== NULL){
			$this->db->where('loggedOnly', intval($vintage));
		}
		return $this->getCommonCount($here, true);
	}
		
	//---------------------------------------------------------------------------------------------------------------------------
	//Get only embeds (video only) from database, use limit 
	//---------------------------------------------------------------------------------------------------------------------------
	public function getEmbeds($id=NULL ,$vintage=0, $logged=1, $resultLimit=NULL, $offset=NULL, $here=null){
		$this->db->where('embed !=', '');
		$this->db->where('mediaType', 'video');
		//Only limit to vintage or nonvintage when proper value
		if($vintage != NULL){
			$this->db->where('vintage', intval($vintage));
		}
		//Only limit when logging needs to limit
		if($logged != NULL){
			$this->db->where('loggedOnly', intval($vintage));
		}
		return $this->getCommonMedia($id, $resultLimit, $offset, $here, true);
	}
	public function getEmbedCount($vintage=0, $logged=1, $here=null){
		$this->db->where('embed !=', '');
		$this->db->where('mediaType', 'video');
		if($vintage != NULL){
			$this->db->where('vintage', intval($vintage));
		}
		if($logged != NULL){
			$this->db->where('loggedOnly', intval($vintage));
		}
		return $this->getCommonCount($here, true);
	}
	//--------------------------------------------------------------------------------------------------------------------
	//Get only avatars from database, use limit
	//--------------------------------------------------------------------------------------------------------------------
	public function getAvatarLogo($id=NULL, $resultLimit=NULL, $offset=NULL, $here=null){
		$this->db->where('fileLoc !=', '');
		$this->db->where('mediaType', 'avatar');
          $this->db->or_where('mediaType', 'logo');
		return $this->getCommonMedia($id, $resultLimit, $offset, $here, NULL);
	}
	
	public function getAvatarLogoCount($here=null){
		$this->db->where('fileLoc !=', '');
		$this->db->where('mediaType', 'avatar');
          $this->db->or_where('mediaType', 'logo');
		return $this->getCommonCount($here, NULL);
	}
	//----------------------------------------------------------------------------------------------------------------------
	//Generic function used to get all media for the frontend without profile pics
	//--------------------------------------------------------------------------------------------------------------------
	public function getFrontMedia($id=NULL ,$vintage=0, $logged=1, $resultLimit=NULL, $offset=NULL, $here=null){
		if($id===NULL){
			$this->db->where('mediaType !=', 'avatar');
               $this->db->where('mediaType !=', 'logo');
		}
		//Only limit to vintage or nonvintage when proper value
		if($vintage != NULL){
			$this->db->where('vintage', intval($vintage));
		}
		//Only limit when logging needs to limit
		if($logged != NULL){
			$this->db->where('loggedOnly', intval($vintage));
		}
		return $this->getCommonMedia($id, $resultLimit, $offset, $here, true);
	}
	
	//---------------------------------------------------------------------------------------------------
	//Generic media functions to reduce repeated code
	//---------------------------------------------------------------------------------------------------
	private function getCommonMedia($id=NULL, $resultLimit=NULL, $offset=NULL, $here=null, $timeNeed=NULL){
		if($timeNeed!=NULL){
			$now=date('Y-m-d H:i:s');	
			$visArr=array('visibleWhen <='=> $now, 'visibleWhen !=' => '0000-00-00 00:00:00');
			$this->db->where($visArr);
		}
		$this->restrictSect($here);
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
	private function getCommonCount($here=null, $timeNeed=NULL){
		if($timeNeed!=NULL){
			$now=date('Y-m-d H:i:s');	
			$visArr=array('visibleWhen <='=> $now, 'visibleWhen !=' => '0000-00-00 00:00:00');
			$this->db->where($visArr);
		}
		$this->restrictSect($here);
		return $this->db->count_all_results($this->_table_name);
	}
}
