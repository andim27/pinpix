<?php include('head.php'); ?>

<script type="text/javascript" src="<?php echo static_url(); ?>js/tablesorter/jquery-latest.js"></script>
<script type="text/javascript" src="<?php echo static_url(); ?>js/tablesorter/jquery.tablesorter.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo static_url(); ?>/css/ui.all.css">
<script type="text/javascript" src="<?php echo static_url(); ?>js/jquery-ui-1.6.custom.min.js"></script>
<script type="text/javascript" src="<?php echo static_url(); ?>js/phh.javascript.js"></script>

<script type="text/javascript">
$(function(){
	$("#start").datepicker({
		showOn: 'button',
		buttonImage: '/css/images/calendar.png',
		buttonImageOnly: true,
		dateFormat: 'dd M yy',
		duration: '',
		yearRange: '-10:+10'
	});
	$("#end").datepicker({
		showOn: 'button',
		buttonImage: '/css/images/calendar.png',
		buttonImageOnly: true,
		dateFormat: 'dd M yy',
		duration: '',
		yearRange: '-10:+10'
	});
});
</script>

<body class="pix">
	
	<div id="pix">
		
		<?php include('header.php'); ?>
	   
		<?php display_errors(); ?>
	          
		<div id="mainContent">
		   
			<div id="main_side_right">
			   
				<!-- start main_enter_box -->
				<?php if ( ! $user_id) echo $auth_block; ?>
				<!-- end main_enter_box -->
			        
				<!-- Start Main_side_und_box-->
				<div style="display:none;" id="main_side_und_box" class="nifty">
				   
					
					   
					<!--Center-->
					<div id="ms_center">
						<div id="ms_cen_zag" align="left"><span class="m_zag_txt"></span></div>
						<div id="ms_cen_box">
							<?php // include('admin_menu.php'); ?>
						</div>
					</div>
					<!-- End center-->
					  
					
				  
				</div>   
				<!-- End Main_side_und_box-->
			   
			</div>  
			<!-- End main_side_right -->
			   
			<!-- main_cont_body -->
			<div id="main_cont_body">
			   	
			   	<!-- Start center main-->
				<div id="c_box">

					<div>
						<?php echo $comments_block; ?>
					</div>
  					
					
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