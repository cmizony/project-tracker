<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Time_trackers extends MY_Controller {

	public function index ($id = NULL)
	{
		$time_tracker = new Time_tracker ($id);

		if (!$this->acl->has_privilege('internal_access') OR
			!$time_tracker->exists())
			show_404();

		$time_intervals = new Time_interval();
		$time_intervals->where('time_tracker_id',$id);
		$time_intervals->where('end IS NOT ','NULL',false);
		$time_intervals->include_related('contact',array('name'));
		$time_intervals->get();

		$this->data['my_account_id'] = $this->account_id;
		$this->data['time_tracker'] = $time_tracker;
		$this->data['time_intervals'] = $time_intervals;

		$this->breadcrumb->push('Time Tracking','clock-o');

		$this->_render("tools/time_trackers/index.php");
	}

	public function index_mini ($id = NULL)
	{
		$time_tracker = new Time_tracker($id);	

		if (!$this->acl->has_privilege('internal_access') OR
			!$time_tracker->exists())
			show_404();

		$time_intervals = new Time_interval();
		$time_intervals->where('time_tracker_id',$id);
		$time_intervals->where('end IS NOT ','NULL',false);
		$time_intervals->include_related('contact',array('name'));
		$time_intervals->get();

		$this->data['time_tracker'] = $time_tracker;
		$this->data['time_intervals'] = $time_intervals;

		$this->_render("tools/time_trackers/index_mini.php");
	}

	public function add ($id = NULL)
	{
		$time_tracker = new Time_tracker($id);

		if (!$this->acl->has_privilege('internal_access') OR
			!$time_tracker->exists())
			show_404();

		$this->data['time_tracker'] = $time_tracker;

		$this->_render("tools/time_trackers/add.php");
	}

	public function create($id = NULL)
	{
		$time_tracker = new Time_tracker($id);

		if (!$this->acl->has_privilege('internal_access') OR
			!$time_tracker->exists())
			show_404();

		$time_interval = new Time_interval();

		$time_interval->time_tracker_id = $id;
		$time_interval->contact_id = $this->account_id;
		$time_interval->note = $this->input->post("note");
		$time_interval->start = format_date("Y-m-d ",$this->input->post('start_date')).$this->input->post("start_time");
		$time_interval->end = date("Y-m-d H:i:s",strtotime($time_interval->start) + intval($this->input->post('duration'))*60);

		$x_link = $time_interval->time_tracker->x_link();
		if ($x_link == "iterations")
			$this->_log($time_interval->id,'Create',"Create Time slip on Iteration","projects/iterations/view/".$time_interval->time_tracker->iteration->id,"Time Interval");
		else if ($x_link == "tickets")
			$this->_log($time_interval->id,'Create',"Create Time slip on Ticket","projects/tickets/view/".$time_interval->time_tracker->ticket->id,"Time Interval");

		$time_interval->save();

		$this->_alert("Timer saved",'success');

		$this->_redirect("tools/time_trackers/index/$id");
	}

	public function start_timer ($id = NULL)
	{
		$time_tracker = new Time_tracker ($id);

		if (!$this->acl->has_privilege('internal_access') OR
			!$time_tracker->exists())
			show_404();

		$time_intervals = new Time_interval();
		$time_intervals->where('contact_id',$this->account_id);
		$time_intervals->where('end IS ','NULL',false);
		$time_intervals->get();

		if ($time_intervals->result_count() > 0)
			$this->_alert("Current time tracking closed",'success');

		foreach ($time_intervals as $time_interval)
		{
			$time_interval->end = date("Y-m-d H:i:s");
			$time_interval->save();
		}

		$time_interval = new Time_interval();
		$time_interval->contact_id = $this->account_id;
		$time_interval->start = date("Y-m-d H:i:s");
		$time_interval->end = NULL;
		$time_interval->time_tracker_id = $id;
		$time_interval->save();

		$this->_alert("Timer started",'success');

		$this->_redirect_previous();
	}

	public function edit ($time_interval_id = NULL)
	{
		$time_interval = new Time_interval($time_interval_id);

		if (!$this->acl->has_privilege('internal_access') OR
			!$time_interval->exists())
			show_404();

		$this->data['time_interval'] = $time_interval;

		$this->_render("tools/time_trackers/edit.php");
	}

	public function update ($time_interval_id = NULL)
	{
		$time_interval = new Time_interval($time_interval_id);

		if (!$this->acl->has_privilege('internal_access') OR
			!$time_interval->exists())
			show_404();

		$time_interval->note = $this->input->post("note");

		$x_link = $time_interval->time_tracker->x_link();

		if (is_null($time_interval->end))
		{
			$time_interval->end = date("Y-m-d H:i:s");

			if ($x_link == "iterations")
				$this->_log($time_interval->id,'Create',"Create Time slip on Iteration","projects/iterations/view/".$time_interval->time_tracker->iteration->id,"Time Interval");
			else if ($x_link == "tickets")
				$this->_log($time_interval->id,'Create',"Create Time slip on Ticket","projects/tickets/view/".$time_interval->time_tracker->ticket->id,"Time Interval");
		}
		else
		{
			$time_interval->start = format_date("Y-m-d ",$this->input->post('start_date')).$this->input->post("start_time");
			$time_interval->end = date("Y-m-d H:i:s",strtotime($time_interval->start) + intval($this->input->post('duration'))*60);

			if ($x_link == "iterations")
				$this->_log($time_interval->id,'Update',"Update Time slip on Iteration","projects/iterations/view/".$time_interval->time_tracker->iteration->id,"Time Interval");
			else if ($x_link == "tickets")
				$this->_log($time_interval->id,'Update',"Update Time slip on Ticket","projects/tickets/view/".$time_interval->time_tracker->ticket->id,"Time Interval");
		}

		$time_interval->save();

		$this->_alert("Timer saved",'success');

		$this->_redirect_previous();
	}

	public function delete ($time_interval_id = NULL)
	{
		$time_interval = new Time_interval($time_interval_id);

		if (!$this->acl->has_privilege('internal_access') OR
			!$time_interval->exists() OR
			$this->account_id != $time_interval->contact_id)
			show_404();

		$x_link = $time_interval->time_tracker->x_link();

		if ($x_link == "iterations")
			$this->_log($time_interval->id,'Delete',"Delete Time slip on Iteration","projects/iterations/view/".$time_interval->time_tracker->iteration->id,"Time Interval");
		else if ($x_link == "tickets")
			$this->_log($time_interval->id,'Delete',"Delete time slip on Ticket","projects/tickets/view/".$time_interval->time_tracker->ticket->id,"Time Interval");
		
		$time_interval->delete();
		$this->_alert("Timer slip deleted",'success');

		$this->_redirect_previous();
	}
}
/* End of file */
