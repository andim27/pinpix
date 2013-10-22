<?php display_errors(); ?>
<?php modules::load_file('ajax_requests_js.php',MODBASE.modules::path().'/scripts/'); ?>
<script language="javascript" type="text/javascript">
	function toggle_tabs(index)
	{
		$("#rem_feedback").html("");
		$("#reg_feedback").html("");
		$("#login_feedback").html("");
		
		if(index==1)
		{
		  	$('#m_reg_box_1').show();
			$('#m_reg_box_2').hide();		
			$('#m_reg_box_3').hide();
		  	$('#reg_zak_1').addClass('active');
		  	$('#reg_zak_2').removeClass('active');
		}
		if(index==2)
		{
			$('#m_reg_box_1').hide();
		  	$('#m_reg_box_3').hide();
		  	$('#m_reg_box_2').show();
		  	$('#reg_zak_1').removeClass('active');
		  	$('#reg_zak_2').addClass('active');
		}
		if(index==3)
		{
		  	$('#m_reg_box_1').hide();
		  	$('#m_reg_box_2').hide();
		  	$('#m_reg_box_3').show();
		}
	}
</script>

<!-- start main_enter_box -->
<div class="roundedBlock hasTopTabs">
	<div class="tr">
		<div class="br">
			<div class="bl" id="main_enter_box">
				<div id="main_en_center">
					<div id="m_reg_box"></div>
					<div class="loginBlock" style="position:relative;">
						<h3 style="margin-bottom:5px;"><?php echo lang('enter'); ?></h3>
						<form id="login_form" method="post" name="login_form" onkeypress="if (event.keyCode == 13) authorize();">	
							<span><?php echo lang('login'); ?>
							<input class="i" id="login" name="login" type="text" /> </span>
							<span><?php echo lang('password'); ?>
							<input class="i" name="password" id="password" type="password" /></span>
							<span><label for="pp1"><input name="pp1" type="checkbox" value="" id="pp1" /> <?php echo lang('remember_me'); ?></label>
							<a href="#" onclick="javascript: toggle_tabs(3); return false;"><?php echo lang('forget_pwd'); ?></a></span>
							<span style="margin:0;"><input name="" type="button" value="ok" class="button"  onclick="javascript: authorize();"/></span>
							<a style="bottom:0;left:177px;position:absolute;" href="<?=url('register_url')?>"><?php echo lang('registration'); ?></a>
						</form>
					</div>
					<div style="padding-top:10px;">
						<span class="ent_txt" id='login_feedback'></span>
					</div>					
					<!-- End m_reg_box_1-->	  				
			   		<div id="m_reg_box_3" class="loginBlock" style = "display : none">
						<form method="post" id="remember_form" name="remember_form">				
							<span><?php echo lang('email_field'); ?>			
								<input class="i" type="text" id="email1" name="email1" value="<?php echo set_value('email'); ?>" />
							</span>
							<span style="margin:0;">
								<input name="" type="button" value="ok" onclick="javascript: remember();" class="button" />
							</span>
							<div style="float:right;">
								<div style="float: left;"><a style="border:0; margin-right:10px;" href="#" onclick="javascript: toggle_tabs(1);$('#login').focus(); return false;" id="reg_zak_1"><?php echo lang('enter'); ?></a></div>
								<div style="float: left;"><a style="text-decoration: underline;" href="<?=url('register_url')?>"><?php echo lang('registration'); ?></a></div>
							</div>
						</form>				
			  			<div style=" margin:0 15px; text-align:left;">
							<span class="ent_txt" id='rem_feedback'></span>
						</div>
					</div><!-- End m_reg_box_2 -->			
					<div id="m_reg_box_2" class="loginBlock" style = "display : none">
						<form method="post" id="register_form" name="register_form" onkeypress="if (event.keyCode == 13) register();"  >						
							<span><?php echo lang('username_field'); ?>
							<input class="i" type="text" id="username" name="username" value="" /></span>							
							<span><?php echo lang('email_field'); ?>
							<input class="i" type="text" id="email" name="email" value="<?php echo set_value('email'); ?>" /></span>							
							<span><input name="" type="button" value="ok" class="button"  onclick="javascript: register();" /></span>						
						</form>
						<div style=" margin:0 15px; text-align:left;">
							<span class="ent_txt" id='reg_feedback'></span>
						</div>
					</div><!-- End m_reg_box_2 -->
				</div>
				<div class="box_bot">
					<div class="bb_leftfix"></div>
					<div class="bb_rightfix"></div>
				</div><!-- end main_enter_box -->
			</div>
		</div>
	</div>
</div>
