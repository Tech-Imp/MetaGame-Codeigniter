<?
class Profilepages_model extends MY_Model{
    	
    protected $_table_name='contacts_database';
	protected $_primary_key='static_id';
	protected $_primary_filter='intval';
	protected $_order_by='static_id DESC';
	public $rules=array();
	protected $_timestamp=TRUE;
	
	function __construct(){
		parent::__construct();
	}
	
	
	
	public function saveProfile($title='', $profileName, $body, $exFlag, $section, $avatarID, $id=NULL){
			
		$data=array(
			'title'=>$title,
			'profileName'=>$profileName,
			'body'=>$body,
			'exclusiveSection'=>$exFlag,
			'forSection'=>$section,
			'avatarID'=>$avatarID,
		); 
		

		$rowId=$this->save($data, $id);
		
		return $rowId;
	}
	

	//------------------------------------------------------------------------------------------------------
	//Display limited list of recent activites
	//-----------------------------------------------------------------------------------------------------
	public function getProfile($id=NULL, $limit=NULL, $offset=NULL)
	{
		if($limit !== NULL){
			if($offset!==NULL && intval($offset)>0){
				$this->db->limit(intval($resultLimit), intval($offset));
			}
			else {
				$this->db->limit(intval($limit));
			}
		}
		$this->joinTable("media_database",  "avatarID", "media_id", "*", "fileLoc, embed, mediaType");
		return $this->get($id);
	}
	//-----------------------------------------------------------------------------------------------------------
	//
	//---------------------------------------------------------------------------------------------------------
}
