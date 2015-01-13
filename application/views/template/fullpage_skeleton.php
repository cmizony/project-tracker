<!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title><?=$title ?></title>
<meta name="description" content="<?=$description ?>" />
<meta name="viewport" content="width=device-width">
<meta name="keywords" content="<?=$keywords ?>" />
<meta name="author" content="<?=$author ?>" />

<link rel="stylesheet" href="<?=base_url(CSS."bootstrap.min.css");?>">
<link rel="stylesheet" href="<?=base_url(CSS."bootstrap-theme.min.css");?>">
<link rel="stylesheet" href="<?=base_url(CSS."fix-bootstrap.css");?>">


<link rel="stylesheet" href="<?=base_url(CSS."jquery.dataTables.css");?>">
<link rel="stylesheet" href="<?=base_url(CSS."pnotify.custom.min.css");?>">
<link rel="stylesheet" href="<?=base_url(CSS."font-awesome.css");?>">
<link rel="stylesheet" href="<?=base_url(CSS."jquery.fancybox.css");?>">
<link rel="stylesheet" href="<?=base_url(CSS."custom.css");?>">
<link rel="stylesheet" href="<?=base_url(CSS."datatables/ColVis.css");?>">
<link rel="stylesheet" href="<?=base_url(CSS."datatables/ColReorder.css");?>">
<link rel="stylesheet" href="<?=base_url(CSS."datatables/TableTools.css");?>">
<link href='http://fonts.googleapis.com/css?family=PT+Sans' rel='stylesheet' type='text/css'>


<!-- extra CSS-->
<?php foreach($css as $c):?>
<link rel="stylesheet" href="<?=base_url().CSS.$c?>">
<?php endforeach;?>


<!-- extra fonts-->
<?php foreach($fonts as $f):?>
<link href="http://fonts.googleapis.com/css?family=<?=$f?>"
	rel="stylesheet" type="text/css">
<?php endforeach;?>

<!-- Le fav and touch icons -->
<link rel="shortcut icon" href="<?=base_url(IMAGES.'ico/favicon.ico');?>">
<link rel="apple-touch-icon" href="<?=base_url(IMAGES.'ico/apple-touch-icon-precompresse.png');?>">
<link rel="apple-touch-icon" sizes="57x57" href="<?=base_url(IMAGES.'ico/apple-touch-icon-57x57-precompressed.png');?>">
<link rel="apple-touch-icon" sizes="72x72" href="<?=base_url(IMAGES.'ico/apple-touch-icon-72x72-precompressed.png');?>">
<link rel="apple-touch-icon" sizes="114x114" href="<?=base_url(IMAGES.'ico/apple-touch-icon-114x114-precompressed.png');?>">

<!-- Jquery -->
<script>window.jQuery || document.write('<script src="<?=base_url(JS."libs/jquery/jquery-1.9.1.min.js");?>"><\/script>')</script>

</head>
<body>
	<?=$body?>

	<script defer src="<?=base_url(JS."libs/jquery/jquery.dataTables.min.js")?>"></script>
	<script defer src="<?=base_url(JS."libs/datatables/ColReorder.js")?>"></script>
	<script defer src="<?=base_url(JS."libs/datatables/ColVis.js")?>"></script>
	<script defer src="<?=base_url(JS."libs/datatables/TableTools.js")?>"></script>
	<script defer src="<?=base_url(JS."libs/datatables/ZeroClipboard.js")?>"></script>
	<script defer src="<?=base_url(JS."libs/jquery/jquery.dataTables.bootstrap.js")?>"></script>
	<script defer src="<?=base_url(JS."libs/bootstrap.min.js")?>"></script> 
	<script defer src="<?=base_url(JS."libs/underscore-min.js")?>"></script>
	<script defer src="<?=base_url(JS."libs/jquery/pnotify.custom.min.js")?>"></script>
	<script defer src="<?=base_url(JS."libs/crc32.js")?>"></script>
	<script defer src="<?=base_url(JS."libs/jquery/jquery.fancybox.pack.js")?>"></script>
	<script defer src="<?=base_url(JS."custom.js")?>"></script>

	<!-- extra js-->
	<?php foreach($javascript as $js):?>
	<script defer src="<?=base_url().JS.$js?>"></script>
	<?php endforeach;?>

	<!-- Onload js -->
	<script defer>
		window.onload = function(){ 
			init_custom_ui();
			<?=$alerts?>
			tab_state_save();
			collapse_state_save();
		}
	</script>
</body>
</html>
