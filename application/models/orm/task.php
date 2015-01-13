<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Task extends DataMapper
{
	public $has_one = array ('iteration','tag','chat','contact');
	public static $statuses = array('New','Assigned','Stopped','Finished');
	public static $priorities = array('Medium','High','Low');

	public $default_order_by = array('date' => 'desc');

	public static $inline_fields = array('start_date','estimated','iteration_id');

	function __construct($id = NULL)
	{
		parent::__construct($id);
	}

	function delete_deep()
	{
		$this->tag->delete();
		$this->chat->delete_deep();
		$this->time_tracker->delete_deep();

		return $this->delete();
	}

	function on_time ($deadline)
	{
		if ($this->status == 'Finished')
			return true;
	
		if ($this->status == 'Assigned')
			return time() > $deadline;

		return time() + $this->estimated > $deadline;
	}
}

/* End of file */
