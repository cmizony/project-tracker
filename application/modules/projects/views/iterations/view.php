
<div class="alert alert-info pull-right" data-toggle="tooltip" title="Non-Members can not view this page" data-placement="bottom"><i class="fa fa-key"></i> Member Area</div>
<h1 class="row">
	<i class="fa fa-tasks"></i> <?=$iteration->title?>
	<div class="btn-group">
		<a data-toggle="modal" data-target=".modal-edit-iteration-<?=$iteration->id?>" class="btn btn-info" href="<?=site_url("projects/iterations/edit/$iteration->id")?>" ><i class="fa fa-pencil"></i></a>
		<button class="btn delete-iteration btn-danger" data-id="<?=$iteration->id?>" data-text="<?=$iteration->title?>"><i class="fa fa-trash-o"></i></button>
		<?=time_tracking($iteration->time_tracker_id)?>
	</div>
</h1>
<?=empty_modal("modal-edit-iteration-$iteration->id","Edit Iteration")?>

<div class="row">
	<div class="col-md-7 well">
		<table class="table table-bordered">
			<tbody>
				<tr class="gray">
					<td>
						<div class="text-primary text-center"><?=format_date('F j, Y',$iteration->start_date)?>&nbsp;</div>
						<small class="text-left text-muted"><em>Start</em></small>
					</td>
					<td>
						<div class="text-primary text-center"><?=date('F j, Y',strtotime($iteration->start_date)+$iteration->time)?>&nbsp;</div>
						<small class="text-left text-muted"><em>End</em></small>
					</td>
					<td>
						<div class="text-primary text-center"><?=humanize_sec($iteration->time)?>&nbsp;</div>
						<small class="text-left text-muted"><em>Duration</em></small>
					</td>
					<td>
						<div class="text-primary text-center"><?=humanize_sec(strtotime($iteration->start_date)+$iteration->time-time())?>&nbsp;</div>
						<small class="text-left text-muted"><em>Due in</em></small>
					</td>
				</tr>
			</tbody>
		</table>

		<ul class="list-unstyled">
			<li><span class="pull-right label label-<?=convert_status($iteration->status)?>"><?=$iteration->status?></li>
			<li><span class="badge"><?=$iteration->label?></span></li>
		</ul>	
		<hr>

		<div class="row">
			<div class="col-md-6">
				<span class="pull-right badge"><?=$iteration->count_tasks?></span>
				<i class="fa fa-tasks"></i> Tasks<br>
				<div class="progress">
					<?php if ($iteration->count_tasks > 0): ?>
					<div class="progress-bar progress-bar-warning" data-toggle="tooltip" data-placement="bottom" title="<?=$iteration->count_tasks_new?> New" style="width: <?=$iteration->count_tasks_new*100/$iteration->count_tasks?>%;"></div>
					<div class="progress-bar progress-bar-info" data-toggle="tooltip" data-placement="bottom" title="<?=$iteration->count_tasks_assigned?> Open" style="width: <?=$iteration->count_tasks_assigned*100/$iteration->count_tasks?>%;"></div>
					<div class="progress-bar progress-bar-danger" data-toggle="tooltip" data-placement="bottom" title="<?=$iteration->count_tasks_stopped?> Stopped" style="width: <?=$iteration->count_tasks_stopped*100/$iteration->count_tasks?>%;"></div>
					<div class="progress-bar progress-bar-success" data-toggle="tooltip" data-placement="bottom" title="<?=$iteration->count_tasks_finished?> Finished" style="width: <?=$iteration->count_tasks_finished*100/$iteration->count_tasks?>%;"></div>
					<?php endif ?>
				</div>
			</div>
			<div class="col-md-6">
				<span class="pull-right badge"><?=round(min(1,abs(time()-strtotime($iteration->start_date))/$iteration->time)*100)?>%</span>
				<i class="fa fa-calendar"></i> Due Date<br>
				<div class="progress">
					<div class="progress-bar" style="width:<?=round(min(1,abs(time()-strtotime($iteration->start_date))/$iteration->time)*100)?>%"></div>
				</div>
			</div>
		</div>

		<div class="panel panel-default">
			<div class="panel-body markdown-content">
				<?=empty($iteration->description)?'Description':nl2br($iteration->description)?>
			</div>
		</div>

		<hr>	
		<div class="text-muted text-right markdown-content">#iteration-<?=$iteration->id?> <small><em>Created: <?=date("F j, Y",strtotime($iteration->date))?></small></em></div>	
	</div>
	<div class="col-md-5">
		<div class="panel-default panel">
			<div class="panel-body">
				<ul class="nav nav-pills">
					<li class="active"><a data-toggle="tab" href="#tab-iteration-general"><i class="fa fa-info-sign"></i> General</a></li>
					<li><a data-toggle="tab" href="#tab-iteration-file"><i class="fa fa-folder-open"></i> Files</a></li>
					<li><a data-toggle="tab" href="#tab-iteration-timers"><i class="fa fa-clock-o"></i> Time</a></li>
					<li><a data-toggle="tab" href="#tab-iteration-log"><i class="fa fa-search"></i> Logs</a></li>
				</ul>

				<div class="tab-content">
					<div class="tab-pane active" id="tab-iteration-general">
						<h4>Project</h4>
						<ul>
							<li><a href="<?=site_url("projects/view/$iteration->project_id")?>"><?=$iteration->project->name?></a></li>
							<li><?=$iteration->project->type?></li>
							<li><?=$iteration->project->status?></li>
						</ul>
						<h4>Beneficiary</h4>

						<ul class="list-unstyled">
							<li><i class="fa fa-user"></i> <a href="<?=site_url("accounts/view/".$iteration->project->beneficiary_id)?>"><?=$iteration->project->beneficiary->name?></a></li>
							<li><i class="fa fa-envelope-o"></i> <a href="mailto:<?=$iteration->project->beneficiary->email?>"><?=$iteration->project->beneficiary->email?></a></li>
							<li><i class="fa fa-building"></i> <?=$iteration->project->beneficiary->company?></li>
						</ul>
					</div>
					<div class="tab-pane" id="tab-iteration-log">
						<?=$rendered_logs?>
					</div>
					<div class="tab-pane" id="tab-iteration-file">
						<?=$rendered_folder?>
					</div>
					<div class="tab-pane" id="tab-iteration-timers">
						<?=$rendered_time_trackers?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<h3>
	Tasks
	<a data-toggle="modal" data-target=".modal-add-task" href="<?=site_url("projects/tasks/add/$iteration->id")?>"class="btn btn-success btn-sm"><i class="fa fa-plus"></i> New</a>

	<div class="pull-right btn-group">
		<button data-href="<?=site_url("projects/tasks/index_custom/mini/$iteration->id")?>" data-target=".tasks-view" class="btn btn-ajax btn-default"><i class="fa fa-th"></i></button>
		<button data-href="<?=site_url("projects/tasks/index_custom/grid/$iteration->id")?>" data-target=".tasks-view" class="btn btn-ajax btn-default"><i class="fa fa-th-large"></i></button>
		<button data-href="<?=site_url("projects/tasks/index_custom/table/$iteration->id")?>" data-target=".tasks-view" class="btn btn-ajax btn-default"><i class="fa fa-table"></i></button>
	</div>
</h3>
<?=empty_modal("modal-add-task","Add Task")?>

<div class="tasks-view">
	<?=$rendered_tasks?>
</div>

<script defer>
	$(".delete-iteration").click(delete_iteration);

	function delete_iteration ()
	{
	var id = $(this).data("id");
	var text = $(this).data("text");
	var url = ARNY.site_url+"projects/iterations/delete/"+id;
	confirm_delete(url,text);
	}
</script>
