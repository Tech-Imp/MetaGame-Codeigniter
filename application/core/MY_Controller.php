<?php
class MY_Controller extends CI_Controller{
		
	//public $data=array();
	function __construct(){
		parent::__construct();
		$data['errors']=array();
		// $data['site_name']=config_item('site_name');
	}
	protected function dropdownOptions($selected, $arrayOpts){
		if(count($arrayOpts)==1){
		 	return "<option selected value='".$selected."'> ".$arrayOpts." </option>";	
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
}
