<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_page_visibility extends CI_Migration {

	public function up()
	{
		
		
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 12,
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			'sub_url' => array(
				'type' => 'VARCHAR',
				'constraint' => 70,
			),
			'min_role'=>array(
				'type' => 'INT',
				'constraint' => 2,
	  			'default'=> 0,
			),
			'redirect_to' => array(
				'type' => 'VARCHAR',
				'constraint' => 70,
				'default'=>NULL,
			),
			'comment' => array(
				'type' => 'VARCHAR',
				'constraint' => 100,
			),
		));
		
		$this->dbforge->add_key('id', true);
		$this->dbforge->create_table('page_visibility');
	}

	public function down()
	{
		$this->dbforge->drop_table('page_visibility', TRUE);
	}
}