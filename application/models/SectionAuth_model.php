<?
class SectionAuth_model extends MY_Model{
    	
    protected $_table_name='subsite_database';
	protected $_primary_key='sub_dir';
	protected $_primary_filter='varchar';
	protected $_order_by='sub_dir DESC';
	public $rules=array();
	protected $_timestamp=TRUE;
	
	function __construct(){
		parent::__construct();
	}
	
	
	
	public function saveSubsection($section, $name=NULL, $about=NULL, $id=NULL){
		$myID=$this->session->userdata('id');
				
		$data=array(
			'sub_dir'=>$section,
			'sub_name'=>$name,
			'usage'=>$name,
			'author_id' => $myID,
		); 
		

		$rowId=$this->save($data, $id);
		
		return $rowId;
	}
	
	//--------------------------------------------------------------------------------------------------------
	//Get info on who has access to what using additional models
	//essentially acting as a security wrapper around other classes
	//------------------------------------------------------------------------------------------------------
	public function addUserToSection($user, $section){
		$result=$this->get($section);
		if(count($result)){
			$this->load->model("Subauth_model");
			$id=$this->Subauth_model->saveSubAuth($user, $section);	
			if(isset($id)){
				return "User added successfully";
			}
			else{
				return "Error adding user";
			}
		}
		else{
			return "Section does not exist. Please add section first";	
		}
	}
	
	public function isSelfAuthorized($section){
		$this->load->model("Subauth_model");
		return $this->Subauth_model->isAuthorized($section);
	}
	public function getValidSections(){
		$this->load->model("Subauth_model");
		return $this->Subauth_model->getSubSelects();
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
