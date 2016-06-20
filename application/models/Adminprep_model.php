<?
class Adminprep_model extends Dataprep_model{
    	
	
	function __construct(){
		parent::__construct();
	}
     
     
     //-------------------------------------------------------------------------------------------------------------------------
//Prepares items for the backend
//-------------------------------------------------------------------------------------------------------------------------
     public function gatherItemsAdmin($myMedia, $items, $primary_key, $editFn, $maxPerRow=0, $limitPerRows=1, $pageOffset=0, $databaseType='all' ){
          //myMedia is the raw data coming back from the model
          //Items is just a string of what these things are
          //editFn leads over the the editing function needed on the dashboard since this is written generically
          //maxRows is the total number of items 
          //limitRows is the amount we want displayed at once
          //pageOffset sets how many multiples of limitRows down we are looking 
          $export="<strong>No ".$items." to display that you have editing rights on.</strong>";
          if(count($myMedia)){
               $export="";
               //Loop through the data and make a new row for each
               foreach ($myMedia as $row) {
                    $newsID=$row->$primary_key;
                    $visItems=$this->visFlag($row);
                    $vis=$visItems['text'];
                    //check if exclusive to logged members
                    $vis.=$this->loggedFlag($row);
                    //Create the block item based on info gathered and add to return value
                    $export.=
                    "<div id=mediaItem".$newsID." class='col-lg-4 col-md-6 col-xs-12 well ".$visItems['css']."'>
                    <div class='titleSizer'><strong>".$row->title."</strong></div>
                    ".$this->mediaDisplay($row)."
                    <br>
                    ".$this->meatyContent($row, count($myMedia), 'dashboard', 'admin', $limitPerRows, $maxPerRow, $primary_key, $editFn, FALSE)."
                    ".$this->textualContent($row)."
                    <div>".$vis."</div>
                    ".$this->whereShown($row->forSection, $row->exclusiveSection)."
                    <div>Created: ".date("M jS, Y",strtotime($row->created))."</div>
                    <br>
                    ".$this->vintageFlag($row)."
                    ".$this->modifiedCreation($row, TRUE)."
                    <div>".anchor('admin/dashboard/'.$editFn.'/'.$newsID,"<span class='glyphicon glyphicon-cog'></span><strong>Edit</strong>")."</div>
                </div>";
                         
               }
               //Handle pagination when multiple items are limited and it exceeds the limit
               $export=$this->generalPagination($export, $maxPerRow, $limitPerRows, $pageOffset, $databaseType);
          }
          return $export;
     }
     
     public function profileItemsAdmin($myMedia, $items, $primary_key, $editFn, $maxPerRow=0, $limitPerRows=1, $pageOffset=0, $databaseType='all' ){
          //myMedia is the raw data coming back from the model
          //Items is just a string of what these things are
          //editFn leads over the the editing function needed on the dashboard since this is written generically
          //maxRows is the total number of items 
          //limitRows is the amount we want displayed at once
          //pageOffset sets how many multiples of limitRows down we are looking 
          $export="<strong>No ".$items." to display right now. Check back soon!</strong>";
          if(count($myMedia)){
               $export="";
               //Loop through the data and make a new row for each
               foreach ($myMedia as $row) {
                    $profileID=$row->$primary_key;
                    //Create the block item based on info gathered and add to return value
                    $export.=
                    "<div id=profile".$profileID." class='col-xs-12 well'>
                    <div class='titleSizer'><strong>".$row->profileName." the ".$row->title."</strong></div>
                    Using following as avatar: ".$this->mediaDisplay($row)."
                    <br>
                    <div class='row'>
                         <div class='col-xs-3'>
                         ".$this->meatyContent($row, count($myMedia), 'dashboard', 'admin', $limitPerRows, $maxPerRow, $primary_key, $editFn, FALSE)."
                         </div>
                         <div class='col-xs-9'>
                         ".$this->textualContent($row, false)."
                         </div>
                    </div>
                    <br>
                    ".$this->whereShown($row->forSection, $row->exclusiveSection)."
                    <div>Created: ".date("M jS, Y",strtotime($row->created))."</div>
                    <br>
                    ".$this->modifiedCreation($row, TRUE)."
                    <div>".anchor('admin/dashboard/'.$editFn.'/'.$profileID,"<span class='glyphicon glyphicon-cog'></span><strong>Edit</strong>")."</div>
                </div>";
                         
               }
               //Handle pagination when multiple items are limited and it exceeds the limit
               $export=$this->generalPagination($export, $maxPerRow, $limitPerRows, $pageOffset, $databaseType);
          }
          return $export;
     }
     
