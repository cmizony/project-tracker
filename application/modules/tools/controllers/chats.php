<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Chats extends MY_Controller {

	public function index()
	{
		show_404();
	}

	public function view ($id=NULL)
	{
		$chat = new Chat($id);

		if (!$chat->exists() OR
			(!$this->acl->has_privilege('internal_access') AND
			!$chat->is_valid($this->account_id)))
			show_404();

		$this->data['chat'] = $chat;
		$this->data['account_id'] = $this->account_id;

		$this->_render('tools/chats/view.php','FULL');
	}

	public function delete_message($message_id = NULL)
	{
		$message = new Message($message_id);
		
		if (!$message->is_valid($this->account_id) OR
			$message->chat->flag_lock == 1 OR
			!($this->is_admin OR $this->account_id == $message->contact_id))
			show_404();

		$chat = new Chat($message->chat_id);
		$x_link = $chat->x_link(); // Table link

		if ($x_link == "project")
			$this->_log($message->id,'Delete',"Delete message on Project","projects/view/".$chat->project->id,"Message");
		else if ($x_link == "ticket")
			$this->_log($message->id,'Delete',"Delete message on Ticket","projects/tickets/view/".$chat->ticket->id,"Message");
		else if ($x_link == "thread")
			$this->_log($message->id,'Delete',"Delete message on Thread","projects/view/".$chat->thread->project_id,"Message");
		
		$message->delete();

		$this->_alert('Message deleted','success');
		$this->data['chat'] = $chat;

		$this->_render('tools/chats/view.php');
	}

	public function create_message ($id = NULL)
	{
		$chat = new Chat($id);

		if ((!$this->acl->has_privilege('internal_access') AND
			!$chat->is_valid($this->account_id)) OR
			$chat->flag_lock == 1)
			show_404();

		$message = new Message();
		$message->content= $this->input->post('content');
		$message->date = date("Y-m-d H:i:s");
		$message->chat_id = $chat->id;
		$message->contact_id = $this->is_admin ? NULL : $this->account_id;
		
		$message->save();

		$x_link = $chat->x_link(); // Table link

		if ($x_link == "projects")
			$this->_log($message->id,'Create',"Add message on Project","projects/view/".$chat->project->id,"Message");
		else if ($x_link == "tickets")
			$this->_log($message->id,'Create',"Add message on Ticket","projects/tickets/view/".$chat->ticket->id,"Message");
		else if ($x_link == "threads")
			$this->_log($message->id,'Create',"Create message on Thread","projects/view/".$chat->thread->project_id,"Message");

		$this->_alert('Message created','success');
		$this->data['chat'] = $chat;

		$this->_render('tools/chats/view.php');
	}

}
/* End of file */
