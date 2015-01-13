<div class="row">
	<?php
	$count = 0;
	foreach ($tasks as $task): 
	?>
	<?php if ($count != 0 AND ($count % 4) == 0): ?>
	</div>
	<div class="row">
	<?php endif ?>

	<div class="col-md-3 task-view">
		<div class="panel panel-<?=convert_status($task->status)?>" data-toggle="modal" href="<?=site_url("projects/tasks/view/$task->id")?>" data-target=".modal-task-view-<?=$task->id?>">
			<div class="panel-heading">
				<div class="container-fluid">
					<div class="row">
						<h5><i class="fa fa-check-square-o"></i> <?=$task->title?>
							<div class="btn-group pull-right">
								<a href="<?=site_url("projects/tasks/edit/$task->id")?>" data-target=".modal-edit-task-<?=$task->id?>" class="btn btn-info btn-sm edit-task"><i class="fa fa-pencil"></i></a>
								<button data-text="<?=$task->title?>" class="delete-task btn btn-sm btn-danger" data-id="<?=$task->id?>" title="Delete"><i class="fa fa-trash-o"></i></button>
							</div>
						</h5>
					</div>
				</div>
			</div>

			<div class="panel-body">
				
				<p class="pull-right label label-<?=convert_status($task->status)?>"><?=$task->status?></p>
				<ul class="list-unstyled">
					<li><span class="badge"><?=$task->label?></span></li>
					<li><span class="text-muted">Responsible:</span> <?=$task->contact_name?></li>
					<li><span class="text-muted">Priority:</span> <span class="label label-<?=convert_status($task->priority)?>"><?=$task->priority?></span></li>
					<li><span class="text-muted">Start:</span> <?=format_date("F j, Y",$task->start_date)?></li>
					<li><span class="text-muted">Estimated:</span> <span class="label label-info"><?=humanize_sec($task->estimated)?></span></li>
				</ul>
		
				<hr>	
				<div class="text-muted text-right markdown-content">#task-<?=$task->id?> <small><em>Created: <?=date("F j, Y",strtotime($task->date))?></small></em></div>	
			</div>	
		</div>	
	</div>	
	<?php $count++; endforeach ?>
</div>

<?php foreach ($tasks as $task): ?>
<?=empty_modal("modal-edit-task-$task->id","Edit Task")?>
<?=empty_modal("modal-task-view-$task->id",'','modal-lg')?>
<?php endforeach ?>

<script defer>
	$(".delete-task").click(delete_task);
	$(".edit-task").click(edit_task);

	function delete_task ()
	{
		var id = $(this).data("id");
		var text = $(this).data("text");
		var url = ARNY.site_url+"projects/tasks/delete/"+id;
		confirm_delete(url,text);
		return false;
	}

	function edit_task ()
	{
		var modal = $($(this).data("target"));
		var modal_href = $(this).attr("href");

		modal.on('show.bs.modal', function () {
			$(this).find('.modal-body').load(modal_href);
		}).on('shown.bs.modal', function (){
			bind_all_markdown();
			bind_all_tag();
		}).modal();

		return false;
	}
</script>
