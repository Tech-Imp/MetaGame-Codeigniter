<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_text_content_media_update extends CI_Migration {

	//This migration adds the ability for media items to have text blurbs
     
	public function up()
	{
	     $blurb=array(
               'body'=>array(
                    'type' => 'text',
                    'null' => FALSE,
               ),
          );
		
		//Add modified by colum
          $this->dbforge->add_column('media_database', $blurb);
	}

	public function down()
	{
          $this->dbforge->drop_column('media_database', "body");
	}

	
}