     public function simpleAvatars($myMedia, $items, $primary_key, $editFn, $maxPerRow=0, $limitPerRows=1, $pageOffset=0, $databaseType='all'){
          //myMedia is the raw data coming back from the model
          //Items is just a string of what these things are
          //editFn leads over the the editing function needed on the dashboard since this is written generically
          //maxRows is the total number of items 
          //limitRows is the amount we want displayed at once
          //pageOffset sets how many multiples of limitRows down we are looking 
          $export="<strong>No ".$items." to display that you have editing rights on.</strong>";
          if(count($myMedia)){
               $export="";
               //Loop through the data and make a new row for each
               foreach ($myMedia as $row) {
                    $avatarID=$row->$primary_key;
                    $visItems=$this->visFlag($row);
                    $vis=$visItems['text'];
                    //check if exclusive to logged members
                    $vis.=$this->loggedFlag($row);
                    //Create the block item based on info gathered and add to return value
                    $export.=
                    "<div id=mediaItem".$avatarID." class='col-lg-4 col-md-6 col-xs-12 well itemVis'>
                    <div class='titleSizer'><strong>".$avatarID."</strong></div>
                    ".$this->mediaDisplay($row)."
                    <br>
                    ".$this->meatyContent($row, count($myMedia), 'dashboard', 'admin', $limitPerRows, $maxPerRow, $primary_key, $editFn, FALSE)."
                    <br>
                    ".$this->whereShown($row->forSection, $row->exclusiveSection)."
                    <div>Created: ".date("M jS, Y",strtotime($row->created))."</div>
                    ".$this->modifiedCreation($row, TRUE)."
                    <div>".anchor('admin/dashboard/'.$editFn.'/'.$avatarID,"<span class='glyphicon glyphicon-cog'></span><strong>Edit</strong>")."</div>
                </div>";
                         
               }
               //Handle pagination when multiple items are limited and it exceeds the limit
               $export=$this->generalPagination($export, $maxPerRow, $limitPerRows, $pageOffset, $databaseType);
          }
          return $export;
     }
     
     public function socialItemsAdmin(){
          return "FUNCTION NOT YET MADE";
     }
     
     
//-----------------------------------------------------------
//Admin only functions
//-----------------------------------------------------------     
     public function getSectionLogs($logs){
          if(count($logs)){
               $logOutput='<div><h4>Recent activity:</h4><br><ul>';
               foreach ($logs as $row) {
                    $logOutput.='<li>'.$row->change.'</li>';     
               }
               $logOutput.='</ul></div>';
               return $logOutput;
          }
          else{
               return "<div><h4>Recent Section Activity:</h4><br>No recent section activity to report.</div>";
          }
     }
     public function getWhoAssigned($assignments){
          $delegation="<thead><td>Section</td><td>User</td><td>Email</td><td>Remove User?</td></thead><tbody>";
          if(count($assignments)){
               foreach($assignments as $area){
                    $delegation.="<tr><td>".$area->sub_name."</td>
                    <td>".$area->name."</td>
                    <td>".$area->email."</td>
                    <td>".anchor('admin/systems/tools/removeuser/'.$area->subauth_id,"<span class='glyphicon glyphicon-remove'></span>")."</td>
                    </tr>";
               }
               return "<table class='table table-striped'>".$delegation."</tbody></table>";
          }
          return "<ul><li>You havent granted permissions</li></ul>";
     }
     public function getMyRoles($sections){
          if(count($sections)){
               $logOutput='<div><h4>Assigned to:</h4><br><ul>';
               foreach ($sections as $row) {
                    $logOutput.='<li>'.$row->sub_name.'</li>';   
               }
               $logOutput.='</ul></div>';
               return $logOutput;
          }
          else{
               return "<div><h4>Assigned to:</h4><br>You are not in any special sections.</div>";
          }
     }
     public function getSectionControlled($controlled){
          if(count($controlled)){
               $logOutput="<thead><td>Section</td><td>URL suffix</td><td>Created by</td><td>Email</td><td>Delete?</td></thead><tbody>";
               foreach ($controlled as $row) {
                    $logOutput.='<tr><td>'.$row->sub_name.'</td>
                         <td>'.$row->sub_dir.'</td>
                         <td> '.$row->name. ' </td>
                         <td>'.$row->email.'</td>
                         <td>'.anchor('admin/systems/tools/removesection/'.$row->subsite_id,"<span class='glyphicon glyphicon-remove'></span>").'</td>
                         </tr>';   
               }
               return '<table class="table table-striped">'.$logOutput.'<tbody></table>';
          }
          else{
               return "<li><ul>You dont control any sections.</li></ul>";
          }
          
          
     }

}