<!-- ********************************************************* -->
<!-- Start opis_field_box-->

<div id="opis_field_box_<?=$i?>" class="roundedBlock"> <div class="tr"><div class="br"><div class="bl">
<form action="<?=base_url().'profile/albumedt1/'.$a_single->user_id?>" method="post" onreset="AlbumFormReset(<?=$i?>);" id="album_form_<?=$i?>" onsubmit="return AlbumEditSubmit(<?=$i?>);">
<input type="hidden" name="album_id" value="<?=$a_single->album_id?>">

<div class="edit">
		<div class="photoOptions">
			<h2><?php echo lang('album_edit_title');?></h2>
			<span> <?php echo lang('album_title');?> <input id="album_title_<?=$i?>" class="i" type="text" name="title" value="<?=$a_single->title?>" /> </span>
			<span> <?php echo lang('album_desc');?> <textarea class="i" name="short_description" cols="" rows=""><?=$a_single->description?></textarea></span>
			
			<span><?php echo lang('album_cat');?> 
			<select name="category_id" class="s">
			<?php foreach($categories as $category):?>
  			<option value="<?php echo $category->id;?>" <?=(($a_single->category_id == $category->id)?' selected="selected"':"")?>>
  			<?php echo $category->name?>
			</option>
			<?php endforeach;?>
 			</select></span>
  		
			<span><?php echo lang('album_rule');?>
			<select id="album_allowed_<?=$i?>" class="s" name="view_allowed" onchange="ShowAlbumPwdById(this, <?=$i?>);">
			<?php foreach($view_allowed as $idx => $view):?>
		  	<option value="<?=$idx?>"<?=(($a_single->view_allowed == $idx)?' selected="selected"':"")?>>
		  	<?=$view?>
			</option>
			<?php endforeach;?>
  			</select></span>
  
  			<div id="album_pwd_<?=$i?>" <?php if($a_single->view_allowed != 0)  echo "style='display:none;'"; ?>>
	 		  <span><?php echo lang('New_password');?> 
			  <input value="<?=$a_single->view_password?>" id="album_pass_<?=$i?>" class="i" type="text" name="password" />
	   		  </span>
	   		  <span><?php echo lang('New_password_confirmation');?> 
			  <input id="album_confirm_<?=$i?>" class="s" type="text" name="password_confirm" />						 
	   		  </span>
	   		</div>
  
			<span><?php echo lang('album_erotic');?>
				<input  name="erotic_p" class="ch" type="checkbox"<?=(($a_single->erotic_p == 1)? ' checked=checked': "")?> /></span>
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
<!-- ********************************************************* -->	