<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * * Session Class Extension
 * */
class MY_Session extends CI_Session {

	protected $_CI;

	/*
	 * * Do not update an existing session on ajax calls
	 * *
	 * * @access    public
	 * * @return    void
	 * */
	function sess_update() {

		$this->_CI =& get_instance();

		if ( !$this->_CI->input->is_ajax_request() ){
			parent::sess_update();
		}
	}
}
