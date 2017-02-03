<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends Common_frontend {
	
	function __construct(){
		parent::__construct();
		
	}
	
	
	public function index()
	{
		$this->load->model('beta/Media_model', "Media_model");
		$this->load->model('beta/Article_model', "Article_model");
		$this->load->model('beta/Dataprep_model', "Dataprep_model");
		$data=$this->commonHeader();
		// $data['js'][0]='bookObject.js';
		// $data['js'][1]='bookIndex.js';
		$data['title']="Home";
		$data['css'][2]="frontend/home.css";		
		
				
		$currentRole=$_SESSION['role'];
		

		if($currentRole>0){
			$myMedia=$this->Media_model->getFrontMedia(NULL, NULL, NULL, 5);
		}
		else{
			$myMedia=$this->Media_model->getFrontMedia(NULL, NULL, 0, 5);
		}
		$articles=$this->Article_model->getNewsPublic(NULL, 1);
		
		// $data['mediaHeader'].=$this->prepHeader("<h3>Recently Added Photos</h3>");
		// $data['singularContent'].=$this->prepContent($this->Dataprep_model->createCarousel($myMedia, TRUE));
		$data['singularContent'].=$this->Dataprep_model->createCarousel($myMedia, TRUE, TRUE);
		$data['singularContent'].="</br></br>";
		// $data['mediaHeader'].=$this->prepHeader("<h3>Most Recent Post</h3>", 2);
		// $data['singularContent'].=$this->prepContent($this->Dataprep_model->gatherItems($articles, "news", "news_id", "news"), 2);
		$data['singularContent'].=$this->Dataprep_model->gatherItems($articles, "news", "news_id", "news");
		
		
		$this->load->view('templates/header', $data);
		$this->load->view('mainLayout', $data);
		// $this->load->view('inc/modals');
		$this->load->view('templates/footer', $data);
		
		
	}

	
	
}

