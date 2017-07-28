<?
class Sectionexposure_model extends MY_Model{
    	
    protected $_table_name='page_visibility';
	protected $_primary_key='id';
	protected $_primary_filter='intval';
	protected $_order_by='id DESC';
	public $rules=array();
	protected $_timestamp=FALSE;
	private $_basicSection=array("index", "news", "articles", "media", "photos", "video", "audio", "merch", "contact");
     private $_origRouteFile="application/config/routes.php";
     private $_exRouteFile="application/config/addroute.php";
	
	 
	function __construct(){
		parent::__construct();
	} 
	
	public function showSection($url){
          if(!(empty($url))){
               $this->db->like('sub_url', url);
          }
          return $this->get();
     }
//--------------------------------------------------------------------------------------------------------
//-----------------------Core add/removal----------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------	
 	//Add all the basic pages that should exist per section
	public function sectionAddBasic($url=NULL, $minRole=NULL, $redirect="", $writeSave=TRUE){
		if(!(empty($url))){
			// test to see if anyone else is adding sections currently
			if($this->createLock()){	     	
	          	if($writeSave){$this->saveSectionVis($url, $minRole, $redirect);}
		 		$this->appendRoute($url);
	           	foreach($this->_basicSection as $item){
                         if($writeSave){$this->saveSectionVis($url.'/'.$item, $minRole, $redirect);}
                         $this->appendRoute($url, $item);
	           	}
	           	return $this->removeLock();
			}
			else{
				return FALSE;	//This false means lock file could not be established
			}
     	}
      	else{
           	return FALSE;
  		}
     }
     
     //Removes all subsections of a section
     public function sectionRemoveBasic($url){
      	if(!(empty($url))){
   			if($this->createLock()){	//Check lock just in case some other process is running
	       		$otherUrls=array();
	           	$otherUrls=array_map(function($suffix) use ($url){
	            	      return $url."/".$suffix;
	           	}, $this->_basicSection);
	           
	           	$this->db->where('sub_url', $url);
	           	$this->db->or_where_in('sub_url', $otherUrls);
	           	$this->db->delete($this->_table_name);
	           	//Double check the deed was done correctly
	           	$this->db->like('sub_url', $url);  
	           	$results=$this->get();
				$this->removeLock();
				if( count($results) ==0){
				     return "success";
	           	}
	           	else {
	            	      //Show variance of results found versus expected
	            	      return count($results) - (count($this->_basicSection)+1);
	           	}
			}
			else{return " ERROR[Routing locked, unable to delete] ";}
      	}
  		else{return " ERROR[No URL passed] ";}
 	}
 	
