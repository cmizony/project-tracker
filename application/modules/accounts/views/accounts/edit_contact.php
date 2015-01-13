<form method="post" action="<?=site_url("accounts/update_contact/$contact->id")?>">
	<button class="btn btn-info btn-sm pull-right" type="submit"><i class="fa fa-save"></i> Save</button>

	<p class="text-muted">Profile</p>
	<br>
	
	<div class="input-group input-group">
		<span class="input-group-addon"><i class="fa fa-user icon-lg" title="Name"></i></span>
		<input type="text" class="form-control" title="Name" name="name" value="<?=$contact->name?>">
	</div>
	<div class="input-group" title="Phone">
		<span class="input-group-addon"><i class="fa fa-phone icon-lg"></i></span>
		<input type="text" class="form-control" name="phone" value="<?=$contact->phone?>">
	</div>
	<div class="input-group" title="Role">
		<span class="input-group-addon"><i class="fa fa-key icon-lg"></i></span>
		<select name="role" class="form-control">
			<?php foreach ($roles as $role): ?>
			<option value="<?=$role?>" <?=is_selected($role,$contact->role)?>><?=humanize($role)?></option>
			<?php endforeach ?>
		</select>
	</div>
	<div class="input-group" title="Company">
		<span class="input-group-addon"><i class="fa fa-briefcase icon-lg"></i></span>
		<input type="text" name="company" class="form-control" value="<?=$contact->company?>">
	</div>
	<textarea type="text" class="form-control" name="address"><?=$contact->address?></textarea>
	<br>

</form>
