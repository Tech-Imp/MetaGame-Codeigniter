<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class News extends Common_frontend {
	
	function __construct(){
		parent::__construct();
		
	}


	public function index($id=NULL)
	{
		$this->load->model('beta/Article_model', "Article_model");
		$this->load->model('beta/Dataprep_model', "Dataprep_model");
		
		$maxLimit=$this->config->item('maxArticles');	
		$data=$this->commonHeader();
		$data['css'][2]='frontend/blog.css';
		$data['js'][0]='commonShared.js';
		$data['title']="News";
		
		
		if($id===NULL){
			$articles=$this->Article_model->getNewsPublic(NULL, $maxLimit, 0);
			$maxItems=$this->Article_model->getNewsCount();
			$data['singularContent']=$this->Dataprep_model->gatherItems($articles, "news", "news_id", "news", 1, $maxItems, $maxLimit);
		}
		else{
			
			$allData=$this->Article_model->getNewsPublic(intval($id));
			if(count($allData)){
				$articles=array("solo" => $allData);
				$data['singularContent']=$this->Dataprep_model->gatherItems($articles, "news", "news_id", "news");
			}
			else{
				$data['singularContent']="<div><h4>That article does not exist.</h4></div>";
			}
		}
		
		
		$this->load->view('templates/header', $data);
		$this->load->view('mainLayout', $data);
		// $this->load->view('inc/modals');
		$this->load->view('templates/footer', $data);
		
		
	}
	
	
}

