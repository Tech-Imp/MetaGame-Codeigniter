<?
class Subauth_model extends MY_Model{
    	
    protected $_table_name='sub_auth';
	protected $_primary_key='subauth_id';
	protected $_primary_filter='intval';
	protected $_order_by='subauth_id DESC';
	public $rules=array();
	protected $_timestamp=TRUE;
	
	function __construct(){
		parent::__construct();
	}
	
	
	
	public function saveSubAuth($user, $section, $id=NULL){
				
		$data=array(
			'sub_dir'=>$section,
			'user_id'=>$user,
		); 
		

		$rowId=$this->save($data, $id);
		
		return $rowId;
	}
	

	//------------------------------------------------------------------------------------------------------
	//Display limited list of subdirectory persons information
	//-----------------------------------------------------------------------------------------------------
	public function getSubAuth($id=NULL, $limit=NULL, $offset=NULL)
	{
		if($limit !== NULL){
			if($offset!==NULL && intval($offset)>0){
				$this->db->limit(intval($resultLimit), intval($offset));
			}
			else {
				$this->db->limit(intval($limit));
			}
		}
		$this->joinTable("subsite_database",  "sub_dir", "sub_dir", "*", "sub_name, visible, usage");
          $this->joinTable("users", "user_id", "id", NULL, "name, email");
		return $this->get($id);
	}
	//-----------------------------------------------------------------------------------------------------------
	//Check if user is authorized
	//---------------------------------------------------------------------------------------------------------
	//TODO evaluate this further!!
	public function isAuthorized($here=null){
		$myID=$this->session->userdata('id');
		
		if($here===null || $this->session->userdata('role') <= $this->config->item('contributor')){
			return false;
		}
		
		$this->db->where('sub_dir', $here);
		$this->db->where("user_id", $myID);
		if(count($this->get())){
			return true;
		}
		else{
			return false;
		}
	}
	//--------------------------------------------------------------------------------------------------------
	//Display list of subdirectories that user has access to
	//------------------------------------------------------------------------------------------------------
	public function getSubSelects(){
		$myID=$this->session->userdata('id');
		
		if($this->session->userdata('role') >= $this->config->item('contributor')){
			$this->db->where("user_id", $myID);
			$this->joinTable("subsite_database",  "sub_dir", "sub_dir", "*", "sub_name, visible");
			return $this->get();
		}
		else{
			return "";
		}
	}
	//------------------------------------------------------------------------------------------------------
	//Get ID of entry based on user and section
	//------------------------------------------------------------------------------------------------------
	public function getIDBySecUser($user, $section){
		$this->db->where("user_id",$user);
		$this->db->where("sub_dir",$section);
		return $this->get();
	}
	//-----------------------------------------------------------------------------------------------------
	//Get all users within a subsection
	//------------------------------------------------------------------------------------------------------
	public function getBySection($section){
		$this->db->where("sub_auth.sub_dir",$section);
          $this->joinTable("subsite_database",  "sub_dir", "sub_dir", "*", "sub_name, visible");
          $this->joinTable("users", "user_id", "id", NULL, "name, email");
		return $this->get();
	}
	//-----------------------------------------------------------------------------------------------------
	//Get all sudirectory assignments made by user
	//-----------------------------------------------------------------------------------------------------
	public function getSecAssigned(){
		$myID=$this->session->userdata('id');
		$this->db->where("sub_auth.author_id", $myID);
		$this->joinTable("subsite_database",  "sub_dir", "sub_dir", "*", "sub_name, visible");
          $this->joinTable("users", "user_id", "id", NULL, "name, email");
		return $this->get();
	}
	//----------------------------------------------------------------------------------------------------
	//Get all sections user is in
	//----------------------------------------------------------------------------------------------------
	public function getMySection(){
		$myID=$this->session->userdata('id');
		$this->db->where("user_id", $myID);
		$this->joinTable("subsite_database",  "sub_dir", "sub_dir", "*", "sub_name, visible");
		return $this->get();
	}
}
