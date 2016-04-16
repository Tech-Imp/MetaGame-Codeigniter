<?
class User_model extends MY_Model{
    	
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
		$this->joinTable("auth_role",  "id", "id", "name, email, id" , "role, comment, active");
		//Only limit view if not superadmin
		if($myRole<$this->config->item('superAdmin')){
			$this->db->where('auth_role.role <', $myRole);
		}
		
		return $this->get($id);
		
	}
//-------------------------------------------------------------------------------------------------------
//Get users that are above a rank
//-------------------------------------------------------------------------------------------------------	
	public function getByMinRank($rank=0){
		$myRole=$this->session->userdata('role');
		if(!(abs($rank)<=$myRole)){
			$rank=0;
		}
		$this->db->where('auth_role.role >=', $rank);
		return $this->getUsers();
	} 
}
