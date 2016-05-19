<?
class Admin_model extends MY_Model{
    	//THIS MODEL HANDLES CORE FUNCTIONALITY, use user_model to get at things instead
     protected $_table_name='users';
	protected $_primary_key='id';
	protected $_primary_filter='intval';
	protected $_order_by='id ASC';
	protected $_timestamp='FALSE';
	
	public $rules=array(
		'password'=>array(
			'field'=>'password', 
			'label'=>'New Password', 
			'rules'=>'trim|required|min_length[3]'
		),
		'passConfirm'=>array(
			'field'=>'passConfirm', 
			'label'=>'Retype Password', 
			'rules'=>'trim|required|matches[password]'
		)
	);
	
	public $signUpRules=array(
		'name'=>array(
			'field'=>'name', 
			'label'=>'Name', 
			'rules'=>'trim|required|max_length[100]'
		),
		'email'=>array(
			'field'=>'email', 
			'label'=>'Email', 
			'rules'=>'trim|required|valid_email|max_length[130]|is_unique[users.email]'
		),
		'emailVerify'=>array(
			'field'=>'emailVerify', 
			'label'=>'Retype Email', 
			'rules'=>'trim|required|valid_email|matches[email]'
		),
		'password'=>array(
			'field'=>'password', 
			'label'=>'Password', 
			'rules'=>'trim|required|min_length[10]|max_length[100]'
		),
		'passConfirm'=>array(
			'field'=>'passConfirm', 
			'label'=>'Retype Password', 
			'rules'=>'trim|required|matches[password]'
		),
		'captcha'=>array(
			'field'=>'g-recaptcha-response', 
			'label'=>'captcha', 
			'rules'=>'required'
		)
	);
	public $adminRules=array(
          'name'=>array(
               'field'=>'name', 
               'label'=>'Name', 
               'rules'=>'trim|required|max_length[100]'
          ),
          'email'=>array(
               'field'=>'email', 
               'label'=>'Email', 
               'rules'=>'trim|required|valid_email|max_length[130]|is_unique[users.email]'
          ),
          'emailVerify'=>array(
               'field'=>'emailVerify', 
               'label'=>'Retype Email', 
               'rules'=>'trim|required|valid_email|matches[email]'
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
				'role'=>$this->config->item('normUser'),
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
		
		if($myRole>=$this->config->item('sectionAdmin') && $myRole > $affectedRole){
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
		if($myRole<$this->config->item('superAdmin')){
			$this->db->where('auth_role.role <', $myRole);
		}
		
		return $this->db->get()->result();
		
	}
//-----------------------------------------------------------------------------------------------------------------------
//Add UNIQUE user to database
//--------------------------------------------------------------------------------------

	public function signUp(){
		$newPass=FALSE;
		$newPass= $this->input->post('password');
		$newEmail=FALSE;
		$newEmail= $this->input->post('email');
		$newName=FALSE;
		$newName= $this->input->post('name');
		
		return $this->createUserEntry($newName, $newEmail, $newPass);
	}

     public function adminSignUp(){
          // Generate a random enough password
          $newPass=bin2hex(openssl_random_pseudo_bytes(12));
          $newEmail=FALSE;
          $newEmail= $this->input->post('email');
          $newName=FALSE;
          $newName= $this->input->post('name');
          
          return $this->createUserEntry($newName, $newEmail, $newPass, true);
          
     }
     
     private function createUserEntry($newName, $newEmail, $newPass, $adminGen=false){
          $this->load->model("Errorlog_model");
          $this->load->model("Logging_model"); 
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
                    $this->generateCreationEmail($newEmail, $newPass, $newID, $adminGen);
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

//----------------------------------------------------------------------------------------------------------------
//Email functions
//---------------------------------------------------------------------------------------------------------------
     public function generateCreationEmail($email, $pass, $id=-1, $sendPass=FALSE){
          
          $message="";
          $message.="Greetings! \n";
          $message.="An account has been created with this email: ".$email."\n";
          $message.="\n";
          if($sendPass){
               $message.="A password was randomly generated for you. It is purposely long so that you will change it upon login. \n";
               $message.="Please consider using a passphrase made up of several words to make it easy to remember, and hard to crack. \n\n";
               $message.="Password: ".$pass;
               $message.="\n\n";
               $message.="Please copy and paste to avoid errors.";
               $message.="\n\n";
          }
          $message.="You can log into the site at www.meta-game.net/login";
          $message.="\n\n";
          $message.="This email was sent from an automated system. Please do not attempt to write back, as no one will respond.";
          
          $this->generateEmail($email, $message, $id, "An account has been created for you at Meta-game.net");
     }
     
     private function generateEmail($recip=NULL, $message=NULL, $id=-1, $subject="META-GAME Auto-generated System message"){
          $this->load->model("Errorlog_model");
          $this->load->model("Logging_model");
          
          if($recip!==NULL && $message!==NULL){
               $this->load->library('email');
               
               $this->email->clear(TRUE);
               
               $this->email->from('system@meta-game.net', 'NO-REPLY');
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
}
