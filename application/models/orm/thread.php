<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Thread extends DataMapper
{
	public $has_one = array ('project','chat');

	function __construct($id = NULL)
	{
		parent::__construct($id);
	}

	function is_valid ($account_id)
	{
		// Manager
		if ($account_id == -1)
			return true;

		return $this->project->is_valid($account_id);
	}


	function thumbnail_exists()
	{
		$folder_path = FILES."threads/p$this->project_id/";

		return is_file($folder_path.$this->thumbnail)?
			$folder_path.$this->thumbnail:
			FALSE;
	}	

	function delete_deep()
	{
		$this->chat->delete_deep();

		return $this->delete();
	}
}

/* End of file */
