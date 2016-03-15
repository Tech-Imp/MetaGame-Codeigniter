			
			<div class="col-md-8  col-md-offset-1">
				<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
				<div class='panel panel-default'>
					<div class="panel-heading" role="tab" id="photoAvatar">
			      		<h4 class="panel-title text-center">
				        	<a data-toggle="collapse" data-parent="#accordion" href="#avatarController" aria-expanded="false" aria-controls="articleController">
				          		<strong class="green"><span class='glyphicon glyphicon-camera'></span> Add Avatar Picture </strong>
					        </a>
				      	</h4>
				    </div>
				    <div id="avatarController" class="panel-collapse collapse" role="tabpanel" aria-labelledby="photoAvatar">
			      		<div class="panel-body">
					      	<div class="row">
					      		<div class="col-xs-2">Stub Notes</div>
					      		<div class="col-xs-10 col-md-4"><input type="text" class='textReq' id="avatarNotes" name="stub" value=""></div>
					      	</div>
					      	<?php echo $exclusiveAvatar;?>
					      	<br>
							<div class='nUpload'>
	      						<p>Your browser doesn't have Flash, Silverlight or HTML5 support.</p>
	      					</div>
	      					<br>
					      	<div class='panel panel-default'>
								<div class="panel-heading" >
				      				<h4 class="panel-title text-center">
					        			Existing Avatar Images
					      			</h4>
					    		</div>
				      			<div class="panel-body">
						        	<?php echo $avatarTable?>
					      		</div>
			      			</div>
				      	
				      	</div>
				    </div>
					<div class="panel-heading" role="tab" id="mceContactAdd">
			      		<h4 class="panel-title text-center">
				        	<a data-toggle="collapse" data-parent="#accordion" href="#profileController" aria-expanded="true" aria-controls="articleController">
				          		<strong class="green"><span class='glyphicon glyphicon-user'></span> Add New Contact Info </strong>
					        </a>
				      	</h4>
				    </div>
				    <div id="profileController" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="mceContactAdd">
			      		<div class="panel-body">
					      	<div class="row">
					      		<div class="col-xs-2">Your Title</div>
					      		<div class="col-xs-10 col-md-4"><input type="text" class='textReq' id="contactTitle" name="title" value=""></div>
					      		<div class="col-xs-2">Current Avatar</div>
					      		<div class="col-xs-10 col-md-4"><input type="text" class='textReq' id="avatarUsed" name="avatar" value=""></div>
					      	</div>
					      	<br>
					      	<div class="row">
					      		<div class="col-xs-2">User Name</div>
					      		<div class="col-xs-10 col-md-4"><input type="text" class='textReq' id="contactName" name="name" value=""></div>
					      	</div>
					      	<?php echo $exclusiveProfile;?>
							<h3>Contact Info</h3>
							<div class="alert alert-danger noshow" id="textArea-alert">
    							<button type="button" class="close" data-dismiss="alert">x</button>
    							<strong id="userMessage">You need to add Contact Info </strong>
							</div>
					      	<textarea name="MCEarea" class='cleanMe' cols="40" rows="10" id="mceContact" style="width: 100%; resize: vertical; overflow-y: scroll; "></textarea>
					       	<br>
					       	<button id="saveNewContact" class="btn btn-success" >Save to Database</button>
					       	<button id="clearArticle" class="btn btn-warning">Clear</button>
					      	<div class='panel panel-default'>
								<div class="panel-heading" >
				      				<h4 class="panel-title text-center">
					        			Your Profile Info
					      			</h4>
					    		</div>
				      			<div class="panel-body">
						        	<?php echo $contactTable?>
					      		</div>
			      			</div>
				      	
				      	</div>
				    </div>
				    <div class="panel-heading" role="tab" id="mceTravelAdd">
			      		<h4 class="panel-title text-center">
				        	<a data-toggle="collapse" data-parent="#accordion" href="#travelController" aria-expanded="false" aria-controls="travelController">
				          		<strong class="green"><span class='glyphicon glyphicon-pencil'></span> Add New Travel Info </strong>
					        </a>
				      	</h4>
				    </div>
				    <div id="travelController" class="panel-collapse collapse" role="tabpanel" aria-labelledby="mceTravelAdd">
			      		<div class="panel-body">
					      	<div class="row">
					      		<div class="col-xs-2">Where to? (City/state)</div>
					      		<div class="col-xs-10 col-md-4"><input type="text" class='textReq' id="whereToTravel" name="location" value=""></div>
					      	</div>
					      	<div class="row">
					      		<div class="col-xs-2">Leaving:</div>
					      		<div class="col-xs-10 col-md-4"><input type="text" id="leaveWhen" name="displayDate" value="" class='when '></div>
					      		<div class="col-xs-2">Returning:</div>
					      		<div class="col-xs-10 col-md-4"><input type="text" id="returnWhen" name="displayDate" value="" class='when '></div>
					      	</div>
							<h3>Travel Info</h3>
							<!-- <div class="alert alert-danger noshow" id="textArea-alert">
    							<button type="button" class="close" data-dismiss="alert">x</button>
    							<strong id="userMessage">You need to add Travel Info </strong>
							</div> -->
					      	<textarea name="MCEarea" class='cleanMe' cols="40" rows="10" id="mceTravel" style="width: 100%; resize: vertical; overflow-y: scroll; "></textarea>
					       	<br>
					       	<button id="saveNewTravel" class="btn btn-success" >Save to Database</button>
					       	<button id="clearArticle" class="btn btn-warning">Clear</button>
					      	<div class='panel panel-default'>
								<div class="panel-heading" >
				      				<h4 class="panel-title text-center">
					        			Existing Travel Info
					      			</h4>
					    		</div>
				      			<div class="panel-body">
						        	<?php echo $travelTable?>
					      		</div>
			      			</div>
				      	
				      	</div>
				    </div>
				    
				</div>
				</div>
	      	</div>