<div class="row">
	<?php if ($manage_projects): ?>
	<div class="col-md-3">
		<a class="btn btn-success" data-target=".modal-add-project" data-toggle="modal"  href="<?=site_url("projects/add")?>">
			<i class="fa fa-plus"></i> Create Project
		</a>
	</div>
	<?=empty_modal('modal-add-project','Add Project')?>
	<?php endif ?>
	<div class="alert alert-success col-md-6 text-center"><i class="fa fa-list-alt"></i> <?=$page_title?></div>
</div>

<div style="display:none">
	<?php foreach ($projects as $project): ?>
	<div id="project-vignette-<?=$project->id?>">
		<div class="col-md-3">
			<div class="panel panel-<?=convert_status($project->status)?>">
				<div class="panel-heading">
					<a href="<?=site_url("projects/view/$project->id")?>"><i class="fa fa-list-alt"></i> <?=$project->name?></a>
				</div>

				<div class="panel-body">
					<span class="pull-right badge"><?=$project->label?></span>
					<p><?=$project->type?></p>

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

					<hr>	
					<div class="text-muted text-right markdown-content">#project-<?=$project->id?> <small><em>Created: <?=date("F j, Y",strtotime($project->date))?></small></em></div>	
				</div>	
			</div>	
		</div>	
	</div>	
	<?php endforeach ?>
</div>	

<div id="project-box-vignettes">
</div>

<table class="table table-striped table-bordered table-condensed projects-datatable">
	<thead>
		<tr>
			<th>#</th>
			<th>Name</th>
			<th>Type</th>
			<th>Status</th>
			<th>Label</th>
			<th>Creation Date</th>
			<th>Ticket Count</th>
			<th>Task Count</th>
		</tr>
	</thead>

	<tbody>
		<?php foreach ($projects as $project): ?>
		<tr>
			<td><?=$project->id?></td>
			<td><a href="<?=site_url("projects/view/$project->id")?>"><?=$project->name?></a></td>
			<td><?=$project->type?></td>
			<td><span class="label label-<?=convert_status($project->status)?>"><?=$project->status?></span></td>
			<td><span class="badge"><?=$project->label?></span></td>
			<td><?=date("F j, Y",strtotime($project->date))?></td>
			<td><?=$project->count_tickets?></td>
			<td><?=$project->count_tasks?></td>
		</tr>
		<?php endforeach ?>
	</tbody>
</table>

<script defer>
$(function() {
	var global_projects_timeout;
	
	var projects_dtt = $('.projects-datatable').dataTable( {
		"aaSorting": [],
		"iDisplayLength": 4,
		"aLengthMenu": [[4, 8, 16, 24, -1], [4, 8,16, 24, "All"]],
		"aoColumnDefs": [{ "bVisible": false, "aTargets": [0,6,7] }],
		"sDom": "<'row'<'col-md-5'R l><'col-md-7'C f>r>t<'row'<'col-md-4'i><'col-md-4'T><'col-md-4'p>>",
		"oTableTools": {"sSwfPath": ARNY.swf_path,"aButtons": ["print","copy","csv","xls",]},
		"oColVis": { "aiExclude": [ 0 ] },
		"bStateSave": true,
		"sPaginationType": "bootstrap",
		"oLanguage": {"sSearch": "Filter:"},
		"fnDrawCallback": function (o) {
			$(".ColVis_MasterButton",o.nTableWrapper).addClass("btn btn-default");
			window.clearTimeout(global_projects_timeout);
			global_projects_timeout = window.setTimeout(refresh_vignettes,500);
		},
		"fnInitComplete": function(oSettings, json) {
			$(this).removeClass("projects-datatable");
		}
	
	});
	
	function refresh_vignettes ()
	{
		var o = projects_dtt.fnSettings();
		var count_vignette = 0;
		var html_vignettes = "";

		o.oInstance._('tr', {"page": "current"}).forEach(function(entry){
			if (count_vignette % 4 == 0)
				html_vignettes += '</div><div class="row">';
			
			vignette = $("#project-vignette-"+entry[0]).clone();
			html_vignettes += vignette.html();
			count_vignette ++;
		});

		$("#project-box-vignettes").empty().html(html_vignettes);
		$("#project-box-vignettes [data-toggle='tooltip']").tooltip(); 
	}
});
</script>
