<div class='mainContent'>
	<!-- <div class='imageBanner'>
		<img src="<?php echo base_url() ; ?>assets/image/header.jpg" class='img-responsive' alt="Official site of Meta-Game Productions" width="100%">
	</div> -->
	<nav class="navbar navbar-inverse" role="navigation">
	  <div class="container-fluid">
	    <!-- Brand and toggle get grouped for better mobile display -->
	    <div class="navbar-header">
	      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#NavContent">
	        <span class="sr-only">Toggle navigation</span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
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
	<div class="container-fluid">
	
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
    					<a href='' target='_blank'><img class='img-responsive img-rounded'src="<?php echo base_url() ; ?>assets/image/Placeholder.jpg" alt='Support Meta-game via PayPal'> </a>
    					<br>
  						<a href='http://www.skype.com/en/download-skype/skype-for-computer/' target='_blank'><img class='img-responsive img-rounded'src="<?php echo base_url() ; ?>assets/image/Placeholder.jpg" alt='Skype at '> </a>
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