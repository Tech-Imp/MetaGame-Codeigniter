			
			<div class="col-md-8  col-md-offset-1">
				<div class='panel panel-default'>
					<div class="panel-heading" role="tab" id="mceContactAdd">
			      		<h4 class="panel-title text-center">
				        	<a data-toggle="collapse" data-parent="#accordion" href="#articleController" aria-expanded="false" aria-controls="articleController">
				          		<strong class="green"><span class='glyphicon glyphicon-pencil'></span> Add New Contact Info </strong>
					        </a>
				      	</h4>
				    </div>
				    <div id="articleController" class="panel-collapse collapse" role="tabpanel" aria-labelledby="mceContactAdd">
			      		<div class="panel-body">
					      	<div class="row">
					      		<div class="col-xs-2">Title</div>
					      		<div class="col-xs-10 col-md-4"><input type="text" class='textReq' id="contactTitle" name="title" value=""></div>
					      	</div>
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
					        			Existing Contact Info
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