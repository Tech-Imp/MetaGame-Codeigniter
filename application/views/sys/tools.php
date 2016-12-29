
			
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
			      		<div id="recentChanges" class="panel-body">
					      	<?php echo $recentChanges;?>
				      	</div>
				    </div>
					<!-- My roles section -->
					<div class="panel-heading" role="tab" id="myRoles">
			      		<h4 class="panel-title text-center">
				        	<a data-toggle="collapse" data-parent="#accordion" href="#myRolesController" aria-expanded="true" aria-controls="myRoleController">
				          		<strong class="green"><span class='glyphicon glyphicon-king'></span> My current roles </strong>
					        </a>
				      	</h4>
				    </div>
				    <div id="myRolesController" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="myRoleController">
			      		<div id="myRoles" class="panel-body">
					      	<?php echo $myRole?>
				      	</div>
				    </div>
				    <!-- Add roles section -->
					<div class="panel-heading" role="tab" id="roleAdd">
			      		<h4 class="panel-title text-center">
				        	<a data-toggle="collapse" data-parent="#accordion" href="#addRoleController" aria-expanded="false" aria-controls="addRoleController">
				          		<strong class="green"><span class='glyphicon glyphicon-pawn'></span> Add New People to Section</strong>
					        </a>
				      	</h4>
				    </div>
				    <div id="addRoleController" class="panel-collapse collapse" role="tabpanel" aria-labelledby="mceContactAdd">
			      		<div class="panel-body">
			      			<div class="alert alert-danger noshow" id="roleArea-alert">
    							<button type="button" class="close" data-dismiss="alert">x</button>
    							<strong id="roleMessage">You need to add Info </strong>
							</div>
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
					      	<div class='panel panel-default'>
								<div class="panel-heading" >
				      				<h4 class="panel-title text-center">
					        			Users you have granted access
					      			</h4>
					    		</div>
				      			<div id="sectionAccess" class="panel-body">
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
					      		<div class="col-xs-2"><strong>Name of new section:</strong></div>
					      		<div class="col-xs-10 col-md-4"><input type="text" id="secName" class="textReq" value=""></div>
					      		<div class="col-xs-12 col-md-6">This is the name as it will appear in the dropdowns.</div>
					      	</div>
							<br>
							<div class="row">
					      		<div class="col-xs-2"><strong>www.meta-game.net/</strong></div>
					      		<div class="col-xs-10 col-md-4"><input type="text" id="secDir" class="textReq" placeholder="YOUR STUFF"></div>
					      		<div class="col-xs-12 col-md-6">The sudirectory address to reach your stuff directly. (ie meta-game.net/main)</div>
					      	</div>
					      	<br>
					      	<div class="row">
                                        <div class="col-xs-2"><strong>Visible in links?</strong></div>
                                        <div class="col-xs-10 col-md-4">
                                             <select id='linkVis'>
                                                  <?php echo $linkVisibility?>
                                             </select>
                                        </div>
                                        <div class="col-xs-12 col-md-6">Determines if a section will appear on right hand side for easy access</div>
                                   </div>
                                   <br>
					      	<div class="row">
                                        <div class="col-xs-2"><strong>Link on where?</strong></div>
                                        <div class="col-xs-10 col-md-4">
                                             <select id='linkLoc'>
                                                  <option value="">MAIN</option>
                                                  <?php echo $sectionList?>
                                             </select>
                                        </div>
                                        <div class="col-xs-12 col-md-6">Determines which section that link would be visible on as a shortcut</div>
                                   </div>
					      	<br>
							<h3>Usage (what this section is for):</h3>
							<div class="alert alert-danger noshow" id="sectionArea-alert">
    							<button type="button" class="close" data-dismiss="alert">x</button>
    							<strong id="sectionMessage">You need to add Usage Info </strong>
							</div>
					      	<textarea name="MCEarea" class='cleanMe' cols="40" rows="10" id="sectionUsage" style="width: 100%; resize: vertical; overflow-y: scroll; "></textarea>
					       	<br>
					       	<button id="saveNewSection" class="btn btn-success" disabled="disabled">Create Section</button>
					       	<button id="clearSection" class="btn btn-warning">Clear</button>
					      	<div class='panel panel-default'>
								<div class="panel-heading" >
				      				<h4 class="panel-title text-center">
					        			Existing sections you control
					      			</h4>
					    		</div>
				      			<div id="sectionTable" class="panel-body">
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
				    <!-- Add internal user-->
                        <div class="panel-heading" role="tab" id="visibilityAdd">
                              <h4 class="panel-title text-center">
                              <a data-toggle="collapse" data-parent="#accordion" href="#newUserController" aria-expanded="false" aria-controls="newUserController">
                                        <strong class="green"><span class='glyphicon glyphicon-user'></span> Add new user account </strong>
                                 </a>
                              </h4>
                        </div>
                        <div id="newUserController" class="panel-collapse collapse" role="tabpanel" aria-labelledby="visibilityAdd">
                              <div class="panel-body">
                                   This will allow a new user to be created. It will default them to basic login rights. 
                                   <br>
                                   Contact site admin to have those privledges elevated.
                                   <br>
                                   <?php echo $createUserLink?>
                              </div>
                        </div>
				</div>
			</div>
		</div>
		