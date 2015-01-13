<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stakeholder extends DataMapper
{
	public $has_one = array ('project','contact');

	function __construct($id = NULL)
	{
		parent::__construct($id);
	}
}

/* End of file */
