<?=$basejs?>

<div id="wrapper">
	<?=$header ?>

	<div id="main">

		<div class="container">
			<div id="content-body">
				<?=$breadcrumb?>
				<?=$content_body ?>
			</div>
		</div>

		<div class="alerts"></div>

	</div>
	<div id="footer-push"></div>
</div>


<?=isset($footer)?$footer:''?>
