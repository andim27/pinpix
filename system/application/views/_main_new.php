<?php include('head_new.php'); ?>
<body class="pix">
	
	<div id="pix">
		
		<?php include('header_new.php'); ?>
	   
		<?php display_errors(); ?>
	          
		<div class="content">
			<div class="righCol">
			
			    <?php if ( ! $user_id) echo $register_block; ?>
			
				 <?php echo $ads_block['right_ads']; ?>
			</div>
			
			<?php include('home_new.php'); ?>
		</div>
			  
		<?php include('footer.php'); ?>		
	</div>
	<!-- end #pix -->

