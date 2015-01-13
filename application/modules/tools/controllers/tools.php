<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author		Camille Mizony
 * @copyright	Copyright (c) 2013, Camille Mizony
 * @filesource
 */

// ------------------------------------------------------------------------

class Tools extends MY_Controller
{
	public function index ()
	{
		show_404();
	}

	public function breadcrumb ()
	{
		if (!$this->input->is_ajax_request())
			show_404();

		$url = $this->input->post('url');
		$breadcrumb = $this->breadcrumb->display($url);

		echo $breadcrumb;
	}
}
