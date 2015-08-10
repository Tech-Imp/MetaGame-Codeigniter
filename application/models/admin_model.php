<?
class Admin_model extends MY_Model{
    	
    protected $_table_name='users';
	protected $_primary_key='id';
	protected $_primary_filter='intval';
	protected $_order_by='id';
	protected $_timestamp='FALSE';
	
	public $rules=array(
		'password'=>array(
			'field'=>'password', 
			'label'=>'New Password', 
			'rules'=>'trim|required|xss_clean|min_length[3]'
		),
		'passConfirm'=>array(
			'field'=>'passConfirm', 
			'label'=>'Retype Password', 
			'rules'=>'trim|required|xss_clean|matches[password]'
		)
	);
	
	public $signUpRules=array(
		'name'=>array(
			'field'=>'name', 
			'label'=>'Name', 
			'rules'=>'trim|required|xss_clean|max_length[100]'
		),
		'email'=>array(
			'field'=>'email', 
			'label'=>'Email', 
			'rules'=>'trim|required|valid_email|xss_clean|max_length[130]|is_unique[users.email]'
		),
		'emailVerify'=>array(
			'field'=>'emailVerify', 
			'label'=>'Retype Email', 
			'rules'=>'trim|required|valid_email|xss_clean|matches[email]'
		),
		'password'=>array(
			'field'=>'password', 
			'label'=>'Password', 
			'rules'=>'trim|required|xss_clean|min_length[10]|max_length[100]'
		),
		'passConfirm'=>array(
			'field'=>'passConfirm', 
			'label'=>'Retype Password', 
			'rules'=>'trim|required|xss_clean|matches[password]'
		),
		'captcha'=>array(
			'field'=>'g-recaptcha-response', 
			'label'=>'captcha', 
			'rules'=>'required'
		)
	);
	
	
	
