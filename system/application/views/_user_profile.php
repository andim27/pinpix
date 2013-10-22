<?php include('head_new.php'); ?>

<body class="pix">

	<div id="pix">
		
		<?php include('header_new.php'); ?>
	   
		<?php display_errors(); ?>
	          
		<div class="content">
			<div class="righCol">			
			     <?php include('users_page_right_block.php'); ?>	
				  <?php echo $ads_block['right_ads']; ?>		     
			</div>
			
			<?php include("gallery_grid.php"); ?>   
			
			<?php 
				if ($my)
					include("edit_photo_form.php"); 
			?>   
		</div>
		  
		<?php include('footer.php'); ?>		
	</div>
<!-- end #pix -->
