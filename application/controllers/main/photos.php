<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Photos extends common_frontend {
	
	function __construct(){
		parent::__construct();
		
	}

	public function index($id=NULL)
	{
		$this->load->model('Media_model');	
		$this->load->model('Dataprep_model');
		$data=$this->commonHeader();
		$maxLimit=12;
	
		$data['js'][0]='commonShared.js';
		// $data['js'][1]='bookIndex.js';
		$data['title']="Photos";
		
		
		$currentRole=$this->session->userdata('role');
		if($currentRole===FALSE){
			$currentRole=0;
		}
		
		
		//Populate the photo types
		//first check if we are just getting all the records
		if($id===NULL){
			if($currentRole>0){
				
				$myMedia=$this->Media_model->getPhotos(NULL, 0, NULL, $maxLimit, 0);
				$maxItemsNew=$this->Media_model->getPhotoCount(0, NULL);
				
				$myVintage=$this->Media_model->getPhotos(NULL, 1, NULL, $maxLimit, 0);
				$maxItemsVintage=$this->Media_model->getPhotoCount(1, NULL);
			}
			else{
				
				$myMedia=$this->Media_model->getPhotos(NULL, 0, 0, $maxLimit, 0);
				$maxItemsNew=$this->Media_model->getPhotoCount(0, 0);	
				
				$myVintage=$this->Media_model->getPhotos(NULL, 1, 0, $maxLimit, 0);
				$maxItemsVintage=$this->Media_model->getPhotoCount(1, 0);
			}
			
			
			$data['mediaHeader'].=$this->prepHeader("<h3>New Photos</h3>");
			$data['mediaContent'].=$this->prepContent($this->Dataprep_model->gatherItems($myMedia, "media", "media_id", "photos", 3, $maxItemsNew, $maxLimit, "primary"));
			
			$data['mediaHeader'].=$this->prepHeader("<h3>THE VAULT</h3>", 2);
			$data['mediaContent'].=$this->prepContent($this->Dataprep_model->gatherItems($myVintage, "media", "media_id", "photos", 3, $maxItemsVintage, $maxLimit, "secondary"), 2);
		
			
		}
		// Then check if we are getting just a specific record
		else{
			if($currentRole>0){
				$myMedia=$this->Media_model->getPhotos(intval($id), NULL, NULL);
			}
			else{
				$myMedia=$this->Media_model->getPhotos(intval($id), NULL, 0);
			}
			
			
			//
			if(count($myMedia)){
				$allMedia=array("solo" => $myMedia);
				$data['singularContent']=$this->Dataprep_model->gatherItems($allMedia, "media", "media_id", "photos");
			}
			else{
				$data['singularContent']="<div><h4>That item does not exist.</h4></div>";
			}
		}
		
		
		
		// $data['numUsers']=count($articles);
		
		
		$this->load->view('templates/header', $data);
		$this->load->view('mainLayout', $data);
		// $this->load->view('inc/modals');
		$this->load->view('templates/footer', $data);
		
		
	}

	
}

