<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MY_Controller {

	public function index()
	{
		if ($this->is_log)
			$this->_redirect('projects');

		array_push($this->css,'login.css');

		$this->data['destination']=$this->input->get('dest');
		$this->data['company']=$this->config->item('company');

		$this->breadcrumb->reset();
		$this->_render('welcome','FULL');
	}

	public function terms_and_conditions ()
	{
		$this->breadcrumb->push('Terms & Conditions','legal');
		$this->_render('terms_and_conditions.php','FULL');
	}
	
	public function change_log ()
	{
		$this->breadcrumb->push('Change Log','dropbox');
		$this->_render('change_log','FULL');
	}
}

/* End of file */
