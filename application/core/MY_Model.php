<?php
class MY_Model extends CI_Model{
	
	protected $_table_name='';
	protected $_primary_key='id';
	protected $_primary_filter='intval';
	protected $_order_by='';
	public $rules=array();
	protected $_timestamp=FALSE;
		
	function __construct(){
		parent::__construct();
		
	}
	public function get($id=NULL, $single=FALSE, $altTable=FALSE, $altOrder=FALSE){
		// Does a querry sans a where clause but can be limited to a specific row rather than all	
		if($id != NULL){
			$filter=$this->_primary_filter;
			$id=$filter($id);
			$method='row';
			$this->db->where($this->_primary_key, $id);
		}
		elseif ($single) {
			$method='row';
		}
		else{
			$method='result';
		}
		
		// Handles case where I need to reference multiple different tables for join functions
		if($altTable === FALSE){
			$this->db->order_by($this->_order_by);
			$this->db->from($this->_table_name);
			return $this->db->get()->$method();
		}
		else{
			if($altOrder !== FALSE){
				$this->db->order_by($altOrder);
			}
			$this->db->from($altTable);
			return $this->db->get()->$method();
		}
	
	}
//----------------------------------------------------------------------------------------	
	public function get_by($where, $single=FALSE, $altTable=FALSE, $altOrder=FALSE){
		//Used essentially when you want a where to limit a querry	
		$this->db->where($where);
		return $this->get(NULL, $single, $altTable, $altOrder);
	}
	
	
//----------------------------------------------------------------------------------------	
	public function save($data, $id=NULL, $altTable=FALSE, $overtime=FAlSE){
		//Are timestamps important for this transaction?
		
		if($this->_timestamp  === TRUE || $overtime===TRUE ){
			$now=date('Y-m-d H:i:s');
			if($id===NULL){
				$data['created']=$now;
			}
			else{
				$data['modified']=$now;
			}
		}
			
		// Insert into DB
		if ($id===NULL){
			if(isset($data[$this->_primary_key])){
				$data[$this->_primary_key]=NULL;
			}
			$this->db->set($data);
			if($altTable===FALSE){
				$this->db->insert($this->_table_name);
			}
			else{
				$this->db->insert($altTable);
			}
			$id=$this->db->insert_id();
		}
		//Update/Edit in DB
		//TODO Authorization of edit
		//TODO LOG transaction
		elseif ($id===FALSE) {
			$this->db->set($data);
			if($altTable===FALSE){
				$this->db->insert($this->_table_name);
			}
			else{
				$this->db->insert($altTable);
			}
			$id=$this->db->insert_id();
		}
		else{
			$filter=$this->_primary_filter;
			$id=$filter($id);
			$this->db->set($data);
			$this->db->where($this->_primary_key, $id);
			//Account for differ from core variable
			if($altTable===FALSE){
				$this->db->update($this->_table_name);
			}
			else{
				$this->db->update($altTable);
			}
		}
		// return $data;
		return $id;
	}
	
//-------------------------------------------------------------
	
	public function delete($id){
		//Only used for removal of entry
		//TODO Authorization of delete
		//TODO LOG transaction	
		$filter=$this->_primary_filter;
		$id=$filter($id);
		if(!$id){
			return FALSE;
		}
		$this->db->where($this->_primary_key, $id);
		$this->db->limit(1);
		$this->db->delete($this->_table_name);
	}
	
//-----------------------------------------------------------	
	public function joinTable($secondTable, $priIndex, $secIndex, $reqPriFields="*", $reqSecFields="*", $itemID=NULL, $typeOfJoin){
		// Join function to allow ease of use for results that need 2 tables. The current class is assumed primary
		$priSelect=$this->selectIterator($reqPriFields, $_table_name);
		$secSelect=$this->selectIterator($reqSecFields, $secondTable);
		$combined=$priSelect.", ".$secSelect;
		$this->db->select($combined);
		//TODO May need to add from statement and then not use get
		// if($id != NULL){
			// $filter=$this->_primary_filter;
			// $id=$filter($id);
			// $method='row';
			// $this->db->where($this->_primary_key, $id);
		// }
		// else{
			// $method='result';
		// }
		// $this->db->order_by($this->_order_by);
		// $this->db->from($this->_table_name);
		//TODO May need to add from statement and then not use get
		// Handles case where I need to reference multiple different tables for join functions
		if($priIndex==$secIndex){
			$joinStatement=$secondTable.'.'.$secIndex." = ".$_table_name.'.'.$priIndex;
		}
		else{
			$joinStatement=$secIndex." = ".$priIndex;
		}
		$this->db->join($secondTable, $joinStatement);
		// return $this->db->get()->$method();
		return $this->get($itemID);
	}
//--------------------------------------------------------------------------------
	private function selectIterator($reqFields, $table){
		$selectArray=explode(",", $reqFields);
		$selStatement="";
		$length=$loc=0;
		
		$length=count($selectArray);
		foreach($selectArray as $selItem){
			$selStatement.=$table.'.'.$selItem;	
			++$loc;
			if($loc!=$length){$selStatement.=", ";}
		}
		return $selStatement;
	}


//-----------------------------------------------------------------------------------

	public function hashP($string, $salt){
		return hash('sha512', $salt . $string . config_item('encryption_key'));
	}

//---------------------------------------------------------------

	public function restrictSect($here=null){
		//Whole section dedicated to making sure that items can be separated based on 
		//exclusive areas.
		if($here===null){
			$here=$this->uri->segment(1, $this->config->item('mainPage'));
		}
		$excludeLoc=$this->config->item('excludeLoc');
		
		// $here=explode('/', uri_string());
		
		if (in_array($here, $excludeLoc)==FALSE && $here != ""){
			$this->db->where('forSection =', $here);
		}
		elseif(strpos($here, $this->config->item('adminPage')) === false){
			$this->db->where('exclusiveSection =', 0);
		}
		// End content Exclusion
	}


}

