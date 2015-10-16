<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_section_split extends CI_Migration {

	public function up()
	{
		//This one create the table handling the extra folders for subsites		
		$this->createSubsite();	
		//This one handles permissions of individuals
		$this->createSubAuth();
		
	}

	public function down()
	{
		$this->dbforge->drop_table('extra_subsites');
		$this->dbforge->drop_table('sub_auth');
	}
	
	
	//-------------------------------------------------------------------------------------------------
	private function createSubsite(){
		$this->dbforge->add_field(array(
			'sub_name'=> array(
				'type' => 'VARCHAR',
				'constraint' => 128
			),
			'sub_dir'=> array(
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
		
		$this->dbforge->add_key('sub_dir', true);
		$this->dbforge->create_table('extra_subsites');
	}
	//----------------------------------------------------------------------------------------------------
	private function createSubAuth(){
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 12,
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			'sub_dir'=> array(
				'type' => 'VARCHAR',
				'constraint' => 128
			),
			'user_id' => array(
				'type' => 'INT',
				'constraint' => 12,
				'unsigned' => TRUE,
			),
			'created' => array(
				'type' => 'DATETIME',
				'null'=> FALSE,
			),
			'author_id' => array(
				'type' => 'INT',
				'constraint' => 12,
				'unsigned' => TRUE,
			)
		));
		
		$this->dbforge->add_key('id', true);
		$this->dbforge->create_table('sub_auth');
	}
}