 	//Core functionality to update or create new entries for visibility
	private function saveSectionVis($subUrl=NULL, $minRole=NULL, $redirect="", $comment="", $id=NULL){
		if(!(empty($subUrl)) || !(empty($id))){		
     		$data=array(
                    'redirect_to'=>$redirect,
                    'comment'=>$comment
     		); 
               if(!empty($subUrl)){$data['sub_url']=$subUrl;}
               if(empty($minRole) && $minRole!==0){
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
//----------------------------------------------------------------------------------------------------------------------------
//---------------Advanced functions-------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------------	 
     public function maintenanceMode(){
          $items=$this->maintenanceItems();
          $result=0;
          foreach ($items as $row) {
              $prev=$result;
              $result=$this->saveSectionVis(NULL, $this->config->item('contributor'), "", "lastMaintenance ".date('Y-m-d H:i:s [e]'), $row->id);
              if($result==-1)return $result*$prev.", item failed: ".$row->id;//shows last entry that was processed before failure and failure
          }
          return $result;
          
     }
     
     
     //Update group with similar issues     
     public function adjustGroupingBasic($url=NULL, $minRole=NULL, $redirect="", $comment=""){
          if(!(empty($url))){
               $this->db->like('sub_url', $url);  
               $results=$this->get();
               //verify that the section exists and has children and elements to change
               if( count($results)&& ((isset($minRole)) || !(empty($redirect)) || !(empty($comment)))){
                    foreach($results as $row){
                         $this->saveSectionVis(NULL, $minRole, $redirect, $comment, $row->id);
                    }
                    return TRUE;
               }
               else{
                    return FALSE;
               }
          } 
          else{
               return FALSE;
          }    
          
     }
     public function regenRoutes(){
 		if($this->createLock("route.lock")){
	     	$skipMe=array("beta", "admin", "main");
	     	$this->load->model("SectionAuth_model");
			//Remove existing secondary routing file
			if(file_exists($this->_exRouteFile)){
                    unlink($this->_exRouteFile);
          	}
          	$result=$this->SectionAuth_model->get();
			if(count($result)){
				foreach($result as $item){
					if(in_array($item->sub_dir, $skipMe)){continue;}
					if(!$this->sectionAddBasic($item->sub_dir, NULL, "", FALSE)){return FALSE;}
				}
			}
			return $this->removeLock("route.lock");
		}
		return FALSE;
     }
     // Returns all items user has access to for maintenance to lockdown
     public function maintenanceItems(){
          $myID=$_SESSION['id'];
          $myRole=$_SESSION['role'];
          if($myRole< $this->config->item('superAdmin')){
               $this->db->where("author_id", $myID);
          }
          $this->db->not_like("sub_url", "beta");
          $this->joinTable("subsite_database",  "sub_url", "sub_dir", "*", "subsite_id, sub_name, visible, forSection, author_id");
          $this->joinTable("users",  "subsite_database.author_id", "users.id", "*", "name, email");
          return $this->get();
     }
     
     
     //Retrieves the data on visible pages, providing a second option returns all available results
     public function getSectionVis($id=NULL, $subdir=false){
          $myID=$_SESSION['id'];
          $myRole=$_SESSION['role'];
          if($myRole< $this->config->item('superAdmin')){
               $this->db->where("author_id", $myID);
          }
          
          if(!$subdir){
               $this->db->not_like("sub_url", "/");
          }
          
          $this->joinTable("subsite_database",  "sub_url", "sub_dir", "*", "subsite_id, sub_name, visible, forSection, author_id");
          $this->joinTable("users",  "subsite_database.author_id", "users.id", "*", "name, email");
          return $this->get($id);
     }

//--------------------------------------------------------------------------------------------
//Routes specific functions
//--------------------------------------------------------------------------------------------

     private function createLock($lockFile="generic.lock"){
          if (fopen($lockFile, 'x')){
               return TRUE;  //Lock file didnt exist but now does
          }
          else{
               return FALSE; //Lock already in progress
          }
     }
     private function removeLock($lockFile="generic.lock"){
          if(file_exists($lockFile)){
               unlink($lockFile);
               return TRUE;
          }
          else{
               return FALSE;
          }
     }
	 // Generic routing function
	 private function appendRoute($section, $sub="", $remapSection=""){
	 	$this->_exRouteFile="application/config/addroute.php";
		
		$newroute="";
		// first time creation of extra routing
		if(!(file_exists($this->_exRouteFile))){
			$newroute="<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');\n\n";
			$forceInclude="include_once 'addroute.php';";
			if( strpos(file_get_contents($this->_origRouteFile), $forceInclude) !== false) {
        		file_put_contents($this->_origRouteFile, $forceInclude, FILE_APPEND );
    		}
			
		}
		// Prepend a slash in the event the sub is empty	
		if($sub!="")$sub="/".$sub;	
	 	
	 	// Generate the new route and append to file
	 	if($remapSection==""){
	 		$newroute.="\$route['".$section.$sub."']='main".$sub."';\n";
	 	}
		else{
			$newroute.="\$route['".$section.$sub."']='".$remapSection.$sub."';\n";
		}
	 	file_put_contents($this->_exRouteFile, $newroute, FILE_APPEND );
	 }     
     
}
