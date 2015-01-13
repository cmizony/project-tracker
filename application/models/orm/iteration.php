<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Iteration extends DataMapper
{
	public $has_one = array ('project','folder','time_tracker');
	public $has_many = array ('task');

	public static $statuses = array('New','In Progress','Stopped','Finished');
	public static $units = array(
		'min' => 60,
		'hour' => 3600,
		'day' => 86400,
		'week' => 604800,
		'month' => 2678400);

	public $default_order_by = array('date' => 'desc');

	function __construct($id = NULL)
	{
		parent::__construct($id);
	}

	function delete_deep ()
	{
		foreach ($this->tasks as $task)
			$task->delete();

		return $this->delete();
	}

	function is_valid ($account_id)
	{
		// Manager
		if ($account_id == -1)
			return true;

		return $this->project->is_valid($account_id);
	}

}

/* End of file */
