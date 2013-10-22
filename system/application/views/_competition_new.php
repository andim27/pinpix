<?php include('head1.php'); ?>


<?php include('juri23.php');?>

<body class="pix">
	
	<div id="pix">
		
		<?php include('header1.php'); ?>
		
		<?php display_errors(); ?>
		<!-- <div id="mainContent_new">	 -->
			<div class="content">	
			
			<?php 
				if (empty ($_user_id))
					{
						$t = $mask&10000;		
						if ($t == 10000)	
						{
						?>	
						<div style = "width: 60%; margin:0 auto">			
							<?php include( APPPATH . 'modules/users_mod/views/competition_register_form.php'); ?> 	
						</div>
			<?php
						} 	
					}	
			?>	
				
			
				<div class="righCol">
				<?php if (($mask != 10000)&&($mask != 1000))	{ ?>
					<div class="roundedBlock"><div class="tr"><div class="br"><div class="bl">
						<ul class="juryList">
							<li class="title"><a href = "<?=base_url() . 'jury1'?>" style="color:white;"><strong>PinPix</strong> <?=lang('pix_juri')?> </a></li>   
							<li><a href="#demidov_<?=$lng?>"  class="avatara"> <img src="<?=base_url().'images/demidov_small.jpg' ?>" /></a> <strong> 		<a href="#demidov_<?=$lng?>" class="defaultDOMWindow" style="color:white;"><?=lang('demidov_name')?></a> </strong> <span style = "color:#86BC3F;"><?=lang('demidov_desc')?></span></li>
							<li><a href="#ywakov_<?=$lng?>" class="avatara"><img src="<?=base_url().'images/ushakov.jpg' ?>" /></a> <strong> 				<a href="#ywakov_<?=$lng?>" class="defaultDOMWindow" style="color:white;"><?=lang('ywakov_name')?></a>  </strong> <span style = "color:#86BC3F;"><?=lang('ywakov_desc')?></span></li>												
							<li><a href="#babkin_<?=$lng?>" class="avatara"><img src="<?=base_url().'images/babkin.jpg' ?>" /></a> <strong> 				<a href="#babkin_<?=$lng?>" class="defaultDOMWindow" style="color:white;"><?=lang('babkin_name')?></a> </strong> <span style = "color:#86BC3F;"><?=lang('babkin_desc')?></span></li>						
							<li><a href="#korolev_<?=$lng?>" class="avatara"><img src="<?=base_url().'images/korolev.jpg' ?>" /></a> <strong> 				<a href="#korolev_<?=$lng?>" class="defaultDOMWindow" style="color:white;"><?=lang('korolev_name')?></a>       </strong> <span style = "color:#86BC3F;"><?=lang('korolev_desc')?></span></li>
						
						</ul>
					</div></div></div></div>
				<?php } ?>
				</div>
<SCRIPT type="text/javascript"> 
$('.defaultDOMWindow').openDOMWindow({ 
eventType:'click', 
loader:1, 
loaderImagePath:'animationProcessing.gif', 
loaderHeight:16,
height:747,
width:903, 
loaderWidth:17 
}); 

$('.avatara').openDOMWindow({ 
eventType:'click', 
loader:1, 
loaderImagePath:'animationProcessing.gif', 
loaderHeight:16,
height:747,
width:903, 
loaderWidth:17 
}); 
</SCRIPT> 					
				<div class="middCol">
				<?php 				
									
					$t = $mask&100;		
					if (($t == 100)&&(!empty ($_user_id)))				
					{
					?>	
						<div class="roundedBlock"><div class="tr"><div class="br"><div class="bl">
						<h3><strong><?php echo $user_login; ?></strong>, <?=lang('welcome')?> </h3>
						<div class="OkMan" style = "text-align:center; ">
						<input  style = "margin-top:20px;" class="regEntBut" type="button" value="<?=lang('ok_redirect')?>" onclick="javascript: window.location = '<?=base_url() . 'bibb/100010' ?>'"/>
						</div>
						</div></div></div></div>
					<?php 
					}
											
					if (!empty ($_user_id))	
					{
						$t = $mask&10;		
						if ($t == 10)		
						{						
								include('small_upload_form.php');					
						}
					}
					
					$t = $mask&1;		
					if (($t == 1)&&(!empty ($_user_id)))				
					{
					?>		
					<div class="roundedBlock"><div class="tr"><div class="br"><div class="bl">
						<h3><strong><?php echo $user_login; ?></strong>, <?php echo lang('work_added')?></h3>
						<div class="OkMan">
							<em><?php echo lang('comp_continue')?></em><br />
							<br />
						
							<input name="" type="image" src="<?=base_url()?>images/but_continue.gif" onclick="window.location.href = '<?=base_url()?>bibb/100010'" />
						</div>
					</div></div></div></div>
					<?php 
					}
						
					$t = $mask&100000;	
					if ($t == 100000)										
						include( APPPATH . 'modules/competition_mod/views/cond.php'); 	
					if (($mask != 10000)&&($mask != 1000))
						include( APPPATH . 'modules/competition_mod/views/prize.php'); 	
					?>
					
					</div>
		</div>

		<!-- </div> end #mainContent -->
	</div>  
			<!-- End main_side_right -->
<?php include('footer1.php'); ?>		
