<div class="container">
	<div class="row">

		<div class="main">

		<h3><i class="fa fa-flask"></i><?=$company?></h3>
			<div class="login-or">
				<hr class="hr-or">
				<span class="span-or">Log in</span>
			</div>

			<div class="panel panel-default">
				<div class="panel-body">
					<form method="post" action="<?=site_url('accounts/authentification/verify')?>">
						<div class="form-group">
							<label for="username"><i class="fa fa-user"></i> Username</label>
							<input type="text" class="form-control" name="username">
						</div>
						<div class="form-group">
							<label for="password"><i class="fa fa-key"></i> Password</label>
							<input type="password" class="form-control" name="password">
						</div>
						<input type="hidden" name="destination" value="<?=$destination?>">
						<p><!--<a href="#"><i class="fa fa-question-sign"></i> Forgot password?</a>--></p>
						<button type="submit" class="btn btn btn-warning">
							<i class="fa fa-sign-in"></i> Log In
						</button>
					</form>

				</div>
			</div>
		</div>

	</div>
</div>
