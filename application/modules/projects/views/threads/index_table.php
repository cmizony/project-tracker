<table class="table table-striped table-bordered datatable-default">
	<thead>
		<tr>
			<th>#</th>
			<th>Title</th>
			<th>Date</th>
			<th>Outline</th>
			<th>Description</th>
			<th class="col-md-2">Actions</th>
		</tr>
	</thead>

	<tbody>
		<?php foreach ($threads as $thread): ?>
		<tr>
			<td><?=$thread->id?></td>
			<td>
				<a data-toggle="modal" href="<?=site_url("projects/threads/view/$thread->id")?>" data-target=".modal-thread-view-<?=$thread->id?>">
				<?=$thread->title?>
				</a>
			</td>
			<td><?=format_date("F j, Y",$thread->date)?></td>
			<td><?=$thread->outline?></td>
			<td class="markdown-content"><?=$thread->description?></td>
			<td>
				<div class="btn-group">
					<a data-toggle="modal" href="<?=site_url("projects/threads/edit/$thread->id")?>" data-target=".modal-edit-thread-<?=$thread->id?>" class="btn btn-sm btn-info"><i class="fa fa-pencil"></i></a>
					<?php if ($internal_access): ?>
					<button data-text="<?=$thread->title?>" class="delete-thread btn btn-danger btn-sm " data-id="<?=$thread->id?>" title="Delete"><i class="fa fa-trash-o"></i></button>
					<?php endif ?>
				</div>
			</td>
		</tr>
		<?php endforeach ?>
	</tbody>
</table>

<? foreach ($threads as $thread):  ?>
<?=empty_modal("modal-thread-view-$thread->id",'','modal-lg')?>
<?=empty_modal("modal-edit-thread-$thread->id","Edit Thread")?>
<?php endforeach ?>

<script defer>
$(function(){
	$(".delete-thread").unbind("click").click(delete_thread);

	function delete_thread ()
	{
		var id = $(this).data("id");
		var text = $(this).data("text");
		var url = ARNY.site_url+"projects/threads/delete/"+id;
		confirm_delete(url,text);
		return false;
	}
});
</script>
