<form method="post" action="<?=site_url("projects/update/details/$project->id")?>">
	<p class="text-muted">
	Description
	<button class="btn btn-info pull-right btn-sm" type="submit"><i class="fa fa-save"></i> Save</button>
	</p>
	<p class="markdown-help label label-info"><i class="fa fa-info-circle"></i> Markdown</p>
	<textarea class="form-control" name="description" class="textarea-full"  rows=10><?=$project->description?></textarea>
</form>
