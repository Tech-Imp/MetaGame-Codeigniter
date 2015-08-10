<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<title><?php echo $site_name; ?> <?php echo $title; ?> </title>
	<script src="<?php echo base_url().'assets/scripts/jQuery.js'; ?>"></script>
	<script src="<?php echo base_url().'assets/scripts/bootstrap.min.js'; ?>"></script>
	<script src="<?php echo base_url().'assets/scripts/jQueryUI/jquery-ui.min.js'; ?>"></script>
	<link rel="stylesheet" href="<?php echo base_url().'assets/scripts/jQueryUI/jquery-ui.min.css'; ?>" type="text/css" />
	<link rel="stylesheet" href="<?php echo base_url().'assets/style/bootstrap.min.css'; ?>" type="text/css" />
	<link rel="stylesheet" href="<?php echo base_url().'assets/style/bootstrap-theme.min.css'; ?>" type="text/css" />
	<?php
	/** -- Copy from here -- */
	if(!empty($meta))
	foreach($meta as $name=>$content){
		echo "\n\t\t";
		?><meta name="<?php echo $name; ?>" content="<?php echo $content; ?>" /><?php
			 }
	echo "\n";

	if(!empty($css))
	foreach($css as $file){
	 	echo "\n\t\t";
		?><link rel="stylesheet" href="<?php echo base_url().'assets/style/'. $file; ?>" type="text/css" /><?php
	} echo "\n\t";
	
	if(!empty($js))
	foreach($js as $file){
			echo "\n\t\t";
			?><script src="<?php echo base_url().'assets/scripts/'.$file; ?>"></script><?php
	} echo "\n\t";

	/** -- to here -- */
	?>
	
</head>
<body>
	