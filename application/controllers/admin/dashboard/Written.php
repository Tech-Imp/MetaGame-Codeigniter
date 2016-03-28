<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Written extends Dash_backend{
	
	function __construct(){
		parent::__construct();
	}
	
//------------------------------------------------------------------------------------------------------------------------------------
//Add/remove/edit New articles
//------------------------------------------------------------------------------------------------------------------------------------
	
	public function index(){
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
		
		$data['articleTable']=$this->Dataprep_model->gatherItemsAdmin($articles, "news", "news_id", "written/editWritten", $maxNewsCount, $maxLimit, 0);
		
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
}