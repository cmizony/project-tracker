<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Time_interval extends DataMapper
{
	public $has_one = array ('time_tracker','contact');
	public $cascade_delete = FALSE;

	public $default_order_by = array('start' => 'desc');

	function __construct($id = NULL)
	{
		parent::__construct($id);
	}

	function is_valid ($account_id)
	{
		if (!$this->time_tracker->is_valid($account_id))
			return false;

		// Manager
		if ($account_id == -1)
			return true;

		// Contact
		return ($this->contact_id == $account_id);
	}

	function related_resource ()
	{
		return $this->time_tracker->related_resource();
	}
}

/* End of file */
