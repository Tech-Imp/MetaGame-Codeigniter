
			
			<div class="col-md-8 col-md-offset-1">
				<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
				<div class='panel panel-default'>
					<div class="panel-heading" role="tab" id="changes">
			      		<h4 class="panel-title text-center">
				        	<a data-toggle="collapse" data-parent="#accordion" href="#recentController" aria-expanded="false" aria-controls="recentController">
				          		<strong class="green"><span class='glyphicon glyphicon-search'></span> Recent changes </strong>
					        </a>
				      	</h4>
				    </div>
				    <div id="recentController" class="panel-collapse collapse" role="tabpanel" aria-labelledby="recentController">
			      		<div class="panel-body">
					      	<?php echo $recentChanges;?>
				      	</div>
				    </div>
					<!-- My roles section -->
					<div class="panel-heading" role="tab" id="myRoles">
			      		<h4 class="panel-title text-center">
				        	<a data-toggle="collapse" data-parent="#accordion" href="#myRolesController" aria-expanded="false" aria-controls="myRoleController">
				          		<strong class="green"><span class='glyphicon glyphicon-king'></span> My current roles </strong>
					        </a>
				      	</h4>
				    </div>
				    <div id="myRolesController" class="panel-collapse collapse" role="tabpanel" aria-labelledby="myRoleController">
			      		<div class="panel-body">
					      	<?php echo $myRole?>
				      	</div>
				    </div>
				    <!-- Add roles section -->
					<div class="panel-heading" role="tab" id="roleAdd">
			      		<h4 class="panel-title text-center">
				        	<a data-toggle="collapse" data-parent="#accordion" href="#addRoleController" aria-expanded="true" aria-controls="addRoleController">
				          		<strong class="green"><span class='glyphicon glyphicon-pawn'></span> Add New Roles to Section</strong>
					        </a>
				      	</h4>
				    </div>
				    <div id="addRoleController" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="mceContactAdd">
			      		<div class="panel-body">
					      	<div class="row">
					      		<div class="col-xs-2"><strong>Add person:</strong></div>
					      		<div class="col-xs-10 col-md-4">
					      			<select id='addPerson'>
					      				<?php echo $personList ?>
					      			</select>
				      			</div>
			      			</div>
			      			<br>
			      			<div class="row">
					      		<div class="col-xs-2"><strong>to Group:</strong></div>
					      		<div class="col-xs-10 col-md-4">
					      			<select id='addToSection'>
					      				<?php echo $sectionList?>
					      			</select>
					      			</div>
					      	</div>
					       	<br>
					       	<button id="saveNewContact" class="btn btn-success" >Save to Database</button>
					       	<button id="clearArticle" class="btn btn-warning">Clear</button>
					      	<div class='panel panel-default'>
								<div class="panel-heading" >
				      				<h4 class="panel-title text-center">
					        			Users you have granted access
					      			</h4>
					    		</div>
				      			<div class="panel-body">
						        	<?php echo $sectionAccess?>
					      		</div>
			      			</div>
				      	
				      	</div>
				    </div>
				    <!-- New section add -->
				    <div class="panel-heading" role="tab" id="sectionAdd">
			      		<h4 class="panel-title text-center">
				        	<a data-toggle="collapse" data-parent="#accordion" href="#sectionController" aria-expanded="false" aria-controls="sectionController">
				          		<strong class="green"><span class='glyphicon glyphicon-th-list'></span> Add New Section </strong>
					        </a>
				      	</h4>
				    </div>
				    <div id="sectionController" class="panel-collapse collapse" role="tabpanel" aria-labelledby="sectionAdd">
			      		<div class="panel-body">
					      	
					      	<div class="row">
					      		<div class="col-xs-2">Name of new section:</div>
					      		<div class="col-xs-10 col-md-4"><input type="text" id="secName" value=""></div>
					      		<div class="col-xs-12 col-md-6">This is the name as it will appear in the dropdowns.</div>
					      	</div>
							<br>
							<div class="row">
					      		<div class="col-xs-2">www.meta-game.net/</div>
					      		<div class="col-xs-10 col-md-4"><input type="text" id="secDir" placeholder="YOUR STUFF"></div>
					      		<div class="col-xs-12 col-md-6">The sudirectory address to reach your stuff directly. (ie meta-game.net/main)</div>
					      	</div>
					      	<br>
							<h3>Usage (what this section is for):</h3>
							<div class="alert alert-danger noshow" id="textArea-alert">
    							<button type="button" class="close" data-dismiss="alert">x</button>
    							<strong id="userMessage">You need to add Usage Info </strong>
							</div>
					      	<textarea name="MCEarea" class='cleanMe' cols="40" rows="10" id="sectionUsage" style="width: 100%; resize: vertical; overflow-y: scroll; "></textarea>
					       	<br>
					       	<button id="saveNewSection" class="btn btn-success" >Create Section</button>
					       	<button id="clearSection" class="btn btn-warning">Clear</button>
					      	<div class='panel panel-default'>
								<div class="panel-heading" >
				      				<h4 class="panel-title text-center">
					        			Existing Sections
					      			</h4>
					    		</div>
				      			<div class="panel-body">
						        	<?php echo $sectionTable?>
					      		</div>
			      			</div>
				      	
				      	</div>
				    </div>
				    <!-- Visiblity section -->
				    <div class="panel-heading" role="tab" id="visibilityAdd">
			      		<h4 class="panel-title text-center">
				        	<a data-toggle="collapse" data-parent="#accordion" href="#visController" aria-expanded="false" aria-controls="visController">
				          		<strong class="green"><span class='glyphicon glyphicon-eye-open'></span> Change page visibility </strong>
					        </a>
				      	</h4>
				    </div>
				    <div id="visController" class="panel-collapse collapse" role="tabpanel" aria-labelledby="visibilityAdd">
			      		<div class="panel-body">
					      	NYI
							<!-- <div class="alert alert-danger noshow" id="textArea-alert">
    							<button type="button" class="close" data-dismiss="alert">x</button>
    							<strong id="userMessage">You need to add Travel Info </strong>
							</div> -->
					      	<textarea name="MCEarea" class='cleanMe' cols="40" rows="10" id="mceTravel" style="width: 100%; resize: vertical; overflow-y: scroll; "></textarea>
					       	<br>
					       	<button id="saveNewTravel" class="btn btn-success" >Save to Database</button>
					       	<button id="clearArticle" class="btn btn-warning">Clear</button>
					      	
				      	
				      	</div>
				    </div>
				</div>
			</div>
		</div>
		