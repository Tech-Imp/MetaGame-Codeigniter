			
			<div class="col-md-8  col-md-offset-1">
				<div class='panel panel-default'>
					<div class="panel-heading" role="tab" id="mceArticleAdd">
			      		<h4 class="panel-title text-center">
				        	<a data-toggle="collapse" data-parent="#accordion" href="#articleController" aria-expanded="false" aria-controls="articleController">
				          		<strong class="green"><span class='glyphicon glyphicon-pencil'></span> Add new Article </strong>
					        </a>
				      	</h4>
				    </div>
				    <div id="articleController" class="panel-collapse collapse" role="tabpanel" aria-labelledby="mceArticleAdd">
			      		<div class="panel-body">
					      	<div class="row">
					      		<div class="col-xs-2">Title</div>
					      		<div class="col-xs-10 col-md-4"><input type="text" class='textReq' id="articleTitle" name="title" value=""></div>
					      		<div class="col-xs-2">Show when</div>
					      		<div class="col-xs-10 col-md-4"><input type="text" id="articleWhen" name="displayDate" value="" class='when '></div>
					      	</div>
					      	<br>
							<div class="row">
								<div class="col-xs-2">Stub</div>
					      		<div class="col-xs-10 col-md-4"><input type="text" class='textReq' id="articleStub" name="title" value=""></div>
					      		<div class="col-xs-12 col-md-6">Leaving "Show when" blank will hide the article</div>
							</div>
							<?php echo $exclusive;?>
							<br>
							<h3>Body of article</h3>
							<div class="alert alert-danger noshow" id="textArea-alert">
    							<button type="button" class="close" data-dismiss="alert">x</button>
    							<strong id="userMessage">You need to add something to the body of the article! </strong>
							</div>
					      	<textarea name="MCEarea" class='cleanMe' cols="40" rows="10" id="mceNewsArea" style="width: 100%; resize: vertical; overflow-y: scroll; "></textarea>
					       	<br>
					       	<!-- <button id="saveNewArticle" class="btn btn-success" disabled="disabled">Save to Database</button> -->
					       	<button id="saveWrittenNews" class="btn btn-primary" disabled="disabled">Save as <span class="glyphicon glyphicon-send">NEWS</span></button>
					       	<button id="saveWrittenArticle" class="btn btn-success" disabled="disabled">Save as <span class="glyphicon glyphicon-list-alt">ARTICLE</span></button>
					       	<button id="clearArticle" class="btn btn-warning">Clear</button>
				      	</div>
				    </div>
				</div>
				<div class='panel panel-default'>
					<div class="panel-heading" >
			      		<h4 class="panel-title text-center">
				        	Existing acticles
				        	
				      	</h4>
				    </div>
		      		<div id='mediaTable' class="panel-body">
				        	<?php echo $articleTable?>
			      	</div>
		      	</div>
	      	</div>