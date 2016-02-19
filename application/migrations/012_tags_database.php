<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_tags_database extends CI_Migration {

	public function up()
	{
		
		
		$this->dbforge->add_field(array(
			'tag_id' => array(
				'type' => 'INT',
				'constraint' => 12,
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			'tag_name'=> array(
				'type' => 'VARCHAR',
				'constraint' => 128
			),
			'usage'=>array(
				'type' => 'text',
				'null' => FALSE,
			),
			'created' => array(
				'type' => 'DATETIME',
				'null'=> FALSE,
			),
			'author_id' => array(
				'type' => 'INT',
				'constraint' => 12,
				'unsigned' => TRUE,
			),
			'visible'=>array(
				'type' => 'TINYINT',
				'constraint' => 1,
				'default' => 0
			)
		));
		
		$this->dbforge->add_key('tag_id', true);
		$this->dbforge->create_table('tags_database');
	}

	public function down()
	{
		$this->dbforge->drop_table('tags_database', TRUE);
	}
}