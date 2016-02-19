<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_update_profile_avatars extends CI_Migration {

	//This migration adds the functionality for the static database (profiles)
	//It adds the ability for a union to occur by referencing an item in the media database
	//it was done this way to reduce redundancy. Also renames unused column

	public function up()
	{
		$fields= array(
			'avatarID' => array(
				'type' => 'INT',
				'constraint' => 12,
				'unsigned' => TRUE
			)
		);
		$this->dbforge->add_column('contacts_database', $fields);
		
		$update = array(
                'stub' => array(
                 	'name' => 'profileName',
                 	'type' => 'VARCHAR',
                 	'constraint' => 120,
        	)
		);
		$this->dbforge->modify_column('contacts_database', $update);
	}

	public function down()
	{
		$this->dbforge->drop_column('contacts_database', 'avatarID');
		$update = array(
                'profileName' => array(
                 	'name' => 'stub',
                 	'type' => 'VARCHAR',
                 	'constraint' => 120,
        	)
		);
		$this->dbforge->modify_column('contacts_database', $update);
	}
}