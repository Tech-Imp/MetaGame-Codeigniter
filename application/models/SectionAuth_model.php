<?
class SectionAuth_model extends MY_Model{
    	
    protected $_table_name='subsite_database';
	protected $_primary_key='subsite_id';
	protected $_primary_filter='intval';
	protected $_order_by='subsite_id DESC';
	public $rules=array();
	protected $_timestamp=TRUE;
	
	function __construct(){
		parent::__construct();
	}
	
	
	
	public function saveSubsection($section, $name=NULL, $about=NULL, $id=NULL){
		$myID=$this->session->userdata('id');
				
		$data=array(
			'sub_name'=>$name,
			'sub_dir'=>$section,
			'usage'=>$about,
			'author_id' => $myID,
		); 

		$rowId=$this->save($data, $id);
		
		return $rowId;
	}
	public function getSectionControl(){
		$myID=$this->session->userdata('id');
		$myRole=$this->session->userdata('role');
		if($myRole< $this->config->item('superAdmin')){
			$this->db->where("author_id", $myID);
		}
		$this->joinTable("users",  "author_id", "id", "*", "name, email");
		return $this->get();
	}
	//--------------------------------------------------------------------------------------------------------
	//Get info on who has access to what using additional models
	//essentially acting as a security wrapper around other classes
	//------------------------------------------------------------------------------------------------------
	public function sectionExists(){
		$result=$this->get($section);
		if(count($result)){
			return true;
		}
		else{
			return false;
		}
	}
	
	public function addUserToSection($user, $section){
		$this->load->model("Subauth_model");
		return $this->Subauth_model->saveSubAuth($user, $section);	
	}
	
	public function isSelfAuthorized($section){
		$this->load->model("Subauth_model");
		return $this->Subauth_model->isAuthorized($section);
	}
	public function getValidSections(){
		$this->load->model("Subauth_model");
		return $this->Subauth_model->getSubSelects();
	}
	public function whoIAssigned(){
		$this->load->model("Subauth_model");
		return $this->Subauth_model->getSecAssigned();
	}
	public function whereImAssigned(){
		$this->load->model("Subauth_model");
		return $this->Subauth_model->getMySection();
	}
	//----------------------------------------------------------------------------------------
	//Removal functions
	//----------------------------------------------------------------------------------------
	public function removeUserFromSection($id=NULL, $user=NULL, $section=NULL){
		
		$this->load->model("Subauth_model");
		if($id!==NULL){
			$this->Subauth_model->delete($id);
		}
		elseif ($user!==NULL && $section!==NULL){
			$results=$this->Subauth_model->getIDBySecUser($user, $section);
			foreach($results as $row){
				$this->Subauth_model->delete(intval($row->user_id));
			}
		}
		else{
			return "Error: Data missing";
		}
		return "Removal successful";
	}
	public function removeSubDir($section){
		$this->load->model("Subauth_model");
		
		if ($section!==NULL){
			$results=$this->Subauth_model->getBySection($section);
			foreach($results as $row){
				$this->Subauth_model->delete(intval($row->user_id));
			}
		}
		$result=$this->get($section);
		if(count($result)){
			$this->delete($section);
		}
	}
}
