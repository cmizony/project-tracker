<div class="read-view">
	<div class="panel panel-primary">

		<div class="panel-heading">
			<p class="lead"><i class="fa fa-file fa-2x"></i> <?=$file->title?></p>
		</div>

		<div class="panel-body">
			<table class="table table-bordered">
				<tbody>
					<tr class="gray">
						<td>
							<div class="text-primary text-center"><?=$file->contact->name?>&nbsp;</div>
							<small class="text-left text-muted"><em>Author</em></small>
						</td>
						<td>
							<div class="text-primary text-center"><?=humanize_file($file->size)?>&nbsp;</div>
							<small class="text-left text-muted"><em>Size</em></small>
						</td>
						<td>
							<div class="text-primary text-center"><?=$file->downloads?> Downloads</div>
							<small class="text-left text-muted"><em>Total</em></small>
						</td>
						<td>
							<div class="text-primary text-center"><?=$file->type?>&nbsp;</div>
							<small class="text-left text-muted"><em>Type</em></small>
						</td>
					</tr>
				</tbody>
			</table>

			<?php if (file_exists($file->path) AND getimagesize($file->path) > 0): ?>
			<div class="text-center">
				<img src="<?=base_url($file->path)?>" alt="file-<?=$file->id?>" width="85%" class="img-thumbnail">
			</div>
			<?php endif ?>

			<div class="panel panel-default panel-description">
				<div class="panel-body">
					<?=nl2br(markdown_replace($file->note))?>
				</div>
			</div>

			<table class="table table-bordered table-condensed">
				<tbody>
					<tr class="gray">
						<td><small><a href="<?=$file->parent_url?>"><i class="fa fa-globe"></i> <?=$file->parent_object?> - <?=$file->parent_name?></a></small></td>
						<td><small><a href="<?=site_url("tools/files/download/$file->id")?>"><i class="fa fa-download"></i> Download - <?=$file->title?></a></small></td>
						<td>
							<div class="text-muted text-right"><?=markdown_replace("#file-$file->id")?> <small><em>Created: <?=date("F j, Y",strtotime($file->date))?></small></em></div>	
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
