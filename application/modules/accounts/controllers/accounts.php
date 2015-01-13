<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Accounts extends MY_Controller {

	public function _remap($method, $params = array())
	{
		if (!$this->acl->has_privilege('internal_access'))
			show_404();

		if (method_exists($this, $method))
			return call_user_func_array(array($this, $method), $params);
		show_404();
	}

	public function index($object = NULL, $id = NULL)
	{
		if(!$this->acl->has_privilege('internal_access'))
			show_404();

		$contacts = new Contact();

		switch ($object)
		{
		case 'project':
			$project = new Project ($id);
			if (!$project->exists())
				show_404();

			$page_title = "Contacts associated to project $project->name";
			$contacts->where_related('stakeholder','project_id',$id);
			break;
		case NULL:
			$page_title = "All Contacts";
			$this->breadcrumb->reset();
			break;
		default:
			show_404();
		}
		$contacts->get();

		$this->data['rendered_logs'] = modules::run('tools/activities/index',$object,$id);
		$this->data['contacts']=$contacts;
		$this->data['manage_contacts'] = $this->acl->has_privilege('manage_contacts');
		$this->data['page_title'] = $page_title;

		$this->breadcrumb->push('Contacts','group');

		$this->_render('accounts/accounts/index.php','FULL');
	}

	public function link ($project_id = NULL)
	{
		$project = new Project($project_id);

		if (!$project->exists() OR
			!$this->acl->has_privilege('internal_access'))
			show_404();

		$exising_contacts = new Contact();
		$exising_contacts->select('id');
		$exising_contacts->where_related('stakeholder','project_id',$project_id);

		$contacts = new Contact();
		$contacts->where_not_in_subquery('id',$exising_contacts);
		$contacts->get();

		$stakeholders = new Stakeholder();
		$stakeholders->group_by('role');
		$stakeholders->get();
		$this->load->helper('array');

		$this->breadcrumb->push('Link','link',1);

		array_push($this->javascript,'libs/jquery/typeahead.jquery.js');

		$this->data['project'] = $project;
		$this->data['contacts'] = $contacts;
		$this->data['manage_contacts'] = $this->acl->has_privilege('manage_contacts');
		$this->data['roles']=json_encode(get_sub_array($stakeholders,'role'));

		$this->_render('accounts/accounts/link.php','FULL');
	}

	public function associate ($project_id = NULL)
	{
		$project = new Project($project_id);
		$contact_id = $this->input->post('contact');
		$contact = new Contact($contact_id);

		if (!$project->exists() OR
			!$contact->exists() OR
			!$this->acl->has_privilege('internal_access'))
			show_404();

		$stakeholder = new Stakeholder();
		$stakeholder->contact_id = $contact->id;
		$stakeholder->project_id = $project->id;
		$stakeholder->role = $this->input->post('role');
		$stakeholder->save();

		$this->_alert("Contact $contact->name associated",'success');
		$this->_log($task->id,'Create',"Link contact $contact->name to $project->name","projects/view/$project->id");

		$this->_redirect("projects/view/$project->id");
	}

	public function index_mini($project_id = NULL)
	{
		$project = new Project($project_id);

		if (!$project->exists() OR
			(!$this->acl->has_privilege('internal_access') AND
			!$project->is_valid($this->account_id)))
			show_404();

		$stakeholders = new Stakeholder();
		$stakeholders->where('project_id',$project_id);
		$stakeholders->include_related('contact',array('email','name','phone','company'));
		$stakeholders->get();

		$this->data['stakeholders'] = $stakeholders;
		$this->data['project'] = $project;
		$this->data['internal_access'] = $this->acl->has_privilege('internal_access');

		$this->_render('accounts/accounts/index_mini.php');
	}

	public function add ()
	{
		if (!$this->acl->has_privilege('manage_contacts'))
			show_404();

		$this->data['roles'] = $this->acl->get_roles();

		$this->breadcrumb->push('Add','plus',1);

		$this->_render('accounts/accounts/add.php','FULL');
	}

	public function edit_contact($id=NULL)
	{
		$contact = new Contact($id);

		if (!$contact->exists() OR
			!$this->input->is_ajax_request() OR
			!$this->acl->has_privilege('manage_contacts'))
			show_404();

		$this->data['roles'] = $this->acl->get_roles();
		$this->data['contact'] = $contact;

		$this->_render('accounts/accounts/edit_contact.php');
	}

	public function edit_description ($id=NULL)
	{
		$contact = new Contact($id);

		if (!$contact->exists() OR
			!$this->input->is_ajax_request() OR
			!$this->acl->has_privilege('manage_contacts'))
			show_404();

		$this->data['contact'] = $contact;

		$this->_render('accounts/accounts/edit_description.php');
	}

	public function update_contact($id=NULL)
	{
		$contact = new Contact($id);
		$role = $this->input->post("role");

		if (!$contact->exists() OR
			!$this->acl->has_privilege('manage_contacts') OR
			!in_array($role,$this->acl->get_roles()))
			show_404();

		$contact->name = $this->input->post("name");
		$contact->company = $this->input->post("company");
		$contact->address = $this->input->post("address");
		$contact->phone = $this->input->post("phone");
		$contact->role = $role;

		$this->load->helper('geo');
		$localisation = google_lat_long($contact->address);
		if (is_null($localisation))
			$this->_alert("Address company building not found","info");
		else
		{
			$contact->latitude = $localisation[0];
			$contact->longitude = $localisation[1];
		}

		$contact->save();
		$this->_alert("Contact $contact->name updated",'success');
		$this->_log($contact->id,'Update',"Edit contact $contact->company");

		$this->_redirect("accounts/view/$contact->id");
	}

	public function update_description($id=NULL)
	{
		$contact = new Contact($id);

		if (!$contact->exists() OR
			!$this->acl->has_privilege('manage_contacts'))
			show_404();

		$contact->description = $this->input->post("description");
		$contact->save();
		$this->_alert("Contact $contact->name updated",'success');
		$this->_log($contact->id,'Update',"Edit contact $contact->company");

		$this->_redirect("accounts/view/$contact->id");
	}

	public function edit($id=NULL)
	{
		$contact = new Contact($id);

		if (!$this->acl->has_privilege('manage_contacts') OR
			!$contact->exists())
			show_404();

		$this->data['contact'] = $contact;

		$this->_render('accounts/accounts/edit.php');
	}

	public function update($id=NULL)
	{
		$contact = new Contact($id);

		if (!$this->acl->has_privilege('manage_contacts') OR
			!$contact->exists())
			show_404();

		$this->load->helper('security');

		$contact->login = $this->input->post("login");
		$contact->email = $this->input->post("email");
		$password = $this->input->post("password");
		
		if (!empty($password))
		{
			$contact->password = secure_hash($password);
			$this->_alert("Password updated",'info');
		}

		$contact->save();
		$this->_alert("Contact $contact->name updated",'success');
		$this->_log($contact->id,'Update',"Edit $contact->name contact account");

		$this->_redirect("accounts/view/$contact->id");
	}

	public function update_inline ($id=NULL)
	{
		if (!$this->acl->has_privilege('manage_contacts') OR
			!$this->input->is_ajax_request())
			show_404();
		
		$field = $this->input->post("field");
		$val = $this->input->post("val");
		$contact = new Contact($id);

		if (!$contact->exists() OR
			!in_array($field,Contact::$inline_fields))
			show_404();

		if ($contact->$field != $val)
		{
			$contact->$field = $val;
			$contact->save();
		}
	}

	public function read ($id=NULL)
	{
		$contact = new Contact($id);

		if (!$contact->exists())
			show_404();

		$this->data['contact'] = $contact;

		$this->breadcrumb->push($contact->name,'user');

		$this->_render('accounts/accounts/read.php');
	}

	public function view ($id=NULL)
	{
		$contact = new Contact($id);

		if (!$contact->exists() OR
			!$this->acl->has_privilege('internal_access'))
			show_404();

		$contacts = new Contact();
		$contacts->where('login',$contact->login);
		$contacts->get();
		if ($contacts->result_count() >= 2)
			$this->_alert("Login $contact->login duplicated, please update it",'warning');

		$this->data['contact'] = $contact;
		$this->data['manage_contacts'] = $this->acl->has_privilege('manage_contacts');
		$this->data['manage_projects'] = $this->acl->has_privilege('manage_projects');
		$this->data['rendered_logs'] = modules::run('tools/activities/view',$contact->id,'contact');
		$this->data['rendered_folder'] = modules::run('tools/files/view',$contact->folder_id);
		$this->data['rendered_projects'] = modules::run('projects/index_mini',$contact->id);

		$this->breadcrumb->push($contact->name,'user');

		$this->_render('accounts/accounts/view.php');
	}

	public function create ()
	{
		$role = $this->input->post('role');

		if (!$this->acl->has_privilege('manage_contacts') OR
			!in_array($role,$this->acl->get_roles()))
			show_404();

		$login = $this->input->post('login');
		$password = $this->input->post('password');

		if (!$login OR !$password)
		{
			$this->_alert('Login and Password required','error');
			$this->_redirect('accounts');
		}

		$this->load->helper('security');

		$folder = new Folder();
		$folder->flag_lock=0;
		$folder->save();

		$contact = new Contact();
		$contact->folder_id = $folder->id;
		$contact->login = $login;
		$contact->role = $role;
		$contact->password = secure_hash($password);
		$contact->email = $this->input->post('email');
		$contact->name = $this->input->post('name');
		$contact->company = $this->input->post('company');
		$contact->address = $this->input->post("address");
		$contact->description = $this->input->post("description");
		$contact->date = date("Y-m-d H:i:s");
		$contact->flag_lock = 0;

		$this->load->helper('geo');
		$localisation = google_lat_long($contact->address);
		if (is_null($localisation))
			$this->_alert("Address company building not valid","info");
		else
		{
			$contact->latitude = $localisation[0];
			$contact->longitude = $localisation[1];
		}

		$contact->save();

		$contact->clear();
		$contact->where('login',$login);
		$contact->get();
		if ($contact->result_count() >= 2)
			$this->_alert("Login $login duplicated, please update it",'warning');

		$this->_alert('Contact created','success');
		$this->_log($contact->id,'Create',"Add contact $contact->name");

		$this->_redirect("accounts/view/$contact->id");
	}

	public function delete ($id=NULL)
	{
		$contact = new Contact($id);

		if (!$this->acl->has_privilege('manage_contacts') OR
			!$contact->exists())
			show_404();

		if ($contact->project->result_count() >= 1)
			$this->_alert("Projects related to $contact->login still exists");
		
		$this->_log($contact->id,'Delete',"Delete contact $contact->name","accounts/");
		$contact->delete();

		$this->_alert('Contact deleted','success');
		$this->_redirect('accounts');
	}

	public function lock ($id=NULL)
	{
		$contact = new Contact($id);

		if (!$this->acl->has_privilege('manage_contacts') OR
			!$contact->exists())
			show_404();

		$contact->flag_lock=1;
		$contact->save();

		$this->_alert("Account $contact->login locked",'success');
		$this->_redirect("accounts/view/$contact->id");
	}

	public function unlock ($id=NULL)
	{
		$contact = new Contact($id);

		if (!$this->acl->has_privilege('manage_contacts') OR
			!$contact->exists())
			show_404();

		$contact->flag_lock=0;
		$contact->save();

		$this->_alert("Account $contact->login unlocked",'success');
		$this->_redirect("accounts/view/$contact->id");
	}
}

/* End of file */
