<table class="table table-striped table-bordered datatable-default table-condensed">
	<thead>
		<tr>
			<th>#</th>
			<th>Tag</th>
			<th>Title</th>
			<th>Status</th>
			<th>Priority</th>
			<th>Label</th>
			<th>Responsible</th>
			<th>Start</th>
			<th>Estimated</th>
			<th class="col-md-2 no-sort">Actions</th>
		</tr>
	</thead>

	<tbody>
		<?php foreach ($tasks as $task): ?>
		<tr class="<?=convert_status($task->status)?>">
			<td><?=$task->id?></td>
			<td>
				<span id="box-tag-<?=$task->tag_id?>">
					<?=colored_tag($task->tag_id,$task->tag_color,$task->tag_text,$task->tag_date) ?>
				</span>
			</td>
			<td>
				<a data-toggle="modal" href="<?=site_url("projects/tasks/view/$task->id")?>" data-target=".modal-task-view-<?=$task->id?>">
				<?=$task->title?>
				</a>
			</td>
			<td><span class="label label-<?=convert_status($task->status)?>"><?=$task->status?></span></td>
			<td><span class="label label-<?=convert_status($task->priority)?>"><?=$task->priority?></span></td>
			<td><span class="badge"><?=$task->label?></span></td>
			<td><a href="<?=site_url("accounts/view/$task->contact_id")?>"><?=$task->contact_name?></a></td>
			<td><?=format_date("F j, Y",$task->start_date)?></td>
			<td><span class="label label-info"><?=humanize_sec($task->estimated)?></span></td>
			<td>
				<div class="btn-group">
					<a data-toggle="modal" href="<?=site_url("projects/tasks/edit/$task->id")?>" data-target=".modal-edit-task-<?=$task->id?>" class="btn btn-info btn-sm"><i class="fa fa-pencil"></i></a>
					<button data-text="<?=$task->title?>" class="delete-task btn btn-sm btn-danger" data-id="<?=$task->id?>" title="Delete"><i class="fa fa-trash-o"></i></button>
				</div>
			</td>
		</tr>
		<?php endforeach ?>
	</tbody>
</table>

<?php foreach ($tasks as $task): ?>
<?=empty_modal("modal-edit-task-$task->id","Edit Task")?>
<?=empty_modal("modal-task-view-$task->id",'','modal-lg')?>
<?php endforeach ?>

<script defer>
	$(".delete-task").click(delete_task);

	function delete_task ()
	{
		var id = $(this).data("id");
		var text = $(this).data("text");
		var url = ARNY.site_url+"projects/tasks/delete/"+id;
		confirm_delete(url,text);
		return false;
	}
</script>
