<?php include('head_new.php'); ?>
<body class="pix">
	
	<div id="pix">
		
		<?php include('header_new.php'); ?>
	   
		<?php display_errors(); ?>
	          
		<div class="content">
			
			<div class="righCol">
			<?php include(MODBASE.'competition_mod/views/comp_sidebar.php'); ?>		
			<?php if ( ! $user_id)  if (!empty ($register_block)) echo $register_block; ?>	
			<?php echo $ads_block['right_ads']; ?>
			</div>
			
			<?php echo $competition_blok; ?>   
		</div>
		<!-- end #mainContent -->
		  
		<?php include('footer.php'); ?>		
	</div>
	<!-- end #pix -->