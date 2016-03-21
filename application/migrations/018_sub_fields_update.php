<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_sub_fields_update extends CI_Migration {

	//This migration adds missing columns for 2 databases

	public function up()
	{
		$subfields= array(
			'modified' => array(
				'type' => 'DATETIME',
				'null'=> FALSE,
			)
		);
		$social=array(
			'twitch'=>array(
				'type' => 'VARCHAR',
				'constraint' => 128
			), 
			'email'=>array(
				'type' => 'VARCHAR',
				'constraint' => 128
			)
		);
		
		$this->dbforge->add_column('sub_auth', $subfields);
		$this->dbforge->add_column('sub_info_database', $social);
	}

	public function down()
	{
		$this->dbforge->drop_column('sub_auth', 'modified');
		$this->dbforge->drop_column('sub_info_database', 'twitch');
		$this->dbforge->drop_column('sub_info_database', 'email');
	}

	
}