<?php include('head_new.php'); ?>

<body class="pix">
	
	<div id="pix">
		
		<?php include('header_new.php'); ?>
	   
		<?php display_errors(); ?>
	          
		<div class="content">
			<div class="righCol">					
				 <?php echo $ads_block['right_ads']; ?>	
			</div>
			<div style = "align:center; width:300px;">
			<?php 	include('upload_form.php');		 ?>
			</div>
		</div>
		<!-- end #mainContent -->
		  
		<?php include('footer.php'); ?>		
	</div>
	<!-- end #pix -->

