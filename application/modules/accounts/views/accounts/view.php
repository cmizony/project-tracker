<div class="contact-view">
	<div class="alert alert-info pull-right" data-toggle="tooltip" title="Non-Members can not view this page" data-placement="bottom"><i class="fa fa-key"></i> Member Area</div>
	<h1 class="row">
		Contact 
		<?php if ($manage_contacts): ?>
		<button class="btn delete-contact btn-danger" data-id="<?=$contact->id?>" data-text="<?=$contact->login?>"><i class="fa fa-trash-o"></i></a>
		<?php endif ?>
	</h1>

	<div class="row">

		<div class="col-md-7 well">
			<div class="row">
				<div class="col-md-5 contact-general">
					<?php if ($manage_contacts): ?>
					<button data-href="<?=site_url("accounts/edit_contact/$contact->id")?>" class="pull-right btn btn-sm btn-ajax-unique btn-info" data-target=".contact-general"><i class="fa fa-pencil"></i></button>
					<?php endif ?>
					<p class="text-muted">Profile</p>
					<p>
					<i class="fa fa-user icon-lg" title="Name"></i> <?=$contact->name?>
					</p>
					<p>
					<i class="fa fa-phone icon-lg" title="Phone"></i> <?=format_phone($contact->phone)?>
					</p>
					<p>
					<i class="fa fa-key icon-lg" title="Role"></i> <?=humanize($contact->role)?>
					</p>
					<p>
					<i class="fa fa-briefcase icon-lg" title="Company"></i> <?=$contact->company?>
					</p>
					<p>
						<?=nl2br($contact->address)?>
					</p>
				</div>
				<div class="col-md-7">
					<p class="text-muted">
						Note
					</p>
					<?php if ($manage_contacts): ?>
					<textarea class="form-control" rows="7" class="textarea-full" id="input-contact-note" data-id="<?=$contact->id?>"><?=$contact->note?></textarea>
					<?php else: ?>
					<div class="well well-white well-small"><?=nl2br($contact->note)?></div>
					<?php endif ?>
				</div>
			</div>

			<div class="contact-description">
				<?php if ($manage_contacts): ?>
				<button data-href="<?=site_url("accounts/edit_description/$contact->id")?>" data-target=".contact-description" class="btn pull-right btn-ajax-unique btn-info btn-sm"><i class="fa fa-pencil"></i></button>
				<?php endif ?>
				<p class="text-muted">
					Description
				</p>
				<div class="panel panel-default">
					<div class="panel-body markdown-content">
						<?=nl2br($contact->description)?>
					</div>
				</div>
			</div>
			<a target="_blank" href="<?="https://maps.google.com/maps?q=".urlencode($contact->address)?>"><i class="fa fa-map-marker"></i> Get Directions</a>
			<div class="google-map" id="google-map-canvas"></div>
			
			<hr>	
			<div class="text-muted text-right markdown-content">#contact-<?=$contact->id?> <small><em>Created: <?=date("F j, Y",strtotime($contact->date))?></small></em></div>	
		</div>
		<div class="col-md-5">
			<div class="panel panel-default">
				<div class="panel-body">

					<ul class="nav nav-pills">
						<li class="active"><a data-toggle="tab" href="#tab-contact-account"><i class="fa fa-user"></i> Account</a></li>
						<li><a data-toggle="tab" href="#tab-contact-file"><i class="fa fa-folder-open"></i> Files</a></li>
						<li><a data-toggle="tab" href="#tab-contact-projects"><i class="fa fa-list-alt"></i> Projects</a></li>
					</ul>

					<div class="tab-content">
						<div class="tab-pane" id="tab-contact-file">
							<?=$rendered_folder?>
						</div>
						<div class="tab-pane" id="tab-contact-projects">
							<a class="btn btn-sm btn-default" href="<?=site_url("projects/index/contact/$contact->id")?>">
								<i class="fa fa-arrows-alt"></i> Details
							</a>
							
							<?=$rendered_projects?>
						</div>
						<div class="tab-pane active" id="tab-contact-account">
							<?php if ($manage_contacts): ?>
							<span class="btn-group">
								<button data-toggle="modal" data-target=".modal-account-edit" href="<?=site_url("accounts/edit/$contact->id")?>" class="btn btn-info btn-sm"><i class="fa fa-pencil"></i></button>
							<?php if ($contact->flag_lock == 1): ?>
								<a href="<?=site_url("accounts/unlock/$contact->id")?>" title="Unlock login" class="btn btn-default btn-sm"><i class="fa fa-unlock-alt"></i></a>
								<?php else: ?>
								<a href="<?=site_url("accounts/lock/$contact->id")?>" title="Lock login" class="btn btn-default btn-sm"><i class="fa fa-lock"></i></a>
									<?php endif ?>
							</span>
							<?=empty_modal("modal-account-edit","Edit Account")?>
							<?php endif ?>

							<?php if ($contact->flag_lock == 1): ?>
							<div class="alert alert-warning pull-right">
								<i class="fa fa-lock"></i> Locked
							</div>
							<?php else: ?>
							<div class="alert alert-success pull-right">
								<i class="fa fa-unlock-alt"></i> Unlocked
							</div>
							<?php endif ?>


							<div class="clearfix"></div>

							<ul class="list-unstyled clearfix">
								<li><i class="fa fa-sign-in" title="Login"></i> <?=$contact->login?></li>
								<li><i class="fa fa-envelope-o" title="Email"></i> <a href="mailto:<?=$contact->email?>"><?=$contact->email?></a></li>
							</ul>

							<h4><i class="fa fa-search"></i> Logs</h4>
							<?=$rendered_logs?>
						</div>

						<div class="tab-pane" id="tab-contact-file">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div> 

<script src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script defer>
	$("#input-contact-note").focusout(save_contact_note);
	$(".delete-contact").click(delete_contact);
	initializeMap();

	function save_contact_note ()
	{
		var val = $(this).val();
		var id = $(this).data("id");
		var url = ARNY.site_url+"accounts/update_inline/"+id;
		var obj = {	field : "note",	val : val};
		$.post(url,obj).done(function(){
			new PNotify({text: 'Note saved', type:'success'});
		});
	}

	function delete_contact ()
	{
		var id = $(this).data("id");
		var text = $(this).data("text");
		var url = ARNY.site_url+"accounts/delete/"+id;
		confirm_delete(url,text);
	}

	function initializeMap() {
		var latitude = <?=json_encode($contact->latitude)?>;
		var longitude = <?=json_encode($contact->longitude)?>;

		if (!latitude || !longitude)
		return;

		var myLatlng = new google.maps.LatLng(latitude,longitude);

		var mapOptions = {
			zoom: 10,
			center: myLatlng,
			mapTypeControl: false
		};

		var map = new google.maps.Map(document.getElementById('google-map-canvas'), mapOptions);

		var marker = new google.maps.Marker({
			position: myLatlng,
			map: map,
			icon: ARNY.base_url+"resources/img/marker-building.png"
		});

	}
</script>
