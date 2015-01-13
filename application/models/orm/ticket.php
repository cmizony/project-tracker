<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ticket extends DataMapper
{
	public $has_one = array ('folder','project','chat','time_tracker','tag');

	public static $types = array('Bug','Feature','Support');
	public static $priorities = array('Low','Medium','High','Critical');
	public static $statuses = array('Open','In Discussion','In Progress','Closed','Resolved');

	public $default_order_by = array('date' => 'desc');

	public static $inline_fields = array('note');

	function __construct($id = NULL)
	{
		parent::__construct($id);
	}

	function is_valid ($account_id)
	{
		// Manager
		if ($account_id == -1)
			return true;

		return $this->project->is_valid($account_id);
	}

	function delete_deep()
	{
		$this->time_tracker->delete_deep();
		$this->chat->delete_deep();
		$this->tag->delete();

		return $this->delete();
	}
}

/* End of file */
