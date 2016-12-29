			
			<div class="col-md-8 well col-md-offset-1">
				<h4><strong> <?php echo $userEdit; ?> </strong></h4>
				<?php echo validation_errors();?>
		      	<?php echo form_open();?>
		      	<table>
		      		<tr>
		      			<td>New Password</td>
		      			<td><?php echo form_password('password'); ?></td>
		      		</tr>
		      		<tr>
		      			<td>Retype Password</td>
		      			<td><?php echo form_password('passConfirm'); ?></td>
		      		</tr>
		      		<tr>
		      			<td><?php echo anchor('admin/dashboard/users/listUsers',"<span class='glyphicon glyphicon-home'></span> Home", array('class'=>'btn btn-danger', 'id'=>'retHome'));?></td>
		      			<td><?php echo form_submit('submit', 'Change Password', 'class="btn btn-success"'); ?></td>
		      		</tr>
		      	</table>
		       	<?php echo form_close();?>
		      </div>
			