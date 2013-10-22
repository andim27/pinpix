	<div style="height:auto;width:100%;"><?php if(isset($ads_block) && !empty($ads_block['top_ads'])) echo $ads_block['top_ads']; ?></div>
	<?php if (!browser_access()) {?>
		<div class="header" style = "height: 50px; font-size:15px;">
			<a href="<?php echo base_url(); ?>" onfocus="this.blur()" class="logo"><img src="<?php echo static_url(); ?>images/logo_pinPix.gif" /></a>		
			<?php  echo   lang("upgrade_browser") ;?>
		</div>
	<?php 	exit;	 }   ?>
	  
	<div class="header">
		<a href="<?php echo base_url(); ?>" onfocus="this.blur()" class="logo" style="text-decoration:none;"><img src="<?php echo static_url(); ?>images/logo_pinPix.gif" />
		<span style="position:relative;top:6px;">beta</span></a>
		<ul class="topMenu">
			<li <?php echo (($this->uri->segment(1) == '')||($this->uri->segment(1) == 'main'))?"class = 'active'" :''?> ><a onfocus="this.blur()" href="<?php echo base_url(); ?>"><span>PinPix</span></a></li>
			<span id = "menu_l1" <?php if( ! $user_id) echo 'style="display: none;"';?> >
			<li <?php echo (($this->uri->segment(1) == 'profile')&&($this->uri->segment(2) == 'view_profile'))?"class = 'active'" :''?>><a onfocus="this.blur()" href="<?php echo base_url(); ?>profile/view_profile/"><span><?=lang('my_photo')?></span></a></li>
			<li <?php echo (($this->uri->segment(1) == 'profile')&&($this->uri->segment(2) == 'edit'))?"class = 'active'" :''?> ><a onfocus="this.blur()" href="<?php echo base_url(); ?>profile/edit"><span><?=lang('my_profile')?></span></a></li>
			</span>
			<li <?php echo ($this->uri->segment(1) == 'competition')?"class = 'active'" :''?> > <a onfocus="this.blur()" href="<?php echo url('competition_url'); ?>"><span><?=lang('competitions')?></a></span></li>
			<li <?php echo ($this->uri->segment(2) == 'upload_photo')?"class = 'active'" :''?> >  <a onfocus="this.blur()" href="<?php echo base_url(); ?>photo/upload_photo"><span><?=lang('add_photo')?></a></span></li>
			<li class="hasSubmenu <?php echo ($this->uri->segment(2) == 'category')? "active" :'' ?>"><a href="#" onfocus="this.blur()" onclick="javascript: toggleVisibility('header_menu_raz');"><span><?=lang('categories')?></span></a></li>
		</ul>
			 
		<form action="<?php echo base_url(). 'search/searching';?>" name="search_form" method="post">			 
			     <div id="p_h_search_area"><input class="search_area" name="keyword" type="text" value = "<?php echo lang('search'); ?>" onclick="this.value = '';"/></div>
			     <div id="p_h_s_butt"><input class="s_butt" type="submit" value="" /></div>
	    </form> 

    
		<ul class="lingva">
			<li><a href="#" onclick="SetUserLang('ru', '<?=$user_id?>', '<?= base_url()?>'); return false;">RUS</a></li>
			<li><a href="#" onclick="SetUserLang('kz', '<?=$user_id?>', '<?= base_url()?>'); return false;">KAZ</a></li>
		</ul>
		
		<div class="hi" id="place_txt_people" <?php if( ! $user_id) echo 'style="display: none;"';?>>
			<a href="<?php echo url('my_account_url'); ?>" class="user" id="user_login"><b><?php echo $user_login; ?></b></a> 
			<a href="<?php echo url('logout_url'); ?>" id="p_h_link_2"><?=lang('exit')?></a>
		</div>

		
	</div>
  	
	<div id="header_menu_raz">
		<div id="header_menu_box">
			<div id="menu_box_content">
			<?php $cats = modules::run('catalog_mod/catalog_ctr/get_tree');?>
				<?php for($i=0; $i<count($cats); $i++) : ?>
				<!--Menu_box 1-->
				<div class="menu_box" id="menu_box_id_<?= $i; ?>" align="left">
				<?php echo lang_translate(modules::run('catalog_mod/catalog_ctr/view_tree_', $cats[$i],'header'),"kz"); ?>
				<!-- End Menu_box 1-->
				</div>
				<?php endfor; ?>
				<div class="clear"></div>
     		</div>
		<div class="clear"></div>
		</div><!--header_menu_raz-->
	</div>
	<?php if (!empty($flashbox_block)) {    include('flashbox.php');} ?>
	