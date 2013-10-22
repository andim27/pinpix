<?php include('head_new.php'); ?>

<body class="pix">
	
	<div id="pix">
		
		<?php include('header_new.php'); ?>
		
		<?php display_errors(); ?>
		<!-- <div id="mainContent_new">	 -->
			<div class="content">				
				<div class="righCol">
				<?php 			
					include( APPPATH . 'modules/users_mod/views/competition_enter_box.php'); 					
				?>
				</div>
				
				<div class="middCol">
				<?php 				
					include( APPPATH . 'modules/users_mod/views/competition_register_form.php'); 	
				?>					
				</div>
		</div>
		<!-- end #mainContent -->
		  
		<?php include('footer.php'); ?>		
	</div>
	<!-- end #pix -->


