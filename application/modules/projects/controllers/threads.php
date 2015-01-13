<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Threads extends MY_Controller {

	public function index($project_id = NULL)
	{
		$cookie_name = crc32("threads-index-display");
		$value = $this->input->cookie($cookie_name);

		switch ($value)
		{
		case "mini":
			return $this->index_custom("mini",$project_id);
		case "accordion":
			return $this->index_custom("accordion",$project_id);
		case "table":
			return $this->index_custom("table",$project_id);
		default:
		case "grid":
			return $this->index_custom("grid",$project_id);
		}
	}

	public function index_custom ($display = NULL,$project_id = NULL)
	{
		$project = new Project ($project_id);

		if (!$project->exists() OR
			(!$this->acl->has_privilege('internal_access') AND
			!$project->is_valid($this->account_id)) OR
			!in_array($display,array('mini','accordion','table','grid')))
			show_404();

		$cookie_name = crc32("threads-index-display");
		$this->input->set_cookie($cookie_name, $display, 86500*7);

		$threads = new Thread();
		$threads->where('project_id',$project_id);
		$threads->get();

		$this->load->helper('text');

		$this->data['threads'] = $threads;
		$this->data['internal_access'] = $this->acl->has_privilege('internal_access');

		$this->_render("projects/threads/index_$display.php");
	}

	public function read ($id=NULL)
	{
		$thread = new Thread();
		$thread->select('threads.*');
		$thread->select_max('chat_messages.date','max_date');
		$thread->select_min('chat_messages.date','min_date');
		$thread->select_func('COUNT', '*','total');
		$thread->where('id',$id);
		$thread->include_related('chat/messages',array('id','date'),FALSE);
		$thread->group_by('id');
		$thread->get();

		if (!$thread->exists() OR
			(!$this->acl->has_privilege('internal_access') AND
			!$thread->is_valid($this->account_id)))
			show_404();

		$this->data['thread'] = $thread;

		$this->breadcrumb->push($thread->title,'comment-o');

		$this->_render('projects/threads/read.php');
	}

	public function view ($id = NULL)
	{
		$thread = new Thread($id);

		if (!$thread->exists() OR
			(!$this->acl->has_privilege('internal_access') AND
			!$thread->is_valid($this->account_id)))
			show_404();

		$this->data['rendered_chat'] = modules::run('tools/chats/view',$thread->chat_id);
		$this->data['thread'] = $thread;

		$this->breadcrumb->push($thread->title,'comment-o');

		$this->_render("projects/threads/view.php");
	}

	public function edit ($id = NULL)
	{
		$thread = new Thread($id);

		if (!$thread->exists() OR
			(!$this->acl->has_privilege('internal_access') AND
			!$thread->is_valid($this->account_id)) OR
			$thread->flag_lock == 1)
			show_404();

		$this->data['thread'] = $thread;

		$this->_render("projects/threads/edit.php");
	}

	public function update ($id = NULL)
	{
		$thread = new Thread ($id);

		if (!$thread->exists() OR
			(!$this->acl->has_privilege('internal_access') AND
			!$thread->is_valid($this->account_id)) OR
			$thread->flag_lock == 1)
			show_404();

		$thread->title = $this->input->post("title");
		$thread->outline = $this->input->post("outline");
		$thread->description = $this->input->post("description");
		$new_thumbnail = $this->_upload_thumbnail($thread->thumbnail,$thread->project_id);
		if (!is_null($new_thumbnail))
			$thread->thumbnail = $new_thumbnail;

		$thread->save();
		$this->_alert("Thread $thread->title updated",'success');
		$this->_log($thread->id,'Update',"Edit thread $thread->title to project $project->name","projects/view/$thread->project_id");

		$this->_redirect("projects/view/$thread->project_id");
	}

	public function add ($project_id = NULL)
	{
		$project = new Project($project_id);

		if (!$project->exists() OR
			!$this->acl->has_privilege('create_thread') OR
			(!$this->acl->has_privilege('internal_access') AND
			!$project->is_valid($this->account_id)))
			show_404();

		$this->data['project'] = $project;

		$this->_render("projects/threads/add.php");
	}

	public function create ($project_id = NULL)
	{
		$project = new Project ($project_id);

		if (!$project->exists() OR
			!$this->acl->has_privilege('create_thread') OR
			(!$this->acl->has_privilege('internal_access') AND
			!$project->is_valid($this->account_id)))
			show_404();

		$chat = new Chat();
		$chat->flag_lock=0;
		$chat->save();

		$thread = new Thread();
		$thread->chat_id = $chat->id;
		$thread->title = $this->input->post("title");
		$thread->date = date("Y-m-d H:i:s");
		$thread->flag_lock = 0;
		$thread->outline = $this->input->post("outline");
		$thread->description = $this->input->post("description");
		$thread->project_id = $project_id;
		$thread->thumbnail = $this->_upload_thumbnail(md5((string)time()).".png",$project_id);

		$thread->save();
		$this->_alert("Thread $thread->title created",'success');
		$this->_log($thread->id,'Create',"Add thread $thread->title to project $project->name","projects/view/$project_id");

		$this->_redirect("projects/view/$project->id");
	}

	public function _upload_thumbnail ($filename,$project_id)
	{
		$this->load->library('upload');

		$folder_path = FILES."threads/p$project_id";

		if (!is_dir($folder_path))
			mkdir($folder_path);

		$config['upload_path'] = $folder_path;
		$config['max_size'] = 2048;
		$config['overwrite'] = TRUE;
		$config['file_name'] = $filename;
		$config['allowed_types'] = 'jpeg|jpg|png';

		$this->upload->initialize($config);

		if (! $this->upload->do_upload("file") )
		{
			$errors = $this->upload->display_errors('','');
			$this->_alert("No Thumbnail uploaded ($errors)",'info');
			return NULL;
		}

		$data = $this->upload->data();

		$config['source_image'] = $data['full_path'];
		$config['maintain_ratio'] = TRUE;
		$config['width'] = 100;
		$config['height'] = 100;

		$this->load->library('image_lib', $config); 
		$this->image_lib->resize();

		return $data['file_name'];
	}

	public function thumbnail ($id = NULL)
	{
		$thread = new Thread($id);
		$thumbnail = $thread->thumbnail_exists();

		if (!$thread->exists() OR
			!$thumbnail OR
			(!$this->acl->has_privilege('internal_access') AND
			!$thread->is_valid($this->account_id)))
			show_404();

		$file_name = $thumbnail;

		$this->output
			->set_content_type(mime_content_type($file_name))
			->set_output(file_get_contents($file_name));
	}

	public function delete ($id = NULL)
	{
		$thread = new Thread($id);

		if (!$thread->exists() OR
			!$this->acl->has_privilege('internal_access'))
			show_404();

		$file_path = FILES."threads/p$thread->project_id/$thread->thumbnail";

		if (is_file($file_path))
			unlink($file_path);

		$project_id = $thread->project_id;

		$thread->delete_deep();
		$this->_log($thread->id,'Delete',"Delete thread $thread->title","projects/view/$project_id");

		$this->_alert("Thread deleted",'success');
		$this->_redirect("projects/view/$project_id");
	}
}

/* End of file */
