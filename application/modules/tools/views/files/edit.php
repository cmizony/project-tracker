<form class="form-horizontal" method="post" action="<?=site_url("tools/files/update/$file->id")?>">
	<div class="form-group">
		<label class="col-sm-2 control-label" for="name">Title</label>
		<div class="col-sm-10">
		<input class="form-control" type="text" name="title" required value="<?=$file->title?>">
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-2 control-label" for="note">Note</label>
		<div class="col-sm-10">
		<p class="markdown-help label label-info pull-right"><i class="fa fa-info-circle"></i> Markdown</p>
		<textarea class="form-control" name="note" rows="6"><?=$file->note?></textarea>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button type="submit" class="btn btn-info"><i class="fa fa-pencil"></i> Update file</button>
		</div>
	</div>
</form>
