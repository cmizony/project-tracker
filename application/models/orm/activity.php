<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Activity extends DataMapper
{
	public $has_one = array ('contact');

	public $table = 'activities';

	public $default_order_by = array('date' => 'desc');

	function __construct($id = NULL)
	{
		parent::__construct($id);
	}
}

/* End of file */
