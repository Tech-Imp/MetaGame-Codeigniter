<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile extends Dash_backend{
	
	function __construct(){
		parent::__construct();
	}
	
//-------------------------------------------------------------------------------------------------------------------------------------
//Profile Specific
//---------------------------------------------------------------------------------------------------------------------------------	
	public function index(){
		$this->load->model('Media_model');
		$this->load->model('Profilepages_model');
		$this->load->model('Subpages_model');
		$this->load->model('Subauth_model');
		$this->load->model('Dataprep_model');
		$data=$this->commonHeader();
		$data['js'][0]= 'tinymce/jquery.tinymce.min.js';
		$data['js'][1]= 'plupload/plupload.full.min.js';
		$data['js'][2]= 'plupload/jquery.ui.plupload/jquery.ui.plupload.js';
		$data['js'][3]= 'dash/dashboardIndex.js';
		$data['js'][4]= 'dash/dashboardStatic.js';
		$data['css'][1]='plupload/jquery.ui.plupload.css';
		
		
		//TODO NEED TO alter dropdowns to accept value inputs
		$myAuths=$this->Subauth_model->getSubSelects();
		if(count($myAuths)){
			$data['validSections']=$this->dropdownOptions(NULL, $myAuths->sub_name, $myAuths->sub_dir);
		}
		
		
		$data['exclusiveAvatar']=$this->exclusiveSelector("Avatar");
		$data['exclusiveProfile']=$this->exclusiveSelector("Profile");
		$data['exclusiveSocial']=$this->exclusiveSelector("Social");
		
		$maxLimit=$this->config->item('maxAdmin');
		// Get Avatars
		$myMedia=$this->Media_model->getAvatar(NULL, $maxLimit, 0);
		$maxMediaCount=$this->Media_model->getAvatarCount();
		$data['avatarTable']=$this->Dataprep_model->gatherItemsAdmin($myMedia, "media", "media_id", "multimedia/editMedia", $maxMediaCount, $maxLimit, 0);
		// Get profile
		$contacts=$this->Profilepages_model->getProfile();
		$data['contactTable']=$this->Dataprep_model->profileItemsAdmin($contacts, "profiles", "static_id", "profile/editProfile");
		// Get Social
		$social=$this->Subpages_model->getSocial();
		$data['socialTable']=$this->Dataprep_model->socialItemsAdmin();
		$data['travelTable']="ITEM NOT HOOKED UP TO DATABASE DO NOT USE";
		
		
		$data['currentLocation']="<div class='navbar-brand'>Profile Dashboard</div>";		
		//To cover bases, any additional outside tech is documented
		$data['additionalTech']="<div class='row'>
			<br>
			<div class='col-xs-12 col-md-offset-5 col-md-3 addedTech'>
				<div> This page uses tinyMCE for text editing and Plupload for the file upload interface. </div>
			</div>
		</div>";
		
		$this->load->view('templates/header', $data);
		$this->load->view('inc/dash_header', $data);
		$this->load->view('dash/staticUploader');
		$this->load->view('inc/dash_footer', $data);
		$this->load->view('templates/footer', $data);	
	}

	public function editProfile($id=NULL){
		$this->load->model('Media_model');
		$this->load->model('Profilepages_model');
		$this->load->model('Dataprep_model');
		$data=$this->commonHeader();
		$data['js'][0]= 'tinymce/jquery.tinymce.min.js';
		$data['js'][1]= 'dash/dashboardIndex.js';
		$data['js'][2]= 'dash/dashboardUpdateContact.js';
		$data['currentLocation']="<div class='navbar-brand'>Edit Contact Info</div>";
		$this->load->view('templates/header', $data);
		$this->load->view('inc/dash_header', $data);
		
		if($id===NULL){
			$this->load->view('dash/errorInfo');
		}
		else {
			//In lieu of a more succinct way to display this in the edit just use same method
			$maxLimit=$this->config->item('maxAdmin');
			$myMedia=$this->Media_model->getAvatar(NULL, $maxLimit, 0);
			$maxMediaCount=$this->Media_model->getAvatarCount();
			$data['avatarTable']=$this->Dataprep_model->simpleAvatars($myMedia, "media", "media_id", "multimedia/editMedia", $maxMediaCount, $maxLimit, 0);
			$allData=$this->Profilepages_model->getProfile(intval($id));
			if(count($allData)){
				$data['staticID']=$allData->static_id;
				$data['contactTitle']=$allData->title;
				$data['avatarUsed']=$allData->avatarID;
				$data['contactName']=$allData->profileName;
				$data['staticBody']=$allData->body;
				$data['exclusive']=$this->exclusiveSelector(NULL, $allData->exclusiveSection, $allData->forSection);
				$this->load->view('dash/contactEdit', $data);
			}
			else{
				$this->load->view('dash/errorInfo');
			}	
		}
		
		$this->load->view('inc/dash_footer', $data);
		$this->load->view('templates/footer', $data);
	}
}