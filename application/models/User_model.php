<?
class User_model extends MY_Model{
    	//This model is a stripped down version of the admin model primarily used by section auth stuff
    	//Use this rather than admin model for new changes involving users that do not require authentication/admin stuffs
     protected $_table_name='users';
	protected $_primary_key='id';
	protected $_primary_filter='intval';
	protected $_order_by='id';
	protected $_timestamp='FALSE';
	
	
	function __construct(){
		parent::__construct();
	}
	
//------------------------------------------------------------
//Get all users of rank lower than self
//-----------------------------------------------------------	
	public function getUsers($id=NULL){
		$myRole=$this->session->userdata('role');
          $myID=$this->session->userdata('id');
		$this->joinTable("auth_role",  "id", "id", "name, email, id" , "role, comment, active");
		//Only limit view if not superadmin
		if($myRole<$this->config->item('superAdmin')){
			$this->db->where('auth_role.role <', $myRole);
		}
		if($id==NULL){
		     $this->db->where('users.id !=', $myID);
		}
		return $this->get($id);
		
	}
//------------------------------------------------------------
//Get all users 
//-----------------------------------------------------------    
     private function getInternal($id=NULL){
          $myRole=$this->session->userdata('role');
          $this->joinTable("auth_role",  "id", "id", "name, email, id" , "role, comment, active");
          return $this->get($id);
     }
//-------------------------------------------------------------------------------------------------------
//Get users that are above a rank
//-------------------------------------------------------------------------------------------------------	
	public function getByMinRank($rank=0, $id=NULL){
		$myRole=$this->session->userdata('role');
		if(!(abs($rank)<=$myRole)){
			$rank=0;
		}
		$this->db->where('auth_role.role >=', $rank);
		return $this->getInternal();
	}

//-------------------------------------------------------------------------------------------------------------
//Adjust role/rank
//-------------------------------------------------------------------------------------------------------------
	public function setRole($id, $rank){
		$data=array(
				'role'=>$rank,
			); 
		if($this->save($data, $id,'auth_role') !== NULL){
			return " role updated ";	
		}
		return "!*! ROLE UPDATE FAILED !*!";
	}
 
}
