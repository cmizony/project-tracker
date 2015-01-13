<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tasks extends MY_Controller {

	public function index ($iteration_id = NULL)
	{
		if (!$this->acl->has_privilege('internal_access') OR
			!$this->is_intern_call)
			show_404();
		
		$cookie_name = crc32("tasks-index-display");
		$value = $this->input->cookie($cookie_name);

		switch ($value)
		{
		case "mini":
			return $this->index_custom("mini",$iteration_id);
		case "grid":
			return $this->index_custom("grid",$iteration_id);
		default:
		case "table":
			return $this->index_custom("table",$iteration_id);
		}
	}

	public function index_custom ($display = NULL,$iteration_id = NULL)
	{
		if (!$this->acl->has_privilege('internal_access') OR
			!in_array($display,array('mini','table','grid')))
			show_404();

		$cookie_name = crc32("tasks-index-display");
		$this->input->set_cookie($cookie_name, $display, 86500*7);

		$tasks = new Task();
		$tasks->include_related('tag',array('id','text','color','date'));
		$tasks->include_related('contact',array('name'));
		$tasks->where('iteration_id',$iteration_id);
		$tasks->get();

		$this->data['tasks'] = $tasks;

		$this->_render("projects/tasks/index_$display.php");
	}

	public function read ($id=NULL)
	{
		$task = new Task($id);

		if (!$this->acl->has_privilege('internal_access') OR
			!$task->exists())
			show_404();

		$this->data['task'] = $task;

		$this->breadcrumb->push($task->title,'check-square-o');

		$this->_render('projects/tasks/read.php');
	}

	public function view ($id = NULL)
	{
		$task = new Task($id);
		$task->where('id',$id);
		$task->include_related('iteration',array('start_date','time'));
		$task->include_related('contact',array('name'));
		$task->include_related('tag',array('id','text','color','date'));
		$task->get();

		if (!$task->exists() OR
			!$this->acl->has_privilege('internal_access'))
			show_404();

		$this->data['rendered_chat'] = modules::run('tools/chats/view',$task->chat_id);
		$this->data['task'] = $task;

		$this->breadcrumb->push($task->title,'check-square-o');

		$this->_render("projects/tasks/view.php");
	}

	public function add ($iteration_id = NULL)
	{
		$iteration = new Iteration($iteration_id);

		if (!$this->acl->has_privilege('internal_access') OR
			!$iteration->exists())
			show_404();

		$contacts = new Contact();
		$contacts->get();

		$this->data['contacts']=$contacts;
		$this->data['my_account']=$this->account_id;
		$this->data['iteration'] = $iteration;

		$this->_render('projects/tasks/add.php');
	}

	public function create ($iteration_id = NULL)
	{
		$iteration = new Iteration($iteration_id);
		$unit = $this->input->post("unit");
		$priority = $this->input->post('priority');

		if (!array_key_exists($unit,Iteration::$units) OR
			!$this->acl->has_privilege('internal_access') OR
			!in_array($priority,Task::$priorities) OR
			!$iteration->exists())
			show_404();

		$tag = new Tag();
		$tag->color = "gray";
		$tag->date = date("Y-m-d H:i:s");
		$tag->save();

		$chat = new Chat();
		$chat->flag_lock=0;
		$chat->save();

		$task = new Task();
		$task->title = $this->input->post('title');
		$task->label = $this->input->post('label');
		$task->description = $this->input->post('description');
		$task->contact_id = $this->input->post('responsible');
		$task->start_date = format_date("Y-m-d ",$this->input->post('start_date')).$this->input->post("start_time");
		$task->date = date("Y-m-d H:i:s");
		$task->estimated = ((float)$this->input->post("estimated")) * Iteration::$units[$unit];
		$task->status = 'New';
		$task->priority = $priority;
		$task->iteration_id = $iteration->id;
		$task->tag_id = $tag->id;
		$task->chat_id = $chat->id;

		$task->save();

		$this->_alert("Task $task->title created",'success');
		$this->_log($task->id,'Create',"Add task $task->title on iteration $iteration->title","projects/iterations/view/$iteration->id");

		$this->_redirect("projects/iterations/view/$iteration->id");
	}

	public function edit ($id = NULL)
	{
		$task = new Task($id);

		if (!$this->acl->has_privilege('internal_access') OR
			!$task->exists())
			show_404();

		$iterations = new Iteration();
		$iterations->where('project_id',$task->iteration->project_id);
		$iterations->get();

		$contacts = new Contact();
		$contacts->get();

		$this->data['contacts']=$contacts;
		$this->data['task'] = $task;
		$this->data['iterations'] = $iterations;

		$this->_render('projects/tasks/edit.php');
	}

	public function update ($id = NULL)
	{
		$task = new Task($id);
		$unit = $this->input->post("unit");
		$priority = $this->input->post('priority');
		$status = $this->input->post('status');

		if (!array_key_exists($unit,Iteration::$units) OR
			!$this->acl->has_privilege('internal_access') OR
			!in_array($priority,Task::$priorities) OR
			!in_array($status,Task::$statuses) OR
			!$task->exists())
			show_404();

		$iterations = new Iteration();
		$iterations->where('project_id',$task->iteration->project_id);
		$iterations->get();
		
		$new_iteration_id = $this->input->post('iteration');
		$old_iteration_id = $task->iteration_id;
		$this->load->helper('array');

		if (!in_array($new_iteration_id,get_sub_array($iterations,'id')))
			show_404();

		$task->title = $this->input->post('title');
		$task->description = $this->input->post('description');
		$task->label = $this->input->post('label');
		$task->contact_id = $this->input->post('responsible');
		$task->status = $status;
		$task->priority = $priority;
		$task->iteration_id = $new_iteration_id;
		$task->start_date = format_date("Y-m-d ",$this->input->post('start_date')).$this->input->post("start_time");
		$task->estimated = ((float)$this->input->post("estimated")) * Iteration::$units[$unit];

		$task->save();

		$this->_alert("Task $task->title edited",'success');
		$this->_log($task->id,'Update',"Edit task $task->title on iteration ".$task->iteration->title,"projects/iterations/view/$task->iteration_id");
		$this->_redirect("projects/iterations/view/$old_iteration_id");
	}

	public function update_inline ($id=NULL)
	{
		if (!$this->input->is_ajax_request())
			show_404();

		$field = $this->input->post("field");
		$val = $this->input->post("val");
		$task = new Task($id);

		if (!$this->acl->has_privilege('internal_access') OR
			!in_array($field,Task::$inline_fields) OR
			!$task->exists())
			show_404();

		if ($field == 'iteration_id')
		{
			$iteration = new Iteration($val);
			if (!$iteration->exists())
				show_404();
		}

		if ($task->$field != $val)
		{
			$task->$field = $val;
			$task->save();
			$this->_log($task->id,'Update',"Edit $field task $task->title","projects/iterations/view/$task->iteration_id");
		}
	}

	public function delete ($id = NULL)
	{
		$task = new Task($id);

		if (!$this->acl->has_privilege('internal_access') OR
			!$task->exists())
			show_404();

		$iteration_id = $task->iteration_id;
		
		$this->_log($task->id,'Delete',"Delete task $task->title from iteration ".$task->iteration->title,"projects/iterations/view/$iteration_id");
		$task->delete_deep();

		$this->_alert('Task deleted','success');
		$this->_redirect("projects/iterations/view/$iteration_id");
	}
}
