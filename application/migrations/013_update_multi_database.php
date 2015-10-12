<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_update_multi_database extends CI_Migration {

	//This migration adds the functionality for each article/item to be tied to a specific project
	//either in addition to or exclusively, allowing multiple areas to be separated in the site 

	public function up()
	{
		
		
		$fields= array(
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
		);
		
		
		$this->dbforge->add_column('news_database', $fields);
		$this->dbforge->add_column('media_database', $fields);
		$this->dbforge->add_column('static_database', $fields);
		$this->dbforge->add_column('contacts_database', $fields);
	}

	public function down()
	{
		$this->dbforge->drop_column('news_database', 'forSection');
		$this->dbforge->drop_column('news_database', 'exclusiveSection');
		$this->dbforge->drop_column('media_database', 'forSection');
		$this->dbforge->drop_column('media_database', 'exclusiveSection');
		$this->dbforge->drop_column('static_database', 'forSection');
		$this->dbforge->drop_column('static_database', 'exclusiveSection');
		$this->dbforge->drop_column('contacts_database', 'forSection');
		$this->dbforge->drop_column('contacts_database', 'exclusiveSection');
	}
}