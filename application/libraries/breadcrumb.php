<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author		Camille Mizony
 * @copyright	Copyright (c) 2013, Camille Mizony
 * @filesource
 */

// ------------------------------------------------------------------------

class Breadcrumb
{
	private static $CI = NULL;

	public function __construct()
	{
		if (is_null(Breadcrumb::$CI))
			Breadcrumb::$CI =& get_instance();
	}

	public function push ($name,$icon = NULL,$ttl = NULL)
	{
		if (Breadcrumb::$CI->input->is_ajax_request())
			return;

		Breadcrumb::$CI->load->helper('text');

		$url = current_url();

		if (is_null($ttl))
			$ttl = PHP_INT_MAX;

		$crumb = array();
		$crumb['url'] = $url;
		$crumb['full_name'] = $name;
		$crumb['short_name'] = character_limiter($name,20);
		$crumb['icon'] = $icon;
		$crumb['ttl'] = $ttl;

		$breadcrumb = Breadcrumb::$CI->session->userdata('breadcrumb');

		if (!$breadcrumb)
			$breadcrumb=array($crumb);
		else
			array_push($breadcrumb,$crumb);

		Breadcrumb::$CI->session->set_userdata('breadcrumb',$breadcrumb);
	}

	private function unshift ($breadcrumb, $url)
	{
		for ($i = 0 ; $i < count($breadcrumb) ; $i ++)
			if ($breadcrumb[$i]['url'] == $url)
				return array_slice($breadcrumb,0,$i+1);
		return $breadcrumb;
	}

	public function reset() 
	{
		Breadcrumb::$CI->session->unset_userdata('breadcrumb');
	}

	public function root()
	{
		$breadcrumb = Breadcrumb::$CI->session->userdata('breadcrumb');

		if (!$breadcrumb OR
			count($breadcrumb) < 1)
			return;

		return $breadcrumb[0]['short_name'];
	}

	public function display($url)
	{
		$breadcrumb = Breadcrumb::$CI->session->userdata('breadcrumb');

		if (!$breadcrumb)
			return;

		$url_exists = FALSE;
		foreach ($breadcrumb as $crumb)
			if ($crumb['url'] == $url AND
				$crumb['ttl'] > 0)
				$url_exists = TRUE;
		if (!$url_exists)
			return;
			
		$breadcrumb = $this->unshift($breadcrumb,$url);

		$out='<ul class="breadcrumb">';

		for ($i = 0 ; $i < count($breadcrumb)-1 ; $i++)
		{
			if ($breadcrumb[$i]['ttl'] <= 0)
				continue;

			$out .= '<li>';
			$out .= '<a href="'.$breadcrumb[$i]['url'].'">';
			$out .= '<span title="'.$breadcrumb[$i]['full_name'].'">';

			if (!is_null($breadcrumb[$i]['icon']))
				$out .= '<i class="fa fa-'.$breadcrumb[$i]['icon'].'"></i> ';

			$out .= $breadcrumb[$i]['short_name'];
			$out .= '</span>';
			$out .= '</a>';
			$out .= '</li>';
			$breadcrumb[$i]['ttl'] -= 1;
		}

		// Last active crumb
		if (count($breadcrumb) > 0)
		{
			$last = count($breadcrumb)-1;

			$out .= '<li class="active">';
			$out .= '<span title="'.$breadcrumb[$last]['full_name'].'">';
			
			if (!is_null($breadcrumb[$i]['icon']))
				$out .= '<i class="fa fa-'.$breadcrumb[$i]['icon'].'"></i> ';

			$out .= $breadcrumb[$last]['short_name'];
			$out .= '</span>';
			$out .= '</li>';
			$breadcrumb[$last]['ttl'] -= 1;
		}

		$out .= '</ul>';

		Breadcrumb::$CI->session->set_userdata('breadcrumb',$breadcrumb);

		return $out;
	}
}
