<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_ambig_fix extends CI_Migration {

	//This migration fixes an ambiguity issue concerning primary keys

	public function up()
	{
		
		$this->dbforge->modify_column('subsite_database', $this->fixID("subsite_id"));
		
		$this->dbforge->modify_column('sub_auth', $this->fixID("subauth_id"));
		
		$this->dbforge->modify_column('sub_info_database', $this->fixID("info_id"));
	}

	public function down()
	{
		$this->dbforge->modify_column('subsite_database', $this->revertToID("subsite_id"));
		
		$this->dbforge->modify_column('sub_auth', $this->revertToID("subauth_id"));
		
		$this->dbforge->modify_column('sub_info_database', $this->revertToID("info_id"));
	}
	//Internal functions to swap the id key back and forth for naming
	private function fixID($newName="id"){
		$update = array(
                'id' => array(
                 	'name' => $newName,
                 	'type' => 'INT',
                 	'constraint' => 12,
					'unsigned' => TRUE,
					'auto_increment' => TRUE
        	)
		);
		return $update;
	}
	private function revertToID($oldName='id'){
		$update = array(
                $oldName => array(
                 	'name' => 'id',
                 	'type' => 'INT',
                 	'constraint' => 12,
					'unsigned' => TRUE,
					'auto_increment' => TRUE
        	)
		);
		return $update;
	}
}