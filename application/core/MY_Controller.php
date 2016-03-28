<?php
class MY_Controller extends CI_Controller{
		
	//public $data=array();
	function __construct(){
		parent::__construct();
		$data['errors']=array();
		// $data['site_name']=config_item('site_name');
	}
	protected function dropdownOptions($selected=NULL, $arrayOpts, $arrayVals=NULL){
		if(count($arrayOpts)==1 && $selected!=NULL){
		 	return "<option selected value='".$selected."'> ".$arrayOpts." </option>";	
		}
		elseif (count($arrayOpts)==count($arrayVals)) {
			$options="";
			$arrCounter=0;
			foreach($arrayOpts as $item){
				if ($selected==$arrayVals[$arrCounter]){
					$options.="<option selected value='".$arrayVals[$arrCounter]."'> ".strtoupper($item)."</option>";
				}
				else{
					$options.="<option value='".$arrayVals[$arrCounter]."'> ".strtoupper($item)."</option>";
				}
				$arrCounter++;
			}
			return $options;
			
		}
		else{
			$options="";
			foreach($arrayOpts as $item){
				if ($selected==$item){
					$options.="<option selected value='".$item."'> ".strtoupper($item)."</option>";
				}
				else{
					$options.="<option value='".$item."'> ".strtoupper($item)."</option>";
				}
			}
			return $options;
		}
	}
	protected function simplePurify($input){
		return htmlspecialchars(strip_tags($input));
	}
}
