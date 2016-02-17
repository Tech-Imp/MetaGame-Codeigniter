<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class dashboard extends admin_controller {
	
	function __construct(){
		parent::__construct();
	}
	
//--------------------------------------------------------------------------------------------------
//Base Page
//--------------------------------------------------------------------------------------------------
	public function index()
	{	
		$data=$this->commonHeader();
		$this->load->model('Logging_model');
		$this->load->model('Media_model');
		$this->load->model('Dataprep_model');
		$data['currentLocation']="<div class='navbar-brand'>Your Dashboard</div>";
		$this->load->view('templates/header', $data);
		$this->load->view('inc/dash_header', $data);
		
		
		//Logging of recent items
		$logOutput="<div><h4>Recent activity:</h4><br>No recent activity to report.</div>";
		$logs=$this->Logging_model->getQuickLogs(15,0);
		if(count($logs)){
			$logOutput='<div><h4>Recent activity:</h4><br><ul>';
			foreach ($logs as $row) {
				$logOutput.='<li>'.$row->change.'</li>';	
			}
			$logOutput.='</ul></div>';
		}
		$data['recentChanges']=$logOutput;
		//Recent Photos
		$myMedia=$this->Media_model->getMedia(NULL, 5, 0);
		$data['mediaTable']=$this->Dataprep_model->gatherItemsAdmin($myMedia, "media", "media_id", "editMedia");
		
		$this->load->view('dashboard', $data);
		$this->load->view('inc/dash_footer', $data);
		$this->load->view('templates/footer', $data);
		
	}
	
//----------------------------------------------------------------------------------------------------
//Common header
//----------------------------------------------------------------------------------------------------

	private function commonHeader()
	{
		
		$data['css'][0]="main.css";
		// $data['js'][0]='bookObject.js';
		// $data['js'][1]='bookIndex.js';
		$data['site_name']=config_item('site_name');
		$data['title']="Content Management";
		$data['additionalTech']="";
		$who=$this->session->userdata('name');
		$data['logout']=$this->logoutPATH;
		$data['userName'] = "<li class='invertColor'>Welcome back, ".$who. "</li>";
		
		$data['userOptions']=$this->menu();
		
		return $data;
	}
	
		
	
//-------------------------------------------------------------------------------------------------------------------
//Menu handles all filling in of action the user can take based on their role
//--------------------------------------------------------------------------------------------------------------------
	
	private function menu()
	{
		// Determine menu options based on role
		$userOptions=anchor('main/index',"<span class='glyphicon glyphicon-arrow-left'></span> Back to Site", array('class'=>'btn btn-primary btn-lg btn-block', 'id'=>'retSite'));
		$userOptions.=anchor('admin/dashboard',"<span class='glyphicon glyphicon-home'></span> Home", array('class'=>'btn btn-primary btn-lg btn-block', 'id'=>'retHome'));
		if($this->session->userdata('role') >= $this->config->item('contributor')){
			
			$userOptions.=anchor('admin/dashboard/written',"<span class='glyphicon glyphicon-align-left'></span> Add Written", array('class'=>'btn btn-primary btn-lg btn-block', 'id'=>'addArticle'));
			$userOptions.=anchor('admin/dashboard/multimedia',"<span class='glyphicon glyphicon-file'></span> Add Media", array('class'=>'btn btn-primary btn-lg btn-block', 'id'=>'addMedia'));
			$userOptions.=anchor('admin/dashboard/items',"<span class='glyphicon glyphicon-usd'></span> Add Merch", array('class'=>'btn btn-primary btn-lg btn-block', 'id'=>'addItem'));
			$userOptions.=anchor('admin/dashboard/profile',"<span class='glyphicon glyphicon-tag'></span> Edit Profile", array('class'=>'btn btn-primary btn-lg btn-block', 'id'=>'editProfileElements'));
			$userOptions.=anchor('admin/dashboard/users',"<span class='glyphicon glyphicon-user'></span> Lookup Users", array('class'=>'btn btn-primary btn-lg btn-block', 'id'=>'vUsers'));
			$userOptions.=anchor('admin/dashboard/stats',"<span class='glyphicon glyphicon-stats'></span> View Stats", array('class'=>'btn btn-primary btn-lg btn-block', 'id'=>'vStats'));
	            	
		}
		if($this->session->userdata('role') >= $this->config->item('superAdmin')){
			$userOptions.=anchor('admin/dashboard/tools',"<span class='glyphicon glyphicon-alert'></span> Admin Tools", array('class'=>'btn btn-primary btn-lg btn-block', 'id'=>'metaTools'));
		}
		$userOptions.=anchor('admin/dashboard/update',"<span class='glyphicon glyphicon-cog'></span> Change Settings", array('class'=>'btn btn-primary btn-lg btn-block', 'id'=>'cSettings'));
	
		return $userOptions;
	}


	
	
//-----------------------------------------------------------------------------------------------------------------
//For updating self and if role is high enough, others as well
//------------------------------------------------------------------------------------------------------------------	
	
	public function update($id=NULL)
	{
		$this->load->model('Admin_model');
		$data=$this->commonHeader();
		$data['currentLocation']="<div class='navbar-brand'>Your Settings</div>";
		
		// Find the name of the person to be changed
		$roleAffected=-1;
		if($id!==NULL){
			$whoArr=$this->Admin_model->get_by(array('id' => $id), true);
			if(count($whoArr)){
				$who=$whoArr->name;
				$whoAuth=$this->Admin_model->get_by(array('id'=>$id), true, 'auth_role', 'id');
				if(count($whoAuth)){ $roleAffected=$whoAuth->role; }
			}
			else{
				$who=-1;
			}
		}
		else{
			$who=$this->session->userdata('name');
		}
		$editID=NULL;
		// Verify all rules for input fields are followed
		$rules=$this->Admin_model->rules;
		$this->form_validation->set_rules($rules);
		if($this->form_validation->run() == TRUE){
			//Login
			if($id===NULL){
				$editID=$this->Admin_model->fixSelf();
			
			}
			elseif($this->session->userdata('role')>= $this->config->item('contributor') && $roleAffected !== -1 && $this->session->userdata('role') > $roleAffected){
				$editID=$this->Admin_model->fixOther(intval($id));
			}
			else{
				$this->load->model("Errorlog_model");
				$this->Errorlog_model->newLog($id, '!Pas', 'Password update failed. Insufficient rights for '.$this->session->userdata('id').'of role '.$this->session->userdata('role').
				' to Attempted edit '.$who.'('.$id.') role '.$roleAffected ); 
				$editID=-1;
			}
			
		}
		$progMessage='';
		if($editID!==NULL){
			if($editID!==-1){
				$progMessage="<br> <span class='text-success'>Update Successful</span>";
			}
			else{
				$progMessage="<br> <span class='text-danger'>Update failed.</span> <br>Cannot alter passwords of users of equal or higher rank than you.";
			}
		}
		
		$data['userEdit']="Edit user ".$who.' '.$progMessage;
		
		$this->load->view('templates/header', $data);
		$this->load->view('inc/dash_header', $data);
		//If user doesnt exist
		if($who===-1){
			$this->load->view('dash/errorInfo');
		}
		// if user exceeds your role
		elseif ($this->session->userdata('role') <= $roleAffected) {
			$this->load->view('dash/privInfo');	
		}
		// if user is acceptable for alteration
		else{
			$this->load->view('dash/updateInfo');
		}
		$this->load->view('inc/dash_footer', $data);
		$this->load->view('templates/footer', $data);
		
	}

//-----------------------------------------------------------------------------------------------------------------------
//View all users and redirect to edit user passwords. Since email is used as a login key it is not allowed for altering
//Will only display users with a lower access level than user logged in
//Will need to code a "where" function for specific user lookup in the future
//Should also link to additional databasse to verify transactions the user has made
//Also pull up log entries on user
//------------------------------------------------------------------------------------------------------------------------
	
	public function users(){
		$this->load->model('Admin_model');
		$data=$this->commonHeader();
		$userRecords=$this->Admin_model->getUsers();
		$data['currentLocation']="<div class='navbar-brand'>Users List</div>";
		$data['numUsers']=count($userRecords);
		$data['userTable']="<strong>No users to display from database that are under your authority.</strong>";
		
		
		if(count($userRecords)){
			$data['userTable']="<div class='table-responsive'><table class='table table-hover table-striped'><thead>
				<tr>
					<td>Name</td>
					<td>Email</td>
					<td>Edit</td>
				</tr>
			</thead>
			<tbody>";
			//Loop through the data and make a new row for each
			foreach ($userRecords as $row) {
				$userId=$row->id;
				$data['userTable'].="
				<tr><td>"
				.$row->name."</td>
				<td>".$row->email."</td>
				<td>".anchor('admin/dashboard/update/'.$userId,"<span class='glyphicon glyphicon-cog'></span>")."</td>
				</tr>";
					
			}
			$data['userTable'].="</tbody></table>";
			// $data['userTable']=var_dump($userRecords);
		}
		
		
		$this->load->view('templates/header', $data);
		$this->load->view('inc/dash_header', $data);
		$this->load->view('dash/userInfo');
		$this->load->view('inc/dash_footer', $data);
		$this->load->view('templates/footer', $data);
	}
	
	
//----------------------------------------------------------------------------------------------------------------------------
//Add, remove, view and edit items for sale
//Requires paypal or other sales intergration
//
//----------------------------------------------------------------------------------------------------------------------------
	public function items(){
		$this->load->model('Admin_model');
		$data=$this->commonHeader();
		$data['currentLocation']="<div class='navbar-brand'>Product Dashboard</div>";
		
		// $data['recaptcha']=$this->config->item('recaptcha-site-key');
		// if ($this->Login_model->loggedin() == TRUE){
			// redirect($dashboard);
		// }
		
		// $this->load->library('curl'); 
		
		$this->load->model("Vendor_model");
		$this->load->library('curl');
		
		$rules=$this->Vendor_model->rules;
		$this->form_validation->set_rules($rules);
		if($this->form_validation->run() == TRUE){
			//Login
			
			
		}
		
		
		$this->load->view('templates/header', $data);
		$this->load->view('inc/dash_header', $data);
		$this->load->view('dash/errorInfo');
		$this->load->view('inc/dash_footer', $data);
		$this->load->view('templates/footer', $data);
	}
	
//-----------------------------------------------------------------------------------------------------------------------------
//Add/Remove items from media gallery
//Allow items to still persist but set visibility based on criteria (such as user must be logged in to view)
//------------------------------------------------------------------------------------------------------------------------------
	
	public function multimedia(){
		$this->load->model('Media_model');
		$this->load->model('Dataprep_model');
		$data=$this->commonHeader();
		$data['js'][0]= 'plupload/plupload.full.min.js';
		$data['js'][1]= 'plupload/jquery.ui.plupload/jquery.ui.plupload.js';
		$data['js'][2]= 'dash/dashboardIndex.js';
		$data['js'][3]= 'dash/dashboardMedia.js';
		$data['js'][4]='commonShared.js';
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
		
		$data['mediaTable']=$this->Dataprep_model->gatherItemsAdmin($myMedia, "media", "media_id", "editMedia", $maxMediaCount, $maxLimit, 0);
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
		$this->load->model('Media_model');
		$data=$this->commonHeader();
		$data['js'][0]= 'dash/dashboardIndex.js';
		$data['js'][1]= 'dash/dashboardUpdateMedia.js';
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
				
				switch ($allData->mediaType) {
					case 'picture':
						$data['mediaPic']='selected';
						$data['mediaInfo']=$data['mediaVid']=$data['mediaAud']='';
						break;
					
					case 'video':
						$data['mediaVid']='selected';
						$data['mediaInfo']=$data['mediaPic']=$data['mediaAud']='';
						break;
					
					case 'sound':
						$data['mediaAud']='selected';
						$data['mediaInfo']=$data['mediaPic']=$data['mediaVid']='';
						break;
					
					default:
						$data['mediaInfo']='<div id="mediaTypeError" class="col-xs-12 alert alert-danger" role="alert">
						Warning: Media type was either not set or improperly set. Please make sure to set it appropriately before saving. 
						</div>';
						$data['mediaAud']=$data['mediaPic']=$data['mediaVid']='';
						break;
				}
				
				$data['exclusive']=$this->exclusiveSelector(NULL, $allData->exclusiveSection, $allData->forSection);
				$data['mediaWhen']=$allData->visibleWhen;
				$this->load->view('dash/mediaEdit', $data);
			}
			else{
				$this->load->view('dash/errorInfo');
			}	
		}
		
		$this->load->view('inc/dash_footer', $data);
		$this->load->view('templates/footer', $data);
	}

