<?php include('head1.php'); ?>
<body class="pix">
	<div id="pix">
		<?php include('header1.php'); ?>
<?php if (isset_errors()):?>
		<?php display_errors(); ?>
<?php else:?>
		<div id="mainContent_new"> <!-- change from mainContent_konk -->
			<div id="main_cont_body" style="margin:0;">
				<!-- Start center main-->
				<h2 class="pages-title"><?=$title?></h2>
				<div class="pages-content"><?=$content?></div>
				<!--End Center main-->

				<br /><br /><br /><br />
			</div>
		<!-- end #mainContent --></div>
<?php endif;?>
	<!-- end #container --></div>
<?php include('footer1.php'); ?>		