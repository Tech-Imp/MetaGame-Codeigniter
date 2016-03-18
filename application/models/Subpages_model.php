<?
class Subpages_model extends MY_Model{
    	
    protected $_table_name='sub_info_database';
	protected $_primary_key='sub_dir';
	protected $_primary_filter='varchar';
	protected $_order_by='sub_dir DESC';
	public $rules=array();
	protected $_timestamp=TRUE;
	
	function __construct(){
		parent::__construct();
	}
	
	
	
	public function saveSocial($facebook='', $youtube='', $twitter='', $tumblr='', $body, $logoID, $exFlag, $section, $id=NULL){
		$myID=$this->session->userdata('id');
				
		$data=array(
			'facebook_url'=>$facebook,
			'youtube_url'=>$youtube,
			'twitter_url'=>$twitter,
			'tumblr_url'=>$tumblr,
			'body'=>$body,
			'logoID'=>$logoID,
			'author_id' => $myID,
			'forSection' => $section,
			'exclusiveSection'=>$exFlag,
		); 
		

		$rowId=$this->save($data, $id);
		
		return $rowId;
	}
	

	//------------------------------------------------------------------------------------------------------
	//Display limited list of subdirectory social information
	//-----------------------------------------------------------------------------------------------------
	public function getSocial($id=NULL, $limit=NULL, $offset=NULL)
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
		$this->joinTable("media_database",  "logoID", "media_id", "*", "fileLoc, embed, mediaType", $id);
		$this->joinTable("subsite_database",  "sub_dir", "sub_dir", NULL, "sub_name, visible", $id);
		return $this->get($id);
	}
	//-----------------------------------------------------------------------------------------------------------
	//
	//---------------------------------------------------------------------------------------------------------
}
