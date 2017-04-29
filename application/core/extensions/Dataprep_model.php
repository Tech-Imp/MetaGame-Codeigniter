<?
class Dataprep_model extends CI_Model{
    	
	
	function __construct(){
		parent::__construct();
	}

     public function linkGen($data){
          $output="";
          if(count($data)){
               foreach($data as $link){
                  $inner="";
                  if($link->fileLoc){
                       $inner="<img class='img-responsive img-rounded' src=".$link->fileLoc." alt=".$link->sub_name.">";
                  }
                  else{
                       $inner="<div class='imageLink'>";
                       $inner.="<img class='img-responsive img-rounded' src=".$this->config->item('defaultImage')." alt=".$link->sub_name.">";
                       $inner.="<div class='linkText'>".$link->sub_name."</div>";
                       $inner.="</div>";
                  }
                  $output.='<br><a title="'.strip_tags($link->usage).'" href='.base_url().$link->sub_dir.' target="">'.$inner.'</a>';  
               }
          }
          // print_r($data);
          return $output;
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

	public function profileItems($myMedia, $items, $primary_key, $editFn, $maxPerRow=0, $limitPerRows=1, $pageOffset=0, $databaseType='all' ){
		//myMedia is the raw data coming back from the model
		//Items is just a string of what these things are
		//editFn leads over the the editing function needed on the dashboard since this is written generically
		//maxRows is the total number of items 
		//limitRows is the amount we want displayed at once
		//pageOffset sets how many multiples of limitRows down we are looking 
		$area=$this->uri->segment(1, $this->config->item('mainPage'));
		$export="<strong>No ".$items." to display right now. Check back soon!</strong>";
		if(count($myMedia)){
			$export="";
			//Loop through the data and make a new row for each
			foreach ($myMedia as $row) {
				$profileID=$row->$primary_key;
				//Create the block item based on info gathered and add to return value
				$export.=
				"<div id=profile".$profileID." class='col-xs-12 metaBorder'>
                    <h2 class='row titleSizer'><strong>".$row->profileName." <h3 style='display:inline;'> the ".$row->title."</h3></strong></h2>
                    <div class='row'>
	                    <div class='col-xs-3'>
	                    ".$this->meatyContent($row, count($myMedia), 'dashboard', 'admin', $limitPerRows, $maxPerRow, $primary_key, $editFn, FALSE)."
	                    </div>
	                    <div class='col-xs-9'>
	                    ".$this->textualContent($row, false)."
	                    </div>
                    </div>
                    <br>
                    <br>
                    ".$this->modifiedCreation($row)."
                    <div>"/*.$this->generatePermalink($profileID, $items, $area, $editFn, NULL, $limitPerRows, $maxPerRow, count($myMedia))*/."</div>
                </div>";
                         
			}
			//Handle pagination when multiple items are limited and it exceeds the limit
			$export=$this->generalPagination($export, $maxPerRow, $limitPerRows, $pageOffset, $databaseType);
		}
		return $export;
	}
	
	
	protected function gatherGenericItems($myMedia, $items, $primary_key, $myLink, $perRow, $maxPerRow, $redirect=NULL){
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
                    <h5 class='author'>Posted by:  ".$row->name."</h5>
                    ".$this->meatyContent($row, count($myMedia), $myLink, $area, $perRow, $maxPerRow, $primary_key)."
                    <br>
                    ".$this->textualContent($row, FALSE, count($myMedia), $perRow)."
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
		//If there are explicit static captions that arent in the database
		if ($captions===TRUE) {
			foreach ($data as $item) {
				array_push($text, $item->title);	
			}
		}
		elseif ($captions!==NULL && count($data)==count($captions)) {
			foreach ($captions as $item) {
				array_push($text, $item);	
			}
		}
		else {
			foreach ($data as $item) {
				array_push($text, '');	
			}
		}
		
		$images=$header="";	
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
		$area=$this->uri->segment(1, $this->config->item('mainPage'));
		foreach ($data as $row) {
			// Set the active pane	
			if($start==0){$active='active';}
			else{$active='';}
			// Sync the captions to the images in the event the logo was used
			if($siteLogo==TRUE){$capText=$text[$start-1];}
			else{$capText=$text[$start];}
			//Append the image setup and increment the counter
			$images.='<div class="item '.$active.'">
			      '.$this->simpleContent($row, $this->senseMediaController($row->mediaType), $area, "media_id").'
			      <div class="carousel-caption">
			       <strong>'.$capText.'</strong> 
			      </div>
			    </div>';
			$header.='<li data-target="#carousel-images" data-slide-to="'.$start.'" class="'.$active.'"></li>';
			++$start;
		} 
		
		if (count($data)){
			//if there were images created, display them, otherwise show message
			$export='
			<div id="carousel-images" class="carousel slide" data-ride="carousel">
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
	protected function checkEmbedDisplay($embed, $redirectPage="", $redirectID=""){
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
          elseif (strpos($embed, "soundcloud.com/player") !==FALSE) {
              $img='<img class="img-responsive center-block youtube-thumb" src="'.base_url().'assets/image/soundcloud-Logo.png">';
               return "<div class='soundcloud-item' >
                    ".anchor($redirectPage.'/'.$redirectID, $img)."
               </div>
               ";
          }
		else{
			return $embed;
		}
	}
	//------------------------------------------------------------------------------------------------
	protected function modifiedCreation($row, $onlyMod=false){
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
	protected function vintageFlag($row){
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
	protected function loggedFlag($row){
		$vis="";
		if(array_key_exists('loggedOnly', $row) && $row->loggedOnly !== ""){
			$vis.="<br>";
			if ($row->loggedOnly==1){$vis.="   <span class='glyphicon glyphicon-lock'></span><strong> to Logged Only</strong>"; }
			else{$vis.="  <span class='glyphicon glyphicon-globe'></span><strong> to Everyone</strong>";}
		}
		return $vis;
	}
	//------------------------------------------------------------------------------------------------
	protected function visFlag($row){
		$currDate=new DateTime("now");
		if(array_key_exists('visibleWhen', $row)){
			$storedDate=new DateTime($row->visibleWhen);
		}
		// Visibility of item logic alters coloring and wording
		if(array_key_exists('visibleWhen', $row) && $row->visibleWhen === "0000-00-00 00:00:00"){
			$artVis="itemHidden";
			$vis="Currently <span class='glyphicon glyphicon-eye-close'></span><strong>HIDDEN</strong>";
               //Special case for Avatars and Logos. Always want to be shown as visible
               if(array_key_exists("mediaType", $row) && ($row->mediaType=="avatar" || $row->mediaType=="logo")){
                    $artVis="itemVis";
                    $vis="Currently <span class='glyphicon glyphicon-eye-open'></span><strong>VISIBLE</strong>";
               }
		}
		elseif (array_key_exists('visibleWhen', $row) && $currDate < $storedDate) {
		     $artVis="itemTemp";
			$vis="<span class='glyphicon glyphicon-exclamation-sign'></span> Visible on ".date("M jS, Y",strtotime($row->visibleWhen));
			//Special case for Avatars and Logos. Always want to be shown as visible
			if(array_key_exists("mediaType", $row) && ($row->mediaType=="avatar" || $row->mediaType=="logo")){
			     $artVis="itemVis";
                    $vis="Currently <span class='glyphicon glyphicon-eye-open'></span><strong>VISIBLE</strong>";
			}
		}
		else {
			$artVis="itemVis";
			$vis="Currently <span class='glyphicon glyphicon-eye-open'></span><strong>VISIBLE</strong>";
		}	
		return array('css'=>$artVis, 'text'=>$vis);
	}
	//----------------------------------------------------------------------------------------------------
	protected function textualContent($row, $adminView=TRUE, $currentCount=1, $perRow=1){
		$content="";
		// Setup styling on a component level so it doesnt need to be bothered
		// Body set up so it displays only when in single item or when its set like a news feed
		if($adminView){
			$styling='class="shortText"';
		}
		else{
			$styling='class="expandedText"';
		}
		
		if(array_key_exists('stub', $row) && $adminView){
			$content="<div><textarea disabled='disabled' {$styling}>".$row->stub."</textarea></div><br>";
		}
		elseif(array_key_exists('body', $row) && $row->body !== "" && ($currentCount==1 || $perRow==1)){
			$content="<div {$styling}>".$row->body."</div><br>";
		}
		else{
			$content="";
		}
		return $content;
	}
	//------------------------------------------------------------------------------------------------
	protected function meatyContent($row, $overallCount, $myLink, $area, $perRow, $maxPerRow, $primary_key, $ctrlFunc='index', $showText=TRUE){
		$media="";
		
		if(array_key_exists('fileLoc', $row) && $row->fileLoc !== ""){
			//TODO Need to verify file exists
			
			if($perRow==1){
				$media="<div>";
			}
			else {
				$media="<div class='embed-responsive embed-responsive-16by9'>"; //Forces image to fit a confined form factor
			}
			$media.=$this->simpleContent($row, $myLink, $area, $primary_key)."</div>";
		}
		elseif (array_key_exists('embed', $row) && $row->embed !== "") {
			// Determine if video is alone on page, and if so just show it. Otherwise, thumbnail. 
			// Youtube and Soundcloud special cases due to interactions with bootstrap
			$cssSizeAdjust="";
			if($perRow==1 && $overallCount==1 && $maxPerRow==0){
				$embedItem=$row->embed;
                    if(strpos($row->embed, "soundcloud.com/player") !==FALSE){
                         $cssSizeAdjust="embedThin";   
                    }
			}
			else{
				$embedItem=$this->simpleContent($row, $myLink, $area, $primary_key);
                    if(strpos($row->embed, "youtube.com/embed/") !==FALSE){
                         $cssSizeAdjust="embedBigger";   
                    }
			}
			$media="<div class='embed-responsive embed-responsive-16by9 ".$cssSizeAdjust."'>"
			.$embedItem."
			</div>";	
		}
		
		return $media;
	}
     //------------------------------------------------------------------------------------------------------------------------------------------------
	protected function simpleContent($row, $myLink, $area, $primary_key, $ctrlFunc='index', $showText=TRUE){
		if(array_key_exists('fileLoc', $row) && $row->fileLoc !== ""){
			return "<img class='img-responsive center-block' alt='{$row->title}' src='".$row->fileLoc."'></img>";
		}
		elseif (array_key_exists('embed', $row) && $row->embed !== "") {
			// Determine if video is alone on page, and if so just show it. Otherwise, thumbnail
			return $this->checkEmbedDisplay($row->embed, $area.'/'.$myLink.'/'.$ctrlFunc, $row->$primary_key);
		}
		
		return "";
	
	}
	//--------------------------------------------------------------------------------------------------------------
	protected function generateRows($newsID, $perRow, $rowCount){
		$export="";	
		if ($perRow==1){
			$export.="<div id=mediaItem".$newsID." class='metaBorder' >";
		}
		else {
			//Manages the whole row nesting. Only triggered when a value is passed.
			if($rowCount%$perRow ==0 || $rowCount==0){
				$export.="<div class='row well'>";
			}
			if(12%$perRow ==0 and $perRow<=12 and $perRow>0){
				$adjusted=12/$perRow;
				$export.="<div id=mediaItem".$newsID." class='col-md-".$adjusted." col-xs-12'>";
			}
			//Catchall case in case of coding fault AKA item is not within 1 to 12 and a clean mod of 12
			else {
				$export.="<div id=mediaItem".$newsID." class='col-xs-12'>";
			}
		}
		return $export;
	}
	//---------------------------------------------------------------------------------------------------------------------
	protected function generatePermalink($newsID, $items, $area,  $myLink, $redirect, $perRow, $maxPerRow, $overallCount){
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
	protected function endRow($perRow, $rowCount, $overallCount){
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
	protected function generalPagination($existing=NULL, $maxPerRow, $limitPerRows, $pageOffset, $databaseType=NULL){
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
	protected function mediaDisplay($row){
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
				case 'avatar':
					$mediaClass="<div><span class='glyphicon glyphicon-user'></span><strong> Avatar </strong></div>";
					break;
                    case 'logo':
                         $mediaClass="<div><span class='glyphicon glyphicon-star-empty'></span><strong> Logo </strong></div>";
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
	//-------------------------------------------------------------------------------------------------------------------------------
	protected function whereShown($section, $exclusive, $default="main"){
		$where="";
		//Only in the exclusive case
		if(!empty($section) and $exclusive){
			$where="EXCLUSIVE to <strong>".$section."</strong>";
		}
		elseif(!empty($section)){
			$where="Appears in <strong>".$default."</strong> and <strong>".$section."</strong>";
		}
		else{
			$where="Appears only in <strong>".$default."</strong>";
		}
		return $where;
	}
	//-------------------------------------------------------------------------------------------------------------------------------
	protected function senseMediaController($type=NULL){
		switch ($type) {
			case 'picture':
				return "photo";
				break;
			case 'video':
				return "video";
				break;
			case 'sound':
				return "audio";
				break;
			default:
				return "";
				break;
		}
	}

}