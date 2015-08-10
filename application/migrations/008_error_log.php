<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_error_log extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_field(array(
			'transact_id' => array(
				'type' => 'INT',
				'constraint' => 12,
				'unsigned' => TRUE,
				'auto_increment' => TRUE,
			),
			'id_of_affected' => array(
				'type' => 'INT',
				'constraint' => 12,
				'unsigned' => TRUE,
				// 'auto_increment' => TRUE,
			),
			'change_category'=>array(
				'type' => 'VARCHAR',
				'constraint' => 4,
			),
			'change' => array(
				'type' => 'VARCHAR',
				'constraint' => '300',
			),
			'dateWhen' => array(
				'type' => 'DATETIME',
			),
			'change_made_by' => array(
				'type' => 'INT',
				'constraint' => 12,
				'unsigned' => TRUE,
			),
			'ip_of_transact' => array(
				'type' => 'VARCHAR',
				'constraint' => '40',
			),
			'role_to_view' => array(
				'type' => 'INT',
				'constraint' => 2,
			),
		));
		$this->dbforge->add_key('transact_id', true);
		$this->dbforge->create_table('error_log');
	}

	public function down()
	{
		$this->dbforge->drop_table('error_log');
	}
}