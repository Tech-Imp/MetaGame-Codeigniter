<div class='mainContent'>
	
	<nav class="navbar navbar-inverse">
	  <div class="container-fluid">
	    <!-- Brand and toggle get grouped for better mobile display -->
	    <div class="navbar-header">
	      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#UserNavContent">
	        <span class="sr-only">Toggle navigation</span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	      </button>
	      <?php echo $currentLocation; ?>
	    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
	    <div class="collapse navbar-collapse " id="UserNavContent">
	      <ul class="nav navbar-nav navbar-right">
	      		<?php echo $userName; ?>
	      		<li><?php echo anchor($logout,'Logout');?></li>
	      </ul>
	    </div><!-- /.navbar-collapse -->
	  </div><!-- /.container-fluid -->
	</nav>
	
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-3 panel panel-default visible-md-block visible-lg-block">
				<div class="panel-heading"><strong>ACTIONS</strong></div>
				<div class="panel-body adminControlPanel">
					
					
					<?php echo $userOptions; ?>
					
		            
				</div>
			</div>
			<!--Account for screen real estate needs on smaller devices  -->
			<div class="panel panel-default visible-xs-block visible-sm-block">
		    	<div class="panel-heading" role="tab" id="headingOne">
		      		<h4 class="panel-title text-center">
			        	<a data-toggle="collapse" data-parent="#accordion" href="#menuControls" aria-expanded="false" aria-controls="menuControls">
			          		<strong>ACTIONS</strong>
				        </a>
			      	</h4>
			    </div>
			    <div id="menuControls" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
		      		<div class="panel-body adminControlPanel">
			        	<?php echo $userOptions; ?>
			      	</div>
			    </div>
		  	</div>
				
					
		            
			