<form class="form-horizontal" method="post" action="<?=site_url('accounts/create')?>">
	<fieldset>
		<legend>Account</legend>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="login" >Login</label>
			<div class="col-sm-10">
				<div class="input-group">
					<span class="input-group-addon"><i class="fa fa-sign-in"></i></span>
					<input class="form-control" type="text" name="login" required>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="name">Role</label>
			<div class="col-sm-10">
				<div class="input-group">
					<span class="input-group-addon"><i class="fa fa-key"></i></span>
					<select class="form-control" name="role">
						<?php foreach ($roles as $role): ?>
						<option value="<?=$role?>"><?=humanize($role)?></option>
						<?php endforeach ?>
					</select>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="email">Email</label>
			<div class="col-sm-10">
				<div class="input-group">
					<span class="input-group-addon"><i class="fa fa-envelope-o"></i></span>
					<input class="form-control" type="email" name="email" required placeholder="example@example.com">
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="password" >Password</label>
			<div class="col-sm-10">
				<div class="input-group">
					<span class="input-group-addon"><i class="fa fa-lock"></i></span>
					<input class="form-control" type="password" name="password" required>
				</div>
			</div>
		</div>
	</fieldset>


	<fieldset>
		<legend>Contact</legend>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="name">Name</label>
			<div class="col-sm-10">
				<div class="input-group">
					<span class="input-group-addon"><i class="fa fa-user"></i></span>
					<input class="form-control" type="text" name="name">
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="company">Company</label>
			<div class="col-sm-10">
				<div class="input-group">
					<span class="input-group-addon"><i class="fa fa-briefcase"></i></span>
					<input class="form-control" type="text" name="company">
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="address">Address</label>
			<div class="col-sm-10">
				<textarea class="form-control" type="text" name="address"></textarea>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="description">Description</label>
			<div class="col-sm-10">
				<textarea class="form-control" type="text" name="description"></textarea>
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-10 col-sm-offset-2">
				<button type="submit" class="btn btn-success">
					<i class="fa fa-plus"></i> Create Account
				</button>
			</div>
		</div>
	</fieldset>
</form>
