<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Migration for release 0.2
 *
 * Add Field start_date to Iterations
 * Add Field start_date to Tasks
 * Add Field estimated to Tasks
 * Add Field number to Iterations
 * Add Field end_date to projects
 * Drop price to projects
 */
class Migration_release_0_2 extends CI_Migration {

	public function up ()
	{	
		// 1. Add start_date to Iterations
		$this->dbforge->add_column('iterations',
			array(
				'start_date' => array(
					'type' => 'datetime',
					'null' => TRUE,
		)));

		// 2. Add start_date to Tasks
		$this->dbforge->add_column('tasks',
			array(
				'start_date' => array(
					'type' => 'datetime',
					'null' => TRUE,
		)));

		// 3. Add estimated to Tasks
		$this->dbforge->add_column('tasks',
			array(
				'estimated' => array(
					'type' => 'INT',
		)));

		// 4. Add number to Iterations
		$this->dbforge->add_column('iterations',
			array(
				'number' => array(
					'type' => 'INT',
		)));

		// 4. Add End date to Projects
		$this->dbforge->add_column('projects',
			array(
				'end_date' => array(
					'type' => 'datetime',
					'null' => TRUE,
		)));
		
		// 5. Drop price to projects
		$this->dbforge->drop_column('projects', 'price');	
	}	

	public function down () 
	{
		// 1. Add start_date to Iterations
		$this->dbforge->drop_column('iterations', 'start_date');	
		// 2. Add start_date to Tasks
		$this->dbforge->drop_column('tasks', 'start_date');	
		// 3. Add estimated to Tasks
		$this->dbforge->drop_column('tasks', 'estimated');	
		// 4. Add number to Iterations
		$this->dbforge->drop_column('iterations', 'number');	
		// 4. Add End date to Projects
		$this->dbforge->drop_column('projects', 'end_date');	
		// 5. Drop price to projects
		$this->dbforge->add_column('projects',
			array(
				'price' => array(
					'type' => 'varchar',
					'constraint' => '45',
					'null' => TRUE,
		)));
	}	
}
