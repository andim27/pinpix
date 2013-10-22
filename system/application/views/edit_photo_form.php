    <!-- Start ph_sv_box-->
    <div id="ph_sv_box" class="popUp" style="display:none">
    <img  style="position:absolute;top:3px;right:3px" title="Закрыть" border="0" src="<?= static_url(); ?>images/ic_close.gif" onclick="$('#detalis_close').click();" />
    <div class="wrap">
    		<div class="photoOptions">
    			<form method="post" action="<?=base_url().'profile/imedit/'.$user->user_id ?>" >

				<!--Center-->
					<h2><?=lang('user_photo_properties')?></h2>
					<span><?=lang('user_photo_title')?>
					<input id="pp_id" type="hidden" name="photo_id"/>
					<input  id="pp_title" class="i" type="text" name="photo_name" value="<?= substr($photo->title,0,38); ?>" /></span>
					<span><?=lang('user_photo_description')?></span>
			
					<textarea id="pp_descr" rows="" cols="39" name="photo_desc" class="i"></textarea>
			        <span><?=lang('user_photo_allowed')?></span>
			        <select id="pp_allowed" class="s" name="view_allowed" onChange="propAlovedChange(this);">
			        <?php foreach($view_allowed as $idx => $restrict):?>
						        <option value="<?=$idx?>"><?=$restrict?></option>
			        <?php endforeach; ?>
					</select>
					
					<div id="hidden_pwd" <?php if($photo->view_allowed != 0)  echo "style='display:none;'"; ?>>
				 		  <span><?php echo lang('New_password');?> 
						  <input value="<?=$photo->view_password?>" id="" class="i" type="text" name="photo_password"  />
				   		  </span>
				   		  <span><?php echo lang('New_password_confirmation');?> 
						  <input id="" class="s" type="text" name="photo_password_confirm" />						 
				   		  </span>
				   	</div>
				   		
			        <span><?= lang('album_cat'); ?>
			        <select id="pp_category" class="s" name="photo_category">
			        <?php foreach($categories as $category):?>
					    <option value="<?php echo $category->id;?>"><?php echo lang_translate($category->name,"kz");?></option>
			        <?php endforeach;?>
			        </select>
			        </span>
			
			        <span><?= lang('photo_of_album'); ?>
			            <select id="pp_album" class="s" name="photo_album">
			            <?php foreach($all_albums as $album):?>
							<option value="<?php echo $album->album_id;?>"><?php echo $album->title?></option>
			            <?	endforeach; ?>
			            </select>
			        </span>
			         <span><?=lang('user_photo_competitions')?></span>			         
			         <select id="photo_competition" class="s" name="photo_competition" style="width: 100%;">
			         <?php
			         	$competition_photo = null;
			         	if(!empty($photo->competition_id)) {
			         		if(is_array($photo->competition_id)) $competition_photo = $photo->competition_id[0];
			         		else $competition_photo = $photo->competition_id;
			         	}
			         	
						$comp_str = '<option value="0">&nbsp;</option>';						
						foreach($competitions as $competition) {
							if($competition->active == 0) continue;
							
							$selected = "";
							if($competition_photo && $competition_photo->photo_id == $photo->photo_id && $competition_photo->competition_id == $competition->competition_id) $selected = "selected";
							$comp_str .= '<option value="'.$competition->competition_id.'" '.$selected.'>'.$competition->title.'</option>';
						}
						echo $comp_str;
	               	?>
	               	</select>
			        
			
			        <!--<span>
			        <em>
			        <strong>Внимание!</strong>
			        На один конкрс можно присылать не больше пяти фото!
			        </em>
			        </span>-->
					 
					<span><?=lang('user_photo_erotic')?>
			        	<input id="pp_erotic" class="ch" name="erotic_p" type="checkbox" />
			        </span>
			        <span><?=lang('user_photo_album_main')?>
			            <input id="pp_album_main" class="col" name="photo_main_page" type="checkbox" />
			        </span>
			        <br/>
	
			        <span>
			        	<input class="button"  type="submit"  value="Ok" />
		  				<input class="button" id="detalis_close" type="button" value="Отмена"  />
		  			</span>
		  			
			    </form>
			    
		    <div class="topBg"></div>
		    <div class="bottBg"></div>
   <!-- wrap -->
   		</div>
<!-- PopUp -->
   </div>		   
</div>		 

