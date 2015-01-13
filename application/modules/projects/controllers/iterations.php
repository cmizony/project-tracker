<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Iterations extends MY_Controller {

	public function index($object = NULL,$id = NULL)
	{
		if (!$this->acl->has_privilege('internal_access'))
			show_404();

		$iterations= new Iteration();
		$project_id = NULL;

		switch ($object)
		{
		case 'project':
			$project = new Project ($id);
			if (!$project->exists())
				show_404();

			$page_title = "Tasks for project $project->name";
			$project_id = $id;
			$iterations->where('project_id',$id);
			break;
		case NULL:
			$page_title = "Tasks for all projects";
			$this->breadcrumb->reset();
			break;
		default:
			show_404();
		}

		$iterations->include_related("project",array("name"));
		$iterations->include_related("task",array("id","title","date","status",'start_date','estimated','priority'));
		$iterations->include_related('task/contact',array('name'));
		$iterations->include_related('task/tag',array('id','text','color','date'));
		$iterations->order_by('project_id desc, date');
		$iterations->get();

		$this->data['project_id'] = $project_id;
		$this->data['iterations'] = $iterations;
		$this->data['page_title'] = $page_title;
		$this->data['manage_iterations'] = $this->acl->has_privilege('manage_iterations');

		array_push($this->javascript,
			'libs/moment.min.js',
			'libs/jquery/jquery-ui-1.10.4.interactions.js',
			'libs/fullcalendar.js'
		);

		array_push($this->css,'fullcalendar.css');
		
		$this->breadcrumb->push('Iterations','tasks');

		$this->_render('projects/iterations/index.php','FULL');
	}

	public function index_mini($project_id=NULL)
	{
		$project = new Project($project_id);

		if (!$project->exists() OR
			(!$this->acl->has_privilege('internal_access') AND
			!$project->is_valid($this->account_id)))
			show_404();

		$iterations= new Iteration();
		$iterations->where('project_id',$project_id);
		$iterations->get();

		$this->data['manage_iterations'] = $this->acl->has_privilege('manage_iterations');
		$this->data['internal_access'] = $this->acl->has_privilege('internal_access');
		$this->data['iterations'] = $iterations;
		$this->data['project'] = $project;

		$this->_render('projects/iterations/index_mini.php');
	}

	public function read ($id=NULL)
	{
		$iteration = new Iteration();
		$iteration->where('id',$id);
		$iteration->include_related_count('task','count_tasks');
		$iteration->get();

		if (!$this->acl->has_privilege('internal_access') OR
			!$iteration->exists())
			show_404();

		$this->data['iteration'] = $iteration;

		$this->breadcrumb->push($iteration->title,'tasks');

		$this->_render('projects/iterations/read.php');
	}

	public function view ($id = NULL)
	{
		$iteration = new Iteration();
		$iteration->where('id',$id);

		$this->load->helper('inflector');

		foreach (Task::$statuses as $status)
		{
			$task = new Task();
			$task->select_func('count','*',"count");
			$task->where('iteration_id','${parent}.id',FALSE);
			$task->where('status',$status);
			$iteration->select_subquery($task, "count_tasks_".underscore($status));
		}
		$task = new Task();
		$task->select_func('count','*',"count");
		$task->where('iteration_id','${parent}.id',FALSE);
		$iteration->select_subquery($task, "count_tasks");
		$iteration->select('iterations.*');

		$iteration->get();

		if (!$this->acl->has_privilege('internal_access') OR
			!$iteration->exists())
			show_404();

		$this->data['iteration'] = $iteration;
		$this->data['rendered_tasks'] = modules::run('projects/tasks/index',$iteration->id);
		$this->data['rendered_logs'] = modules::run('tools/activities/view');
		$this->data['rendered_folder'] = modules::run('tools/files/view',$iteration->folder_id);
		$this->data['rendered_time_trackers'] = modules::run('tools/time_trackers/index_mini',$iteration->time_tracker_id);

		$this->breadcrumb->push($iteration->title,'tasks');

		$this->_render('projects/iterations/view.php','FULL');
	}

	public function add($project_id = NULL)
	{
		
		if (is_null($project_id))
		{
			// Access from Index page
			if (!$this->acl->has_privilege('manage_iterations') OR
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
			if (!$this->acl->has_privilege('manage_iterations') OR
				!$project->exists())
				show_404();
		}

		$iteration = new Iteration();
		$iteration->order_by('id','desc');
		$iteration->limit(1);
		$iteration->get();

		$this->data['iteration'] = $iteration;
		$this->data['project_id'] = $project_id;
		$this->data['manage_projects'] = $this->acl->has_privilege('manage_projects');

		$this->breadcrumb->push('Add','plus',1);

		$this->_render('projects/iterations/add.php','FULL');
	}

	public function create ($project_id = NULL)
	{
		if (is_null($project_id))
			$project_id = $this->input->post('project');

		$unit = $this->input->post("unit");
		$project = new Project($project_id);

		if (!array_key_exists($unit,Iteration::$units) OR
			!$project->exists() OR
			!$this->acl->has_privilege('manage_iterations') OR
			!$this->acl->has_privilege('internal_access'))
			show_404();

		$folder = new Folder();
		$folder->flag_lock=0;
		$folder->save();

		$time_tracker  = new Time_tracker();
		$time_tracker->flag_lock=0;
		$time_tracker->save();

		$iteration = new Iteration();
		$iteration->folder_id = $folder->id;
		$iteration->title = $this->input->post("title");
		$iteration->label = $this->input->post("label");
		$iteration->description = $this->input->post("description");
		$iteration->status = "New";
		$iteration->time = ((float)$this->input->post("time")) * Iteration::$units[$unit];
		$iteration->date = date("Y-m-d H:i:s");
		$iteration->start_date = format_date("Y-m-d H:i:s",$this->input->post('start_date'));
		$iteration->project_id = $project->id;
		$iteration->time_tracker_id = $time_tracker->id;

		$iteration->save();
		$this->_alert("Iteration $iteration->title created",'success');
		$this->_log($iteration->id,'Create',"Add iteration $iteration->title on project $project->name","projects/iterations/view/$iteration->id");

		$this->_redirect("projects/iterations/view/$iteration->id");
	}

	public function edit ($id=NULL)
	{
		$iteration = new Iteration($id);

		if (!$iteration->exists() OR
			!$this->acl->has_privilege('manage_iterations'))
			show_404();

		$projects = new Project();
		$projects->get();

		$this->data['projects'] = $projects;
		$this->data['iteration'] = $iteration;


		$this->_render('projects/iterations/edit');
	}

	public function update ($id = NULL)
	{
		$iteration = new Iteration($id);

		if (!$iteration->exists() OR
			!$this->acl->has_privilege('manage_iterations'))
			show_404();

		$unit = $this->input->post("unit");
		$status = $this->input->post("status");

		if (!array_key_exists($unit,Iteration::$units) OR
			!in_array($status,Iteration::$statuses))
			show_404();

		$iteration->title = $this->input->post("title");
		$iteration->label = $this->input->post("label");
		$iteration->description = $this->input->post("description");
		$iteration->status = $status;
		$iteration->start_date = $this->input->post("start_date");
		$iteration->time = ((float)$this->input->post("time")) * Iteration::$units[$unit];

		$iteration->save();
		$this->_alert("Iteration $iteration->title updated",'success');
		$this->_log($iteration->id,'Update',"Edit iteration $iteration->title","projects/iterations/view/$iteration->id");

		$this->_redirect("projects/iterations/view/$iteration->id");
	}

	public function delete ($id=NULL)
	{
		$iteration = new Iteration($id);

		if (!$this->acl->has_privilege('manage_iterations') OR
			!$iteration->exists())
			show_404();

		$this->_log($iteration->id,'Delete',"Delete iteration $iteration->title","projects/iterations");
		$iteration->delete_deep();

		$this->_alert('Iteration deleted','success');

		$this->_redirect('projects/iterations');
	}
}

/* End of file */
