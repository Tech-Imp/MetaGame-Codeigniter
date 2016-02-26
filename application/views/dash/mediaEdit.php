			
<div class="col-md-8  col-md-offset-1">
	<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
	  	<div class="panel panel-default">
	    	<div class="panel-heading" role="tab" id="photoUploadArea">
	      		<h4 class="panel-title">
          			<strong class="green"><span class='glyphicon glyphicon-wrench'></span>  Edit Media</strong>
	      		</h4>
	    	</div>
	      		<div class="panel-body">
	      			<div class="row">
	      				<?php echo $mediaInfo;?>
	      				<div class="col-xs-2"><strong>MediaID</strong></div>
			      		<div class="col-xs-10 col-md-4"><input type="text" id="mediaID" disabled="disabled" value="<?php echo $mediaID;?>" ></div>
	      			</div><br>
	      			<div class="row">
			      		<div class="col-xs-2"><strong>Title</strong></div>
			      		<div class="col-xs-10 col-md-4"><input type="text" class='textReq' id="mediaTitle" value="<?php echo $mediaTitle;?>"></div>
			      		<div class="col-xs-2"><strong>Show when</strong></div>
			      		<div class="col-xs-10 col-md-4"><input type="text" id="mediaWhen" value="<?php echo $mediaWhen;?>" class='when'></div>
			      	</div>
			      	<div class="row">
		      			<div class="col-xs-12 col-md-6 col-md-offset-6">Leaving "Show when" blank will hide the article</div>
		      		</div>
			      	<br>
					<div class="row">
						<div class="col-xs-2"><strong>Stub</strong></div>
			      		<div class="col-xs-10 col-md-4"><input type="text" class='textReq' id="mediaStub" value="<?php echo $mediaStub;?>"></div>
						<div class="col-xs-2"><strong>Media type</strong></div>
			      		<div class="col-xs-10 col-md-4">
			      			<select id='mediaType'>
								<?php echo $mediaOptions;?>
							</select>
						</div>
					</div>
					<br>
					<div class="row">
						<div class="col-xs-2"><strong>Logged in Only?</strong></div>
			      		<div class="col-xs-10 col-md-4">
			      			<select id="mediaLogged">
			      				<option <?php echo $mediaPrivate;?> value='1'>Yes</option>
			      				<option <?php echo $mediaPublic;?> value='0'>No</option>
			      			</select>
		      			</div>
			      		<div class="col-xs-12 col-md-6">If yes, will only be visible to logged in members.</div>
					</div>
					<br>
					<div class="row">
						<div class="col-xs-2"><strong>Place In VAULT?</strong></div>
			      		<div class="col-xs-10 col-md-4">
			      			<select id="mediaVintage">
			      				<option <?php echo $mediaVintage;?> value='1'>Yes</option>
			      				<option <?php echo $mediaNormal;?> value='0'>No</option>
			      			</select>
		      			</div>
			      		<div class="col-xs-12 col-md-6">If yes, will be put in Vintage section.</div>
					</div>
					<?php echo $exclusive;?>
					<br>
	      				<?php echo $mediaItem;?>
	      			<br>
	      			<div>
	      				<span class='glyphicon glyphicon-link'></span>Direct Link: 
	      				<div><input type="text" value='<?php echo $hardLink;?>' readonly style="width: 100%"></div>
	      			</div>
	      			<br>
	      			<br>
	      			<div class="alert alert-danger noshow" id="textArea-alert">
						<button type="button" class="close" data-dismiss="alert">x</button>
						<strong id="userMessage">Edit saved to database</strong>
					</div>
	      			<button id="saveEdits" class="btn btn-success">Save Edits</button>
			       	<button class="btn btn-danger" type="button" data-toggle="collapse" data-target="#deleteMedia" aria-expanded="false" aria-controls="deleteMedia">
  					Delete media item
					</button>
					<div class="collapse" id="deleteMedia">
  						<div class="well">
    						<div>This will permanently remove the media item from the database and from storage!</div> 
							<div><strong>This cannot be undone!</strong></div>
    						<br>
    						<div>Are you sure?</div><br> 
    						<button class="btn btn-danger" type="button" data-toggle="collapse" data-target="#deleteMedia" aria-expanded="false" aria-controls="deleteMedia">No, Cancel</button>
    						<button class="btn btn-success" type="button" id='permDelete'>Yes, Delete Media</button>
  						</div>
					</div>
	      		</div>
	  	</div> 		
  	</div>
</div>
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				