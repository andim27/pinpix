<?php include('head.php'); ?>
<body class="pix">
	
	<div id="pix">
		
		<?php include('header.php'); ?>
	   
		<?php display_errors(); ?>
	          
		<div id="mainContent">
		   
			<div id="main_side_right">
			   
				<!-- start main_enter_box -->
				<?php if ( ! $user_id) echo $auth_block; ?>
				
                <!-- end main_enter_box -->
			    
			    <div id="admin_menu_box" <?php if ( ! $user_id) echo 'style="display: none;"'; ?>>    
				<!-- Start Main_side_und_box-->
				<div style="display:none;" id="main_side_und_box" class="nifty">
				   
					
					   
					<!--Center-->
					<div id="ms_center">
						<div id="ms_cen_zag" align="left"><span class="m_zag_txt"></span></div>
						<div id="ms_cen_box">
							<div id="admin_menu">
								
							</div>
						</div>
					</div>
					<!-- End center-->
					  
				
				  
				</div>   
				<!-- End Main_side_und_box-->
			   </div>
			   
			</div>  
			<!-- End main_side_right -->
			
			
			   
			<!-- main_cont_body -->
			<div id="main_cont_body">
			   	
			   	<!-- Start center main-->
				<div id="c_box">
               
                <?php if ($user_id) {?>
				<span style="color:#ffffff;">Вы удачно залогинились в админку. Вашему вниманию представлены такие разделы:<br /> 
                      -Users;<br />
                      -Catalog;<br />
                      -Tags;<br />  
                      -Comments;<br />
                      -Photos. 
                </span>	
					<?php } ?>
					
					
					
				</div>
				<!--End Center main-->
				   
				<br /><br /><br /><br />
			</div>
			<!-- end #main_cont_body -->
		   
		</div>   
		<!-- end #mainContent -->
		  
		<?php include('footer.php'); ?>
		
	</div>
	<!-- end #pix -->

</body>
</html>