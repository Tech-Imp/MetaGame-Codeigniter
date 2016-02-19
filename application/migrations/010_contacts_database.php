<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_contacts_database extends CI_Migration {

	public function up()
	{
		
		
		$this->dbforge->add_field(array(
			'static_id' => array(
				'type' => 'INT',
				'constraint' => 12,
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			'content_id'=> array(
				'type' => 'VARCHAR',
				'constraint' => 20
			),
			'title' => array(
				'type' => 'VARCHAR',
				'constraint' => 100,
				'null' => FALSE,
			),
			'stub'=>array(
				'type' => 'VARCHAR',
				'constraint' => 120,
				'null'=>FALSE,
			),
			'created' => array(
				'type' => 'DATETIME',
				'null'=> FALSE,
			),
			'modified' => array(
				'type' => 'DATETIME',
				'null'=> FALSE,
			),
			'author_id' => array(
				'type' => 'INT',
				'constraint' => 12,
				'unsigned' => TRUE,
			),
			'body'=>array(
				'type' => 'text',
				'null' => FALSE,
			),
			'visible'=>array(
				'type' => 'TINYINT',
				'constraint' => 1,
				'default' => 0
			)
		));
		
		$this->dbforge->add_key('static_id', true);
		$this->dbforge->create_table('contacts_database');
	}

	public function down()
	{
		$this->dbforge->drop_table('contacts_database', TRUE);
	}
}