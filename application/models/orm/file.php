<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class File extends DataMapper
{
	public $has_one = array ('folder','contact');

	public $default_order_by = array('date' => 'desc');

	function __construct($id = NULL)
	{
		parent::__construct($id);
	}

	function icon()
	{
		$icon = str_replace('.','',$this->type) . ".png";
		$path = IMAGES."file_ext/$icon";

		if (!file_exists($path))
			return IMAGES."file_ext/_blank.png";
		
		return $path;
	}

	function is_valid ($account_id)
	{
		return $this->folder->is_valid($account_id);
	}
}

/* End of file */
