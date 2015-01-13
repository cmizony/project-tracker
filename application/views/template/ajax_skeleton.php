<?php foreach($css as $c):?>
<link rel="stylesheet" href="<?=base_url().CSS.$c?>">
<?php endforeach;?>

<?php foreach($fonts as $f):?>
<link href="http://fonts.googleapis.com/css?family=<?=$f?>" rel="stylesheet" type="text/css">
<?php endforeach;?>

<?php foreach($javascript as $js):?>
<script defer src="<?=base_url().JS.$js?>"></script>
<?php endforeach;?>

<?=$content?>

<?php if (!empty($alerts)): ?>
<script defer type="text/javascript">
<?=$alerts?>
</script>
<?php endif ?>
