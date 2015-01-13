<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Migration for release 0.4
 *
 */
class Migration_release_0_4 extends CI_Migration {

	public function up ()
	{
		// 1. Add label to tasks
		$this->dbforge->add_column('tasks', array('label' => array(
			'type' => 'VARCHAR',
			'constraint' => '100',
			'null' => TRUE)));

		// 2. Add priority to tasks
		$this->dbforge->add_column('tasks', array('priority' => array(
			'type' => 'VARCHAR',
			'constraint' => '45',
			'null' => TRUE)));

		// 3. Create Table time_trackers
		$this->dbforge->add_field("`id` int(11) NOT NULL auto_increment");
		$this->dbforge->add_key("id",true);
		$this->dbforge->add_field("`flag_lock` int(11) NULL ");
		$this->dbforge->create_table("time_trackers", TRUE);
		$this->db->query('ALTER TABLE  `time_trackers` ENGINE = MyISAM');
		
		// 4. Create Table time_intervals
		$this->dbforge->add_field("`id` int(11) NOT NULL auto_increment");
		$this->dbforge->add_key("id",true);
		$this->dbforge->add_field("`note` text NULL ");
		$this->dbforge->add_field("`start` datetime NULL ");
		$this->dbforge->add_field("`end` datetime NULL ");
		$this->dbforge->add_field("`time_tracker_id` int(11) NOT NULL ");
		$this->dbforge->add_field("`contact_id` int(11) NULL ");
		$this->dbforge->create_table("time_intervals", TRUE);
		$this->db->query('ALTER TABLE  `time_intervals` ENGINE = MyISAM');

		// 5. Create time_tracker instances
		$this->dbforge->add_column('iterations', array('time_tracker_id' => array('type' => 'INT','null' => TRUE)));
		$this->dbforge->add_column('tickets', array('time_tracker_id' => array('type' => 'INT','null' => TRUE)));
		$iterations = new Iteration();
		$iterations->get();
		foreach ($iterations as $iteration)
		{
			$time_tracker = new Time_tracker ();
			$time_tracker->flag_lock = 0;
			$time_tracker->save();
			$iteration->time_tracker_id = $time_tracker->id;
			$iteration->save();
		}
		$tickets = new Ticket();
		$tickets->get();
		foreach ($tickets as $ticket)
		{
			$time_tracker = new Time_tracker ();
			$time_tracker->flag_lock = 0;
			$time_tracker->save();
			$ticket->time_tracker_id = $time_tracker->id;
			$ticket->save();
		}

	}	

	public function down () 
	{
		// 1. Add label to tasks
		$this->dbforge->drop_column('tasks', 'label');

		// 2. Add priority to tasks
		$this->dbforge->drop_column('tasks', 'priority');

		// 3. Create Table time_trackers
		$this->dbforge->drop_table('time_trackers');

		// 4. Create Table time_intervals
		$this->dbforge->drop_table('time_intervals');
		
		// 5. Create time_tracker instances
		$this->dbforge->drop_column('iterations', 'time_tracker_id');
		$this->dbforge->drop_column('tickets', 'time_tracker_id');
	}	
}
