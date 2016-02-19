<?
class Errorlog_model extends MY_Model{
    	
    protected $_table_name='error_log';
	protected $_primary_key='transact_id';
	protected $_primary_filter='intval';
	protected $_order_by='transact_id DESC';
	public $rules=array();
	protected $_timestamp=FALSE;
	
	function __construct(){
		parent::__construct();
	}
	
	//-------------------------------------------------------------------------------------------------------
	//Get Media from database
	//-------------------------------------------------------------------------------------------------------
	public function getLogs($id=NULL){
		$myRole=$this->session->userdata('role');
		$myID=$this->session->userdata('id');
			
		//Only limit view if not superadmin
		$this->db->where('role_to_view <=', $myRole);
		
		return $this->get($id);
		
	}
	//-------------------------------------------------------------------------------------------------------
	//Save normal uploads to server
	//-----------------------------------------------------------------------------------------------------
	public function newLog($affectedID=NULL, $cat, $catStub, $role=NULL){
		$myID=$this->session->userdata('id');
		
		if($role===NULL){
			$role=$this->config->item('superAdmin');
		}
		
		
		if((bool)$myID==FALSE){
			$myID=0;
			$cat=$cat.' by System';
		}
			
		$data=array(
			'id_of_affected'=>$affectedID,
			'change_category'=>$cat,
			'change'=>$catStub,
			'dateWhen'=>date('Y-m-d H:i:s'),
			'change_made_by'=>$myID,
			'ip_of_transact'=>$this->input->ip_address(),
			'role_to_view'=>$role,
		); 
		


		$rowId=$this->save($data);
		
		return $rowId;
	}
	
	
	
	
}
