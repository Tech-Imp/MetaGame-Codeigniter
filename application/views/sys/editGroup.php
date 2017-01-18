<div class="col-md-8 col-md-offset-1">
     <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
          <div class="panel panel-default">
          <div class="panel-heading" role="tab">
               <h4 class="panel-title">
                    <strong class="green"><span class='glyphicon glyphicon-wrench'></span>  Edit Group</strong>
               </h4>
          </div>
               <div class="panel-body">
                    <div class="alert alert-danger noshow" id="textArea-alert">
                    <button type="button" class="close" data-dismiss="alert">x</button>
                    <strong id="userMessage">Unable to comply </strong>
                    </div>
                     <div class="row">
                         <div class="col-xs-12 col-sm-6 col-md-2"><strong>Group Name </strong></div>
                         <div class="col-xs-12 col-sm-6 col-md-4"><?php echo $groupName;?></div>
                         <div class="col-xs-12 col-sm-6 col-md-2"><strong>Group ID</strong></div>
                         <div class="col-xs-12 col-sm-6 col-md-4"><input type="text" id="assocID" disabled="disabled" value="<?php echo $assocID;?>" ></div>
                    </div><br>
                    <div class="row">
                         <div class="col-xs-12 col-sm-6 col-md-2"><strong>Group URL suffix </strong></div>
                         <div class="col-xs-12 col-sm-6 col-md-4"><?php echo $groupURL; ?></div>
                         <div class="col-xs-12 col-sm-6 col-md-2"><strong>Created on</strong></div>
                         <div class="col-xs-12 col-sm-6 col-md-4"><?php echo $creationDate; ?></div>
                    </div><br>
                    <div class="row">
                         <div class="col-xs-12 col-sm-6 col-md-2"><strong>Created by</strong></div>
                         <div class="col-xs-12 col-sm-6 col-md-4"><?php echo $creator; ?></div>
                         <div class="col-xs-12 col-sm-6 col-md-2"><strong>Email</strong></div>
                         <div class="col-xs-12 col-sm-6 col-md-4"><?php echo $creatorEmail; ?></div>
                    </div><br>
                    <div class="row">
                         <div class="col-xs-12 col-sm-6 col-md-2"><strong>Visible in Quicklinks?</strong></div>
                         <div class="col-xs-12 col-sm-6 col-md-4">
                         	<select id='linkVis'>
		      					<?php echo $linkVisibility?>
		      				</select>
	      				 </div>
                         <div class="col-xs-12 col-sm-6 col-md-2"><strong>Quicklinks Parent Page</strong></div>
                         <div class="col-xs-12 col-sm-6 col-md-4">
         				 	<select id='linkLoc'>
         				 		<option value="">MAIN</option>
		      					<?php echo $sectionList?>
		      				</select>
		      			</div>
                    </div><br>
					<h3>Usage (for section):</h3>
					<div class="alert alert-danger noshow" id="sectionArea-alert">
						<button type="button" class="close" data-dismiss="alert">x</button>
						<strong id="sectionMessage">You need to add Usage Info </strong>
					</div>
			      	<textarea name="MCEarea" class='cleanMe' cols="40" rows="10" id="sectionUsage" style="width: 100%; resize: vertical; overflow-y: scroll; "><?php echo $groupUsage;?></textarea>
			       	<br>
                    <button id="saveEdits" class="btn btn-success">Save Edits</button>
               </div>
          </div>         
     </div>
</div>
          