
<div title="Status" class="pull-right alert alert-<?=convert_status($ticket->status)?>">
	<i class="fa fa-info-sign"></i> <?=humanize($ticket->status)?>
</div>

<h3 class="row"><i class="fa fa-ticket"></i> <?=$ticket->title?>
<?php if ($manage_tickets): ?>
	<div class="btn-group">
	<a data-toggle="modal" data-target=".modal-edit-ticket" href="<?=site_url("projects/tickets/edit/$ticket->id")?>" class="btn btn-info" title="Edit"><i class="fa fa-pencil"></i></a>
	<?=time_tracking($ticket->time_tracker_id)?>
	</div>
<?php endif ?>
</h3>

<?php if ($manage_tickets): ?>
<?=empty_modal("modal-edit-ticket","Edit ticket")?>
<?php endif ?>

<div class="row">
	<div class="col-md-7 ticket-view">
		<div class="well">

			<div class="row">
				<div class="col-md-5">

					<ul class="list-unstyled">
						<?php if ($manage_tickets): ?>
						<li><span class="text-muted">Tag:</span>
							<span id="box-tag-<?=$ticket->tag_id?>">
								<?=colored_tag($ticket->tag_id,$ticket->tag_color,$ticket->tag_text,$ticket->tag_date) ?>
							</span>
						</li>
						<?php endif ?>
						<li><span class="text-muted">Type:</span> <?=humanize($ticket->type)?></li>
						<li><span class="text-muted">Priority:</span> <span class="label label-<?=convert_status($ticket->priority)?>"><?=$ticket->priority?></span></li>
					</ul>
				</div>
				<div class="col-md-7">
					<p class="text-muted">Note</p>
					<?php if ($manage_tickets): ?>
					<textarea class="form-control" rows="4" class="textarea-full" id="input-ticket-note" data-id="<?=$ticket->id?>"><?=$ticket->note?></textarea>
					<?php else: ?>
					<div class="well well-white well-small"><?=nl2br($ticket->note)?></div>
					<?php endif ?>
				</div>
			</div>
			<p class="text-muted">Description</p>
			<div class="panel panel-default">
				<div class="panel-body markdown-content">
					<?=nl2br($ticket->description)?>
				</div>
			</div>
			
			<div class="text-muted text-right markdown-content">#ticket-<?=$ticket->id?> <small><em>Created: <?=date("F j, Y",strtotime($ticket->date))?></small></em></div>	

		</div>
	</div>
	<div class="col-md-5">
		<div class="panel panel-default">
			<div class="panel-body">
				<ul class="nav nav-pills">
					<li class="active"><a data-toggle="tab" href="#tab-ticket-general"><i class="fa fa-info-sign"></i> General</a></li>
					<li><a data-toggle="tab" href="#tab-ticket-file"><i class="fa fa-folder-open"></i> Files</a></li>
					<?php if ($internal_access): ?>
					<li><a data-toggle="tab" href="#tab-ticket-timers"><i class="fa fa-clock-o"></i> Time</a></li>
					<?php endif ?>
					<li><a data-toggle="tab" href="#tab-ticket-log"><i class="fa fa-search"></i> Logs</a></li>
				</ul>
	
				<div class="tab-content">
					<div class="tab-pane" id="tab-ticket-file">
						<?=$rendered_folder?>
					</div>
					<div class="tab-pane active" id="tab-ticket-general">
						<h4><i class="fa fa-list-alt"></i> Project</h4>
						<ul>
							<li><a href="<?=site_url("projects/view/$ticket->project_id")?>"><?=$ticket->project->name?></a></li>
							<li><?=$ticket->project->type?></li>
							<li><?=$ticket->project->status?></li>
						</ul>
						<h4>Beneficiary</h4>
	
						<p>
						<i class="fa fa-user"></i> 
						<?php if ($internal_access): ?> <a href="<?=site_url("accounts/view/".$ticket->project->beneficiary_id)?>"> <?php endif ?>
							<?=$ticket->project->beneficiary->name?><br>
						<?php if ($internal_access): ?> </a> <?php endif ?>
	
						<i class="fa fa-envelope-o"></i> <a href="mailto:<?=$ticket->project->beneficiary->email?>"><?=$ticket->project->beneficiary->email?></a><br>
						<i class="fa fa-building"></i> <?=$ticket->project->beneficiary->company?><br>
						</p>
					</div>
					<div class="tab-pane" id="tab-ticket-log">
						<?=$rendered_logs?>
					</div>
					<?php if ($internal_access): ?>
					<div class="tab-pane" id="tab-ticket-timers">
						<?=$rendered_time_trackers?>
					</div>
					<?php endif ?>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-10 col-md-offset-1">
		<?=$rendered_chat?>
	</div>
</div>


<script defer>
	$("#input-ticket-note").focusout(save_ticket_note);

	function save_ticket_note ()
	{
		var val = $(this).val();
		var id = $(this).data("id");
		var url = ARNY.site_url+"projects/tickets/update_inline/"+id;
		var obj = {	field : "note",	val : val};
		$.post(url,obj).done(function(){
			new PNotify({text: 'Note saved', type:'success'});
		});
	}
</script>
