<div class="navbar navbar-default navbar-fixed-top navbar-inverse">
	<div class="container">
		<div class="navbar-header">
			<button class="navbar-toggle" data-toggle="collapse" data-target="#main-navbar-collapse" type="button">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="<?=site_url()?>"><i class="fa fa-home"></i> <?=$label_company?></a>
		</div>

		<div class="navbar-collapse collapse" id="main-navbar-collapse">
			<ul class="nav navbar-nav" >
				<?php if ($internal_access): ?>
				<li class="<?=is_active($highlight,'Contacts')?>"><a href="<?=site_url('accounts')?>"><i class="fa fa-group icon-lg"></i> Contacts</a></li>
				<li class="<?=is_active($highlight,'Projects')?>"><a href="<?=site_url('projects')?>"><i class="fa fa-list-alt icon-lg"></i> Projects</a></li>
				<li class="<?=is_active($highlight,'Iterations')?>"><a href="<?=site_url('projects/iterations')?>"><i class="fa fa-tasks icon-lg"></i> Iterations</a></li>
				<li class="<?=is_active($highlight,'Tickets')?>"><a href="<?=site_url('projects/tickets')?>"><i class="fa fa-ticket icon-lg"></i> Tickets</a></li>
				<?php else: ?>
				<li class="<?=is_active($highlight,'Projects')?>"><a href="<?=site_url('projects')?>"><i class="fa fa-list-alt icon-lg"></i> Projects</a></li>
				<?php endif ?>
			</ul>
		
			<ul class="nav navbar-nav navbar-right">
				<?php if ($time_interval->result_count() > 0): ?>
				<li class="dropdown">
					<a href="<?=site_url("tools/time_trackers/edit/$time_interval->id")?>" data-toggle="modal" data-target=".modal-current-timer">
						<i class="fa fa-clock-o"></i> <span id="current-timer-min"><?=ceil((time()-strtotime($time_interval->start))/60)?></span> min
						<span class="markdown-content">#<?=$time_interval->related_resource()?></span>
						<span class="caret"></span>
					</a>
				</li>
				<?php endif ?>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<i class="fa fa-user"></i> Profile
						<span class="caret"></span>
					</a>
					<ul class="dropdown-menu">
						<li><a href="<?=site_url('accounts/authentification/logout')?>"><i class="fa fa-power-off"></i> Logout</a></li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
</div>

<?php if ($time_interval->result_count() > 0): ?>

<?=empty_modal("modal-current-timer","Close Timer")?>
<script defer>
$(function(){
	setInterval(function (){
		var min = $("#current-timer-min").html();
		$("#current-timer-min").html(parseInt(min)+1);
	}, 1000*60);
});
</script>

<?php endif ?>
