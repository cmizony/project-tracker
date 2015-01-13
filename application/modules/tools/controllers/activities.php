<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Activities extends MY_Controller {

	public function index($object = NULL, $id = NULL)
	{
		if (!$this->acl->has_privilege('internal_access') OR
			(!$this->input->is_ajax_request() AND
			!$this->is_intern_call))
			show_404();

		$activities = new Activity();
		$activities->include_related("contact",array("login","name"));

		switch ($object)
		{
		case 'project':
			$project = new Project ($id);
			if (!$project->exists())
				show_404();

			$this->load->helper('array');

			$contacts = new Contact();
			$contacts->where_related('stakeholder','project_id',$id);
			$contacts->get();
			$contacts_id = get_sub_array($contacts,"id");
			$activities->where_in('contact_id',$contacts_id);
			break;
		case NULL:
			break;
		default:
			show_404();
		}

		$activities->get();

		$this->data['activities']=$activities;

		array_push($this->javascript,
			'libs/moment.min.js',
			'libs/d3.v3.min.js',
			'libs/d3.legend.js',
			'charts/activities_bar.js'
		);

		array_push($this->css,'charts/activities_bar.css');

		$this->_render('tools/activities/index.php');
	}

	public function view ($value = NULL, $display = "uri")
	{
		if (!$this->is_intern_call OR
			!in_array($display,array('uri','contact')))
			show_404();

		$activities = new Activity();	

		switch ($display)
		{
			case 'uri':
				if (is_null($value))
					$value = uri_string();
				$activities->where('uri_link',$value);
				break;
			case 'contact':
				$activities->where('contact_id',$value);
				break;
		}
		$activities->include_related("contact",array("login","name"));
		$activities->get();
		
		$this->data['activities']=$activities;

		$this->_render('tools/activities/view.php');
	}
}

/* End of file */
