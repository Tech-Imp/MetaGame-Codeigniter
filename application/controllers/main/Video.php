<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Video extends Common_frontend {
	
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
		
		
		if($id===NULL){
			if($currentRole>0){
				$myMedia=$this->Media_model->getEmbeds(NULL, 0, NULL, $maxLimit, 0);
				$maxItemsNew=$this->Media_model->getEmbedCount(0,NULL);
				
				$myVintage=$this->Media_model->getEmbeds(NULL, 1, NULL, $maxLimit, 0);
				$maxItemsVintage=$this->Media_model->getEmbedCount(1,NULL);
				
			}
			else{
				$myMedia=$this->Media_model->getEmbeds(NULL, 0, 0, $maxLimit, 0);
				$maxItemsNew=$this->Media_model->getEmbedCount(0, 0);
				
				$myVintage=$this->Media_model->getEmbeds(NULL, 1, 0, $maxLimit, 0);
				$maxItemsVintage=$this->Media_model->getEmbedCount(1,0);
			}
			
			
			
			$data['mediaHeader'].=$this->prepHeader("<h3>New Videos</h3>");
			$data['mediaContent'].=$this->prepContent($this->Dataprep_model->gatherItems($myMedia, "media", "media_id", "video", 3, $maxItemsNew, $maxLimit, "primary"));
			
			$data['mediaHeader'].=$this->prepHeader("<h3>Pinned Videos</h3>", 2);
			$data['mediaContent'].=$this->prepContent($this->Dataprep_model->gatherItems($myVintage, "media", "media_id", "video", 3, $maxItemsVintage, $maxLimit, "secondary"), 2);
			
		}
		else{
			if($currentRole>0){
				$myMedia=$this->Media_model->getEmbeds(intval($id), NULL, NULL);
			}
			else{
				$myMedia=$this->Media_model->getEmbeds(intval($id), NULL);
			}
			
			
			//
			if(count($myMedia)){
				$allMedia=array("solo" => $myMedia);
				//Due to the change to a media model, this page will default to redirect to there
				$data['singularContent']=$this->Dataprep_model->gatherItems($allMedia, "media", "media_id", "media");
			}
			else{
				$data['singularContent']="<div><h4>That item does not exist.</h4></div>";
			}
		}
		
		
		$this->load->view('templates/header', $data);
		$this->load->view('mainLayout', $data);
		// $this->load->view('inc/modals');
		$this->load->view('templates/footer', $data);
		
		
	}
	
	
}

