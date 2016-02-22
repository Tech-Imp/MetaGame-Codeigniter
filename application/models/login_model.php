<?
class Login_model extends MY_Model{
    	
    protected $_table_name='users';
	protected $_order_by='name';
	public $rules=array(
		'email'=>array(
			'field'=>'email', 
			'label'=>'Email', 
			'rules'=>'trim|required|valid_email|max_length[130]'
		),
		'password'=>array(
			'field'=>'password', 
			'label'=>'Password', 
			'rules'=>'trim|required'
		)
	);
	
	function __construct(){
		parent::__construct();
	}
	
	public function login(){
		
		//Find user ID	
		$userSaltID=	$this->get_by(array(
				'email'=> $this->input->post('email'),
		), TRUE);
		//Verify something returned
		if(count($userSaltID)){
			// Find salt for user
			$userSalt = $this->get_by(array(
				'id' => $userSaltID->id,
			), 
			TRUE, 'auth_role', 'id');
			//Verify salt
			if(count($userSalt)){
				//Verify active status for user
				if($userSalt->active >= 1 ){
						
					//TODO set conditional to look for passphrases above a certain role
					$capturedPW=$this->input->post('password');
					
					$user= $this->get_by(array(
						'email'=> $this->input->post('email'),
						'password' => $this->hashP($capturedPW, $userSalt->salt),
					), TRUE);
					//Verify user exists for pass/email combo
					if(count($user)){
						//log in user
						$data=array(
							'name'=> $user->name,
							'email'=> $user->email,
							'id'=> $user->id,
							'loggedin'=> TRUE,
							'role'=> $userSalt->role,
							'activeStatus'=> $userSalt->active,
						);
						$this->session->set_userdata($data);
					}
				}			
			}
		
		}
		
		
		
		
		
	}
	public function logout(){
		$this->session->sess_destroy();
	}
	public function loggedin(){
		return (bool) $this->session->userdata('loggedin');
	}
	
}
