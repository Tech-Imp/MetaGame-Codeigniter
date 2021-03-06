<?php
class common_frontend extends frontend_controller{
		
	
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
		return $data;
	}
	//-------------------------------------------------------------------------------
	public function baseTemplate(){
		
		$menu='<li>'.anchor('main/index', "Home").'</li>';
		$menu.='<li>'.anchor('main/news', "News").'</li>';
		$menu.='<li>'.anchor('main/video', "Video").'</li>';
		$menu.='<li>'.anchor('main/photos', "Photos").'</li>';
		$menu.='<li>'.anchor('main/gear', "Merch").'</li>';
		
		$menu.='<li>'.anchor('main/contact', "Contact").'</li>';	
		
		return $menu;
	}
	//---------------------------------------------------------------------------------------	
	public function myDash(){
		$currentRole=$this->session->userdata('role');
		if($currentRole===FALSE){
			$currentRole=0;
			return "<ul class='nav navbar-nav navbar-right'>
	      			<li>".anchor('signup', "Sign up")."</li>
	      			<li>".anchor('login', "Log In")."</li>
	      		</ul>";
		}
		if($currentRole>=7){
			return "<ul class='nav navbar-nav navbar-right'>
	      			<li>".anchor('admin/dashboard', "Dashboard")."</li>
	      			<li>".anchor('login/logout', "Logout")."</li>
	      		</ul>";
		}
		else{
			return "<ul class='nav navbar-nav navbar-right'>
	      			<li>".anchor('admin/dashboard', "My Dashboard")."</li>
	      			<li>".anchor('login/logout', "Logout")."</li>
	      		</ul>";
		}
		
	}
	
//-------------------------------------------------------------------------------------	
//----------------------------------------------------------------------------------		
	
	
}