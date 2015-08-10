
<div class="container-fluid">
	<div class="well well-lg">
	<div class="row">
		<div class="col-lg-offset-5 col-lg-3 col-md-offset-5 col-md-4">
		      <div class="mheader">
		        <h4>Log In</h4>
		      </div>
		      <div class="mbody">
		  		<?php echo validation_errors();?>
		      	<?php echo form_open();?>
		      	<table>
		      		<tr>
		      			<td>Email</td>
		      			<td><?php 
		      			$data=array(
							'name' => 'email',
							'autocorrect' => 'off'
						);
		      			echo form_input($data); 
		      			?></td>
		      		</tr>
		      		<tr>
		      			<td>Password</td>
		      			<td><?php echo form_password('password'); ?></td>
		      		</tr>
		      		<tr>
		      			<td></td>
		      			<td><?php echo form_submit('submit', 'Log in', 'class="btn btn-primary"'); ?></td>
		      		</tr>
		      	</table>
		       	<?php echo form_close();?>
		      </div>
		      <div class="mfooter">
		      </div>
			
			</div>
		</div>
	</div>
</div>
