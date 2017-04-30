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
     
     public function socialItemsAdmin($myMedia, $items, $primary_key, $editFn, $maxPerRow=0, $limitPerRows=1, $pageOffset=0, $databaseType='all'){
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
                    $itemID=$row->$primary_key;
                    $visItems=$this->visFlag($row);
                    $vis=$visItems['text'];
                    //check if exclusive to logged members
                    $vis.=$this->loggedFlag($row);
                    //Create the block item based on info gathered and add to return value
                    $export.=
                    "<div id=mediaItem".$itemID." class='col-lg-4 col-md-6 col-xs-12 well itemVis'>
                    <strong>For ".$this->itemFor($row)."</strong>
                    <br>
                    <br>
                    ".$this->socialBox($row)."
                    <br>
                    ".$this->whereShown($row->forSection, $row->exclusiveSection)."
                    <div>Created: ".date("M jS, Y",strtotime($row->created))."</div>
                    ".$this->modifiedCreation($row, TRUE)."
                    <div>".anchor('admin/dashboard/'.$editFn.'/'.$itemID,"<span class='glyphicon glyphicon-cog'></span><strong>Edit</strong>")."</div>
                </div>";
                         
               }
               //Handle pagination when multiple items are limited and it exceeds the limit
               $export=$this->generalPagination($export, $maxPerRow, $limitPerRows, $pageOffset, $databaseType);
          }
          return $export;
     }
     
     
//-----------------------------------------------------------
//Admin only functions
//-----------------------------------------------------------  
     public function itemFor($who){
          if($who->sub_dir!="self"){
               return $who->sub_name." (".$who->sub_dir.")";
          }
          else{
              return $who->name." (Self)"; 
          }
          
     }
     private function socialBox($row){
          $dataItems="";
          if($row->facebook_url){$dataItems.="<a href='{$row->facebook_url}' target='_blank'><span class='label label-success'>Facebook</span></a>";}
          else{$dataItems.="<span class='label label-danger'>Facebook</span>";}
          if($row->youtube_url){$dataItems.="<a href='{$row->youtube_url}' target='_blank'><span class='label label-success'>Youtube</span></a>";}
          else{$dataItems.="<span class='label label-danger'>Youtube</span>";}
          if($row->twitter_url){$dataItems.="<a href='{$row->twitter_url}' target='_blank'><span class='label label-success'>Twitter</span></a>";}
          else{$dataItems.="<span class='label label-danger'>Twitter</span>";}
          if($row->tumblr_url){$dataItems.="<a href='{$row->tumblr_url}' target='_blank'><span class='label label-success'>Tumblr</span></a>";}
          else{$dataItems.="<span class='label label-danger'>Tumblr</span>";}
          if($row->twitch){$dataItems.="<a href='{$row->twitch}' target='_blank'><span class='label label-success'>Twitch</span></a>";}
          else{$dataItems.="<span class='label label-danger'>Twitch</span>";}
          if($row->email){$dataItems.="<a href='mailto:{$row->email}'><span class='label label-success'>Email</span></a>";}
          else{$dataItems.="<span class='label label-danger'>Email</span>";}
          if($row->logoID){$dataItems.="<br><br><img class='img-responsive center-block' alt='Logo for {$row->sub_name}' src='".$row->fileLoc."'></img>";}
          else{$dataItems.="<br><br><img class='img-responsive center-block' alt='Logo for {$row->sub_name}' src='".$this->config->item('defaultImage')."'></img>";}
          
          return $dataItems;
     }


   
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
               $logOutput="<thead><td>Section</td><td>URL suffix</td><td>Parent</td><td>Created by</td><td>Edit/Delete</td></thead><tbody>";
               foreach ($controlled as $row) {
               		if($row->visible==1){$vis="glyphicon-eye-open";}
					else{$vis="glyphicon-eye-close red";}
                    $logOutput.='<tr><td><span class="glyphicon '.$vis.'"></span>'.$row->sub_name.'</td>
                         <td>'.$row->sub_dir.'</td>
                         <td>'.$row->forSection.'</td>
                         <td> '.$row->name.'  ['.$row->email.'] </td>
                         <td>'
                         .anchor('admin/systems/tools/editsection/'.$row->subsite_id,"<span class='glyphicon glyphicon-wrench'>Edit</span>").' / '
                         .anchor('admin/systems/tools/removesection/'.$row->subsite_id,"<span class='glyphicon glyphicon-remove'>Del</span>")
                         .'</td>
                         </tr>';   
               }
               return '<table class="table table-striped">'.$logOutput.'<tbody></table>';
          }
          else{
               return "<li><ul>You dont control any sections.</li></ul>";
          }
          
          
     }
     public function getSectionVis($controlled){
          if(count($controlled)){
               $logOutput="<thead><td>Section</td><td>URL suffix</td><td>Parent</td><td>Created by</td><td>Visibility</td></thead><tbody>";
               foreach ($controlled as $row) {
                    //Current visibility
                    if($row->min_role==$this->config->item('baseUser')){$vis="glyphicon-pawn";}
                    elseif($row->min_role<=$this->config->item('normUser')){$vis="glyphicon-knight";}
                    elseif($row->min_role<=$this->config->item('contributor')){$vis="glyphicon-lock";}
                    else{$vis="glyphicon-flag red";}
                    //Beta exclusivity
                    if($row->sub_url=="beta"){$visActions="<strong class='red'>Cannot alter</strong>";}
                    else{
                         $visActions="<button class='normVis visAdj btn btn-xs btn-success' data-id='".$row->id."'><span class='glyphicon glyphicon-pawn'>Norm</span></button> / ".
                         "<button class='logVis visAdj btn btn-xs btn-warning' data-id='".$row->id."'><span class='glyphicon glyphicon-knight'>Logged</span></button> / ".
                         "<button class='lockVis visAdj btn btn-xs btn-danger' data-id='".$row->id."'><span class='glyphicon glyphicon-lock'>Lock</span></button>";}
                    //row output
                    $logOutput.='<tr><td><span class="glyphicon '.$vis.'"></span> '.$row->sub_name.' </td>
                         <td>'.$row->sub_url.'</td>
                         <td>'.$row->forSection.'</td>
                         <td> '.$row->name.' </td>
                         <td>'
                         .$visActions
                         .'</td>
                         </tr>';   
               }
               return '<table class="table table-striped">'.$logOutput.'<tbody></table>';
          }
          else{
               return "<li><ul>You dont control any sections.</li></ul>";
          }
          
          
     }
}