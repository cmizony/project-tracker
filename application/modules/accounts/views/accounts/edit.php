<form class="form-horizontal" method="post" action="<?=site_url("accounts/update/$contact->id")?>">

	<div class="form-group">
		<label class="col-sm-2 control-label" for="login">Login</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" name="login" required value="<?=$contact->login?>">
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-2 control-label" for="email">Email</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" name="email" required value="<?=$contact->email?>">
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-2 control-label" for="password">Password</label>
		<div class="col-sm-10">
			<input class="form-control" type="password" name="password" placeholder="New password">
			<p class="text-muted">Fill this field will reset password</p>
		</div>
	</div>


	<div class="form-group">
		<div class="col-sm-10">
			<button type="submit" class="btn btn-info"><i class="fa fa-pencil"></i> Update Account</button>
		</div>
	</div>
</form>
