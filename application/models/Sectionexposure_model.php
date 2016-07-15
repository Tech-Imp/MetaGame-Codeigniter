<?
class Sectionexposure_model extends MY_Model{
    	
    protected $_table_name='page_visibility';
	protected $_primary_key='id';
	protected $_primary_filter='intval';
	protected $_order_by='id DESC';
	public $rules=array();
	protected $_timestamp=FALSE;
	private $_basicSection=array("index", "news", "articles", "media", "photos", "video", "merch", "contact");
     
	function __construct(){
		parent::__construct();
	}
	//Core functionality to update or create new entries for visibility
	public function saveSectionVis($subUrl=NULL, $minRole=NULL, $redirect="", $comment="", $id=NULL){
		if(!(empty($subUrl)) || !(empty($id))){		
     		$data=array(
                    'redirect_to'=>$redirect,
                    'comment'=>$comment
     		); 
               if(!empty($subUrl)){$data['sub_url']=$subUrl;}
               if(empty($minRole)){
                    $data['min_role']=$this->config->item('contributor');
               }
               else{
                    $data['min_role']=intval($minRole);
               }
               $rowId=$this->save($data, $id);
          }
          else{
              $rowId=-1; 
          }
		return $rowId;
	}
     //Add all the basic pages that should exist per section
	public function sectionAddBasic($url=NULL, $minRole=NULL, $redirect=""){
	     if(!(empty($url))){
	          $this->saveSectionVis($url, $minRole, $redirect);
               foreach($this->_basicSection as $item){
                    $this->saveSectionVis($url.'/'.$item, $minRole, $redirect);
               }
               return TRUE;
	     }
          else{
               return FALSE;
          }
     }
     //Update group with similar issues     
     public function adjustGroupingBasic($url=NULL, $minRole=NULL, $redirect="", $comment=""){
          if(!(empty($url))){
               $this->db->like('sub_url', url);  
               $results=$this->get();
               //verify that the section exists and has children and elements to change
               if( count($results)&& (!(empty($minRole)) || !(empty($redirect)) || !(empty($comment)))){
                    foreach($results as $row){
                         $this->saveSectionVis(NULL, $minRole, $redirect, $comment, $row->id);
                    }
                    return TRUE;
               }
          } 
          else{
               return FALSE;
          }    
          
     }
     //Removes all subsections of a section
     public function sectionRemoveBasic($url){
          if(!(empty($url))){
               $this->db->like('sub_url', url);  
               $results=$this->get();
               if( count($results) <= (count($this->_basicSection)+1)){
                    $this->db->like('sub_url', url);
                    $this->db->delete($this->_table_name);
                    return 0;
               }
               else {
                    //Show variance of results found versus expected
                    return count($results) - (count($this->_basicSection)+1);
               }
          }
          else{return NULL;}
     }
     
     public function showSection($url){
          if(!(empty($url))){
               $this->db->like('sub_url', url);
          }
          return $this->get();
     }
}
