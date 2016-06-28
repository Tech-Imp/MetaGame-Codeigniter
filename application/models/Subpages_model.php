<?
class Subpages_model extends MY_Model{
    	
    protected $_table_name='sub_info_database';
	protected $_primary_key='info_id';
	protected $_primary_filter='intval';
	protected $_order_by='info_id DESC';
	public $rules=array();
	protected $_timestamp=TRUE;
	
	function __construct(){
		parent::__construct();
	}
	
	
	
	public function saveSocial($forWho='self', $logoID='', $facebook='', $youtube='', $twitter='', $tumblr='', $twitch='', $email='', $body='',  $exFlag, $section, $id=NULL){
				
		$data=array(
               'logoID'=>$logoID,
               'facebook_url'=>$facebook,
               'youtube_url'=>$youtube,
               'twitter_url'=>$twitter,
			'tumblr_url'=>$tumblr,
			'twitch'=>$twitch,
			'email'=>$email,
			'body'=>$body,
			'forSection' => $section,
			'exclusiveSection'=>$exFlag,
		); 
		if($id==NULL){
	         $data['sub_dir']=$forWho;
		}

		$rowId=$this->save($data, $id);
		
		return $rowId;
	}
	

	//------------------------------------------------------------------------------------------------------
	//Display limited list of subdirectory social information
	//-----------------------------------------------------------------------------------------------------
	public function getSocial($id=NULL, $limit=NULL, $offset=NULL, $here=NULL)
	{
		$this->restrictSect($here);
		if($limit !== NULL){
			if($offset!==NULL && intval($offset)>0){
				$this->db->limit(intval($resultLimit), intval($offset));
			}
			else {
				$this->db->limit(intval($limit));
			}
		}
		$this->joinTable("media_database",  "logoID", "media_id", "*", "fileLoc, embed, mediaType");
		$this->joinTable("subsite_database",  "sub_dir", "sub_dir", NULL, "sub_name, visible");
          $this->joinTable("users",  "sub_info_database.author_id", "id", NULL, "name");
		return $this->get($id);
	}
	//-----------------------------------------------------------------------------------------------------------
	//
	//---------------------------------------------------------------------------------------------------------
	public function uniqueSelf($target="self", $group=false){
          //Group refers to the fact that multiple people would have edit permissions
          if($group){
               $myID=$this->session->userdata('id');
               $this->db->where("author_id", $myID);
          }
	     $this->db->where('sub_dir', $target);
          $results=$this->get();
          if(count($results)){
               return false;     
          }
          else{
               return true;
          }
	}
}
