<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_media_database extends CI_Migration {

	public function up()
	{
		
		
		$this->dbforge->add_field(array(
			'media_id' => array(
				'type' => 'INT',
				'constraint' => 12,
				'unsigned' => TRUE,
				'auto_increment' => TRUE
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
			'fileLoc'=>array(
				'type' => 'VARCHAR',
				'constraint' => 254,
				'null'=>FALSE,
			),
			'author_id' => array(
				'type' => 'INT',
				'constraint' => 12,
				'unsigned' => TRUE,
			),
			'embed'=>array(
				'type' => 'text',
				'null' => FALSE,
			),
			'visible'=>array(
				'type' => 'TINYINT',
				'constraint' => 1,
				'default' => 1
			),
			'mediaType'=>array(
				'type' => 'VARCHAR',
				'constraint' => 10,
				'null'=>FALSE,
			),
			'visibleWhen'=>array(
				'type' => 'DATETIME',
				'null'=> FALSE,
			),
			'md5Hash'=>array(
				'type' => 'VARCHAR',
				'constraint'=>32,
				'null'=> FALSE,
			),
			'loggedOnly'=>array(
				'type' => 'TINYINT',
				'constraint' => 1,
				'default' => 1,
			),
			'vintage'=>array(
				'type' => 'TINYINT',
				'constraint' => 1,
				'default' => 0
			)
			
		));
		
		$this->dbforge->add_key('media_id', true);
		$this->dbforge->create_table('media_database');
	}

	public function down()
	{
		$this->dbforge->drop_table('media_database');
	}
}