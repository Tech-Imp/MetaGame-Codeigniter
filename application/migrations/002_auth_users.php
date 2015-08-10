<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_auth_users extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 12,
				'unsigned' => TRUE,
				// 'auto_increment' => TRUE
			),
			'role' => array(
				'type' => 'INT',
				'constraint' => '2',
			),
			'salt' => array(
				'type' => 'VARCHAR',
				'constraint' => '16',
			),
			'active' => array(
				'type' => 'INT',
				'constraint' => '2',
				'default'=> 3,
			),
			'comment' => array(
				'type' => 'VARCHAR',
				'constraint' => '300',
			),
			
		));
		$this->dbforge->add_key('id', true);
		$this->dbforge->create_table('auth_role');
	}

	public function down()
	{
		$this->dbforge->drop_table('auth_role');
	}
}