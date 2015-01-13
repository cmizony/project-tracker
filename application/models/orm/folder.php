<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Folder extends DataMapper
{
	public $has_many = array ('file','project','iteration','ticket','contact');

	// Exclusitivty constraint
	public $x_constraint = array ('projects','iterations','tickets','contacts');

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
		// Owner
		if ($account_id == -1)
			return true;

		// Contact
		foreach ($this->x_constraint as $constraint)
			foreach ($this->$constraint as $object)
				if ($object->is_valid($account_id))
					return true;

		return false;
	}
}

/* End of file */
