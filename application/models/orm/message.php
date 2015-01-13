<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Message extends DataMapper
{
	public $has_one = array ('chat','contact');
	public $cascade_delete = FALSE;

	public $default_order_by = array('date' => 'desc');

	function __construct($id = NULL)
	{
		parent::__construct($id);
	}

	function is_valid ($account_id)
	{
		if (!$this->chat->is_valid($account_id))
			return false;

		// Manager
		if ($account_id == -1 AND $this->contact_id != NULL)
			return false;

		// Contact
		if ($account_id != -1 AND $this->contact_id != $account_id)
			return false;

		return true;
	}
}

/* End of file */
