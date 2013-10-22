	<div class="sprite_block">

		<div class="sprite">
			<div class="profil_txt_height">
				<div class="im_znak">
					<img alt="" id="img_pm_0" src="<?php echo static_url(); ?>images/m_box_plus.gif" onclick="changeButton(this, '0')"/>
				</div>
				<span class="im_txt_profil"><?=lang('album_new')?></span>
				<div class="im_return"></div><br />
			</div>
		</div>
			
		<div id="album_invisible_0" style="display:none" class="active_sprite">
<!-- ********************************************************* -->
<!-- Start opis_field_box-->
<div class="item">
<div id="opis_field_box" class="roundedBlock"> <div class="tr"><div class="br"><div class="bl">
<form action="<?php echo base_url().'profile/albumedt1/'.$this->db_session->userdata('user_id');	?>" method="post" onreset="AlbumFormReset(0);" id="album_form_0" onsubmit="return NewAlbumSubmit();">
		<input type="hidden" name="album_id" value="0" />

		<div class="edit">
		<div class="photoOptions">		
			<h2><?=lang('album_new')?></h2>
			
			<span> <?php echo lang('album_title');?> 
			<input class="i" type="text" name="title" id="album_title"  /> </span>
			
			<span> <?=lang('user_photo_description')?>
			<textarea class="i" name="short_description" cols="" rows=""></textarea></span>
			  				  
			<span><?php echo lang('album_cat');?> 
			<select name="category_id" class="s">
			<?php foreach($categories as $category):?>
  			<option value="<?php echo $category->id;?>">
  			<?php echo $category->name?>
			</option>
			<?php endforeach;?>
 			</select></span>
  		
			<span><?php echo lang('album_rule');?>
			<select id="new_album_allowed"  class="s" name="view_allowed" onchange="ShowAlbumPwdById(this);">
			<?php foreach($view_allowed as $idx => $view):?>
		  	<option value="<?=$idx?>">
		  	<?=$view?>
			</option>
			<?php endforeach;?>
  			</select></span>
  			 		
  			<div id="album_pwd" style="display:none;">
	 		  <span><?php echo lang('New_password');?> 
			  <input id="new_album_pass" class="i" type="text" name="password" />
	   		  </span>
	   		  <span><?php echo lang('New_password_confirmation');?> 
			  <input id="new_album_confirm" class="s" type="text" name="password_confirm" />						 
	   		  </span>
	   		</div>
  
			<span><?php echo lang('album_erotic');?>
				<input  name="erotic_p" class="ch" type="checkbox" /></span>
			<br />
																	
			<span><input type="submit" value="Ok" class="button" /> 
			<input type="reset" value="Отмена" class="button"  />
			<!--  <input  type="reset"  value="Отмена" class="button" />  -->
			</span>
		</div>
		<div class="topBg"><!-- --></div>
		<div class="bottBg"><!-- --></div>
	</div>
<!-- End opis_field_box --></form>
</div></div></div></div>

</div></div>
</div>