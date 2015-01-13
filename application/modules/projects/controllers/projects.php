<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Projects extends MY_Controller {

	public function index($object = NULL, $id = NULL)
	{
		$projects= new Project();
		
		if (!$this->acl->has_privilege('internal_access'))
			$projects->where('beneficiary_id',$this->account_id);

		switch ($object)
		{
		case 'contact':
			$contact = new Contact ($id);
			if (!$contact->exists())
				show_404();

			$page_title = "Projects associated to contact $contact->name";
			$projects->where('beneficiary_id',$contact->id);
			$projects->or_where_related('stakeholder','contact_id',$contact->id);
			break;
		case NULL:
			$page_title = "All projects";
			$this->breadcrumb->reset();
			break;
		default:
			show_404();
		}

		$this->load->helper('inflector');

		foreach (Task::$statuses as $status)
		{
			$task = new Task();
			$task->select_func('count','*',"count");
			$task->where_related('iteration','project_id','${parent}.id',FALSE);
			$task->where('status',$status);
			$projects->select_subquery($task, "count_tasks_".underscore($status));
		}
		$task = new Task();
		$task->select_func('count','*',"count");
		$task->where_related('iteration','project_id','${parent}.id',FALSE);
		$projects->select_subquery($task, "count_tasks");
		
		foreach (Ticket::$statuses as $status)
		{
			$ticket = new Ticket();
			$ticket->select_func('count','*',"count");
			$ticket->where('project_id','${parent}.id',FALSE);
			$ticket->where('status',$status);
			$projects->select_subquery($ticket, "count_tickets_".underscore($status));
		}
		$ticket = new Ticket();
		$ticket->select_func('count','*',"count");
		$ticket->where('project_id','${parent}.id',FALSE);
		$projects->select_subquery($ticket, "count_tickets");

		$projects->select('projects.*');
		$projects->get();

		$this->data['projects'] = $projects;
		$this->data['manage_projects'] = $this->acl->has_privilege('manage_projects');
		$this->data['page_title'] = $page_title;

		$this->breadcrumb->push('Projects','list-alt');

		$this->_render('projects/projects/index.php','FULL');
	}

	public function index_mini ($contact_id = NULL)
	{
		$contact = new Contact($contact_id);

		if (!$contact->exists() OR
			!$this->acl->has_privilege('internal_access'))
			show_404();

		$projects = new Project();
		$projects->where('beneficiary_id',$contact_id);
		$projects->or_where_related('stakeholder','contact_id',$contact_id);
		$projects->include_related('stakeholder',array('role'));
		$projects->get();

		$this->data['projects'] = $projects;

		$this->_render('projects/projects/index_mini.php');
	}

	public function read ($id=NULL)
	{
		$project = new Project($id);

		if (!$project->exists() OR
			(!$this->acl->has_privilege('internal_access') AND
			!$project->is_valid($this->account_id)))
			show_404();

		$this->data['project'] = $project;

		$this->breadcrumb->push($project->name,'list-alt');

		$this->_render('projects/projects/read.php');
	}

	public function view ($id=NULL)
	{
		$project = new Project();
		$project->where('id',$id);

		foreach (Task::$statuses as $status)
		{
			$task = new Task();
			$task->select_func('count','*',"count");
			$task->where_related('iteration','project_id','${parent}.id',FALSE);
			$task->where('status',$status);
			$project->select_subquery($task, "count_tasks_".underscore($status));
		}
		$task = new Task();
		$task->select_func('count','*',"count");
		$task->where_related('iteration','project_id','${parent}.id',FALSE);
		$project->select_subquery($task, "count_tasks");
		
		foreach (Ticket::$statuses as $status)
		{
			$ticket = new Ticket();
			$ticket->select_func('count','*',"count");
			$ticket->where('project_id','${parent}.id',FALSE);
			$ticket->where('status',$status);
			$project->select_subquery($ticket, "count_tickets_".underscore($status));
		}
		$ticket = new Ticket();
		$ticket->select_func('count','*',"count");
		$ticket->where('project_id','${parent}.id',FALSE);
		$project->select_subquery($ticket, "count_tickets");

		$project->select('projects.*');
		$project->get();

		if (!$project->exists() OR
			(!$this->acl->has_privilege('internal_access') AND
			!$project->is_valid($this->account_id)))
			show_404();

		$activities = new Activity();
		$activities->where('uri_link',uri_string());
		$activities->include_related("contact",array("login","name"));
		$activities->limit(5);
		$activities->get();
		
		$this->data['manage_projects'] = $this->acl->has_privilege('manage_projects');
		$this->data['internal_access'] = $this->acl->has_privilege('internal_access');
		$this->data['rendered_threads'] = modules::run('projects/threads/index',$id);
		$this->data['rendered_logs'] = modules::run('tools/activities/view');
		$this->data['rendered_iterations'] = modules::run('projects/iterations/index_mini',$id);
		$this->data['rendered_tickets'] = modules::run('projects/tickets/index_mini',$id);
		$this->data['rendered_contacts'] = modules::run('accounts/index_mini',$id);
		$this->data['rendered_folder'] = modules::run('tools/files/view',$project->folder_id);
		$this->data['project'] = $project;
		$this->data['activities'] = $activities;

		$this->breadcrumb->push($project->name,'list-alt');

		$this->_render('projects/projects/view.php','FULL');
	}

	public function edit($type=NULL,$id=NULL)
	{
		$project = new Project($id);

		if (!$project->exists() OR
			!$this->acl->has_privilege('manage_projects') OR
			!$this->input->is_ajax_request() OR
			!in_array($type,array('general','details')))
			show_404();

		$this->load->helper('array');

		$projects = new Project();
		$projects->group_by('label')->get();
		$this->data['labels']=json_encode(get_sub_array($projects,'label'));

		$projects->clear();
		$projects->group_by('type')->get();
		$this->data['types']=json_encode(get_sub_array($projects,'type'));

		$this->data['project'] = $project;

		$this->_render("projects/projects/edit_$type.php");
	}

	public function update($type=NULL,$id=NULL)
	{
		$project = new Project($id);
		$status = $this->input->post("status");

		if (!$project->exists() OR
			!$this->acl->has_privilege('manage_projects') OR
			!in_array($type,array('general','details')))
			show_404();

		if ($type == "general")
		{
			$status = $this->input->post("status");

			if (!in_array($status,Project::$statuses))
				show_404();

			$name = $this->input->post("name");
			$project->name = empty($name)? $project->name : $name;
			$project->type = $this->input->post("type");
			$project->status = $status;
			$project->label = $this->input->post("label");
		}
		if ($type == "details")
			$project->description = $this->input->post("description");

		$project->save();
		$this->_log($project->id,'Update',"Edit project $project->name","projects/view/$id");
		$this->_alert("Project $project->name updated",'success');

		$this->_redirect("projects/view/$project->id");
	}

	public function add ()
	{
		if (!$this->acl->has_privilege('manage_projects'))
			show_404();

		$this->load->helper('array');

		$contacts = new Contact();
		$contacts->get();
		$this->data['contacts'] = $contacts;

		$projects = new Project();
		$projects->group_by('label')->get();
		$this->data['labels']=json_encode(get_sub_array($projects,'label'));

		$projects->clear();
		$projects->group_by('type')->get();
		$this->data['types']=json_encode(get_sub_array($projects,'type'));

		$this->data['manage_contacts'] = $this->acl->has_privilege('manage_projects');

		$this->breadcrumb->push('Add','plus',1);

		$this->_render("projects/projects/add.php",'FULL');
	}

	public function create ()
	{
		$contact_id = $this->input->post('contact');
		$end_date = $this->input->post("end_date");
		$status = $this->input->post("status");
		$contact = new Contact($contact_id);

		if (!$this->acl->has_privilege('manage_projects') OR
			!$contact->exists() OR
			!in_array($status,Project::$statuses))
			show_404();


		$folder = new Folder();
		$folder->flag_lock=0;
		$folder->save();

		$project = new Project();
		$project->folder_id = $folder->id;
		$project->beneficiary_id = $contact->id;
		$project->name = $this->input->post('name');
		$project->type = $this->input->post('type');
		$project->description = $this->input->post('description');
		$project->status = $status;
		$project->label = $this->input->post('label');
		$project->date = date("Y-m-d H:i:s");
		$project->end_date = format_date("Y-m-d H:i:s",empty($end_date)?date("Y-m-d"):$end_date);
		$project->save();

		$folder->clear();
		$folder->flag_lock=0;
		$folder->save();

		$iteration = new Iteration ();
		$iteration->folder_id = $folder->id;
		$iteration->status = "New";
		$iteration->title = "Product Backlog";
		$iteration->description = "Contains all the project tasks not yet assigned.\n Feel free to move them to other iterations.";
		$iteration->time = strtotime($project->end_date) - strtotime($project->date);
		$iteration->date = date("Y-m-d H:i:s");
		$iteration->project_id = $project->id;
		$iteration->number = 0;
		$iteration->save();

		$chat = new Chat();
		$chat->flag_lock=0;
		$chat->save();

		$thread = new Thread();
		$thread->chat_id = $chat->id;
		$thread->title = "General";
		$thread->date = date("Y-m-d H:i:s");
		$thread->flag_lock = 0;
		$thread->outline = "General discussions around the project.";
		$thread->project_id = $project->id;
		$thread->save();
		
		$this->_alert('Project created','success');
		$this->_log($project->id,'Create',"Add project $project->name for contact $contact->company");

		$this->_redirect("projects/view/$project->id");
	}

	public function update_inline ($id=NULL)
	{
		if (!$this->input->is_ajax_request())
			show_404();
		
		$field = $this->input->post("field");
		$val = $this->input->post("val");
		$project = new Project($id);

		if (!$project->exists() OR
			!in_array($field,Project::$inline_fields) OR
			!$this->acl->has_privilege('manage_projects'))
			show_404();

		if ($project->$field != $val)
		{
			$project->$field = $val;
			$project->save();
		}
	}

	public function delete ($id=NULL)
	{
		$project = new Project($id);

		if (!$this->acl->has_privilege('manage_projects') OR
			!$project->exists())
			show_404();

		if ($project->file->result_count() >= 1 OR
			$project->thread->result_count() >= 1)
		{
			$this->_alert("Please delete files & threads first",'warning');
			$this->_redirect("projects/view/$project->id");
		}

		$this->_log($id,'Delete',"Delete project $project->name","projects");
		$project->delete_deep();
		$this->_alert('Project deleted','success');

		$this->_redirect("projects");
	}
}

/* End of file */
