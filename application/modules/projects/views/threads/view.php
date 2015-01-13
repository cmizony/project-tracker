<h2><i class="fa fa-comment-o"></i> <?=$thread->title?></h2>

<?php if ($thread->thumbnail_exists()): ?>
<div class="container-fluid">
	<div class="row">
		<img src="<?=site_url("projects/threads/thumbnail/$thread->id")?>" class="img-rounded" height="100px" align=left style="margin:9px;">
		<p class="text-muted"><?=nl2br($thread->outline)?></p>
	</div>
</div>
<?php else: ?>
<p class="text-muted"><?=nl2br($thread->outline)?></p>
<?php endif ?>

<?php if (!empty($thread->description)): ?>
<div class="panel panel-default">
	<div class="panel-body markdown-content">
		<?=nl2br($thread->description)?>
	</div>
</div>
<?php endif ?>

<div class="text-muted text-right markdown-content">#thread-<?=$thread->id?> <small><em>Created: <?=date("F j, Y",strtotime($thread->date))?></small></em></div>	

<hr>	
<?=$rendered_chat?>
