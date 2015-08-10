<script src='https://www.google.com/recaptcha/api.js'></script>
<div class="container-fluid">
	<div class="well well-lg">
	<div class="row">
		<div class="col-lg-offset-5 col-lg-3 col-md-offset-5 col-md-4">
		      <div class="mheader">
		        <h3>Sign Up</h3>
		      </div>
		      <div class="mbody">
		      	<strong class='text-danger'><?php echo $this->session->flashdata('error'); ?></strong>
		  		<strong class='text-danger'><?php echo validation_errors();?></strong>
		      	<?php echo form_open();?>
		      	<table>
		      		<tr>
		      			<td>Name</td>
		      			<td><?php echo form_input('name', set_value('name')); ?></td>
		      		</tr>
		      		<tr><td colspan="100%"></td></tr>
		      		<tr>
		      			<td>Email</td>
		      			<td><?php 
		      			$data=array(
							'name' => 'email',
							'autocorrect' => 'off',
							'value'=>set_value('email')
						);
		      			echo form_input($data); 
		      			?></td>
		      		</tr>
		      		<tr>
		      			<td>Retype Email</td>
		      			<td><?php 
		      			$data=array(
							'name' => 'emailVerify',
							'autocorrect' => 'off',
							'value'=>set_value('emailVerify')
						);
		      			echo form_input($data); 
		      			?></td>
		      		</tr>
		      		<tr><td colspan="100%"></td></tr>
		      		<tr><td colspan="100%">Please consider using a passphrase made up of multiple short words.</td></tr>
		      		<tr>
		      			<td>Password</td>
		      			<td><?php echo form_password('password'); ?></td>
		      		</tr>
		      		<tr>
		      			<td>Retype Password</td>
		      			<td><?php echo form_password('passConfirm'); ?></td>
		      		</tr>
		      		<tr><td colspan="100%"></td></tr>
		      		<tr>
		      			
		      			<td colspan="100%"><div class="g-recaptcha center-block" data-sitekey="<?php echo $recaptcha; ?>"></div></td>
		      		</tr>
		      		<tr>
		      			<td></td>
		      			<td><?php echo form_submit('submit', 'Complete Sign Up', 'class="btn btn-primary"'); ?></td>
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