//------------------------------------------------------------------------------------------------------------------------------------
//Add/remove/edit New articles
//------------------------------------------------------------------------------------------------------------------------------------
	
	public function written(){
		$this->load->model('Article_model');
		$this->load->model('Dataprep_model');
		$data=$this->commonHeader();
		$data['js'][0]= 'tinymce/jquery.tinymce.min.js';
		$data['js'][1]= 'dash/dashboardIndex.js';
		$data['js'][2]= 'dash/dashboardNews.js';
		$data['js'][3]='commonShared.js';
		
		
		
		
		//To cover bases, any additional outside tech is documented
		$data['additionalTech']="<div class='row'>
			<br>
			<div class='col-xs-12 col-md-offset-5 col-md-3 addedTech'>
				<div> This page uses tinyMCE for text editing. </div>
			</div>
		</div>";
		
		$maxLimit=$this->config->item('maxAdmin');
		$articles=$this->Article_model->getArticles(NULL, $maxLimit, 0);
		$maxNewsCount=$this->Article_model->getWrittenCount(false);
		
		$data['articleTable']=$this->Dataprep_model->gatherItemsAdmin($articles, "news", "news_id", "editWritten", $maxNewsCount, $maxLimit, 0);
		
		$data['exclusive']=$this->exclusiveSelector();
		
		$data['currentLocation']="<div class='navbar-brand'>News Dashboard</div>";
		$this->load->view('templates/header', $data);
		$this->load->view('inc/dash_header', $data);
		$this->load->view('dash/newsUploader');
		$this->load->view('inc/dash_footer', $data);
		$this->load->view('templates/footer', $data);
	}
	
	public function editWritten($id=NULL){
		$this->load->model('Article_model');
		$data=$this->commonHeader();
		$data['js'][0]= 'tinymce/jquery.tinymce.min.js';
		$data['js'][1]= 'dash/dashboardIndex.js';
		$data['js'][2]= 'dash/dashboardUpdateNews.js';
		$data['currentLocation']="<div class='navbar-brand'>Edit Written</div>";
		$this->load->view('templates/header', $data);
		$this->load->view('inc/dash_header', $data);
		
		if($id===NULL){
			$this->load->view('dash/errorInfo');
		}
		else {
			$allData=$this->Article_model->getArticles(intval($id));
			if(count($allData)){
				$data['newsID']=$allData->news_id;
				$data['newsTitle']=$allData->title;
				$data['newsStub']=$allData->stub;
				$data['newsBody']=$allData->body;
				$data['newsType']=$this->validTypes($allData->type);
				
				$data['newsWhen']=$allData->visibleWhen;
				$data['exclusive']=$this->exclusiveSelector(NULL, $allData->exclusiveSection, $allData->forSection);
				$this->load->view('dash/newsEdit', $data);
			}
			else{
				$this->load->view('dash/errorInfo');
			}	
		}
		
		$this->load->view('inc/dash_footer', $data);
		$this->load->view('templates/footer', $data);
	}
	
	
	
