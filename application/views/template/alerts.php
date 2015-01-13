<?php foreach ($alerts as $alert): ?>
new PNotify({
	text: <?=json_encode($alert['text'])?>,
	type: <?=json_encode($alert['type'])?>,
	<?=$alert['type'] != 'success'?'hide:false':''?>
});
<?php endforeach ?>
