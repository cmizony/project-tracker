<form class="form-horizontal" method="post" enctype="multipart/form-data" action="<?=site_url("tools/files/upload/$folder->id")?>">
	<div class="form-group">
		<label class="col-sm-2 control-label" for="title">Title</label>
		<div class="col-sm-10">
			<input type="text" name="title" required class="form-control">
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-2 control-label" for="note">Note</label>
		<div class="col-sm-10">
			<p class="markdown-help label label-info pull-right"><i class="fa fa-info-circle"></i> Markdown</p>
			<textarea rows=6 name="note" class="form-control"></textarea>
		</div>
	</div>
		
	<div class="form-group">
		<label class="col-sm-2 control-label" for="file">File</label>
		<div class="col-sm-10">
			<input type="file" name="file" required>
			<p class="text-muted">Max size 8MB</p>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button type="submit" class="btn btn-success"><i class="fa fa-upload"></i> Upload file</button>
		</div>
	</div>
</form>
