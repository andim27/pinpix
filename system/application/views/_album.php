<?php include('head_new.php'); ?>
<body class="pix">
	
	<div id="pix">
		
		<?php include('header_new.php'); ?>
	   
		<?php display_errors(); ?>
	          
		<div class="content">
			
			<div class="righCol">
			<?php include(MODBASE.'gallery_mod/views/albums_sitebar.php'); ?>	
			<?php echo $ads_block['right_ads']; ?>
			</div>
			
			<?php echo $view_album_block;	?>  
		</div>
		<!-- end #mainContent -->
		  
		<?php include('footer.php'); ?>		
	</div>
	<!-- end #pix -->