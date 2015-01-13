<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Project extends DataMapper
{
	public $has_many = array ('ticket','iteration','thread','stakeholder');
	public $has_one = array (
		'beneficiary' => array (
			'class' => 'contact',
			'other_field' => 'owned_project', 
		),
		'folder');

	public static $statuses = array('New','In Progress','Stopped','Finished');
	
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

		// Contact
		$projects = new Project();
		$projects->where("id",$this->id);
		$projects->where("beneficiary_id",$account_id);
		$projects->get();
		return $projects->result_count() >= 1;
	}

	function delete_deep()
	{
		foreach ($this->iterations as $iteration)
			$iteration->delete();
		foreach ($this->tickets as $ticket)
			$ticket->delete_deep();
		foreach ($this->stakeholders as $stakeholder)
			$stakeholder->delete();

		$this->chat->delete_deep();

		return $this->delete();
	}
}

/* End of file */
