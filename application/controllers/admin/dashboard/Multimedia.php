<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Multimedia extends Dash_backend{
	
	function __construct(){
		parent::__construct();
	}
	
//-----------------------------------------------------------------------------------------------------------------------------
//Add/Remove items from media gallery
//Allow items to still persist but set visibility based on criteria (such as user must be logged in to view)
//------------------------------------------------------------------------------------------------------------------------------
	
	public function index(){
	     $this->secureArea();
		$this->load->model('Media_model');
		$this->load->model('Adminprep_model');
		$data=$this->commonHeader();
          $data['js'][0]= 'tinymce/jquery.tinymce.min.js';
		$data['js'][1]= 'plupload/plupload.full.min.js';
		$data['js'][2]= 'plupload/jquery.ui.plupload/jquery.ui.plupload.js';
		$data['js'][3]= 'dash/dashboardIndex.js';
		$data['js'][4]= 'dash/dashboardMedia.js';
		$data['js'][5]='commonShared.js';
		$data['css'][1]='plupload/jquery.ui.plupload.css';
		$data['currentLocation']="<div class='navbar-brand'>Media Dashboard</div>";
		$data['additionalTech']="<div class='row'>
			<br>
			<div class='col-xs-12 col-md-offset-5 col-md-3 addedTech'>
				<div> This page uses Plupload for the file upload interface. </div>
			</div>
		</div>";
		$data['mediaTable']="<strong>No media to display that you have rights on.</strong>";
		$data['exclusivePic']=$this->exclusiveSelector("Pic");
		$data['exclusiveEmbed']=$this->exclusiveSelector("Embed");
		$maxLimit=$this->config->item('maxAdmin');
		$myMedia=$this->Media_model->getMedia(NULL, $maxLimit, 0);
		$maxMediaCount=$this->Media_model->getMediaCount();
		
		$data['mediaTable']=$this->Adminprep_model->gatherItemsAdmin($myMedia, "media", "media_id", "multimedia/editMedia", $maxMediaCount, $maxLimit, 0);
		$data['mediaOptions']="<select id='mediaOptions'>
			<option value='video'>Video</option>
			<option value='sound'>Audio</option>
		</select>";
		
		$this->load->view('templates/header', $data);
		$this->load->view('inc/dash_header', $data);
		$this->load->view('dash/mediaUploader');
		$this->load->view('inc/dash_footer', $data);
		$this->load->view('templates/footer', $data);
	}

	public function editMedia($id=NULL){
	     $this->secureArea();
		$this->load->model('Media_model');
		$data=$this->commonHeader();
		$data['js'][0]= 'tinymce/jquery.tinymce.min.js';
		$data['js'][1]= 'dash/dashboardIndex.js';
		$data['js'][2]= 'dash/dashboardUpdateMedia.js';
		$data['currentLocation']="<div class='navbar-brand'>Edit Media</div>";
		
		$this->load->view('templates/header', $data);
		$this->load->view('inc/dash_header', $data);
		
		if($id===NULL){
			$this->load->view('dash/errorInfo');
		}
		else {
			$allData=$this->Media_model->getMedia(intval($id));
			if(count($allData)){
				$data['mediaID']=$allData->media_id;
				$data['mediaTitle']=$allData->title;
				$data['mediaStub']=$allData->stub;
				// Display Media item
				if($allData->fileLoc !== ""){
					$data['mediaItem']="<img class='img-responsive' alt='{$allData->title}' src='".$allData->fileLoc."'></img>";
					$data['hardLink']=$allData->fileLoc;
				}
				else if($allData->embed !== ""){
					$data['mediaItem']="<div class='embed-responsive embed-responsive-16by9'>".$allData->embed."</div>";
					$data['hardLink']=$allData->embed;
				}
				// Display privacy of item
				if($allData->loggedOnly == 1 ){
					$data['mediaPrivate']='selected';	
					$data['mediaPublic']='';
				}
				else{
					$data['mediaPrivate']='';	
					$data['mediaPublic']='selected';
				}
				//Display if Vintage section
				if($allData->vintage == 1 ){
					$data['mediaVintage']='selected';	
					$data['mediaNormal']='';
				}
				else{
					$data['mediaVintage']='';	
					$data['mediaNormal']='selected';
				}
				//Determine if it saved right/show the type it is via dropdown
				$medias=$this->config->item('recognizedMedia');
				if(in_array($allData->mediaType, $medias)===TRUE){
					$data['mediaOptions']=$this->dropdownOptions($allData->mediaType, $medias);
					$data['mediaInfo']='';
				}
				else{
					$data['mediaInfo']='<div id="mediaTypeError" class="col-xs-12 alert alert-danger" role="alert">
					Warning: Media type was either not set or improperly set. Please make sure to set it appropriately before saving. 
					</div>';
					$data['mediaOptions']=$this->dropdownOptions($allData->mediaType, $medias);
				}
				
				
				$data['exclusive']=$this->exclusiveSelector(NULL, $allData->exclusiveSection, $allData->forSection);
				$data['mediaWhen']=$allData->visibleWhen;
                    $data['mediaBlurb']=$allData->body;
				$this->load->view('dash/mediaEdit', $data);
			}
			else{
				$this->load->view('dash/errorInfo');
			}	
		}
		
		$this->load->view('inc/dash_footer', $data);
		$this->load->view('templates/footer', $data);
	}
}