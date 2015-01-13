<div class="row">
	<?php
	$count = 0;
	foreach ($threads as $thread): 
	?>
	<?php if ($count != 0 AND ($count % 3) == 0): ?>
</div>
<div class="row">
	<?php endif ?>

	<div class="col-md-4 thread-view">
		<div class="panel panel-default" data-toggle="modal" href="<?=site_url("projects/threads/view/$thread->id")?>" data-target=".modal-thread-view-<?=$thread->id?>">
			<div class="panel-heading">
				<h4>
					<i class="fa fa-comment-o"></i> <?=$thread->title?>
					<div class="btn-group pull-right">
						<a href="<?=site_url("projects/threads/edit/$thread->id")?>" data-target=".modal-edit-thread-<?=$thread->id?>" class="edit-thread btn btn-sm btn-info"><i class="fa fa-pencil"></i></a>
						<?php if ($internal_access): ?>
						<button data-text="<?=$thread->title?>" class="delete-thread btn btn-danger btn-sm " data-id="<?=$thread->id?>" title="Delete"><i class="fa fa-trash-o"></i></button>
						<?php endif ?>
					</div>
				</h4>
			</div>

			<div class="panel-body">	
				<?php if ($thread->thumbnail_exists()): ?>
				<div class="container-fluid">
					<div class="row">
						<img src="<?=site_url("projects/threads/thumbnail/$thread->id")?>" class="img-rounded" width="80px" align=left style="margin:9px;">
						<p class="text-muted"><?=nl2br(character_limiter($thread->outline,100))?></p>
					</div>
				</div>
				<?php else: ?>
				<p class="text-muted"><?=nl2br(character_limiter($thread->outline,100))?></p>
				<?php endif ?>

				<hr>
				<p class="markdown-content"><?=nl2br($thread->description)?></p>

				<hr>	
				<div class="text-muted text-right markdown-content">#thread-<?=$thread->id?> <small><em>Created: <?=date("F j, Y",strtotime($thread->date))?></small></em></div>	
			</div>	
		</div>	
	</div>	
	<?php $count++; endforeach ?>
</div>

<? foreach ($threads as $thread):  ?>
<?=empty_modal("modal-thread-view-$thread->id",'','modal-lg')?>
<?=empty_modal("modal-edit-thread-$thread->id","Edit Thread")?>
<?php endforeach ?>

<script defer>
$(function(){
	$(".delete-thread").unbind("click").click(delete_thread);
	$(".edit-thread").click(edit_thread);

	function delete_thread ()
	{
		var id = $(this).data("id");
		var text = $(this).data("text");
		var url = ARNY.site_url+"projects/threads/delete/"+id;
		confirm_delete(url,text);
		return false;
	}
	function edit_thread ()
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
});
</script>
