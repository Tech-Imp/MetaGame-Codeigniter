<?
class Logging_model extends MY_Model{
    	
    protected $_table_name='user_log';
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
		$myRole=$_SESSION['role'];
		$myID=$_SESSION['id'];
			
		//Only limit view if not superadmin
		if($myRole<$this->config->item('sectionAdmin')){
			$this->db->where('change_made_by =', $myID);
		}
		
		return $this->get($id);
		
	}
	//-------------------------------------------------------------------------------------------------------
	//Save normal uploads to server
	//-----------------------------------------------------------------------------------------------------
	public function newLog($affectedID=NULL, $cat, $catStub){
		$myID=$_SESSION['id'];
		
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
			
		); 
		$rowId=$this->save($data);
		return $rowId;
	}
	//-------------------------------------------------------------------------------------------------------
	//Update normal uploads to server
	//-----------------------------------------------------------------------------------------------------
	public function incrementalLog($updateID=NULL, $cat, $catStub){
		$myID=$_SESSION['id'];
		
		if((bool)$myID==FALSE){
			$myID=0;
			$cat=$cat.' by System';
		}
			
		$data=array(
			'change_category'=>$cat,
			'change'=>$catStub,
			'dateWhen'=>date('Y-m-d H:i:s'),
			'change_made_by'=>$myID,
			'ip_of_transact'=>$this->input->ip_address(),
			
		); 
		$rowId=$this->save($data, $updateID);
		return $rowId;
	}
	
	//------------------------------------------------------------------------------------------------------
	//Display limited list of recent activites
	//-----------------------------------------------------------------------------------------------------
	public function getQuickLogs($limit=NULL, $offset=NULL)
	{
		$myRole=$_SESSION['role'];
		$myID=$_SESSION['id'];
		
		if($myRole<$this->config->item('sectionAdmin')){
			$this->db->where('change_made_by =', $myID);
		}
		
		if($limit !== NULL && $offset!==NULL){
			$this->db->limit(intval($limit), intval($offset));			
		}
		return $this->get();
	}
	//--------------------------------------------------------------------------------------------------------
	//Grab a specific type or types of logs
	//-------------------------------------------------------------------------------------------------------
	public function getTypeLogs($type=NULL, $limit=NULL, $offset=NULL){
		if($type!=NULL){
			$numItem=0;
			foreach($type as $item){
				if($numItem==0){
					$this->db->where("change_category", $item);
				}
				else{
					$this->db->or_where("change_category", $item);
				}
				++$numItem;
			}
		}
		return $this->getQuickLogs($limit, $offset);
	}
}