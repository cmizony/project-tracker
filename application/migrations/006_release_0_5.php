<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Migration for release 0.5
 *
 */
class Migration_release_0_5 extends CI_Migration {

	public function up ()
	{
		// 1. Remove deadline tasks
		$this->dbforge->drop_column('tasks', 'deadline');

	}	

	public function down () 
	{
		// 1. Remove deadline tasks
		$this->dbforge->add_column('tasks', array('deadline' => array(
			'type' => 'DATETIME',
			'null' => TRUE)));
	}	
}
