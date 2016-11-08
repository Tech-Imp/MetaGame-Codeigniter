<div class='mainContent'>
	<div class="container-fluid">
		<div class="row">
			<div class='imageBanner metaBorder col-md-10 col-md-offset-1'>
				<img src="<?php echo base_url() ; ?>assets/image/Metagame Banner Text.png" class='img-responsive' alt="Official site of Meta-Game Productions" width="100%">
			
				<nav class="navbar" role="navigation">
				  <div class="container-fluid">
				    <!-- Brand and toggle get grouped for better mobile display -->
				    <div class="navbar-header">
				      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#NavContent">
				        <span class="sr-only">Toggle navigation</span>
				        <span class="glyphicon glyphicon-th-list"></span>
				      </button>
				      <!-- <a class="navbar-brand" href="#">Meta-Game</a> -->
				    </div>
				    <!-- Collect the nav links, forms, and other content for toggling -->
				    <div class="collapse navbar-collapse " id="NavContent">
				      
				      <ul class="nav navbar-nav navbar-left">
				      		<?php echo $userOptions; ?>
				      </ul>
				      <?php echo $dashboard; ?>
				    </div><!-- /.navbar-collapse -->
				  </div><!-- /.container-fluid -->
				</nav>
			</div>
		</div>
		<br>
	
		<div class="row">
			<div id="mediaDatabase" class="col-md-8 col-md-offset-1">
				<?php echo $singularContent ?>
				<div role="tabpanel">
	  				<!-- Nav tabs -->
				  	<ul class="nav nav-tabs" role="tablist">
				    	<?php echo $mediaHeader ?>
				  	</ul>
				  	<!-- Tab panes -->
				  	<div class="tab-content">
				    	<?php echo $mediaContent ?>
				  	</div>
				</div>
				
				
			</div>
			<div class="col-md-2  col-md-offset-1 well">
				<div class="panel panel-default">
  					<div class="panel-heading">
    					<h3 class="panel-title">Quick Links</h3>
  					</div>
  					<div class="panel-body">
    					<a href='<?php echo base_url() ; ?>main'><img class='img-responsive img-rounded'src="<?php echo base_url() ; ?>assets/image/Placeholder.jpg" alt='Main site'> </a>
                         <?php echo $siteLinks ?>
  					</div>
  					
				</div>
				<br>
				<div class="panel panel-default">
  					<div class="panel-heading">
    					<h3 class="panel-title">Attending Events</h3>
  					</div>
  					<div class="panel-body">
    					No events currently lined up.
  					</div>
				</div>
			</div>
		</div>
		
	</div>
	
	
</div>