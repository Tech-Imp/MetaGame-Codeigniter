<?php
class Common_frontend extends Frontend_controller{
		
	
	function __construct(){
		parent::__construct();	
	}	
//------------------------------------------------------------------------------
//GENERIC PREP DATA FUNCTIONS FOR FRONTEND
//------------------------------------------------------------------------------
	public function prepHeader($Header, $count=1){
		//Prep generic tab
		if($count==1){
			return "<li role='presentation' class='active'><a href='#Tab$count' aria-controls='Tab$count' role='tab' data-toggle='tab'> $Header </a></li>";
		}	
		else{
			return "<li role='presentation'><a href='#Tab$count' aria-controls='Tab$count' role='tab' data-toggle='tab'> $Header </a></li>";
		}	
	}
	//--------------------------------------------------------------------------
	public function prepContent($Content, $count=1){
		//Prep generic content
		if($count==1){
			return "<div role='tabpanel' class='tab-pane active' id='Tab$count'> $Content</div>";
		}	
		else{
			return "<div role='tabpanel' class='tab-pane' id='Tab$count'> $Content</div>";
		}	
	}
	//-----------------------------------------------------------------------------
	public function commonHeader()
	{
		$data['css'][0]="main.css";
		$data['css'][1]="frontend/frontend.css";
		$data['site_name']=config_item('site_name');
		$data['dashboard']=$this->myDash();
		$data['userOptions'] = $this->baseTemplate();
		$data['singularContent']='';
		$data['mediaContent']=$data['mediaHeader']='';
          $data['siteLinks']=$this->showLinks();
		return $data;
	}
	//-------------------------------------------------------------------------------
	public function baseTemplate(){
		$section=$this->uri->segment(1, $this->config->item('mainPage'));
		// TODO Need to adjust this to the multisection model dynamically
		$menu='<li>'.anchor($section.'/index', "<img src='/assets/image/Metagame Home Button2.png' class='icon-header img-responsive' alt='Home'>").'</li>';
		$menu.='<li>'.anchor($section.'/news', "<img src='/assets/image/News Button.png' class='icon-header img-responsive' alt='News'>").'</li>';
		$menu.='<li>'.anchor($section.'/media', "<img src='/assets/image/Media Button.png' class='icon-header img-responsive' alt='Media'>").'</li>';
		$menu.='<li>'.anchor($section.'/articles', "<img src='/assets/image/Articles Button.png' class='icon-header img-responsive' alt='Articles'>").'</li>';
		// $menu.='<li>'.anchor($section.'/video', "Video").'</li>';
		// $menu.='<li>'.anchor($section.'/photos', "Photos").'</li>';
		// $menu.='<li>'.anchor($section.'/merch', "<img src='/assets/image/Shop Button.png' class='icon-header img-responsive' alt='Merch'>").'</li>';
		
		$menu.='<li>'.anchor($section.'/contact', "<img src='/assets/image/Profiles Button.png' class='icon-header img-responsive' alt='Profiles'>").'</li>';	
		// $menu.=$section;
		
		return $menu;
	}
	//---------------------------------------------------------------------------------------	
	public function myDash(){
		$currentRole=$this->session->userdata('role');
		if($currentRole===FALSE){
			$currentRole=0;
			// return "<ul class='nav navbar-nav navbar-right'>
	      			// <li>".anchor('signup', "<span class='glyphicon glyphicon-plus'></span>Sign up")."</li>
	      			// <li>".anchor('login', "<span class='glyphicon glyphicon-log-in' title='Log In'></span>")."</li>
	      		// </ul>";
      		return "";
		}
		if($currentRole>=$this->config->item('contributor')){
			return "<ul class='nav navbar-nav navbar-right'>
	      			<li>".anchor('admin/dashboard', "<span class='glyphicon glyphicon-dashboard' title='Dashboard'></span> Dash")."</li>
	      			<li>".anchor('login/logout', "<span class='glyphicon glyphicon-log-out' title='Log Out'></span> Logout")."</li>
	      		</ul>";
		}
		else{
			return "<ul class='nav navbar-nav navbar-right'>
	      			<li>".anchor('admin/dashboard', "<span class='glyphicon glyphicon-dashboard' title='Dashboard'></span> Dash")."</li>
	      			<li>".anchor('login/logout', "<span class='glyphicon glyphicon-log-out' title='Log Out'></span> Logout")."</li>
	      		</ul>";
		}
		
	}
	
//-------------------------------------------------------------------------------------	
//----------------------------------------------------------------------------------	
     public function showLinks(){
          $this->load->model('SectionAuth_model');
          $this->load->model('Dataprep_model');
          $linkData=$this->SectionAuth_model->getQuicklinks();
          return $this->Dataprep_model->linkGen($linkData);
     }	
	
	
}