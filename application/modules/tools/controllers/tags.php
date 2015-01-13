
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tags extends MY_Controller {
	
	public function index ()
	{
		show_404();
	}

	public function update($id=NULL)
	{
		$tag = new Tag($id);

		$color = $this->input->post('color');

		if (!$tag->exists() OR
			!$tag->is_valid($this->account_id) OR
			!in_array($color,Tag::$colors) OR
			!$this->input->is_ajax_request())
			show_404();

		$tag->color = $color;
		$tag->date = date("Y-m-d H:i:s");
		$tag->text = $this->input->post('text');
		$tag->save();

		$x_link = $tag->x_link(); // Table link

		switch ($x_link)
		{
		case 'ticket':		$url = "projects/tickets/view/".$tag->ticket->id; break;
		case 'task':	$url = "projects/tasks/view/".$tag->task->id; break;
		}

		$this->_log($tag->id,'Update',"Edit tag $tag->text on $x_link",$url);

		$this->data['tag'] = $tag;

		$this->_render('tools/tags/view.php');
	}

}
