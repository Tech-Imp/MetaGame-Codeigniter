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
			$this->db->where($this->_table_name.'.'.$this->_primary_key, $id);
		}
		elseif ($single) {
			$method='row';
		}
		else{
			$method='result';
		}
		// Handles case where I need to reference multiple different tables for join functions
		if($altTable === FALSE){
	          $parts=explode(",", $this->_order_by);
               foreach($parts as $orderClause){
                    $this->db->order_by($this->_table_name.'.'.$orderClause);
               }
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
                    $data['author_id']=$this->session->userdata('id');
			}
			else{
				$data['modified']=$now;
                    $data['modified_by']=$this->session->userdata('id');
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
//-------------------------------------------------------------------------------
//	Fine Tuned basic querry controls
//--------------------------------------------------------------------------	
	public function joinTable($secondTable, $priIndex, $secIndex, $reqPriFields="*", $reqSecFields="*", $typeOfJoin='left'){
		// Join function to allow ease of use for results that need 2 tables. The current class is assumed primary
		$priSelect=$this->selectIterator($reqPriFields, $this->_table_name);
		$secSelect=$this->selectIterator($reqSecFields, $secondTable);
		$combined=$priSelect.", ".$secSelect;
		$this->db->select($combined);
		if($priIndex==$secIndex){
			$joinStatement=$secondTable.'.'.$secIndex." = ".$this->_table_name.'.'.$priIndex;
		}
		else{
			$joinStatement=$secIndex." = ".$priIndex;
		}
		$this->db->join($secondTable, $joinStatement, $typeOfJoin);
	}
//--------------------------------------------------------------------------------
	private function selectIterator($reqFields, $table){
		$selectArray=explode(",", $reqFields);
		$selStatement="";
		if($reqFields!==NULL){
			$length=$loc=0;
			$length=count($selectArray);
			foreach($selectArray as $selItem){
				$selStatement.=$table.'.'.$selItem;	
				++$loc;
				if($loc!=$length){$selStatement.=", ";}
			}
		}
		return $selStatement;
	}
//---------------------------------------------------------------
	public function restrictSect($here=NULL, $exclusiveExists=TRUE, $specTable=FALSE){
		//Whole section dedicated to making sure that items can be separated based on 
		//exclusive areas.
		if($here===NULL){
			$here=$this->uri->segment(1, $this->config->item('mainPage'));
		}
		$excludeLoc=$this->config->item('excludeLoc');
          
          $section='forSection =';
          $exFlag='exclusiveSection ='; 
		if($specTable){
               $section=$specTable.'.forSection =';
               $exFlag=$specTable.'.exclusiveSection =';   
		}
		// $here=explode('/', uri_string());
		
		if (in_array($here, $excludeLoc)==FALSE && $here != ""){
			$this->db->where($section, $here);
		}
		elseif(strpos($here, $this->config->item('adminPage')) === false && $exclusiveExists){
			$this->db->where($exFlag, 0);
		}
          elseif (!$exclusiveExists) {
              $this->db->where($section, "");
          }
		// End content Exclusion
	}
 //---------------------------------------------------------------------------------
 //Generic shared functions
 //------------------------------------------------------------------------------    
     protected function generateEmail($recip=NULL, $message=NULL, $id=-1, $subject="Auto-generated System message"){
          $this->load->model("Errorlog_model");
          $this->load->model("Logging_model");
          
          if($recip!==NULL && $message!==NULL){
               $this->load->library('email');
               
               $this->email->clear(TRUE);
               $this->email->from($this->config->item('systemEmail'), 'NO-REPLY');
               $this->email->to($recip); 
               // $this->email->bcc('them@their-example.com'); 
               
               $this->email->subject($subject);
               $this->email->message($message);     
               
               if($this->email->send()){
                    $this->Logging_model->newLog($id, 'sEma', 'Email -'.$subject.'- to ('.$recip.')sent successfully'); 
               }
               else{
                    // Write out a log with truncation in effect (max size is 300)
                    $this->Errorlog_model->newLog($id, 'sEma', 'Email Failed, Debug: '.substr($this->email->print_debugger(),0,270)."XX"); 
               }
          }
          else{
               $this->Errorlog_model->newLog($id, 'sEma', 'Generate email failed. There was a lack of an email address or message');
          }
     }
//-----------------------------------------------------------------------------------
     public function hashP($string, $salt){
          return hash('sha512', $salt . $string . config_item('encryption_key'));
     }
}

