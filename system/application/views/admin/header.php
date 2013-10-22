<div id="header">
	<div id="header_left" align="left">
		<div id="logo_place">
			<a href="<?php echo base_url(); ?>">
				<img alt="" border="0" src="<?php echo static_url(); ?>images/logo.gif" width="128" height="49" />
			</a>
		</div>
		<a class="head_b_link" href="<?php echo base_url(); ?>">PinPix</a>
		<span id="admin_menu">
			<?php if ($user_id) include('admin_menu.php'); ?>
		</span>
	</div><!-- end #header_left -->
	<div id="header_right">
		<div id="h_r_p_people" <?php if( ! $user_id) echo 'style="display: none;"';?>></div> 
		<div id="place_txt_people" <?php if( ! $user_id) echo 'style="display: none;"';?>>
			<a href="<?php echo url('my_account_url'); ?>" class="head_link" style="vertical-align:top;">
				<span class="txt_people" id="user_login"><?php echo $user_login; ?></span>
			</a>
		</div>
		<div id="p_h_link_1"><a href="<?php echo url('help_url'); ?>" class="head_link">Помощь</a></div>
		<div id="p_h_link_2" <?php if( ! $user_id) echo 'style="display: none;"';?>>
			<a href="<?php echo url('logout_url'); ?>" class="head_link">Выход</a>
		</div>
		<div id="p_h_search_area"><input class="search_area" type="text" /></div>
		<div id="p_h_s_butt"><input class="s_butt" type="button" value="" /></div>
	</div><!-- end #header_right -->
</div><!-- end #header -->