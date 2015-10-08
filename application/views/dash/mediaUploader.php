			
<div class="col-md-8  col-md-offset-1">
	<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
	  	<div class="panel panel-default">
	    	<div class="panel-heading" role="tab" id="photoUploadArea">
	      		<h4 class="panel-title">
	        		<a data-toggle="collapse" data-parent="#accordion" href="#photoAccord" aria-expanded="true" aria-controls="photoAccord">
	          			<strong class="green"><span class='glyphicon glyphicon-picture'></span>  Add new Photo</strong>
	        		</a>
	      		</h4>
	    	</div>
	    	<div id="photoAccord" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="photoUploadArea">
	      		<div class="panel-body">
	      			<div class="row">
			      		<div class="col-xs-2"><strong>Title</strong></div>
			      		<div class="col-xs-10 col-md-4"><input type="text" class='textReq' id="uploadTitle" name="titleE" value=""></div>
			      		<div class="col-xs-2"><strong>Show when</strong></div>
			      		<div class="col-xs-10 col-md-4"><input type="text" id="uploadWhen" name="displayDateE" value="" class='when '></div>
			      	</div>
			      	<br>
			      	<div class="row">
		      			<div class="col-xs-12 col-md-6 col-md-offset-6">Leaving "Show when" blank will hide the article</div>
		      		</div>
		      		<br>
					<div class="row">
						<div class="col-xs-2"><strong>Stub</strong></div>
			      		<div class="col-xs-10 col-md-4"><input type="text" class='textReq' id="uploadStub" name="stubE" value=""></div>
			      		<div class="col-xs-2"><strong>Media type</strong></div>
			      		<div class="col-xs-10 col-md-4">Picture</div>
					</div>
					<br>
					<div class="row">
						<div class="col-xs-2"><strong>Logged in Only?</strong></div>
			      		<div class="col-xs-10 col-md-4">
			      			<select id="uploadLogged">
			      				<option selected value='1'>Yes</option>
			      				<option value='0'>No</option>
			      			</select>
		      			</div>
			      		<div class="col-xs-12 col-md-6">If yes, will only be visible to logged in members.</div>
					</div>
					<br>
	      			<div class='nUpload'>
	      				<p>Your browser doesn't have Flash, Silverlight or HTML5 support.</p>
	      			</div>
	      		</div>
			</div>
	  	</div>
	  	<div class="panel panel-default">
	    	<div class="panel-heading" role="tab" id="embedUploadArea">
	      		<h4 class="panel-title">
	        		<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#embedAccord" aria-expanded="false" aria-controls="embedAccord">
	          			<strong class="green"><span class='glyphicon glyphicon-play-circle'></span>  Add Embeded media (from Youtube)</strong>
	        		</a>
	      		</h4>
	    	</div>
	    	<div id="embedAccord" class="panel-collapse collapse" role="tabpanel" aria-labelledby="embedUploadArea">
	      		<div class="panel-body">
	      			<div class="row">
			      		<div class="col-xs-2"><strong>Title</strong></div>
			      		<div class="col-xs-10 col-md-4"><input type="text" class='textReq' id="embedTitle" name="titleE" value=""></div>
			      		<div class="col-xs-2"><strong>Show when</strong></div>
			      		<div class="col-xs-10 col-md-4"><input type="text" id="embedWhen" name="displayDateE" value="" class='when '></div>
			      	</div>
			      	<br>
			      	<div class="row">
		      			<div class="col-xs-12 col-md-6 col-md-offset-6">Leaving "Show when" blank will hide the article</div>
		      		</div>
			      	<br>
					<div class="row">
						<div class="col-xs-2"><strong>Stub</strong></div>
			      		<div class="col-xs-10 col-md-4"><input type="text" class='textReq' id="embedStub" name="stubE" value=""></div>
			      		<div class="col-xs-2"><strong>Media type</strong></div>
			      		<div class="col-xs-10 col-md-4"><?php echo $mediaOptions?></div>
					</div>
					<br>
					<h3>Copy embed statement below</h3>
					<div class="alert alert-danger noshow" id="textArea-alert">
						<button type="button" class="close" data-dismiss="alert">x</button>
						<strong id="userEmbedMessage">You need to add the embedded text! </strong>
					</div>
			      	<textarea name="MCEembed" class='cleanMe' cols="40" rows="5" id="mceEmbedArea" style="width: 100%; resize: vertical; overflow-y: scroll; "></textarea>
			       	<br>
			       	<button id="saveEmbedded" class="btn btn-success" disabled="disabled">Save Embedded Media</button>
			       	<button id="clearEmbed" class="btn btn-warning">Clear</button>
	      		</div>
	    	</div>
	  	</div>
  		<div class="panel panel-default">
			<div class="panel-heading" role="tab" id="fileUploadArea">
	      		<h4 class="panel-title">
	        		<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#fileAccord" aria-expanded="false" aria-controls="fileAccord">
	          			<strong class="green"><span class='glyphicon glyphicon-film'></span>  Add New Video (Stored on Server)</strong>
	        		</a>
	      		</h4>
    		</div>
	    	<div id="fileAccord" class="panel-collapse collapse" role="tabpanel" aria-labelledby="fileUploadArea">
	      		<div class="panel-body">
					Feature unavailabe
      			</div>
			</div>
  		</div>
	</div>
	<div class='panel panel-default'>
		<div class="panel-heading" >
      		<h4 class="panel-title text-center">
	        	Existing Media
	      	</h4>
	    </div>
  		<div id='mediaTable' class="panel-body">
	        	<?php echo $mediaTable?>
      	</div>
  	</div>
</div>
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				