<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cli_db extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		if (!$this->input->is_cli_request())
			exit;
	}

	public function migrate_current ()
	{
		$this->load->library('migration');
		$this->migration->current();
	}

	public function migrate_latest ()
	{
		$this->load->library('migration');
		$this->migration->latest();
	}

	public function migrate_to ($version)
	{
		$this->load->library('migration');
		$this->migration->version($version);
	}

	public function generate_migration($tables = NULL)
	{
		$this->load->library('VpxMigration');

		$nb_args = func_num_args();
		if ($nb_args > 0)
		{
			$tables = array();
			$arg_list = func_get_args();
			for ($i = 0; $i < $nb_args; $i++)
				array_push($tables,$arg_list[$i]);
		}

		$file = $this->vpxmigration->generate($tables);
		echo $file;
	}
}
