<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tag extends DataMapper
{
	public $has_many = array ('ticket','task');
	public static $colors = array('red','orange','yellow','green','blue','gray');

	// Exclusitivty constraint
	public $x_constraint = array ('ticket','task');

	// Exclusitivty link
	function x_link ()
	{
		foreach ($this->x_constraint as $table)
			if ($this->$table->exists())
				return $table;
		return NULL;
	}

	function __construct($id = NULL)
	{
		parent::__construct($id);
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
