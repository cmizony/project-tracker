<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author		Camille Mizony
 * @copyright	Copyright (c) 2013, Camille Mizony
 * @filesource
 */

// ------------------------------------------------------------------------

class Acl
{
	private static $CI = NULL;

	// Cummulativ privileges in Order
	public $default_roles = array (
		'observer' => array (
		),
		'reporter' => array (
			'create_tickets'	=> TRUE,
			'create_thread'		=> TRUE,
			// TODO Add privilege "add file" - custom
			// TODO Add privilege "view_iteration" - custom
		),
		'member' => array (
			'internal_access'	=> TRUE,
			'manage_tickets'	=> TRUE,
			'manage_iterations'	=> TRUE,
		),
		'manager' => array (
			'manage_projects'	=> TRUE,
			'manage_contacts'	=> TRUE,
		),
	);

	public function __construct()
	{
		if (is_null(Acl::$CI))
			Acl::$CI =& get_instance();
	}

	public function has_privilege ($privilege)
	{
		// TODO cache session array
		$privileges = Acl::$CI->session->userdata('privileges');

		if (!$privileges)
			return FALSE;

		return (in_array($privilege,$privileges));
	}

	public function get_roles ()
	{
		// TODO check custom config

		return (array_keys($this->default_roles));
	}

	public function load_privileges ($user_role)
	{
		// TODO check custom config
		$roles = $this->default_roles;

		if (!array_key_exists($user_role,$roles))
			return FALSE;

		$user_privileges = array();

		foreach ($roles as $role => $privileges)
		{
			foreach ($privileges as $privilege => $activated)
				if ($activated)
					array_push($user_privileges,$privilege);

			if ($role == $user_role)
				break;
		}

		Acl::$CI->session->set_userdata('privileges',$user_privileges);
	}
}
