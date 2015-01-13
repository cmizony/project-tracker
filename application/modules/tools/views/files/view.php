<a href="<?=site_url("tools/files/add/$folder->id")?>" class="btn btn-success btn-sm" title="Add File" data-toggle="modal" data-target=".modal-add-file">
	<i class="fa fa-plus"></i> New File
</a>
<?=empty_modal("modal-add-file","Add file")?>

<ul class="list-unstyled saved-collapse" style="margin-top:5px">
	<?php foreach ($folder->files as $file): ?>
	<li><a data-toggle="collapse" href="#collapse-file-<?=$file->id?>"><img src="<?=base_url($file->icon())?>" alt="icon" height="25" width="25"> <?=$file->title?></a><br>

	<div id="collapse-file-<?=$file->id?>" class="collapse">
		<div class="panel panel-default">
			<div class="panel-body">
				<div class="btn-group pull-right">
					<a class="btn btn-default btn-sm" title="Download" href="<?=site_url("tools/files/download/$file->id")?>" target="_blank"><i class="fa fa-download"></i></a>
					<?php if ($internal_access OR ($message->contact_id == $account_id)): ?>
					<a data-toggle="modal" href="<?=site_url("tools/files/edit/$file->id")?>" data-target=".modal-edit-file-<?=$file->id?>" class="btn btn-info btn-sm"><i class="fa fa-pencil"></i></a>
					<?php endif ?>
					<?php if ($internal_access OR ($message->contact_id == $account_id)): ?>
					<button data-text="<?=$file->title?>" class="delete-file btn btn-sm btn-sm btn-danger" data-id="<?=$file->id?>" title="Delete"><i class="fa fa-trash-o"></i></button>
					<?php endif ?>
				</div>
				<?=empty_modal("modal-edit-file-$file->id","Edit File")?>
				<ul class="list-unstyled">
					<li>Author:<?=nbs(5)?> <span class="text-muted"><?=$file->contact->name?></span></li>
					<li>Size:<?=nbs(9)?> <span class="text-muted"><?=humanize_file($file->size)?></span></li>
					<li>Date: <?=nbs(8)?><span class="text-muted"><?=format_date("F j, Y, g:i a",$file->date)?></span></li>
					<li>Download: <span class="text-muted"><?=$file->downloads?> times</span></li>
				</ul>

				<?php if (!empty($file->note)): ?>
				<div class="panel panel-default">
					<div class="panel-body markdown-content">
						<?=nl2br($file->note)?>
					</div>
				</div>
				<?php endif ?>
				<div class="text-muted text-right markdown-content">#file-<?=$file->id?> <small><em>Created: <?=date('F j, Y \a\t g:i a',strtotime($file->date))?></small></em></div>	
			</div>
		</div>
	</div>
	</li>
	<?php endforeach ?>
</ul>

<script defer>
	$(".delete-file").click(delete_file);

	function delete_file ()
	{
		var id = $(this).data("id");
		var text = $(this).data("text");
		var url = ARNY.site_url+"tools/files/delete/"+id;
		confirm_delete(url,text);
	}
</script>
