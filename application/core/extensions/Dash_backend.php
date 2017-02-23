<?php
class Dash_backend extends Admin_controller{
		
	function __construct(){
		parent::__construct();	
	}
	
//-------------------------------------------------------------------------------
//Common functions
//------------------------------------------------------------------------------
	protected function exclusiveSelector($multi="", $exFlag=TRUE, $exPage=''){
			
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
							<select id="section'.$multi.'" >
							   <option value=""></option>'
							   .$this->dropdownSections(NULL, NULL, $exPage).
							'</select>
						</div>
		      			<div class="col-xs-12 col-md-6">Always displays to main as well, unless exclusive</div>
					</div><br><div class="row">
						<div class="col-xs-2"><strong>Exclusive to section?</strong></div>
						<div class="col-xs-10 col-md-4">
	      					<select id="exclusiveFlag'.$multi.'">
                                        <option '.$exYes.' value="1">Yes</option>
	      						<option '.$exNo.' value="0">No</option>
							</select>
      					</div>
		      			<div class="col-xs-12 col-md-6">"Yes" will only display to specific section</div>
					</div>';	
		
		return $exclusive;
	}
	
	protected function validTypes($type){
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
//-----------------------------------------------------------------------------------
//Common header
//-----------------------------------------------------------------------------------
	protected function commonHeader()
	{
		
		$data['css'][0]="main.css";
		$data['site_name']=config_item('site_name');
		$data['title']="Content Management";
		$data['additionalTech']="";
		$who=$_SESSION['name'];
		$data['logout']=$this->logoutPATH;
		$data['userName'] = "<li class='invertColor'>Welcome back, ".$who. "</li>";
		
		$data['userOptions']=$this->menu();
		
		return $data;
	}
//----------------------------------------------------------------------------------	
//Menu handles all filling in of action the user can take based on their role
//----------------------------------------------------------------------------------
	protected function menu()
	{
		// Determine menu options based on role
		$userOptions=anchor('main/index',"<span class='glyphicon glyphicon-arrow-left'></span> Back to Site", array('class'=>'btn btn-primary btn-lg btn-block', 'id'=>'retSite'));
		$userOptions.=anchor('admin/dashboard',"<span class='glyphicon glyphicon-home'></span> Home", array('class'=>'btn btn-primary btn-lg btn-block', 'id'=>'retHome'));
		if($_SESSION['role'] >= $this->config->item('contributor')){
			
			$userOptions.=anchor('admin/dashboard/written',"<span class='glyphicon glyphicon-align-left'></span> Add Written", array('class'=>'btn btn-primary btn-lg btn-block', 'id'=>'addArticle'));
			$userOptions.=anchor('admin/dashboard/multimedia',"<span class='glyphicon glyphicon-file'></span> Add Media", array('class'=>'btn btn-primary btn-lg btn-block', 'id'=>'addMedia'));
			$userOptions.=anchor('admin/dashboard/items',"<span class='glyphicon glyphicon-usd'></span> Add Merch", array('class'=>'btn btn-primary btn-lg btn-block', 'id'=>'addItem'));
			$userOptions.=anchor('admin/dashboard/profile',"<span class='glyphicon glyphicon-folder-open'></span> Edit Profile", array('class'=>'btn btn-primary btn-lg btn-block', 'id'=>'editProfileElements'));
			$userOptions.=anchor('admin/dashboard/stats',"<span class='glyphicon glyphicon-stats'></span> View Stats", array('class'=>'btn btn-primary btn-lg btn-block', 'id'=>'vStats'));
	            	
		}
		if($_SESSION['role'] >= $this->config->item('sectionAdmin')){
			$userOptions.=anchor('admin/systems/',"<span class='glyphicon glyphicon-alert'></span> Admin Tools", array('class'=>'btn btn-primary btn-lg btn-block', 'id'=>'metaTools'));
		}
		$userOptions.=anchor('admin/dashboard/users',"<span class='glyphicon glyphicon-cog'></span> Change Settings", array('class'=>'btn btn-primary btn-lg btn-block', 'id'=>'cSettings'));
	
		return $userOptions;
	}
//--------------------------------------------------------------------------------------
//Admin Functions
//---------------------------------------------------------------------------------------	
	protected function adminHeader()
	{
		if($_SESSION['role'] >= $this->config->item('sectionAdmin')){
			$data['css'][0]="main.css";
			$data['site_name']=config_item('site_name');
			$data['title']="System Management";
			$data['additionalTech']="";
			$who=$_SESSION['name'];
			$data['logout']=$this->logoutPATH;
			$data['userName'] = "<li class='invertColor'>Welcome back, ".$who. "</li>";
			$data['userOptions']=$this->toolMenu();
			return $data;
		}
		else{
			redirect('admin/dashboard');	
		}
	}
	
	
	protected function toolMenu()
	{
		// Determine menu options based on role
		$userOptions=anchor('main/index',"<span class='glyphicon glyphicon-arrow-left'></span> Back to Site", array('class'=>'btn btn-primary btn-lg btn-block', 'id'=>'retSite'));
		$userOptions.=anchor('admin/dashboard',"<span class='glyphicon glyphicon-home'></span> Back to Dashboard", array('class'=>'btn btn-primary btn-lg btn-block', 'id'=>'retHome'));
		if($_SESSION['role'] >= $this->config->item('sectionAdmin')){
               $userOptions.=anchor('admin/systems/tools',"<span class='glyphicon glyphicon-wrench'></span> Section Control", array('class'=>'btn btn-primary btn-lg btn-block', 'id'=>'secControl'));
          }
		
		if($_SESSION['role'] >= $this->config->item('superAdmin')){
			$userOptions.=anchor('admin/systems/logs',"<span class='glyphicon glyphicon-folder-open'></span> Error Logs", array('class'=>'btn btn-primary btn-lg btn-block', 'id'=>'editProfileElements'));
			$userOptions.=anchor('admin/dashboard/users/listUsers',"<span class='glyphicon glyphicon-user'></span> Lookup Users", array('class'=>'btn btn-primary btn-lg btn-block', 'id'=>'vUsers'));
            	$userOptions.=anchor('admin/systems/migration',"<span class='glyphicon glyphicon-level-up'></span> Migration", array('class'=>'btn btn-primary btn-lg btn-block', 'id'=>'migrate'));
		}
	
		return $userOptions;
	}
	protected function secureArea(){
	     $this->protectedArea($this->config->item('contributor'), 'admin/dashboard');
	}
	
}
