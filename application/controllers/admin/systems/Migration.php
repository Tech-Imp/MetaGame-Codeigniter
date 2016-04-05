<?php
	class Migration extends Dash_backend{ 
		
		public function __construct()
		{
			parent::__construct();			
		}
		public function index()
		{
			$data=$this->adminHeader();
			$this->load->model('Dataprep_model');
			$data['currentLocation']="<div class='navbar-brand'>Migration Dashboard</div>";
			$this->load->view('templates/header', $data);
			$this->load->view('inc/dash_header', $data);
			if($this->session->userdata('role') >= $this->config->item('superAdmin')){
				$this->load->library('migration');
				$this->config->load('migration');
				$data['currentVersion']=$this->config->item('migration_version');
				if ( ! $this->migration->current())
				{
					$data['recentChanges']=($this->migration->error_string());
				}
				else{
					$data['recentChanges']= "<div>Migration worked.</div>";
				}
				$data['versionControl']=$this->minilinks();
				$data['migrationName']="<pre>".implode(" \n ", $this->migration->find_migrations())."</pre>";
				$this->load->view('dash/migration', $data);
			}
			else{
				$this->load->view('dash/errorInfo');
			}
				
			$this->load->view('inc/dash_footer', $data);
			$this->load->view('templates/footer', $data);
	
		}
		public function down(){
			$data=$this->adminHeader();
			$this->load->model('Dataprep_model');
			$data['currentLocation']="<div class='navbar-brand'>Migration Dashboard</div>";
			$this->load->view('templates/header', $data);
			$this->load->view('inc/dash_header', $data);
			if($this->session->userdata('role') >= $this->config->item('superAdmin')){
				$this->load->library('migration');
				$this->config->load('migration');
				$version=abs($this->config->item('migration_version')-1);
				if($version>10){
					$data['currentVersion']="<strong>Downgrade</strong> to ".$version;
					if ( ! $this->migration->version($version))
					{
						$data['recentChanges']=($this->migration->error_string());
					}
					else{
						$data['recentChanges']= "<div>Migration downgrade worked.</div>";
					}
				}	
				else{
					$data['recentChanges']= "<div>Migration critical error</div>";
				}
				$data['versionControl']=$this->minilinks();
				$data['migrationName']="<pre>".implode(" \n ", $this->migration->find_migrations())."</pre>";
				$this->load->view('dash/migration', $data);
			}
			else{
				$this->load->view('dash/errorInfo');
			}
		}
		public function latest()
		{
			$data=$this->adminHeader();
			$this->load->model('Dataprep_model');
			$data['currentLocation']="<div class='navbar-brand'>Migration Dashboard</div>";
			$this->load->view('templates/header', $data);
			$this->load->view('inc/dash_header', $data);
			if($this->session->userdata('role') >= $this->config->item('superAdmin')){
				$this->load->library('migration');
				$this->config->load('migration');
				$data['currentVersion']="Latest recognized from list";
				if ( ! $this->migration->latest())
				{
					$data['recentChanges']=($this->migration->error_string());
				}
				else{
					$data['recentChanges']= "<div>Migration Latest worked.</div>";
				}
				$data['versionControl']=$this->minilinks();
				$data['migrationName']="<pre>".implode(" \n ", $this->migration->find_migrations())."</pre>";
				$this->load->view('dash/migration', $data);
			}
			else{
				$this->load->view('dash/errorInfo');
			}
				
			$this->load->view('inc/dash_footer', $data);
			$this->load->view('templates/footer', $data);
	
		}
		
		
		
		private function minilinks(){
			$links=anchor('admin/systems/migration/down',"<span class='glyphicon glyphicon-arrow-down'></span> Downgrade", array('class'=>'btn btn-danger btn-lg', 'id'=>'prevMigrate'));
			$links.=anchor('admin/systems/migration',"<span class='glyphicon glyphicon-screenshot'></span> Target", array('class'=>'btn btn-info btn-lg', 'id'=>'curMigrate'));
			$links.=anchor('admin/systems/migration/latest',"<span class='glyphicon glyphicon-arrow-up'></span> Latest", array('class'=>'btn btn-warning btn-lg', 'id'=>'latestMigrate'));
			return $links;
		}
	}
