<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_sub_contacts_database extends CI_Migration {

	//This migration is adding the last piece of the puzzle to allow each 
	//subdirectory to manage its own social media presence
	//It also renames the subdirectory table and removes the unused static database
	//as it was ineffective for this new model

	public function up()
	{
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
			'facebook_url'=>array(
				'type' => 'VARCHAR',
				'constraint' => 128
			),
			'youtube_url'=>array(
				'type' => 'VARCHAR',
				'constraint' => 128
			),
			'twitter_url'=>array(
				'type' => 'VARCHAR',
				'constraint' => 128
			),
			'tumblr_url'=>array(
				'type' => 'VARCHAR',
				'constraint' => 128
			),
			'body'=>array(
				'type' => 'text',
				'null' => FALSE,				
			),
			'logoID'=>array(
				'type' => 'INT',
				'constraint' => 12,
				'unsigned' => TRUE
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
			'forSection' => array(
				'type' => 'VARCHAR',
				'constraint' => 128,
				'null'=> FALSE
			),
			'exclusiveSection'=>array(
				'type' => 'TINYINT',
				'constraint' => 1,
				'default' => 0
			)
		));
		$this->dbforge->add_key('id', true);
		$this->dbforge->add_key('sub_dir');
		$this->dbforge->create_table('sub_info_database', TRUE);
		
		//conditional to handle odd case where this needs to be spun back up but database still exists
		if ($this->db->table_exists('extra_subsites')){
			$this->dbforge->drop_table('subsite_database', TRUE);
			$this->dbforge->rename_table('extra_subsites', 'subsite_database');
		}
		$this->dbforge->drop_table('static_database', TRUE);
	}

	public function down()
	{
		$this->dbforge->drop_table('sub_info_database', TRUE);
		if ($this->db->table_exists('subsite_database')){$this->dbforge->rename_table('subsite_database', 'extra_subsites');}
	}

	
}