<?php if (!browser_access()) {?>
	<div class="header" style = "height: 50px; font-size:15px;">
		<a href="<?php echo base_url(); ?>" onfocus="this.blur()" class="logo"><img src="<?php echo static_url(); ?>images/logo_pinPix.gif" /></a>		
		<?php  echo   lang("upgrade_browser") ;?>
	</div>
<?php 	exit;	 }   ?>
<?php $lng		= $this->db_session->userdata('user_lang'); ?>
<?php modules::load_file('ajax_requests_js.php',APPPATH.'modules/users_mod/scripts/'); ?>
<div class="header">
			<a href="<?php echo base_url() . "bibb/100000"; ?>" class="logo"><img src="<?php echo base_url(); ?>images/logo_pinPix.gif" /></a>
			<div class="titul_head">
				<?php echo lang ('bibb_comp')?><br />
				<span> <?php echo lang ('bibb_comp_name')?> </span>
			</div>
			
			<!-- user login block -->	
			<?php if (empty ($_user_id) && empty ($username))	{?>
			<div class="registerEnterBox_<?php echo $lng ?>">		  
				<input onfocus="this.blur()" onclick="document.location.href = '<?=base_url() . 'bibb/10000'?>'" style="margin-bottom:8px;" class="regEntBut" type="button" value="<?php echo lang ('bibb_register')?>" /> 
				<br />
				<input class="regEntBut" type="button" onfocus="this.blur()" value="<?php echo lang ('bibb_enter')?>" onclick="javascript: toggleVisibility ('enterGreenBox')"/>
				
				<div class="enterGreenBox" id="enterGreenBox" style = "display:none"> 
					<form method="post" action = "javascript: authorize_bibb();">
						
						<p><?php echo lang('bibb_login'); ?></p>
						<input class="loginTxtInp" id="login" name="login" type="text" />
						
						<p><?=lang('bibb_password')?></p>
						<input class="loginTxtInp" name="password" id="password" type="password" />
						
						<div id="m_reg_butt">			
							<br>					
							<input onfocus="this.blur()"  class = "but_69" name="" type="button" value = "OK" onclick = "javascript: authorize_bibb();"/>
						</div>		
					</form>														
	  			</div>

			</div>			

			<?php }	else 	{	?>
			
			<div id="new_header_right">   
     		<div id="h_r_p_people" <?php if( ! $user_id) echo 'style="display: none;"';?>></div> 
     		<div id="place_txt_people" <?php if( ! $user_id) echo 'style="display: none;"';?>>
        	<span><?=lang('bibb_user')?> </span><span style="font-weight:bold; color:white" id="user_login"><?php echo $user_login; ?></span>
     		</div>
     		<div id="" <?php if( ! $user_id) echo 'style="display: none; Float:right;"';?>> <input onfocus="this.blur()" class="regEntBut" type="button" value="<?=lang('ok_redirect')?>" onclick="javascript: window.location = '<?=base_url() . 'bibb/100010' ?>'"/>
			</div>
     			
			</div>
			<?php }?>
			
			
			<!-- user login block end-->	
			<!-- lang block -->		
			<ul class="langBox" style="text-align:right;">
				<li><a href="#" onclick="SetUserLang('ru', '<?=$user_id?>', '<?= base_url()?>'); return false;">RUS</a></li>
				<li><a href="#" onclick="SetUserLang('kz', '<?=$user_id?>', '<?= base_url()?>'); return false;">KAZ</a></li>
			</ul>
			<br /><br />

			<div id="p_h_link_2"  style = "float:right; <?php if( ! $user_id) echo 'display: none;';?>"> <input class="regEntBut" type="button" onfocus="this.blur()"  value="<?=lang('bibb_exit')?>" onclick="javascript: window.location = '<?php echo url('logout_comp_url'); ?>'"/>
			</div>
	
			<!-- lang block end -->	
			<div id="partnerCanonHeader"> <?php if ($lng == 'ru') echo lang('partner_cannon')?> <img src="<?php echo base_url(); ?>static/images/Canon_TV_FILM_logo_small_white.png" /> <?php if ($lng == 'kz') echo lang('partner_cannon')?> </div>		
</div>		
<?php  include('flashbox.php'); ?>
