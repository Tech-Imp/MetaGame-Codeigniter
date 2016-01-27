<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Media extends common_frontend {
	
	function __construct(){
		parent::__construct();
		
	}


	public function index($id=NULL)
	{
		$this->load->model('Media_model');	
		$this->load->model('Dataprep_model');		
		$data=$this->commonHeader();
		$maxLimit=$this->config->item('maxMMedia');
		$data['js'][0]='commonShared.js';
		// $data['js'][1]='bookIndex.js';
		$data['title']="Video";
		
		
		$currentRole=$this->session->userdata('role');
		if($currentRole===FALSE){
			$currentRole=0;
		}
		
		
		// if($id===NULL){
			if($currentRole>0){
				$myVids=$this->Media_model->getEmbeds(NULL, NULL, NULL, $maxLimit, 0);
				$maxVideoItems=$this->Media_model->getEmbedCount(NULL, NULL);
				
				$myPics=$this->Media_model->getPhotos(NULL, NULL, NULL, $maxLimit, 0);
				$maxPics=$this->Media_model->getPhotoCount(NULL, NULL);
				
			}
			else{
				$myVids=$this->Media_model->getEmbeds(NULL, NULL, 0, $maxLimit, 0);
				$maxVideoItems=$this->Media_model->getEmbedCount(0, 0);
				
				$myPics=$this->Media_model->getPhotos(NULL, NULL, 0, $maxLimit, 0);
				$maxPics=$this->Media_model->getPhotoCount(NULL, 0);	
			}
			
			
			
			$data['mediaHeader'].=$this->prepHeader("<h3>Videos</h3>");
			$data['mediaContent'].=$this->prepContent($this->Dataprep_model->gatherItems($myVids, "media", "media_id", "video", 3, $maxVideoItems, $maxLimit, "primary"));
			
			$data['mediaHeader'].=$this->prepHeader("<h3>Image Gallery</h3>", 2);
			$data['mediaContent'].=$this->prepContent($this->Dataprep_model->gatherItems($myPics, "media", "media_id", "photos", 3, $maxPics, $maxLimit, "secondary"), 2);
			
		// }
		// else{
			// if($currentRole>0){
				// $myMedia=$this->Media_model->getEmbeds(intval($id), NULL, NULL);
			// }
			// else{
				// $myMedia=$this->Media_model->getEmbeds(intval($id), NULL);
			// }
// 			
// 			
			// //
			// if(count($myMedia)){
				// $allMedia=array("solo" => $myMedia);
				// $data['singularContent']=$this->Dataprep_model->gatherItems($allMedia, "media", "media_id", "video");
			// }
			// else{
				// $data['singularContent']="<div><h4>That item does not exist.</h4></div>";
			// }
		// }
		
		
		$this->load->view('templates/header', $data);
		$this->load->view('mainLayout', $data);
		// $this->load->view('inc/modals');
		$this->load->view('templates/footer', $data);
		
		
	}
	
	
}

