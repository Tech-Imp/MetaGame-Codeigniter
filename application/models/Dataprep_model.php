<?
class Dataprep_model extends CI_Model{
    	
	
	function __construct(){
		parent::__construct();
	}

//------------------------------------------------------------------------------------------
//Used to prepare items for the front end
//-------------------------------------------------------------------------------------------
	public function gatherItems($myMedia, $items=NULL, $primary_key, $myLink=NULL, $perRow=1, $maxPerRow=0, $limitPerRows=1, $pageOffset=0, $databaseType='primary' ){
		$existing = $this->gatherGenericItems($myMedia, $items, $primary_key, $myLink, $perRow, $maxPerRow );
		return $this->generalPagination($existing, $maxPerRow, $limitPerRows, $pageOffset, $databaseType);
	}

	public function gatherItemsRedirect($myMedia, $items=NULL, $primary_key, $myLink=NULL, $perRow=1, $maxPerRow=0, $limitPerRows=1, $pageOffset=0, $databaseType='primary', $redirect=NULL){
		$existing = $this->gatherGenericItems($myMedia, $items, $primary_key, $myLink, $perRow, $maxPerRow, $redirect);
		return $this->generalPagination($existing, $maxPerRow, $limitPerRows, $pageOffset, $databaseType);
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
                    ".$this->mediaDisplay($row)."
                    <br>
                    ".$this->meatyContent($row, count($myMedia), 'dashboard', 'admin', $limitPerRows, $maxPerRow, $primary_key, $editFn, FALSE)."
                    ".$this->textualContent($row)."
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
	
	
	private function gatherGenericItems($myMedia, $items, $primary_key, $myLink, $perRow, $maxPerRow, $redirect=NULL){
		//myMedia is the raw data coming back from the model
		//Items is just a string of what these things are
		//primary_key is the name of the primary key, used to retrieve values
		//myLink is used to determine which page/controller to redirect to
		//perRow is the number of items visible per row for fluid row purposes
		//maxRows and limitRows allow limits to be used and only show prev and next buttons when needed
		$export="<strong>No ".$items." to display right now. Check back soon!</strong>";
		if(count($myMedia)){
			$export="";
			//Loop through the data and make a new row for each
			$rowCount=0;
			if($redirect==NULL){
				$area=$this->uri->segment(1, $this->config->item('mainPage'));
			}
			else{
				$area=$redirect;
			}
			foreach ($myMedia as $row) {
				$newsID=$row->$primary_key;
				//Toss all the media stuff onto the growing string
                $export.="
                	".$this->generateRows($newsID, $perRow, $rowCount)."
                    <div class='titleSizer'><h4><strong>".$row->title."</strong></h4></div><br>
                    ".$this->meatyContent($row, count($myMedia), $myLink, $area, $perRow, $maxPerRow, $primary_key)."
                    <br>
                    ".$this->modifiedCreation($row)."
                    ".$this->generatePermalink($newsID, $items, $area, $myLink, $redirect, $perRow, $maxPerRow, count($myMedia)).
                    "</div>";
                // Close off the row tag for items that have it
                $rowCount++;
				$export.=$this->endRow($perRow, $rowCount, count($myMedia));
			}
		}
		return $export;
	}
//-----------------------------------------------------------------------------------------------------------	
//Takes in the data of pictures and optionally captions to create a photo carousel
//-------------------------------------------------------------------------------------------------------
	public function createCarousel($data, $siteLogo=FALSE, $captions=NULL){
		$text=array();
		if($captions!==NULL && count($data)==count($captions)){
			$start=0;
			foreach ($captions as $item) {
				array_push($text, $item);	
			}
		}
		else {
			foreach ($data as $item) {
				array_push($text, '');	
			}
		}
		$images="";	
		$header="";
		if($siteLogo==TRUE){
			$start=1;
			$images.='<div class="item active">
			      <img class="img-responsive center-block" src="'.base_url().'/assets/image/MG WIP.png'.'">
			      <div class="carousel-caption">
			      
			      </div>
			    </div>';
			$header.='<li data-target="#carousel-images" data-slide-to="0" class="active"></li>';
		}
		else{
			$start=0;
		}
		
		
		
		foreach ($data as $row) {
			if($start==0){
				$active='active';
			}
			else{
				$active='';
			}
			if($siteLogo==TRUE){
				$capText=$text[$start-1];
			}
			else{
				$capText=$text[$start];
			}
			$images.='<div class="item '.$active.'">
			      <img class="img-responsive center-block" src="'.$row->fileLoc.'">
			      <div class="carousel-caption">'
			       .$capText. 
			      '</div>
			    </div>';
			$header.='<li data-target="#carousel-images" data-slide-to="'.$start.'" class="'.$active.'"></li>';
			++$start;
		} 
		
		if (count($data)){
			// $export='<div id="carousel-images" class="carousel slide" data-ride="carousel">
			  // <!-- Indicators -->
			  // <ol class="carousel-indicators">
			    // '.$header.'
			  // </ol>
// 	
			  // <!-- Wrapper for slides -->
			  // <div class="carousel-inner" role="listbox">
			    // '.$images.'
			  // </div>
// 			
			  // <!-- Controls -->
			  // <a class="left carousel-control" href="#carousel-images" role="button" data-slide="prev">
			    // <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
			    // <span class="sr-only">Previous</span>
			  // </a>
			  // <a class="right carousel-control" href="#carousel-images" role="button" data-slide="next">
			    // <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
			    // <span class="sr-only">Next</span>
			  // </a>
			// </div>';
			$export='
			<div id="carousel-images" class="carousel slide" data-ride="carousel">
			<!-- Wrapper for slides -->
			  <div class="carousel-inner" role="listbox">
			    '.$images.'
			  </div>
			  </div>';
			
		}
		else{
			$export="<div>No photos to display. Check back soon!</div>";
		}
		return $export;
	}


//-----------------------------------------------------------------------------------------------------------------------------------------------------
//Common shared components
//---------------------------------------------------------------------------------------------------------------------------------------------------
	//-------------------------------------------------------------------------------------------------
	//This function will trigger when it finds a youtube embed was linked.
	//It will strip out the ID of the video so that an image can be used as a placeholder instead to increase optimization
	//of the page itself.
	//------------------------------------------------------------------------------------------------
	private function checkYoutube($embed, $redirectPage="", $redirectID=""){
		if(strpos($embed, "youtube.com/embed/") !==FALSE){
			$precheck=explode("youtube.com/embed/", $embed);
			$embedID=explode("\"", $precheck[1]);
			// return $embedID[0];
			$img='<img class="img-responsive center-block youtube-thumb" src="https://i.ytimg.com/vi/'.$embedID[0].'/hqdefault.jpg">';
			return "<div class='youtube-vid' data-vid=".$embedID[0].">
				".anchor($redirectPage.'/'.$redirectID, $img)."
			</div>
			";
		}
		else{
			return $embed;
		}
	}
	//------------------------------------------------------------------------------------------------
	private function modifiedCreation($row, $onlyMod=false){
		$modified="";
		$editted="Never";
		if(array_key_exists('modified', $row)){
			if($row->modified !== "0000-00-00 00:00:00"){
				if($onlyMod){$editted=$row->modified;}
				else{$modified="<div>Modified: ".date("M jS, Y",strtotime($row->modified))."</div>";}
			}
			if($onlyMod){$modified="<div>Modified: ".$editted."</div>";}
			else{$modified="<div>Created: ".date("M jS, Y",strtotime($row->created))."</div>";}
		}
		return $modified;
	}
	//------------------------------------------------------------------------------------------------
	private function vintageFlag($row){
		$vintage="";
		if(array_key_exists('vintage', $row)){
			if(intval($row->vintage) == 1){
				$vintage="<div><span class='glyphicon glyphicon-flag'></span><strong> PINNED </strong></div>";
			}
			else{
				$vintage="<div><span class='glyphicon glyphicon-fire'></span><strong> NEW! </strong></div>";
			}
		}
		return $vintage;
	}
	//------------------------------------------------------------------------------------------------
	private function loggedFlag($row){
		$vis="";
		if(array_key_exists('loggedOnly', $row) && $row->loggedOnly !== ""){
			$vis.="<br>";
			if ($row->loggedOnly==1){$vis.="   <span class='glyphicon glyphicon-lock'></span><strong> to Logged Only</strong>"; }
			else{$vis.="  <span class='glyphicon glyphicon-globe'></span><strong> to Everyone</strong>";}
		}
		return $vis;
	}
	//------------------------------------------------------------------------------------------------
	private function visFlag($row){
		$currDate=new DateTime("now");
		if(array_key_exists('visibleWhen', $row)){
			$storedDate=new DateTime($row->visibleWhen);
		}
		// Visibility of item logic alters coloring and wording
		if(array_key_exists('visibleWhen', $row) && $row->visibleWhen === "0000-00-00 00:00:00"){
			$artVis="itemHidden";
			$vis="Currently <span class='glyphicon glyphicon-eye-close'></span><strong>HIDDEN</strong>";
		}
		elseif (array_key_exists('visibleWhen', $row) && $currDate < $storedDate) {
			$artVis="itemTemp";
			$vis="<span class='glyphicon glyphicon-exclamation-sign'></span> Visible on ".date("M jS, Y",strtotime($row->visibleWhen));
		}
		else {
			$artVis="itemVis";
			$vis="Currently <span class='glyphicon glyphicon-eye-open'></span><strong>VISIBLE</strong>";
		}	
		return array('css'=>$artVis, 'text'=>$vis);
	}
	//----------------------------------------------------------------------------------------------------
	private function textualContent($row){
		$content="";
		if(array_key_exists('stub', $row)){
			$content="<div><textarea disabled='disabled' rows='4' style='width: 100%; resize: none; overflow-y: scroll;' >".$row->stub."</textarea></div><br>";
		}
		elseif(array_key_exists('body', $row)){
			$content="<div style='height:5em; width: 100%; resize: none; overflow-y: scroll;' >".$row->body."</div><br>";
		}
		else{
			$content="";
		}
		return $content;
	}
	//------------------------------------------------------------------------------------------------
	private function meatyContent($row, $overallCount, $myLink, $area, $perRow, $maxPerRow, $primary_key, $ctrlFunc='index', $showText=TRUE){
		$media="";
		if(array_key_exists('fileLoc', $row) && $row->fileLoc !== ""){
			//TODO Need to verify file exists
			if($perRow==1){
				$media="<div>";
			}
			else {
				$media="<div class='embed-responsive embed-responsive-16by9'>"; //Forces image to fit a confined form factor
			}
			$media.="<img class='img-responsive center-block' alt='{$row->title}' src='".$row->fileLoc."'></img>
			</div>";
		}
		elseif (array_key_exists('embed', $row) && $row->embed !== "") {
			// Determine if video is alone on page, and if so just show it. Otherwise, thumbnail
			if($perRow==1 && $overallCount==1 && $maxPerRow==0){
				$embedItem=$row->embed;
			}
			else{
				$embedItem=$this->checkYoutube($row->embed, $area.'/'.$myLink.'/'.$ctrlFunc, $row->$primary_key);
			}
			$media="<div class='embed-responsive embed-responsive-16by9'>"
			.$embedItem."
			</div>";	
		}
		elseif (array_key_exists('body', $row) && $row->body !== "" && $showText) {
			$media="<div>"
			.$row->body."
			</div>";
		}
		return $media;
	}
	//--------------------------------------------------------------------------------------------------------------
	private function generateRows($newsID, $perRow, $rowCount){
		$export="";	
		if ($perRow==1){
			$export.="<div id=mediaItem".$newsID." class='metaBorder' >";
		}
		else {
			//Manages the whole row nesting. Only triggered when a value is passed.
			if($rowCount%$perRow ==0 || $rowCount==0){
				$export.="<div class='row'>";
			}
			if(12%$perRow ==0 and $perRow<=12 and $perRow>0){
				$adjusted=12/$perRow;
				$export.="<div id=mediaItem".$newsID." class='col-md-".$adjusted." col-xs-12 well '>";
			}
			//Catchall case in case of coding fault AKA item is not within 1 to 12 and a clean mod of 12
			else {
				$export.="<div id=mediaItem".$newsID." class='col-xs-12 well '>";
			}
		}
		return $export;
	}
	//---------------------------------------------------------------------------------------------------------------------
	private function generatePermalink($newsID, $items, $area,  $myLink, $redirect, $perRow, $maxPerRow, $overallCount){
		$export="";
		if($items!==NULL && $myLink!==NULL){
			if($perRow==1 && $overallCount==1 && $maxPerRow==0){
				if($redirect==NULL){
					$export.="<div>".anchor($area.'/'.$myLink.'/',"<span class='glyphicon glyphicon-home'></span><strong>  Return to ".$items." list</strong>")."</div>";
				}
				else{
					$export.="<div>".anchor($area.'/'.$redirect.'/',"<span class='glyphicon glyphicon-home'></span><strong>  Return to ".$items." list</strong>")."</div>";
				}
			}
			else{
				$export.="<div>".anchor($area.'/'.$myLink.'/index/'.$newsID,"<span class='glyphicon glyphicon-search'></span><strong>  Get permanent link to ".$items." </strong>")."</div>";
			}
		}
		return $export;
	}
	//-----------------------------------------------------------------------------------------------------------------------
	private function endRow($perRow, $rowCount, $overallCount){
		$export="";
		if ($perRow!=1){
        	if(($rowCount%$perRow ==0 && $rowCount>1) || ($rowCount==$overallCount)){
				$export.="</div>";
			}
		}
		return $export;
	}
	//---------------------------------------------------------------------------------------------------
	//Generic version of pagination to be reused
	//---------------------------------------------------------------------------------------------------
	private function generalPagination($existing=NULL, $maxPerRow, $limitPerRows, $pageOffset, $databaseType=NULL){
		$export=$existing;
		if($maxPerRow>$limitPerRows && $databaseType!==NULL){
			$export.="<div class='row'><nav class='col-xs-12'>
				<ul class='pager'>";
			//Determine if previous is leading into null area and block
			if($pageOffset>0){
				$export.="<li class='previous'><a href='javascript:void(0);' data-loc='".$pageOffset."' data-type='".$databaseType."' class='prevPage'>Prev</a></li>";
			}
			else{
				$export.="<li class='disabled previous'><a href='javascript:void(0);' data-loc='".$pageOffset."' data-type='".$databaseType."' class='prevPage'>Prev</a></li>";
			}
			if(($pageOffset+1)*$limitPerRows<$maxPerRow){
				$export.="<li class='next'><a href='javascript:void(0);' data-loc='".$pageOffset."' data-type='".$databaseType."' class='nextPage'>Next</a></li>";
			}
			else{
				$export.="<li class='disabled next'><a href='javascript:void(0);' data-loc='".$pageOffset."' data-type='".$databaseType."' class='nextPage'>Next</a></li>";
			}
			$export.="</ul></nav></div>";
		}
		return $export;
	}
	//------------------------------------------------------------------------------------------------------------------------------
	private function mediaDisplay($row){
		//TODO May need to clean this to a more uniform pattern involving config
		$mediaClass="<div><span class='glyphicon glyphicon-ban-circle'></span><strong> UNKNOWN </strong></div>";
		if(array_key_exists('mediaType', $row)){
			switch ($row->mediaType) {
				case 'picture':
					$mediaClass="<div><span class='glyphicon glyphicon-picture'></span><strong> Photo </strong></div>";
					break;
				case 'video':
					$mediaClass="<div><span class='glyphicon glyphicon-facetime-video'></span><strong> Video </strong></div>";
					break;
				case 'sound':
					$mediaClass="<div><span class='glyphicon glyphicon-headphones'></span><strong> Audio </strong></div>";
					break;
				case 'profilePic':
					$mediaClass="<div><span class='glyphicon glyphicon-user'></span><strong> Avatar </strong></div>";
					break;
			}
		}
		elseif (array_key_exists('type', $row)) {
			switch ($row->type) {
				case 'news':
					$mediaClass="<div><span class='glyphicon glyphicon-send'></span><strong> News </strong></div>";
					break;
				case 'articles':
					$mediaClass="<div><span class='glyphicon glyphicon-list-alt'></span><strong> Articles </strong></div>";
					break;
			}
		}
		return $mediaClass;
	}
}