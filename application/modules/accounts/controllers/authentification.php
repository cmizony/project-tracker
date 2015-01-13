<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Authentification extends MY_Controller {

	public function index()
	{
		show_404();
	}

	public function verify ()
	{
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		$destination = $this->input->post('destination');
		$root_account = $this->config->item('root_account');

		// Owner account
		if ($username == $root_account['login'] AND
			$password == $root_account['password'])
		{
			$this->session->set_userdata('accountid',-1);

			if (!$destination)
				$destination = '/';

			$this->acl->load_privileges('manager');

			$this->_redirect(site_url($destination),TRUE);
		}

		// Contact account
		$this->load->helper('security');

		$contact = new Contact();
		$contact->where('login',$username);
		$contact->where('password',secure_hash($password));
		$contact->get();

		if ($contact->exists() AND
			$contact->flag_lock == 0)
		{
			$this->session->set_userdata('accountid',$contact->id);

			if (!$destination)
				$destination = 'projects';

			$this->account_id = $contact->id;
			$this->acl->load_privileges($contact->role);

			$this->_log($contact->id,'Login',"Login contact $contact->login","accounts/view/$contact->id");
			$this->_redirect(site_url($destination),TRUE);
		}

		if ($contact->flag_lock==1)
			$this->_alert('Your account is locked','warning');
		else
			$this->_alert('Account not valid','error');

		$this->_redirect('welcome');
	}

	public function logout ()
	{
		$this->session->sess_destroy();
		$this->_redirect('welcome');
	}
}

/* End of file */
