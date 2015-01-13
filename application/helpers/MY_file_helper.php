<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('humanize_file'))
{
	function humanize_file($size)
	{
		$mod= 1024;

		$units = array('KB','MB','GB');
		for ($i=0 ; $size > $mod ; $i++)
			$size /= $mod;

		return round($size,2).' '.$units[$i];
	}
}
