<form class="form-horizontal" method="post" action="<?=site_url("projects/create")?>">
	<div class="form-group">
		<label class="col-sm-2 control-label" for="name" >Name</label>
		<div class="col-sm-10">
			<input class="form-control" type="text" name="name" required>
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-sm-2 control-label" for="contact" >Beneficiary</label>
		<div class="col-sm-10">
			<select class="form-control" name="contact" required>
				<option value="" label="Choose"></option>
				<?php foreach ($contacts as $contact): ?>
				<option value="<?=$contact->id?>"><?=$contact->name?></option>
				<?php endforeach ?>
			</select>
			<?php if ($manage_contacts): ?>
			<p class="text-muted"><i title="Are shown all the contacts" class="fa fa-info-sign"></i> Does not exists ? <a href="<?=site_url('accounts/add')?>">Create new</a></p>
			<?php endif ?>
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-sm-2 control-label" for="end_date" >End Date</label>
		<div class="col-sm-10">
			<input class="form-control" type="date" name="end_date">
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-2 control-label" for="type" >Type</label>
		<div class="col-sm-10">
			<input class="form-control" type="text" name="type" data-provide="typeahead" data-source='<?=$types?>' autocomplete="off">
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-sm-2 control-label" for="status" >Status</label>
		<div class="col-sm-10">
			<select class="form-control" name="status" required>
				<?php foreach (Project::$statuses as $status): ?>
				<option value="<?=$status?>"><?=$status?></option>
				<?php endforeach ?>
			</select>
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-sm-2 control-label" for="label" >Label</label>
		<div class="col-sm-10">
			<input class="form-control" type="text" name="label" data-provide="typeahead" data-source='<?=$labels?>' autocomplete="off">
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-sm-2 control-label" for="label" >Description</label>
		<div class="col-sm-10">
			<p class="markdown-help label label-info pull-right"><i class="fa fa-info-circle"></i> Markdown</p>
			<textarea class="form-control" rows="4" name="description"></textarea>
		</div>
	</div>
	
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button type="submit" class="btn btn-success">
				<i class="fa fa-plus"></i> Create Project
			</button>
		</div>
	</div>
</form>
