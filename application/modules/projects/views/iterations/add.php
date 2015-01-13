<form class="form-horizontal" method="post" action="<?=site_url("projects/iterations/create/$project_id")?>">
	<div class="form-group">
		<label class="col-sm-2 control-label" for="title">Title</label>
		<div class="col-sm-10">
			<input class="form-control" type="text" name="title" required>
		</div>
	</div>

	<?php if (isset ($projects)) :?>
	<div class="form-group">
		<label class="col-sm-2 control-label" for="project" >Project</label>
		<div class="col-sm-10">
			<select class="form-control" name="project" required>
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
		<label class="col-sm-2 control-label" for="start_date">Start Date</label>
		<div class="col-sm-10">
			<input class="form-control" type="date" name="start_date" value="<?=date('Y-m-d',max(strtotime($iteration->start_date)+$iteration->time,time()))?>">
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-2 control-label" for="time">Duration</label>
			<div class="col-sm-2">
				<input class="form-control" type="number" name="time" class="input-mini" min="0" step="0.1" value=1>
			</div>
			<div class="col-sm-8">
				<select class="form-control" name="unit" class="input-small">
					<option value="day">Days</option>
					<option value="week">Weeks</option>
					<option value="month">Months</option>
				</select>
			</div>
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-2 control-label" for="label">Label</label>
		<div class="col-sm-10">
			<input class="form-control" type="text" name="label">
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-2 control-label" for="description">Description</label>
		<div class="col-sm-10">
			<p class="markdown-help label label-info pull-right"><i class="fa fa-info-circle"></i> Markdown</p>
			<textarea class="form-control" name="description" rows="4" required></textarea>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button type="submit" class="btn btn-success"><i class="fa fa-plus"></i> Create iteration</button>
		</div>
	</div>
</form>