//-------------------------------------------------------------------------------------------------------------------------------------
//View google analytics and various odds and ends
//--------------------------------------------------------------------------------------------------------------------------------------
	
	public function stats(){
		$this->load->model('Admin_model');
		$data=$this->commonHeader();
		$data['currentLocation']="<div class='navbar-brand'>Site Stats</div>";
		$this->load->view('templates/header', $data);
		$this->load->view('inc/dash_header', $data);
		$this->load->view('dash/errorInfo');
		$this->load->view('inc/dash_footer', $data);
		$this->load->view('templates/footer', $data);
	}
//-------------------------------------------------------------------------------------------------------------------------------------
//
//---------------------------------------------------------------------------------------------------------------------------------	
	public function profile(){
		$this->load->model('Staticpages_model');
		$this->load->model('Dataprep_model');
		$data=$this->commonHeader();
		$data['js'][0]= 'tinymce/jquery.tinymce.min.js';
		$data['js'][1]= 'plupload/plupload.full.min.js';
		$data['js'][2]= 'plupload/jquery.ui.plupload/jquery.ui.plupload.js';
		$data['js'][3]= 'dash/dashboardIndex.js';
		$data['js'][4]= 'dash/dashboardStatic.js';
		$data['css'][1]='plupload/jquery.ui.plupload.css';
		
		
		
		//To cover bases, any additional outside tech is documented
		$data['additionalTech']="<div class='row'>
			<br>
			<div class='col-xs-12 col-md-offset-5 col-md-3 addedTech'>
				<div> This page uses tinyMCE for text editing and Plupload for the file upload interface. </div>
			</div>
		</div>";
		
		$data['exclusiveAvatar']=$this->exclusiveSelector("Avatar");
		$data['exclusiveProfile']=$this->exclusiveSelector("Profile");
		
		$contacts=$this->Staticpages_model->getContact();
		$data['contactTable']=$this->Dataprep_model->gatherItemsAdmin($contacts, "static items", "static_id", "editProfile");
		$data['travelTable']="ITEM NOT HOOKED UP TO DATABASE DO NOT USE";
		
		$data['currentLocation']="<div class='navbar-brand'>Static Pages Dashboard</div>";
		$this->load->view('templates/header', $data);
		$this->load->view('inc/dash_header', $data);
		$this->load->view('dash/staticUploader');
		$this->load->view('inc/dash_footer', $data);
		$this->load->view('templates/footer', $data);	
	}

	public function editProfile($id=NULL){
		$this->load->model('Staticpages_model');
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
			$allData=$this->Staticpages_model->getContact(intval($id));
			if(count($allData)){
				$data['staticID']=$allData->static_id;
				$data['staticTitle']=$allData->title;
				$data['staticBody']=$allData->body;
				
				$this->load->view('dash/contactEdit', $data);
			}
			else{
				$this->load->view('dash/errorInfo');
			}	
		}
		
		$this->load->view('inc/dash_footer', $data);
		$this->load->view('templates/footer', $data);
	}
	private function exclusiveSelector($multi="", $exFlag=false, $exPage=''){
			
		if($multi===NULL){$multi="";}
		$exYes=$exNo="";
		
		if ($exFlag) {
			$exYes="selected";
		}
		else{
			$exNo="selected";
		}
		
		$exclusive='<br><div class="row">
						<div class="col-xs-2"><strong>Show in section</strong></div>
						<div class="col-xs-10 col-md-4 ui-widget">
							<input type="text" id="section'.$multi.'" value='.$exPage.'>
						</div>
		      			<div class="col-xs-12 col-md-6">Always displays to main as well, unless exclusive</div>
					</div><br><div class="row">
						<div class="col-xs-2"><strong>Exclusive to section?</strong></div>
						<div class="col-xs-10 col-md-4">
	      					<select id="exclusiveFlag'.$multi.'">
	      						<option '.$exNo.' value="0">No</option>
  								<option '.$exYes.' value="1">Yes</option>
							</select>
      					</div>
		      			<div class="col-xs-12 col-md-6">"Yes" will only display to specific section</div>
					</div>';	
		
		return $exclusive;
	}
	private function validTypes($type){
		$typeOptions=$newsSelected=$articlesSelected="";
		
		if($type=="news"){
			$newsSelected="selected";
		}
		else if($type=="articles"){
			$articlesSelected="selected";
		}
		
		$typeOptions="<select id='typeID'>
			<option ".$newsSelected. " value='news'>NEWS</option>
			<option ".$articlesSelected." value='articles'>ARTICLE</option>
		</select>";
		
		return $typeOptions;
	}



	
}

