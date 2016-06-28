<div class="col-md-8  col-md-offset-1">
     <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
	<div class='panel panel-default'>
		<div class="panel-heading" role="tab" id="photoAvatar">
      		<h4 class="panel-title text-center">
	        	<a data-toggle="collapse" data-parent="#accordion" href="#avatarController" aria-expanded="false" aria-controls="articleController">
	          		<strong class="green"><span class='glyphicon glyphicon-camera'></span> Avatars </strong>
		        </a>
	      	</h4>
	    </div>
	    <div id="avatarController" class="panel-collapse collapse" role="tabpanel" aria-labelledby="photoAvatar">
			<div class="panel-body">
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
	</div>

<br>
		
	  	<div class="panel panel-default">
	    	<div class="panel-heading" role="tab">
      		<h4 class="panel-title">
     			<strong class="green"><span class='glyphicon glyphicon-wrench'></span>  Edit Social for <?php echo $who;?></strong>
      		</h4>
	    	</div>
      		<div class="panel-body">
      			<div class="panel-body">
      			     <div class="row">
                              <div class="col-xs-2"><strong>Social ID</strong></div>
                              <div class="col-xs-10 col-md-4"><input type="text" id="profileID" disabled="disabled" value="<?php echo $socialID;?>"></div>
                              <div class="col-xs-2">Current Logo</div>
                              <div class="col-xs-10 col-md-4"><input type="text" class="cleanMe" id="logoUsed" value="<?php echo $logoUsed;?>"></div>
                         </div>
                         <br>
                         <div class="row">
                              <div class="col-xs-2">Facebook:</div>
                              <div class="col-xs-10 col-md-4"><input type="text" class="cleanMe" id="facebookSocial" value="<?php echo $facebook;?>"></div>
                              <div class="col-xs-2">Youtube:</div>
                              <div class="col-xs-10 col-md-4"><input type="text" class="cleanMe" id="youtubeSocial" value="<?php echo $youtube;?>"></div>
                         </div>
                         <br>
                         <div class="row">
                              <div class="col-xs-2">Twitter:</div>
                              <div class="col-xs-10 col-md-4"><input type="text" class="cleanMe" id="twitterSocial" value="<?php echo $twitter;?>"></div>
                              <div class="col-xs-2">Tumblr:</div>
                              <div class="col-xs-10 col-md-4"><input type="text" class="cleanMe" id="tumblrSocial" value="<?php echo $tumblr;?>"></div>
                         </div>
                         <br>
                         <div class="row">
                              <div class="col-xs-2">Email:</div>
                              <div class="col-xs-10 col-md-4"><input type="text" class="cleanMe" id="emailSocial" value="<?php echo $emailSocial;?>"></div>
                              <div class="col-xs-2">Twitch:</div>
                              <div class="col-xs-10 col-md-4"><input type="text" class="cleanMe" id="twitchSocial" value="<?php echo $twitch;?>"></div>
                         </div>
                         <?php echo $exclusive;?>
                         <h3>Social Info (Used only for subsections)</h3>
                         <div class="alert alert-danger noshow" id="textArea-alert">
                              <button type="button" class="close" data-dismiss="alert">x</button>
                              <strong id="userMessage">You need to add Social Info </strong>
                         </div>
                         <textarea name="MCEarea" class='cleanMe' cols="40" rows="10" id="mceSocial" style="width: 100%; resize: vertical; overflow-y: scroll; "><?php echo $body;?></textarea>
                         <br>
					
			       	
      				<button id="saveEdits" class="btn btn-success">Save Edits</button>
		       		<button class="btn btn-danger" type="button" data-toggle="collapse" data-target="#deleteMedia" aria-expanded="false" aria-controls="deleteMedia">Delete Contact item</button>
					<div class="collapse" id="deleteMedia">
  						<div class="well">
    						<div>This will permanently remove the social item from the database!</div> 
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
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				