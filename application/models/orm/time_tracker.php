<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Time_tracker extends DataMapper
{
	public $has_many = array ('iteration','ticket','time_interval');

	// Exclusitivty constraint
	public $x_constraint = array ('iterations','tickets');

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

		// Contact
		foreach ($this->x_constraint as $constraint)
			foreach ($this->$constraint as $object)
				if ($object->is_valid($account_id))
					return true;

		return false;
	}

	function delete_deep()
	{
		foreach ($this->time_intervals as $time_interval)
			$time_interval->delete();

		return $this->delete();
	}

	function related_resource ()
	{
		$x_link = $this->x_link();

		if (!is_null($x_link))
			return singular($x_link).'-'.$this->$x_link->id;
	}
}

/* End of file */
