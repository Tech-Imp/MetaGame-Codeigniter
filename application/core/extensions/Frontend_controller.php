<?php
class Frontend_controller extends MY_Controller{
		
	public $logoutPATH='login/logout';
	function __construct(){
		parent::__construct();	
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('Login_model');
		
		
		
		$excludeLoc=array(
			'login',
			'login/logout',
			''
			//Include initial page as well
		);
		// Find minimum role needed for page if it exists
		$visRole = $this->Login_model->get_by(array(
						'sub_url LIKE'=> uri_string(),
		), TRUE, "page_visibility");
		
		if(count($visRole)){
			$minRole=$visRole->min_role;
		}
		else {
			$minRole=-1;	
		}
		//Find current role if it exists		
		$currentRole=$this->session->userdata('role');
		if($currentRole===FALSE){
			$currentRole=0;
		}
		
		
		if(in_array(uri_string(), $excludeLoc)==FALSE){
			if($currentRole < $minRole){ //Verify that role allows viewing
				//Verify that theres info from the database on this entry
				if(count($visRole)){											
					if($visRole->redirect_to !== NULL){
						redirect($visRole->redirect_to);
					} 
				}
				//Otherwise, go to the main page
				else{															
					redirect('');
				}
			}
		}
		
	}	
	
	
}
