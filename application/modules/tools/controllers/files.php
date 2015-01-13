<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Files extends MY_Controller {
	
	public function index ()
	{
		show_404();
	}

	public function read ($id=NULL)
	{
		$file = new File($id);

		if (!$file->exists() OR
			(!$this->acl->has_privilege('internal_access') AND
			!$file->is_valid($this->account_id)))
			show_404();

		$x_link = $file->folder->x_link(); // Table link
		
		$file->path = FILES."files/$x_link/$file->folder_id/$file->name";

		switch ($x_link)
		{
		case 'projects':
			$file->parent_url = "projects/view/".$file->folder->project->id; 
			$file->parent_object = "Project";
			$file->parent_name = $file->folder->project->name;
			break;
		case 'tickets':	
			$file->parent_url = "projects/tickets/view/".$file->folder->ticket->id;
			$file->parent_object = "Ticket";
			$file->parent_name = $file->folder->ticket->title;
		   	break;
		case 'iterations':	
			$file->parent_url = "projects/iterations/view/".$file->folder->iteration->id;
			$file->parent_object = "Iteration";
			$file->parent_name = $file->folder->iteration->title;
		   	break;
		case 'contacts':
			$file->parent_url = "accounts/view/".$file->folder->contact->id; 
			$file->parent_object = "Contact";
			$file->parent_name = $file->folder->contact->name;
			break;
		}

		$this->load->helper('file');

		$this->data['file'] = $file;

		$this->breadcrumb->push($file->title,'file');

		$this->_render('tools/files/read.php');
	}

	public function view($id=NULL)
	{
		$folder = new Folder($id);

		if (!$folder->exists() OR
			(!$this->acl->has_privilege('internal_access') AND
			!$folder->is_valid($this->account_id)))
			show_404();

		$this->load->helper('file');

		$this->data['folder'] = $folder;
		$this->data['account_id'] = $this->account_id;
		$this->data['internal_access'] = $this->acl->has_privilege('internal_access');

		$this->_render('tools/files/view.php');
	}

	public function add ($folder_id=NULL)
	{
		$folder = new Folder($folder_id);

		if (!$this->input->is_ajax_request() OR
			!$folder->exists() OR
			(!$this->acl->has_privilege('internal_access') AND
			!$folder->is_valid($this->account_id)))
			show_404();

		$this->data['folder'] = $folder;

		$this->_render('tools/files/add.php');
	}

	public function download ($id = NULL)
	{
		$file = new File($id);

		if (!$file->exists() OR 
			(!$this->acl->has_privilege('internal_access') AND
			!$folder->is_valid($this->account_id)))
			show_404();

		$x_link = $file->folder->x_link(); // Table link
		$file_path = FILES."files/$x_link/$file->folder_id/$file->name";

		if (!file_exists($file_path))
			show_404();

		$file->downloads += 1;
		$file->save();

		$this->load->helper('download');

		$data = file_get_contents($file_path);
		force_download($file->title.$file->type,$data);
	}

	public function upload ($folder_id = NULL)
	{
		$folder = new Folder($folder_id);

		if (!$folder->exists() OR
			(!$this->acl->has_privilege('internal_access') AND
			!$folder->is_valid($this->account_id)))
			show_404();

		$this->load->library('upload');

		$x_link = $folder->x_link(); // Table link

		switch ($x_link)
		{
		case 'projects':$url = "projects/view/".$folder->project->id; break;
		case 'tickets':	$url = "projects/tickets/view/".$folder->ticket->id; break;
		case 'iterations':	$url = "projects/iterations/view/".$folder->iteration->id; break;
		case 'contacts':	$url = "accounts/view/".$folder->contact->id; break;
		}

		$folder_path = FILES."files/$x_link/$folder_id";

		if (!is_dir($folder_path))
			mkdir($folder_path);
		
		$time = time();

		$config['upload_path'] = $folder_path;
		$config['max_size'] = 8192;
		$config['file_name'] = md5("PRE_SALT_4356".((string)$time)."POST_SALT_1846");
		$config['allowed_types'] = '*';

		$this->upload->initialize($config);
		
		if (! $this->upload->do_upload("file") )
		{
			$errors = $this->upload->display_errors('','');
			$this->_alert($errors,'error','text');
			$this->_redirect($url);
		}

		$data = $this->upload->data();

		$file = new File();
		$file->title = $this->input->post("title");
		$file->date = date("Y-m-d H:i:s",$time);
		$file->hash = md5(file_get_contents($data['full_path']));
		$file->name = $data['file_name'];
		$file->size = $data['file_size'];
		$file->type = $data['file_ext'];
		$file->note = $this->input->post("note");
		$file->folder_id = $folder_id;
		$file->downloads = 0;

		$file->save();
		$this->_alert("File $file->title uploaded",'success');
		$this->_log($file->id,'Create',"Add file $file->title",$url);
	
		$this->_redirect($url);
	}

	public function edit ($id = NULL)
	{
		$file = new File($id);

		if (!$file->exists() OR
			!($file->contact_id == $this->account_id OR
			$this->acl->has_privilege('internal_access')))
			show_404();

		$this->data['file'] = $file;

		$this->_render('tools/files/edit.php');
	}

	public function update ($id = NULL)
	{
		$file = new File($id);

		if (!$file->exists() OR
			!($file->contact_id == $this->account_id OR
			$this->acl->has_privilege('internal_access')))
			show_404();
		
		$file->title = $this->input->post('title');
		$file->note = $this->input->post('note');
		$file->save();

		$x_link = $file->folder->x_link(); // Table link
		switch ($x_link)
		{
		case 'projects':$url = "projects/view/".$file->folder->project->id; break;
		case 'tickets':	$url = "projects/tickets/view/".$file->folder->ticket->id; break;
		case 'iterations':	$url = "projects/iterations/view/".$file->folder->iteration->id; break;
		case 'contacts':	$url = "accounts/view/".$file->folder->account->id; break;
		}

		$this->_alert("File $file->title edited",'success');
		$this->_log($file->id,'Update',"Edit file $file->name",$url);

		$this->_redirect($url);
	}

	public function delete ($id = NULL)
	{
		$file = new File($id);

		if (!$file->exists() OR
			!($file->contact_id == $this->account_id OR
			$this->acl->has_privilege('internal_access')))
			show_404();

		$x_link = $file->folder->x_link(); // Table link
		$file_path = FILES."files/$x_link/$file->folder_id/$file->name";
		if (is_file($file_path))
			unlink($file_path);

		$name = $file->name;

		switch ($x_link)
		{
		case 'projects':$url = "projects/view/".$file->folder->project->id; break;
		case 'tickets':	$url = "projects/tickets/view/".$file->folder->ticket->id; break;
		case 'iterations':	$url = "projects/iterations/view/".$file->folder->iteration->id; break;
		case 'contacts':	$url = "accounts/view/".$file->folder->account->id; break;
		}

		$file->delete();
		$this->_log($id,'Delete',"Delete file $name",$url);

		$this->_alert("File deleted",'success');
		$this->_redirect($url);
	}
}

/* End of file */
