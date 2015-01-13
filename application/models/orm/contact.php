<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contact extends DataMapper
{
	public $has_many = array (
		'activity',
		'task',
		'message',
		'file',
		'stakeholder',
		'time_interval',
		'owned_project' => array (
			'class' => 'project',
			'other_field' => 'beneficiary', 
		)
	);
	public $has_one = array ('folder');

	public $default_order_by = array('date' => 'desc');

	public static $inline_fields = array('note');

	function __construct($id = NULL)
	{
		parent::__construct($id);
	}

	function delete_deep ()
	{
		foreach ($this->activity as $activity)
			$activity->delete();
		foreach ($this->stakeholders as $stakeholder)
			$stakeholder->delete();

		return $this->delete();
	}

	function is_valid ($account_id)
	{
		// Manager
		if ($account_id == -1)
			return true;

		return false;
	}
}

/* End of file */
