<form enctype="multipart/form-data" class="form-horizontal" method="post" action="<?=site_url("projects/tickets/create/$project_id")?>">
	<div class="form-group">
		<label class="col-sm-2 control-label" for="title">Title</label>
		<div class="col-sm-10">
			<input type="text" name="title" required class="form-control" >
		</div>
	</div>

	<?php if (isset ($projects)) :?>
	<div class="form-group">
		<label class="col-sm-2 control-label" for="project" >Project</label>
		<div class="col-sm-10">
			<select name="project" required class="form-control" >
				<option value="" label="Choose"></option>
				<?php foreach ($projects as $project): ?>
				<option value="<?=$project->id?>"><?=$project->name?></option>
				<?php endforeach ?>
			</select>
			<?php if ($manage_projects): ?>
			<p class="text-muted"><i title="Are shown all the projects" class="fa fa-info-sign"></i> Does not exists ? <a href="<?=site_url('projects/add')?>">Create new</a></p>
			<?php endif ?>
		</div>
	</div>
	<?php endif ?>

	<div class="form-group">
		<label class="col-sm-2 control-label" for="type">Type</label>
		<div class="col-sm-10">
			<select name="type" class="form-control" >
				<?php foreach (Ticket::$types as $type): ?>
				<option value="<?=$type?>"><?=$type?></option>
				<?php endforeach ?>
			</select>
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-2 control-label" for="priority">Priority</label>
		<div class="col-sm-10">
			<select name="priority" class="form-control" >
				<?php foreach (Ticket::$priorities as $priority): ?>
				<option value="<?=$priority?>" <?=$priority=="Medium"?'selected':''?>>
					<?=$priority?>
				</option>
				<?php endforeach ?>
			</select>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<input type="file" name="file">
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-2 control-label" for="description">Description</label>
		<div class="col-sm-10">
			<p class="markdown-help label label-info pull-right"><i class="fa fa-info-circle"></i> Markdown</p>
			<textarea name="description" rows="6" required class="form-control" ></textarea>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button type="submit" class="btn btn-success"><i class="fa fa-plus"></i> Create ticket</button>
		</div>
	</div>
</form>
