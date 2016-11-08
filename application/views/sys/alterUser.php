
			
<div class="col-md-8 col-md-offset-1">
	<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
          <div class="panel panel-default">
          <div class="panel-heading" role="tab">
               <h4 class="panel-title">
                    <strong class="green"><span class='glyphicon glyphicon-wrench'></span>  Alter User Role </strong>
               </h4>
          </div>
               <div class="panel-body">
                    <div class="alert alert-danger noshow" id="textArea-alert">
                    <button type="button" class="close" data-dismiss="alert">x</button>
                    <strong id="userMessage">Unable to comply </strong>
                    </div>
                     <div class="row">
                         <div class="col-xs-2"><strong>User </strong></div>
                         <div class="col-xs-10 col-md-4"><?php echo $userName;?></div>
                         <div class="col-xs-2"><strong>ID</strong></div>
                         <div class="col-xs-10 col-md-4"><input type="text" id="assocID" disabled="disabled" value="<?php echo $uid;?>" ></div>
                    </div><br>
                 	<div class="row">
                         <div class="col-xs-2"><strong>Current Role</strong></div>
                         <div class="col-xs-10"><?php echo $role;?></div>
                    </div>
                    <div class="row">
                         <div class="col-xs-2"><strong>Email</strong></div>
                         <div class="col-xs-10"><?php echo $userEmail;?></div>
                    </div><br>
                    <div class="row">
                         <div class="col-xs-2"><strong>Comments</strong></div>
                         <div class="col-xs-10"><?php echo $comments;?></div>
                    </div><br>
                    <?php echo $actions;?>
                    
               </div>
          </div>         
     </div>
</div>
		