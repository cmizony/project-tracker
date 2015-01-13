<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tickets extends MY_Controller {

	public function index($object = NULL, $id = NULL)
	{
		if(!$this->acl->has_privilege('internal_access'))
			show_404();

		$tickets = new Ticket ();
		$project_id = NULL;

		switch ($object)
		{
		case 'project':
			$project = new Project ($id);
			if (!$project->exists())
				show_404();

			$page_title = "Tickets for project $project->name";
			$project_id = $id;
			$tickets->where('project_id',$id);
			break;
		case NULL:
			$page_title = "Tickets for all projects";
			$this->breadcrumb->reset();
			break;
		default:
			show_404();
		}

		$tickets->include_related('tag',array('id','text','color','date'));
		$tickets->get();

		$this->data['project_id'] = $project_id;
		$this->data['tickets'] = $tickets;
		$this->data['page_title'] = $page_title;
		$this->data['manage_tickets'] = $this->acl->has_privilege('manage_tickets');

		$this->breadcrumb->push('Tickets','ticket');

		array_push($this->javascript,
			'libs/moment.min.js',
			'libs/d3.v3.min.js',
			'libs/d3.legend.js',
			'charts/tickets_bar.js'
		);

		array_push($this->css,'charts/activities_bar.css');

		$this->_render('projects/tickets/index.php','FULL');
	}

	public function index_mini($project_id=NULL)
	{
		$project = new Project($project_id);

		if (!$project->exists() OR
			(!$this->acl->has_privilege('internal_access') AND
			!$project->is_valid($this->account_id)))
			show_404();

		$tickets= new Ticket();
		$tickets->where('project_id',$project_id);
		$tickets->get();

		$this->data['tickets'] = $tickets;

		$this->_render('projects/tickets/index_mini.php');
	}

	public function add ($project_id = NULL)
	{

		if (is_null($project_id))
		{
			// Access from Index page
			if (!$this->acl->has_privilege('create_tickets') OR
				!$this->acl->has_privilege('internal_access'))
				show_404();

			$projects = new Project();
			$projects->get();
			$this->data['projects'] = $projects;
		}
		else
		{	
			$project = new Project($project_id);

			// Access from Index mini page
			if (!$project->exists() OR
				!$this->acl->has_privilege('create_tickets') OR
				(!$this->acl->has_privilege('internal_access') AND
				!$project->is_valid($this->account_id)))
				show_404();
		}


		$this->data['project_id'] = $project_id;
		$this->data['manage_projects'] = $this->acl->has_privilege('manage_projects');
		$this->breadcrumb->push('Add','plus',1);

		$this->_render('projects/tickets/add.php','FULL');
	}

	public function create ($project_id = NULL)
	{
		if (is_null($project_id))
			$project_id = $this->input->post('project');

		$type = $this->input->post('type');
		$priority = $this->input->post("priority");

		$project = new Project($project_id);

		if (!in_array($type,Ticket::$types) OR
			!in_array($priority,Ticket::$priorities) OR
			!$project->exists() OR
			!$this->acl->has_privilege('create_tickets') OR
			(!$this->acl->has_privilege('internal_access') AND
			!$project->is_valid($this->account_id)))
			show_404();

		$chat = new Chat();
		$chat->flag_lock=0;
		$chat->save();

		$time_tracker  = new Time_tracker();
		$time_tracker->flag_lock=0;
		$time_tracker->save();

		$folder = new Folder();
		$folder->flag_lock=0;
		$folder->save();

		$tag = new Tag();
		$tag->color = "gray";
		$tag->date = date("Y-m-d H:i:s");
		$tag->save();

		$ticket = new Ticket();
		$ticket->chat_id = $chat->id;
		$ticket->folder_id = $folder->id;
		$ticket->time_tracker_id = $time_tracker->id;
		$ticket->type = $type;
		$ticket->priority = $priority;
		$ticket->project_id = $project_id;
		$ticket->title = $this->input->post('title');
		$ticket->description = $this->input->post('description');
		$ticket->number = $project->ticket->result_count()+1;
		$ticket->status = 'Open';
		$ticket->date = date("Y-m-d H:i:s");
		$ticket->tag_id = $tag->id;

		$ticket->save();

		$folder_path = FILES."files/tickets/$folder->id";
		if (!is_dir($folder_path))
			mkdir($folder_path);

		$config['upload_path'] = $folder_path;
		$config['max_size'] = 8192;
		$config['file_name'] = md5((string)time());
		$config['allowed_types'] = '*';

		$this->load->library('upload');
		$this->upload->initialize($config);
		
		if (! $this->upload->do_upload("file") )
		{
			$errors = $this->upload->display_errors('','');
			$this->_alert("No File uploaded ($errors)",'info');
		}
		else
		{
			$data = $this->upload->data();
			$file = new File();
			$file->title = "Initial imported file";
			$file->date = date("Y-m-d H:i:s");
			$file->hash = md5(file_get_contents($data['full_path']));
			$file->name = $data['file_name'];
			$file->size = $data['file_size'];
			$file->type = $data['file_ext'];
			$file->note = "Imported file from Ticket creation";
			$file->folder_id = $folder->id;
			$file->downloads = 0;
			$file->save();
			$this->_alert("File uploaded",'success');
		}

		$this->_log($ticket->id,'Create',"Add ticket $ticket->name","projects/tickets/view/$ticket->id");

		$this->_alert('Ticket created','success');
		$this->_redirect("projects/tickets/view/$ticket->id");
	}

	public function update($id=NULL)
	{
		$ticket = new Ticket($id);

		$title = $this->input->post("title");
		$type = $this->input->post("type");
		$priority = $this->input->post("priority");

		if (!$ticket->exists() OR
			!$this->acl->has_privilege('manage_tickets') OR
			!in_array($type,Ticket::$types) OR
			!in_array($priority,Ticket::$priorities))
			show_404();

		$ticket->title = empty($title)? $ticket->title : $title;
		$ticket->type = $type;
		$ticket->priority = $priority;
		$ticket->release = $this->input->post("release");
		$ticket->status = $this->input->post("status");
		$ticket->description = $this->input->post("description");

		$ticket->save();
		$this->_alert("Ticket $title updated",'success');
		$this->_log($ticket->id,'Update',"Edit ticket $ticket->name","projects/tickets/view/$ticket->id");

		$this->_redirect("projects/tickets/view/$ticket->id");
	}

	public function update_inline ($id=NULL)
	{
		if (!$this->input->is_ajax_request())
			show_404();

		$field = $this->input->post("field");
		$val = $this->input->post("val");
		$ticket = new Ticket($id);

		if (!$ticket->exists() OR
			!in_array($field,Ticket::$inline_fields) OR
			!$this->acl->has_privilege('manage_tickets'))
			show_404();

		if ($ticket->$field != $val)
		{
			$ticket->$field = $val;
			$ticket->save();
			$this->_log($ticket->id,'Update',"Edit $field ticket $ticket->name","projects/tickets/view/$ticket->id");
		}
	}

	public function edit ($id=NULL)
	{
		if (!$this->input->is_ajax_request())
			show_404();

		$ticket = new Ticket($id);
		if (!$ticket->exists() OR
			!$this->acl->has_privilege('manage_tickets'))
			show_404();

		$this->data['ticket'] = $ticket;

		$this->_render('projects/tickets/edit');
	}

	public function read ($id=NULL)
	{
		$ticket = new Ticket();
		$ticket->select('tickets.*');
		$ticket->select_min('chat_messages.date','min_date');
		$ticket->select_func('COUNT', '*','total');
		$ticket->where('id',$id);
		$ticket->include_related('chat/messages',array('id'),FALSE);
		$ticket->group_by('id');
		$ticket->get();

		if (!$ticket->exists() OR
			(!$this->acl->has_privilege('internal_access') AND
			!$ticket->is_valid($this->account_id)))
			show_404();

		$this->data['ticket'] = $ticket;

		$this->breadcrumb->push($ticket->title,'ticket');

		$this->_render('projects/tickets/read.php');
	}

	public function view ($id=NULL)
	{
		$ticket = new Ticket();
		$ticket->include_related('tag',array('id','text','color','date'));
		$ticket->where('id',$id);
		$ticket->get();

		if (!$ticket->exists() OR
			(!$this->acl->has_privilege('internal_access') AND
			!$ticket->is_valid($this->account_id)))
			show_404();

		$this->data['manage_tickets'] = $this->acl->has_privilege('manage_tickets');
		$this->data['internal_access'] = $this->acl->has_privilege('internal_access');
		$this->data['rendered_chat'] = modules::run('tools/chats/view',$ticket->chat_id);
		$this->data['rendered_logs'] = modules::run('tools/activities/view');
		$this->data['rendered_folder'] = modules::run('tools/files/view',$ticket->folder_id);

		if ($this->acl->has_privilege('internal_access'))
			$this->data['rendered_time_trackers'] = modules::run('tools/time_trackers/index_mini',$ticket->time_tracker_id);


		$this->data['ticket'] = $ticket;

		$this->breadcrumb->push($ticket->title,'ticket');

		$this->_render('projects/tickets/view.php','FULL');
	}
}
/* End of file */
