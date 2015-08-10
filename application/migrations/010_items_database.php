<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_items_database extends CI_Migration {

	public function up()
	{
		
		
		$this->dbforge->add_field(array(
			'item_id' => array(
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
			'short_desc'=> array(
				'type' => 'VARCHAR',
				'constraint' => 200,
				'null' => FALSE,
			),
			'body'=> array(
				'type' => 'text',
				'null' => FALSE,
			),
			'purchase_link'=> array(
				'type' => 'VARCHAR',
				'constraint' => 300,
				'null' => FALSE,
			),
			'bucket'=>array(
				'type' => 'VARCHAR',
				'constraint' => 300,
				'null' => FALSE,
			),
			'storage_link'=> array(
				'type' => 'VARCHAR',
				'constraint' => 300,
				'null' => FALSE,
			),
			'author_id' => array(
				'type' => 'INT',
				'constraint' => 12,
				'unsigned' => TRUE,
			),
			'created' => array(
				'type' => 'DATETIME',
				'null'=> FALSE,
			),
			'modified' => array(
				'type' => 'DATETIME',
				'null'=> FALSE,
			)
			
		));
		
		$this->dbforge->add_key('item_id', true);
		$this->dbforge->create_table('items_database');
	}

	public function down()
	{
		$this->dbforge->drop_table('items_database');
	}
}