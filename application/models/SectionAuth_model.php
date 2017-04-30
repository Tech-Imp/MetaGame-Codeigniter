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
	
	
	public function saveSubsection($section, $name=NULL, $about=NULL, $vis=0, $parent="", $id=NULL){
				
		$data=array(
			'sub_name'=>$name,
			'sub_dir'=>$section,
			'usage'=>$about,
			'visible'=>$vis,
			'forSection'=>$parent,
		); 

		$rowId=$this->save($data, $id);
		
		return $rowId;
	}
	
	public function saveSectionEdits($id, $about="", $vis=0, $parent=""){
		$data=array(
			'usage'=>$about,
			'visible'=>$vis,
			'forSection'=>$parent,
		); 
		if( $this->save($data, $id)!== NULL){
			return " Update successful on section item ".$id;	
		}
		return "!*! UPDATE OF SECTION FAILED !*!";
	}
	//Returns based on what should be visible from the current section
	public function getQuicklinks($id=null){
	     $supInfo="logoID, facebook_url, youtube_url, twitter_url, tumblr_url, twitch, email";
		$this->restrictSect(NULL, FALSE, $this->_table_name);
		$this->db->where($this->_table_name.".visible", 1);
		$this->joinTable("sub_info_database",  "sub_dir", "sub_dir", "sub_name, sub_dir, usage", $supInfo);
        $this->joinTable("media_database",  "logoID", "media_id", NULL, "fileLoc, embed, mediaType");
		return $this->get($id);
	}
	//Returns all links based on what the user should be allowed to see, independent of section.
	public function getSelectLinks($id=null){
		$myRole=$_SESSION['role'];
		$this->db->where($this->_table_name.".visible", 1);
		$this->joinTable("page_visibility",  "sub_dir", "sub_dir", "sub_name, sub_dir, usage", "min_role");
	 	if($myRole< $this->config->item('superAdmin')){
               $this->db->where("author_id", $myID);
      	}
		return $this->get($id);
	}
     public function getSectionControl($id=NULL){
          $myID=$_SESSION['id'];
          $myRole=$_SESSION['role'];
          if($myRole< $this->config->item('superAdmin')){
               $this->db->where("author_id", $myID);
          }
          $this->joinTable("users",  "author_id", "id", "*", "name, email");
          return $this->get($id);
     }
     
	//--------------------------------------------------------------------------------------------------------
	//Get info on who has access to what using additional models
	//essentially acting as a security wrapper around other classes
	//------------------------------------------------------------------------------------------------------
	public function sectionExists($section=NULL){
	     $this->db->where("sub_dir", $section);
		$result=$this->get();
		if(count($result)){
			return true;
		}
		else{
			return false;
		}
	}
	public function isNewUser($user, $section){
          $this->load->model("Subauth_model");
	     $result=$this->Subauth_model->getIDBySecUser($user, $section);
	     if(count($result)){
	         return false; 
	     }
          else{
              return true; 
          }
	}
	public function addUserToSection($user, $section){
		$this->load->model("Subauth_model");
		return $this->Subauth_model->saveSubAuth($user, $section);	
	}
     
	public function getUsersBySection($section){
	     $this->load->model("Subauth_model");
          return $this->Subauth_model->getBySection($section);  
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
     public function getAuthInfo($id){ //TODO Weak point, Examine
          $this->load->model("Subauth_model");
          return $this->Subauth_model->getSubAuth($id);
     }
	//----------------------------------------------------------------------------------------
	//Removal functions
	//----------------------------------------------------------------------------------------
	public function removeUserFromSection($id=NULL, $user=NULL, $section=NULL){
		
		$this->load->model("Subauth_model");
          $whoAffected="";
		if($id!==NULL){
		     $userAffected=$this->Subauth_model->getSubAuth($id);
			$this->Subauth_model->delete($id);
               $whoAffected="[".$userAffected->name." (".$userAffected->email.") from ".$userAffected->sub_name."]";
		}
		elseif ($user!==NULL && $section!==NULL){
			$results=$this->Subauth_model->getIDBySecUser($user, $section);
			foreach($results as $row){
				$this->Subauth_model->delete(intval($row->user_id));
                    $whoAffected.="[".$row->name." (".$row->email.") from ".$row->sub_name."]";
			}
		}
		else{
			return "Error: Data missing";
		}
		return "User removal from section successful ".$whoAffected;
	}
     
	public function removeSubDir($id){
	     $actions="Failed user deprovisioning of section (".$id.")";
	     $entry=$this->get($id);
		$this->load->model("Subauth_model");
		
		if ($entry->sub_dir!==NULL){
			$results=$this->Subauth_model->getBySection($entry->sub_dir);
               $numUsers=0;
			foreach($results as $row){
				$this->Subauth_model->delete(intval($row->subauth_id));
                    $numUsers++;
			}
               $actions=$numUsers." of ".count($results)." Users removed";
		}
		$result=$this->get($id);
		if(count($result)){
			$this->delete($id);
               $actions.=" and section ".$entry->sub_name." removed";
		}
          return $actions;
	}
}
