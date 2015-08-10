			
<div class="col-md-8  col-md-offset-1">
	<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
	  	<div class="panel panel-default">
	    	<div class="panel-heading" role="tab">
	      		<h4 class="panel-title">
          			<strong class="green"><span class='glyphicon glyphicon-wrench'></span>  Edit Contact</strong>
	      		</h4>
	    	</div>
	      		<div class="panel-body">
	      			<div class="panel-body">
	      				<div class="row">
		      				<div class="col-xs-2"><strong>Static ID</strong></div>
				      		<div class="col-xs-10 col-md-4"><input type="text" id="newsID" disabled="disabled" value="<?php echo $staticID;?>" ></div>
		      			</div><br>
				      	<div class="row">
				      		<div class="col-xs-2">Title</div>
				      		<div class="col-xs-10 col-md-4"><input type="text" id="articleTitle"  value="<?php echo $staticTitle;?>"></div>
				      	</div>
						<br>
						<h3>Contact Info</h3>
						<div class="alert alert-danger noshow" id="textArea-alert">
							<button type="button" class="close" data-dismiss="alert">x</button>
							<strong id="userMessage">You need to add Contact Info </strong>
						</div>
				      	<textarea name="MCEarea" class='cleanMe' cols="40" rows="10" id="mceNewsArea" style="width: 100%; resize: vertical; overflow-y: scroll; "><?php echo $staticBody;?></textarea>
				       	<br>
				       	
	      				<button id="saveEdits" class="btn btn-success">Save Edits</button>
			       		<button class="btn btn-danger" type="button" data-toggle="collapse" data-target="#deleteMedia" aria-expanded="false" aria-controls="deleteMedia">Delete Contact item</button>
						<div class="collapse" id="deleteMedia">
	  						<div class="well">
	    						<div>This will permanently remove the contact item from the database!</div> 
								<div><strong>This cannot be undone!</strong></div>
	    						<br>
	    						<div>Are you sure?</div><br> 
	    						<button class="btn btn-danger" type="button" data-toggle="collapse" data-target="#deleteMedia" aria-expanded="false" aria-controls="deleteMedia">No, Cancel</button>
	    						<button class="btn btn-success" type="button" id='permDelete'>Yes, Delete</button>
	  						</div>
						</div>
			      	</div>
	      		</div>
	  	</div> 		
  	</div>
</div>
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				