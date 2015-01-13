<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Migration for release 0.1.1
 *
 * Rename table tasks to iterations
 * Rename table subtasks to tasks
 * Rename table clients to contacts
 * Rename table cards to threads
 * Rename FK contact_id to beneficiary_id
 * Add role field to contact (default Observer)
 */
class Migration_release_0_1_1 extends CI_Migration {

	public function up () 
	{
		// 1. Rename Tasks table to iteration
		$this->dbforge->rename_table('tasks', 'iterations');
		$this->dbforge->modify_column('subtasks', array('task_id' => array(
			'name' => 'iteration_id',
			'type' => 'INT')));
		$this->dbforge->rename_table('subtasks', 'tasks');

		// 2. Rename Clients table to contact
		$this->dbforge->rename_table('clients', 'contacts');
		$this->dbforge->modify_column('activities', array('client_id' => array(
			'name' => 'contact_id',
			'type' => 'INT')));
		$this->dbforge->modify_column('projects', array('client_id' => array(
			'name' => 'contact_id',
			'type' => 'INT')));

		// 3. Rename Cards to thread
		$this->dbforge->rename_table('cards', 'threads');

		// 4. Add role field to contact
		$field = array(
			'role' => array(
				'type' => 'VARCHAR',
				'constraint' => '100',
				'default' => 'observer',
			),
		);
		$this->dbforge->add_column('contacts', $field);
		
		//5 Rename FK contact_id to beneficiary_id
		$this->dbforge->modify_column('projects', array('contact_id' => array(
			'name' => 'beneficiary_id',
			'type' => 'INT')));
		
		//6 Create link n-n project contact
		$this->dbforge->add_field("`project_id` int(11) NOT NULL");
		$this->dbforge->add_key("project_id",true);
		$this->dbforge->add_field("`contact_id` int(11) NOT NULL");
		$this->dbforge->add_key("contact_id",true);
		$this->dbforge->add_field("`role` varchar(100) NULL ");
		$this->dbforge->create_table("stakeholders", TRUE);
		$this->db->query('ALTER TABLE  `messages` ENGINE = MyISAM');
	}	

	public function down () 
	{
		//6 Create link n-n project contact
		$this->dbforge->drop_table("stakeholders", TRUE);

		//5 Rename FK contact_id to beneficiary_id
		$this->dbforge->modify_column('projects', array('beneficiary_id' => array(
			'name' => 'contact_id',
			'type' => 'INT')));

		// 4. Remove role field to contact
		$this->dbforge->drop_column('contacts', 'role');	

		// 3. Rename Cards to thread
		$this->dbforge->rename_table('threads', 'cards');

		// 2. Rename Clients table to contact
		$this->dbforge->rename_table('contacts','clients');
		$this->dbforge->modify_column('activities', array('contact_id' => array(
			'name' => 'client_id',
			'type' => 'INT')));
		$this->dbforge->modify_column('projects', array('contact_id' => array(
			'name' => 'client_id',
			'type' => 'INT')));

		// 1. Rename Tasks table to iteration
		$this->dbforge->rename_table('tasks', 'subtasks');
		$this->dbforge->modify_column('subtasks', array('iteration_id' => array(
			'name' => 'task_id',
			'type' => 'INT')));
		$this->dbforge->rename_table('iterations', 'tasks');
		
	}	
}
