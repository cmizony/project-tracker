<div class="btn-group pull-right">
	<?php if ($manage_projects): ?>
	<button class="btn delete-project btn-danger" data-id="<?=$project->id?>" data-text="<?=$project->name?>" ><i class="fa fa-trash-o"></i></button>
	<?php endif ?>
</div>

<h1><i class="fa fa-list-alt"></i> <?=$project->name?></h1>

<div class="row contact-view clear">

	<div class="col-md-7 well">
		<div class="row">
			<div class="col-md-6">
				<div class="project-general">
					<p class="text-muted">
						General
						<?php if ($manage_projects): ?>
						<button data-href="<?=site_url("projects/edit/general/$project->id")?>" data-target=".project-general" class="btn pull-right btn-ajax-unique btn-info btn-sm"><i class="fa fa-pencil"></i></button>
						<?php endif ?>
					</p>
					<ul class="list-unstyled">
						<li><b>Name</b>: <?=$project->name?></li>
						<li><b>Type</b>: <?=$project->type?></li>
						<li><b>Status</b>: <span class="label label-<?=convert_status($project->status)?>"><?=$project->status?></span></li>
						<li><span class="badge"><?=$project->label?></span></li>
					</ul>
				</div>
			</div>
			<div class="col-md-6">
				<p class="text-muted">Beneficiary<p>
				<i class="fa fa-user"></i> 
				<?php if ($internal_access): ?> <a href="<?=site_url("accounts/view/".$project->beneficiary_id)?>"> <?php endif ?>
				<?=$project->beneficiary->name?><br>
				<?php if ($internal_access): ?> </a> <?php endif ?>

				<i class="fa fa-envelope-o"></i> <a href="mailto:<?=$project->beneficiary->email?>"><?=$project->beneficiary->email?></a><br>
				<i class="fa fa-building"></i> <?=$project->beneficiary->company?><br>
				</p>
			</div>
		</div>

		<div class="row">
			<div class="col-md-6">
				<span class="pull-right badge"><?=$project->count_tasks?></span>
				<a href="<?=site_url("projects/iterations/index/project/$project->id")?>"><i class="fa fa-tasks"></i> Tasks</a><br>
				<div class="progress">
					<?php if ($project->count_tasks > 0): ?>
					<div class="progress-bar progress-bar-warning" data-toggle="tooltip" data-placement="bottom" title="<?=$project->count_tasks_new?> New" style="width: <?=$project->count_tasks_new*100/$project->count_tasks?>%;"></div>
					<div class="progress-bar progress-bar-info" data-toggle="tooltip" data-placement="bottom" title="<?=$project->count_tasks_assigned?> Open" style="width: <?=$project->count_tasks_assigned*100/$project->count_tasks?>%;"></div>
					<div class="progress-bar progress-bar-danger" data-toggle="tooltip" data-placement="bottom" title="<?=$project->count_tasks_stopped?> Stopped" style="width: <?=$project->count_tasks_stopped*100/$project->count_tasks?>%;"></div>
					<div class="progress-bar progress-bar-success" data-toggle="tooltip" data-placement="bottom" title="<?=$project->count_tasks_finished?> Finished" style="width: <?=$project->count_tasks_finished*100/$project->count_tasks?>%;"></div>
					<?php endif ?>
				</div>
			</div>

			<div class="col-md-6">
				<span class="badge pull-right"><?=$project->count_tickets?></span>
				<a href="<?=site_url("projects/tickets/index/project/$project->id")?>"><i class="fa fa-ticket"></i> Tickets</a><br>
				<div class="progress">
					<?php if ($project->count_tickets > 0): ?> 
					<div class="progress-bar progress-bar-warning" data-toggle="tooltip" data-placement="bottom" title="<?=$project->count_tickets_open?> Open" style="width: <?=$project->count_tickets_open*100/$project->count_tickets?>%;"></div>
					<div class="progress-bar progress-bar-warning" data-toggle="tooltip" data-placement="bottom" title="<?=$project->count_tickets_in_discussion?> In Discussion" style="width: <?=$project->count_tickets_in_discussion*100/$project->count_tickets?>%;"></div>
					<div class="progress-bar progress-bar-info" data-toggle="tooltip" data-placement="bottom" title="<?=$project->count_tickets_in_progress?> In Progress" style="width: <?=$project->count_tickets_in_progress*100/$project->count_tickets?>%;"></div>
					<div class="progress-bar progress-bar-danger" data-toggle="tooltip" data-placement="bottom" title="<?=$project->count_tickets_closed?> Closed" style="width: <?=$project->count_tickets_closed*100/$project->count_tickets?>%;"></div>
					<div class="progress-bar progress-bar-success" data-toggle="tooltip" data-placement="bottom" title="<?=$project->count_tickets_resolved?> Resolved" style="width: <?=$project->count_tickets_resolved*100/$project->count_tickets?>%;"></div>
					<?php endif ?>
				</div>
			</div>
		</div>

		<div class="project-description">
			<?php if ($manage_projects): ?>
			<button data-href="<?=site_url("projects/edit/details/$project->id")?>" data-target=".project-description" class="btn pull-right btn-ajax-unique btn-info btn-sm"><i class="fa fa-pencil"></i></button>
			<?php endif ?>
			<p class="text-muted">
				Description
			</p>
			<div class="panel panel-default">
				<div class="panel-body markdown-content">
					<?=nl2br($project->description)?>
				</div>
			</div>
		</div>
		<hr>	
		<div class="text-muted text-right markdown-content">#project-<?=$project->id?> <small><em>Created: <?=date("F j, Y",strtotime($project->date))?></small></em></div>	
	</div>

	<div class="col-md-5 file-view">
		<div class="panel panel-default">
			<div class="panel-body">
				<ul class="nav nav-pills">
					<li class="active"><a data-toggle="tab" href="#tab-project-iterations"><i class="fa fa-tasks"></i> Iterations</a></li>
					<li><a data-toggle="tab" href="#tab-project-tickets"><i class="fa fa-ticket"></i> Tickets</a></li>
					<li><a data-toggle="tab" href="#tab-project-contacts"><i class="fa fa-group"></i> Contacts</a></li>
					<li><a data-toggle="tab" href="#tab-project-file"><i class="fa fa-folder-open"></i> Files</a></li>
					<li><a data-toggle="tab" href="#tab-project-log"><i class="fa fa-search"></i> Logs</a></li>
				</ul>
		
				<div class="tab-content">
					<div class="tab-pane" id="tab-project-file">
						<?=$rendered_folder?>
					</div>
					<div class="tab-pane" id="tab-project-log">
						<?=$rendered_logs?>
					</div>
					<div class="tab-pane active" id="tab-project-iterations">
						<?=$rendered_iterations?>
					</div>
					<div class="tab-pane" id="tab-project-tickets">
						<?=$rendered_tickets?>
					</div>
					<div class="tab-pane" id="tab-project-contacts">
						<?=$rendered_contacts?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="pull-right btn-group">
	<button data-href="<?=site_url("projects/threads/index_custom/mini/$project->id")?>" data-target=".threads-view" class="btn btn-ajax btn-default"><i class="fa fa-th"></i></button>
	<button data-href="<?=site_url("projects/threads/index_custom/grid/$project->id")?>" data-target=".threads-view" class="btn btn-ajax btn-default"><i class="fa fa-th-large"></i></button>
	<button data-href="<?=site_url("projects/threads/index_custom/accordion/$project->id")?>" data-target=".threads-view" class="btn btn-ajax btn-default"><i class="fa fa-list"></i></button>
	<button data-href="<?=site_url("projects/threads/index_custom/table/$project->id")?>" data-target=".threads-view" class="btn btn-ajax btn-default"><i class="fa fa-table"></i></button>
</div>

<h3>Threads
	<a data-toggle="modal" data-target=".modal-add-thread" href="<?=site_url("projects/threads/add/$project->id")?>"class="btn btn-success btn-sm"><i class="fa fa-plus"></i> New</a>
</h3>
<?=empty_modal("modal-add-thread","Add Thread")?>

<div class="threads-view">
	<?=$rendered_threads?>
</div>

<script defer>
	$(".delete-project").click(delete_project);
	$(".delete-file").click(delete_file);

	function delete_file ()
	{
		var id = $(this).data("id");
		var text = $(this).data("text");
		var url = ARNY.site_url+"tools/files/delete/"+id;
		confirm_delete(url,text);
	}

	function delete_project ()
	{
		var id = $(this).data("id");
		var text = $(this).data("text");
		var url = ARNY.site_url+"projects/delete/"+id;
		confirm_delete(url,text);
	}
</script>
