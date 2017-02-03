<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Media extends Common_frontend {
	
	function __construct(){
		parent::__construct();
		
	}


	public function index($id=NULL)
	{
		$this->load->model('beta/Media_model', "Media_model");	
		$this->load->model('beta/Dataprep_model', "Dataprep_model");		
		$data=$this->commonHeader();
		$maxLimitVid=$this->config->item('maxVMedia');
		$maxLimitPic=$this->config->item('maxPMedia');
          $maxLimitAudio=$this->config->item('maxAMedia');
		$data['js'][0]='commonShared.js';
		// $data['js'][1]='bookIndex.js';
		$data['title']="Media";
		
		
		$currentRole=$_SESSION['role'];
		
		
		// if($id===NULL){
			if($currentRole>0){
				$myVids=$this->Media_model->getEmbeds(NULL, NULL, NULL, $maxLimitVid, 0);
				$maxVideoItems=$this->Media_model->getEmbedCount(NULL, NULL);
				
				$myPics=$this->Media_model->getPhotos(NULL, NULL, NULL, $maxLimitPic, 0);
				$maxPics=$this->Media_model->getPhotoCount(NULL, NULL);
				
                    $myAudio=$this->Media_model->getAudio(NULL, NULL, NULL, $maxLimitAudio, 0);
                    $maxAudio=$this->Media_model->getAudioCount(NULL, NULL);
			}
			else{
				$myVids=$this->Media_model->getEmbeds(NULL, NULL, 0, $maxLimitVid, 0);
				$maxVideoItems=$this->Media_model->getEmbedCount(0, 0);
				
				$myPics=$this->Media_model->getPhotos(NULL, NULL, 0, $maxLimitPic, 0);
				$maxPics=$this->Media_model->getPhotoCount(NULL, 0);	
                    
                    $myAudio=$this->Media_model->getAudio(NULL, NULL, 0, $maxLimitAudio, 0);
                    $maxAudio=$this->Media_model->getAudioCount(0, 0);
			}
			
			
			if($maxVideoItems>0 || $maxPics>0 || $maxAudio>0){
			     $tabInc=1;
                    if($maxVideoItems>0){
                         $data['mediaHeader'].=$this->prepHeader("<h3><span class='glyphicon glyphicon-facetime-video'></span> Videos </h3>", $tabInc);
                         $data['mediaContent'].=$this->prepContent($this->Dataprep_model->gatherItems($myVids, "media", "media_id", "video", 3, $maxVideoItems, $maxLimitVid, 0, "all.videos"), $tabInc);
                         ++$tabInc;
                    }
                    if($maxPics>0){
                         $data['mediaHeader'].=$this->prepHeader("<h3><span class='glyphicon glyphicon-picture'></span>  Images </h3>", $tabInc);
                         $data['mediaContent'].=$this->prepContent($this->Dataprep_model->gatherItems($myPics, "media", "media_id", "photos", 3, $maxPics, $maxLimitPic, 0, "all.photos"), $tabInc);
                         ++$tabInc;
                    }
                    if($maxAudio>0){
                         $data['mediaHeader'].=$this->prepHeader("<h3><span class='glyphicon glyphicon-volume-up'></span>  Audio </h3>", $tabInc);
                         $data['mediaContent'].=$this->prepContent($this->Dataprep_model->gatherItems($myAudio, "media", "media_id", "audio", 3, $maxAudio, $maxLimitAudio, 0, "all.audio"), $tabInc);
                         ++$tabInc; //in case in the future I need an addition media section
                    }
               }
               else{
                    $data['singularContent']="<div><h4>There doesnt appear to be any media for this section yet.</h4></div>";
               }
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

