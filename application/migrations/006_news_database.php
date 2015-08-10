<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_news_database extends CI_Migration {

	public function up()
	{
		
		
		$this->dbforge->add_field(array(
			'news_id' => array(
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
			),
			'visibleWhen'=>array(
				'type' => 'DATETIME',
				'null'=> FALSE,
			)
		));
		
		$this->dbforge->add_key('news_id', true);
		$this->dbforge->create_table('news_database');
	}

	public function down()
	{
		$this->dbforge->drop_table('news_database');
	}
}