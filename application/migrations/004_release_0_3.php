<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Migration for release 0.3
 *
 * Rename FK contact on Messages
 * Delete FK chat for Projects
 * Add FK Chat for tasks
 * Add FK Contact for files
 * Add FK Contact for Tasks
 * Delete project note
 * Rename task note
 * Add desc to contact
 */
class Migration_release_0_3 extends CI_Migration {

	public function up ()
	{
		// 1. Rename FK Contact on Messages
		$this->dbforge->modify_column('messages', array('client_id' => array(
			'name' => 'contact_id',
			'null' => TRUE,
			'type' => 'INT')));
		
		// 2. Delete FK chat for projects
		$projects = new Project();
		$projects->get();
		foreach ($projects as $project)
			if ($project->chat)
				$project->chat->delete_deep();
		$this->dbforge->drop_column('projects', 'chat_id');
		
		// 3. Add chat to tasks
		$this->dbforge->add_column('tasks', array('chat_id' => array(
			'type' => 'INT')));
		$tasks = new Task();
		$tasks->get();
		foreach ($tasks as $task)
		{
			$chat = new Chat();
			$chat->flag_lock=0;
			$chat->save();
			$task->chat_id = $chat->id;
			$task->save();
		}

		// 4. Add FK contact for files
		$this->dbforge->add_column('files', array('contact_id' => array(
			'type' => 'INT')));
		
		// 5. Add FK Contact for Tasks
		$this->dbforge->modify_column('tasks', array('responsible' => array(
			'name' => 'contact_id',
			'null' => TRUE,
			'type' => 'INT')));
		
		// 6. Delete Project note
		$this->dbforge->drop_column('projects', 'note');
		
		// 7. Rename task note
		$this->dbforge->modify_column('tasks', array('note' => array(
			'name' => 'description',
			'type' => 'TEXT',
			'null' => TRUE)));
		
		// 8. Add desc to contact
		$this->dbforge->add_column('contacts', array('description' => array(
			'type' => 'TEXT',
			'null' => TRUE)));
		
	}	

	public function down () 
	{
		// 1. Rename FK Contact on Messages
		$this->dbforge->modify_column('messages', array('contact_id' => array(
			'name' => 'client_id',
			'null' => TRUE,
			'type' => 'INT')));
		
		// 2. Delete FK chat for projects
		$this->dbforge->add_column('projects', array('chat_id' => array(
			'type' => 'INT')));
		$projects = new Project();
		$projects->get();
		foreach ($projects as $project)
		{
			$chat = new Chat();
			$chat->flag_lock=0;
			$chat->save();
			$project->chat_id = $chat->id;
			$project->save();
		}

		// 3. Add chat to tasks
		$tasks = new Task();
		$tasks->get();
		foreach ($tasks as $task)
			if ($task->chat)
				$task->chat->delete_deep();
		$this->dbforge->drop_column('tasks', 'chat_id');
		
		// 4. Add FK contact for files
		$this->dbforge->drop_column('files', 'contact_id');
		
		// 5. Add FK Contact for Tasks
		$this->dbforge->modify_column('tasks', array('contact_id' => array(
			'name' => 'responsible',
			'null' => TRUE,
			'type' => 'VARCHAR',
			'constraint' => 45)));
		
		// 6. Delete Project note
		$this->dbforge->add_column('projects', array('note' => array(
			'type' => 'TEXT',
			'null' => TRUE)));
		
		// 7. Rename task note
		$this->dbforge->modify_column('tasks', array('description' => array(
			'name' => 'note',
			'type' => 'TEXT',
			'null' => TRUE)));
		
		// 8. Add desc to contact
		$this->dbforge->drop_column('contacts', 'description');
	}	
}
