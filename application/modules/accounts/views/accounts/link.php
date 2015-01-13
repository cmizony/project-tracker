<form class="form-horizontal" method="post" action="<?=site_url("accounts/associate/$project->id")?>">
	<div class="form-group">
		<label class="col-sm-2 control-label" for="contact" >Contact</label>
		<div class="col-sm-10">
			<select name="contact" required class="form-control">
				<?php foreach ($contacts as $contact): ?>
				<option value="<?=$contact->id?>"><?=$contact->name?> (<?=$contact->login?>)</option>	
				<?php endforeach ?>
			</select>
			<?php if ($manage_contacts): ?>
			<p class="text-muted"><i title="Are shown only the one who are not already linked" class="fa fa-info-sign"></i> Does not exists ? <a href="<?=site_url('accounts/add')?>">Create new</a></p>
			<?php endif ?>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label" for="role" >Role</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" name="role" data-provide="typeahead" data-source='<?=$roles?>' autocomplete="off">
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-10">
			<button type="submit" class="btn btn-success">
				<i class="fa fa-link"></i> Link Contact
			</button>
		</div>
	</div>
</form>