	function __construct(){
		parent::__construct();
		$this->form_validation->set_message('is_unique', 'That %s is already taken. Please use a different one.');
	}

//------------------------------------------------------------------------------------------
//Used as the default case when accessing settings. Will only allow changing of own password
//-------------------------------------------------------------------------------------------
	public function fixSelf(){
		$id=$this->session->userdata('id');
		$myName=$this->session->userdata('name');
		$myEmail=$this->session->userdata('email');
		
		$compId=-1;
		//TODO use this bit later when only certain people
		// $auth_table=$this->get_by(array('id' => $id), true);
		// $auth_table->role;
		$password=FALSE;
		$password= $this->input->post('password');
		
		if($password !== FALSE){
			$newSalt=$this->fixSalt($id);
			// var_dump($newSalt);
			// var_dump($password);
			if($newSalt !== NULL){
				$data['password']=$this->hashP($password, $newSalt);
			}
			$compId=$this->save($data, $id);
			$this->load->model("Logging_model");
			$this->Logging_model->newLog($compId, 'sPas', 'Password self updated for User '.$myName.' ('.$myEmail.')  '); 
		}
		else{
			$this->load->model("Errorlog_model");
			$this->Errorlog_model->newLog($id, 'sPas', 'FAILED Password self update. Password not sent for User '.$myName.' ('.$myEmail.')  '); 
		}
		
		return $compId;
	}

//--------------------------------------------------------------------------------------------------------------	
//	ReSalt the password
//---------------------------------------------------------------------------------------------------------------	
	private function fixSalt($id, $newItem=FALSE){
		// $auth_table=$this->get_by(array('id' => $id), true, 'auth_role');
		
		$bytes=openssl_random_pseudo_bytes(8);
		// var_dump($bytes);
		$salt=bin2hex($bytes);
		
		// if its not new (default case) then alter existing
		if($newItem === FALSE){
			$data=array(
				'salt'=>$salt,
			); 
			if($this->save($data, $id,'auth_role') !== NULL){
				return $salt;	
			}
			else{
				return NULL;
			}
		}
		// Otherwise assume new and require insert
		else {
			$data=array(
				'salt'=>$salt,
				'id'=>$id,
				'role'=>3,
				'comment'=>'Joined on: '.date('Y-m-d H:i:s'). ' from '.$this->input->ip_address(),
			);
			if($this->save($data, FALSE,'auth_role') !== NULL){
				return $salt;	
			}
			else{
				return NULL;
			} 
		}
		
	}
//-----------------------------------------------------
//Primarily used for altering other users lower than logged user
//-----------------------------------------------------
	//TODO figure out whats wrong with the logic
	public function fixOther($otherId){
		$id=$this->session->userdata('id');
		$myName=$this->session->userdata('name');
		$myEmail=$this->session->userdata('email');
		
		$compId=-1;
		
		$auth_table=$this->get_by(array('id' => $id), true, 'auth_role');
		$myRole=$auth_table->role;

		$affected_auth_table=$this->get_by(array('id' => $otherId), true, 'auth_role');
		$affectedRole=$affected_auth_table->role;
		
		$otherUser=$this->get_by(array('id' => $otherId), true);
		$otherEmail=$otherUser->email;
		
		if($myRole>=8 && $myRole > $affectedRole){
			$password=FALSE;
			$password= $this->input->post('password');
		
			if($password !== FALSE){
				$newSalt=$this->fixSalt($otherId);
				$this->load->model("Logging_model");
				if($newSalt !== NULL){
					$data['password']=$this->hashP($password, $newSalt);
				}
				$compId=$this->save($data, $otherId);
				$this->Logging_model->newLog($compId, 'oPas', 'Password updated for '.$otherEmail.', role '.$affectedRole.' by User '.$myName.' ('.$myEmail.') role '.$myRole); 
			}
			else{
				$this->load->model("Errorlog_model");
				$this->Errorlog_model->newLog($otherId, 'oPas', 'FAILED Password updated. Password not sent for '.$otherEmail.', role: '.$affectedRole.' by User '.$myName.' ('.$myEmail.') role: '.$myRole); 
			}
		
		}	
		else {
			$this->load->model("Errorlog_model");
			$this->Errorlog_model->newLog($otherId, 'oPas', 'FAILED Password updated. Insufficient privledges to update '.$otherEmail.', role: '.$affectedRole.' by User '.$myName.' ('.$myEmail.') role: '.$myRole); 
		}	
		return $compId;
	}
	
//------------------------------------------------------------
//Get all users of rank lower than self
//-----------------------------------------------------------	
	public function getUsers(){
		$myRole=$this->session->userdata('role');
		$this->db->select("users.name, users.email, users.id, auth_role.comment, auth_role.active");
		$this->db->from('users');
		$this->db->join('auth_role', 'users.id = auth_role.id');
		//TODO Where clause for searching via LIKE
		
		//Only limit view if not superadmin
		if($myRole<9){
			$this->db->where('auth_role.role <', $myRole);
		}
		
		return $this->db->get()->result();
		
	}
//-----------------------------------------------------------------------------------------------------------------------
//Add UNIQUE user to database
//--------------------------------------------------------------------------------------

	public function signUp(){
		$this->load->model("Errorlog_model");
		$this->load->model("Logging_model");	  
		$newPass=FALSE;
		$newPass= $this->input->post('password');
		$newEmail=FALSE;
		$newEmail= $this->input->post('email');
		$newName=FALSE;
		$newName= $this->input->post('name');
		
		
		$precheck=$this->get_by(array("email" => $newEmail));
		if(count($precheck)){
			$this->Errorlog_model->newLog(NULL, 'aUsr', 'Critical Failure. Email ('.$newEmail.') already exists in system.');
			return FALSE;
		}
		$data=array(
						'name'=> $newName,
						'email'=> $newEmail,
					);
		$newID=$this->save($data);
		$logID=$this->Logging_model->newLog($newID, 'aUsr', '!!! INCREMENTAL User '.$newName.'('.$newEmail.')  being added');  
		if($newID !== 0 && $newID!==FALSE){
			$this->Logging_model->incrementalLog($logID, 'aUsr', '!! INCREMENTAL User '.$newName.'('.$newEmail.') about to get salt');  
			$newSalt=$this->fixSalt($newID, TRUE);
			if($newSalt !== NULL){
				$data['password']=$this->hashP($newPass, $newSalt);
				$newID=$this->save($data, $newID);
				
				$this->Logging_model->incrementalLog($logID, 'aUsr', 'User '.$newName.'('.$newEmail.') added to database successfully');  
				return TRUE;
			}
			else{
				$this->Errorlog_model->newLog($newID, 'aUsr', 'Critical Failure. Salt failed');
				return FALSE;
			}
		}
		else{
			$this->Errorlog_model->newLog($newID, 'aUsr', 'Critical Failure. Was unable to retrieve ID of user in database.');
			return FALSE;
		}
		
		
		
	}
	
}
