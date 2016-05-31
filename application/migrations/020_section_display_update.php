<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_section_display_update extends CI_Migration {

	//This migration columns to allow certain sections to display in quick links per section
     //It also adds the modified by column to important tables that rely upon a user having ownership
     
	public function up()
	{
	     $time=array(
               'modified' => array(
                    'type' => 'DATETIME',
                    'null'=> FALSE,
               ),
          );
		$mod= array(
			'modified_by' => array(
				'type' => 'INT',
				'constraint' => 12,
				'null'=> FALSE,
			)
		);
		$visible=array(
			'forSection' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 128,
                    'null'=> FALSE
               )
		);
          // Add the modified time column
          $this->dbforge->add_column('subsite_database', $time);
		//Add modified by colum
		$this->dbforge->add_column('news_database', $mod);
          $this->dbforge->add_column('media_database', $mod);
          $this->dbforge->add_column('subsite_database', $mod);
          $this->dbforge->add_column('sub_info_database', $mod);
          $this->dbforge->add_column('contacts_database', $mod);
          //Add column to separate quicklink visibility
		$this->dbforge->add_column('subsite_database', $visible);
	}

	public function down()
	{
          // Remove the modified time column
          $this->dbforge->drop_column('subsite_database', "modified");
          //Remove modified by column
          $this->dbforge->drop_column('news_database', "modified_by");
          $this->dbforge->drop_column('media_database', "modified_by");
          $this->dbforge->drop_column('subsite_database', "modified_by");
          $this->dbforge->drop_column('sub_info_database', "modified_by");
          $this->dbforge->drop_column('contacts_database', "modified_by");
          //Remove visibility params for quicklinks
          $this->dbforge->drop_column('subsite_database', "forSection");
	}

	
}