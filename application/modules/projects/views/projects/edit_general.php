<form method="post" action="<?=site_url("projects/update/general/$project->id")?>">
	<p class="text-muted">
	General
	<button class="btn btn-info pull-right btn-sm" type="submit"><i class="fa fa-save"></i> Save</button>
	</p>
	<ul class="list-unstyled">
		<li>Name<?=nbs(1)?> <input type="text" class="form-control" name="name" value="<?=$project->name?>" required></li>
		<li>Type<?=nbs(2)?> <input type="text" class="form-control" name="type" data-provide="typeahead" data-source='<?=$types?>' autocomplete="off" value="<?=$project->type?>"></li>
		<li>Status 
		<select name="status" class="form-control">
			<?php foreach (Project::$statuses as $status): ?>
			<option value="<?=$status?>" <?=$project->status==$status?'selected':''?>><?=$status?></option>
			<?php endforeach ?>
		</select>
		</li>
		<li>Label<?=nbs(4)?> <input type="text" class="form-control" name="label" data-provide="typeahead" data-source='<?=$labels?>' value="<?=$project->label?>" autocomplete="off"></li>
	</ul>
</form>
