<?
class Adminprep_model extends CI_Model{
    	
	
	function __construct(){
		parent::__construct();
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