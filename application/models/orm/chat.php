<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Chat extends DataMapper
{
	public $has_many = array ('task','ticket','message','thread');

	// Exclusitivty constraint
	public $x_constraint = array ('tasks','tickets','threads');

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
		foreach ($this->messages as $message)
			$message->delete();

		return $this->delete();
	}
}

/* End of file */
