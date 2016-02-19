<?
class Get extends CI_Controller{
     
	function getBook() {
		header('content-type: text/javascript');
		$type = $this->input->get_post('type');
		// $this->load->model("GeneralModel"); 
		// $this->GeneralModel->bookList($type);
		$data=array('debug' => "This is only a testing model, no database is hooked up");
  		echo json_encode($data);
      	exit; 
 	}
	function getMedia(){
		$type = $this->input->get_post('type');
		// $this->load->model("GeneralModel"); 
		// $this->GeneralModel->bookList($type);
		$data=array('debug' => "This is only a testing model, no database is hooked up");
  		echo json_encode($data);
      	exit; 
	}
         
}  


