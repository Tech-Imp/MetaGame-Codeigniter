<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends Frontend_controller {
	function __construct(){
		parent::__construct();
	}

	public function index()
	{
		
		$data['site_name']=config_item('site_name');
		$data['css'][0]="main.css";
		$this->load->model('Sectionexposure_model');
          
          $data["additionalMessage"]=$data["currentStatus"]="";
          if($this->Sectionexposure_model->maintenanceCurrently()){
               $data['title']="Welcome";
              $data["currentStatus"]= '<a href="https://meta-game.net/main/index"><button type="button" class="btn btn-success btn-lg btn-block">Come on in...</button></a>';
          }
          else{
               $data['title']="Down for maintenance";
               $data["additionalMessage"]="<h3 class='text-center'>Currently down for maintenance, please check back soon!</h3>";
          }
          
		
		
		$this->load->view('templates/header', $data);
		$this->load->view('welcome');
		$this->load->view('templates/footer', $data);
		
	}
}

