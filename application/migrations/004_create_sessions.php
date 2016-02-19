<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_sessions extends CI_Migration {

	public function up()
	{
		
		
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'VARCHAR',
				'constraint' => 40,
				'default'=> '0',
				'null' => FALSE,
			),
			'ip_address' => array(
				'type' => 'VARCHAR',
				'constraint' => 45,
				'default'=> '0',
				'null' => FALSE,
			),
			'timestamp' => array(
				'type' => 'INT',
				'constraint' => '10',
				'unsigned' => TRUE,
				'default'=>0,
				'null'=>FALSE,
			),
			'data' => array(
				'type' => 'BLOB',
				'null'=>FALSE,
			),
		));
		
		$this->dbforge->add_key('session_id', true);
		$this->dbforge->create_table('ci_sessions');
		$this->db->query('ALTER TABLE `ci_sessions` ADD KEY `timestamp_idx` (`timestamp`)');
	}

	public function down()
	{
		$this->dbforge->drop_table('ci_sessions', TRUE);
	}
}