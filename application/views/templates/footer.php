	</body>
	<br>
	<div class="container-fluid">
		<div class="row">
			<div class='col-xs-6 col-xs-offset-4 col-md-offset-4 col-md-4 contentFooter'>
				<strong>Copyright &copy; 2014-<?php echo date("Y"); ?> Meta-Game Productions</strong>
				<div>Website built by Tech-Imp</div>
			</div>
		</div>
	</div>
	<div>
     <?php
     if(!empty($heavy_js))
     foreach($heavy_js as $file){
               echo "\n\t\t";
               ?><script src="<?php echo base_url().'assets/scripts/'.$file; ?>"></script><?php
     } echo "\n\t";
     ?>
	</div>
</html>
