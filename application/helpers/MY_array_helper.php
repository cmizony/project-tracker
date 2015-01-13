<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('get_sub_array'))
{
	function get_sub_array($object,$property)
	{
		$sub_array = array();
		foreach ($object as $element)
			if (!empty($element->$property))
				array_push($sub_array,$element->$property);
		return $sub_array;
	}
}
