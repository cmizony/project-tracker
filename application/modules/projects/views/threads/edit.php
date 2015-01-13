<form class="form-horizontal" method="post" enctype="multipart/form-data" action="<?=site_url("projects/threads/update/$thread->id")?>">
	<div class="form-group">
		<label class="col-sm-2 control-label" for="title">Title</label>
		<div class="col-sm-10">
		<input class="form-control" type="text" name="title" required value="<?=$thread->title?>">
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<input type="file" name="file">
			<span class="text-muted">Image (.png .jpg)</span>
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-2 control-label" for="outline">Outline</label>
		<div class="col-sm-10">
		<textarea class="form-control" name="outline" rows="3" required><?=$thread->outline?></textarea>
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-2 control-label" for="description">Description</label>
		<div class="col-sm-10">
			<p class="markdown-help label label-info pull-right"><i class="fa fa-info-circle"></i> Markdown</p>
			<textarea class="form-control" name="description" rows="8"><?=$thread->description?></textarea>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button type="submit" class="btn btn-info"><i class="fa fa-pencil"></i> Update Thread</button>
		</div>
	</div>
</form>
