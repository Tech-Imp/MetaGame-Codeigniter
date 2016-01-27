<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_update_multi_article_types extends CI_Migration {

	//This migration adds the functionality for articles and news to be their own categoies of written media
	//News is for shorter posts that may be updated infrequently as we have announcements
	//Articles is for indepth pieces, at least this is the going plan as of the start of 2016 

	public function up()
	{
		
		
		$fields= array(
			'type' => array(
				'type' => 'VARCHAR',
				'constraint' => 30,
				'null'=> FALSE
			)
		);
		
		
		$this->dbforge->add_column('news_database', $fields);
	}

	public function down()
	{
		$this->dbforge->drop_column('news_database', 'type');
	}
}