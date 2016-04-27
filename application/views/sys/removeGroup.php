<div class="col-md-8 col-md-offset-1">
     <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
          <div class="panel panel-default">
          <div class="panel-heading" role="tab">
               <h4 class="panel-title">
                    <strong class="green"><span class='glyphicon glyphicon-wrench'></span>  Remove Group</strong>
               </h4>
          </div>
               <div class="panel-body">
                     <div class="row">
                         <div class="col-xs-2"><strong>Group Name </strong></div>
                         <div class="col-xs-10 col-md-4"><?php echo $groupName;?></div>
                         <div class="col-xs-2"><strong>Group ID</strong></div>
                         <div class="col-xs-10 col-md-4"><input type="text" id="assocID" disabled="disabled" value="<?php echo $assocID;?>" ></div>
                    </div><br>
                    <div class="row">
                         <div class="col-xs-2"><strong>Group URL suffix </strong></div>
                         <div class="col-xs-10 col-md-4"><?php echo $groupURL; ?></div>
                         <div class="col-xs-2"><strong>Created on</strong></div>
                         <div class="col-xs-10 col-md-4"><?php echo $creationDate; ?></div>
                    </div><br>
                    <div class="row">
                         <div class="col-xs-2"><strong>Created by</strong></div>
                         <div class="col-xs-10 col-md-4"><?php echo $creator; ?></div>
                         <div class="col-xs-2"><strong>Email</strong></div>
                         <div class="col-xs-10 col-md-4"><?php echo $creatorEmail; ?></div>
                    </div><br>
                    <div class="row">
                         <div class="col-xs-12"><strong>Usage</strong></div>
                         <br>
                         <div class="col-xs-12"><?php echo $groupUsage;?></div>
                    </div><br>
                    <div class="row">
                         <div class="col-xs-12"><strong>This will affect the following users:</strong></div>
                         <div class="col-xs-12"><?php echo $sectionAccess?></div>
                    </div><br>
                    <button id="cancelDelete" class="btn btn-success">Return to Tools</button>
                    <button class="btn btn-danger" type="button" data-toggle="collapse" data-target="#deleteAssoc" aria-expanded="false" aria-controls="deleteAssoc">
                    Delete Association
                    </button>
                    <div class="collapse" id="deleteAssoc">
                         <div class="well">
                         <div>This will remove <strong>ALL</strong> users from <strong><?php echo $groupName;?></strong> and then destroy <strong><?php echo $groupName;?></strong> group. </div> 
                         <div><strong>Are you sure?</strong></div><br> 
                         <button class="btn btn-danger" type="button" data-toggle="collapse" data-target="#deleteAssoc" aria-expanded="false" aria-controls="deleteAssoc">No, Cancel</button>
                         <button class="btn btn-success" type="button" id='permDelete'>Yes, Delete Media</button>
                         </div>
                    </div>
               </div>
          </div>         
     </div>
</div>
          