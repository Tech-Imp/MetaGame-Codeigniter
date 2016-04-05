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
				$data['currentVersion']="UNKNOWN";
				if ( ! $this->migration->current())
				{
					$data['recentChanges']=($this->migration->error_string());
				}
				else{
					$data['recentChanges']= "<div>Migration worked.</div>";
				}
				
				$data['migrationName']="<pre>".implode(" \n ", $this->migration->find_migrations())."</pre>";
				$this->load->view('dash/migration', $data);
			}
			else{
				$this->load->view('dash/errorInfo');
			}
				
			$this->load->view('inc/dash_footer', $data);
			$this->load->view('templates/footer', $data);
	
		}
	}
