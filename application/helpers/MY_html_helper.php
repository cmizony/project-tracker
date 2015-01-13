<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('convert_status'))
{
	function convert_status($status)
	{
		$status = strtolower($status);

		switch ($status)
		{

		case 'success':
		case 'finished':
		case 'approved':
		case 'won':
		case 'ready':
		case 'resolved':
		case 'create':
		case 'low':
			return 'success';
			break;
		case 'error':
		case 'declined':
		case 'rejected':
		case 'closed':
		case 'stopped':
		case 'delete':
		case 'critical':
			return 'danger';
			break;
		case 'warning':
		case 'lose':
		case 'empty':
		case 'in discussion':
		case 'login':
		case 'high':
		case 'new':
			return 'warning';
			break;
		case 'info':
		case 'pending':
		case 'processing':
		case 'in progress':
		case 'update':
		case 'medium':
		case 'assigned':
			return 'info';
			break;
		case 'open':
			return 'gray';
			break;
		default:
			return 'default';
		}
	}
}

if ( ! function_exists('empty_modal'))
{
	function empty_modal($class,$title='',$size='')
	{
		if (preg_match('/(Add|Edit) Thread/i',$title))
			$title = '<i class="fa fa-comment-o"></i> '.$title;
		else if (preg_match('/(Add|Edit) Iteration/i',$title))
			$title = '<i class="fa fa-tasks"></i> '.$title;
		else if (preg_match('/(Add|Edit) Contact/i',$title))
			$title = '<i class="fa fa-user"></i> '.$title;
		else if (preg_match('/(Add|Edit) File/i',$title))
			$title = '<i class="fa fa-file"></i> '.$title;
		else if (preg_match('/(Add|Edit) Ticket/i',$title))
			$title = '<i class="fa fa-ticket"></i> '.$title;
		else if (preg_match('/(Add|Edit) Task/i',$title))
			$title = '<i class="fa fa-check-square-o"></i> '.$title;
		else if (preg_match('/(Add|Edit) Project/i',$title))
			$title = '<i class="fa fa-list-alt"></i> '.$title;
		else if (preg_match('/(Close|Edit|Add) Timer/i',$title))
			$title = '<i class="fa fa-clock-o"></i> '.$title;

		// Header
		$out =  "<div class=\"$class modal fade\">";
		$out .= '	<div class="modal-dialog">';
		$out .= "		<div class=\"modal-content $size\">";
		if (!empty($title))
		{
			$out .= '			<div class="modal-header">';
			$out .= '				<button type="button" class="close" data-dismiss="modal" title="Close">&times;</button>';
			$out .= "				<h3>$title</h3>";
			$out .= '			</div>';
		}

		// Body
		$out .= '			<div class="modal-body">';
		$out .= '			</div>';

		$out .= '		</div>';
		$out .= '	</div>';
		$out .= '</div>';

		return $out;
	}
}

if ( ! function_exists('time_tracking'))
{
	function time_tracking($time_tracker_id)
	{
		if (!intval($time_tracker_id))
			return '';

		$base_url = site_url('tools/time_trackers/');

		$html = '	<button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">';
		$html .= '		<i class="fa fa-clock-o"></i> <span class="caret"></span>';
		$html .= '	</button>';
		$html .= '	<ul class="dropdown-menu" role="menu">';
		$html .= "		<li><a href='$base_url/start_timer/$time_tracker_id'><i class='fa fa-play'></i> Start Timer</a></li>";
		$html .= "		<li><a href='$base_url/index/$time_tracker_id'><i class='fa fa-search'></i> History</a></li>";
		$html .= '	</ul>';

		return $html;
	}
}

if ( ! function_exists('colored_tag'))
{
	function colored_tag($id,$color,$text,$date)
	{
		$fresh_tag = ($color == "gray" AND empty($text));
		$title = "Updated ".time_ago($date); //TODO format date X ago
		$info_sign = '<i class="fa fa-info-sign"></i> ';
		$colors = array('red','orange','yellow','green','blue','gray');

		// Popover
		$html = "<div class='hidden'>";
		$html .="<div class='form-inline' id='content-tag-$id'>";
		$html .="<select class=\"form-control\" class='input-small' name='color'>";
		foreach ($colors as $c)
		{
			$selected = $color == $c ? 'selected':'';
			$html .="<option class='tag-$c' $selected value='$c'>".humanize($c)."</option>";
		}	
		$html .="</select> ";
		$html .="<input class=\"form-control\" type='text' name='text' placeholder='Short description' class='input-medium' value='$text'> ";
		$html .="<div class='btn-group'>";
		$html .="<button title='Save' type='submit'  class='btn-primary btn btn-sm'>";
		$html .= '<i class="fa fa-check"></i>';
		$html .="</button>";
		$html .="<button title='Cancel' data-dismiss='popover' class='btn-danger btn btn-sm'>";
		$html .= '<i class="fa fa-times"></i>';
		$html .="</button>";
		$html .="</div>";
		$html .="</div>";
		$html .="</div>";

		// Tooltip

		if ($fresh_tag)
		{
			$text = $info_sign.repeater('&nbsp',13);
			$title = "Metadata (Members only).<br>$info_sign Click to Edit it";
		}
		else
			$text = "#".$text;

		$html .= "<span data-toggle='color-tag' data-id='$id' data-title='$title' class='color-tag label tag-$color'>$text</span>";

		return $html;
	}
}

if ( ! function_exists('markdown_replace'))
{
	function markdown_replace($text)
	{
		$text = preg_replace('/\#project-(\d+)/i',
			'<a style="text-decoration:none;" href="'.site_url("projects/read/$1").'"><span class="label label-default"><i class="fa fa-list-alt"></i> Project-$1</span></a>',$text);

		$text = preg_replace('/\#thread-(\d+)/i',
			'<a style="text-decoration:none;" href="'.site_url("projects/threads/read/$1").'"><span class="label label-default"><i class="fa fa-comment-o"></i> Thread-$1</span></a>',$text);

		$text = preg_replace('/\#iteration-(\d+)/i',
			'<a style="text-decoration:none;" href="'.site_url("projects/iterations/read/$1").'"><span class="label label-default"><i class="fa fa-tasks"></i> Iteration-$1</span></a>',$text);

		$text = preg_replace('/\#task-(\d+)/i',
			'<a style="text-decoration:none;" href="'.site_url("projects/tasks/read/$1").'"><span class="label label-default"><i class="fa fa-check-square-o"></i> Task-$1</span></a>',$text);

		$text = preg_replace('/\#ticket-(\d+)/i',
			'<a style="text-decoration:none;" href="'.site_url("projects/tickets/read/$1").'"><span class="label label-default"><i class="fa fa-ticket"></i> Ticket-$1</span></a>',$text);

		$text = preg_replace('/\#file-(\d+)/i',
			'<a style="text-decoration:none;" href="'.site_url("tools/files/read/$1").'"><span class="label label-default"><i class="fa fa-file"></i> File-$1</span></a>',$text);

		$text = preg_replace('/\#contact-(\d+)/i',
			'<a style="text-decoration:none;" href="'.site_url("accounts/read/$1").'"><span class="label label-default"><i class="fa fa-user"></i> Contact-$1</span></a>',$text);
		return $text;
	}
}

?>
