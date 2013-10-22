<div id="main_cont_body">
	<div class="middCol">
		<div class="titleFilter">
			<strong><a href="<?php echo base_url().'profile/view/'.$user->user_id?>" class="zag_big_txt" > <?php echo $user->login; ?> </a></strong> 
			/ <a href="<?php echo base_url().'profile/view/'.$user->user_id?>" ><?=lang('user_all_photos')?> </a> 
		</div>
<script type="text/javascript">
function remove_photo(photo_id) {   
	var remove_photo_url = "<?=base_url()?>profile/imremove/<?=$user->user_id?>/"+ photo_id +"/<?=$sort_array['sort_order']?>/<?=$sort_array['sort_as']?>/<?=$cpage;?>";						
	window.location = remove_photo_url;
}
</script>				  
    	<!-- Start sort_menu_box-->
	    <?php      
	    if (isset($view_deleted) && ($view_deleted != FALSE))
	    	$url_part = base_url().'profile/viewdeleted/';
	    else
	    	$url_part = base_url().'profile/view/';
	    ?> 
		<div class="titleFilter">
			<a <?php if($sort_array['date']['sort_by'] == true) echo 'class= "sort_type_bold"'; ?> href="<?php echo $url_part.$user->user_id; ?>/date/<?=$sort_array['date']['sort_as']?>"><?php echo lang('comp_sort_by_date'); ?> 
				<?php if($sort_array['date']['sort_by'] == true) { ?><img src="<?php if($sort_array['date']['sort_as'] == 'asc') echo static_url()."images/m_main_arrow.gif"; else echo static_url()."images/m_main_arrow_desc.gif"; ?>" /><?php } ?>
			</a> |
			<a <?php if($sort_array['title']['sort_by'] == true) echo 'class= "sort_type_bold"'; ?> href="<?php echo $url_part.$user->user_id; ?>/title/<?=$sort_array['title']['sort_as']?>">
				<?php
					if($sort_array['title']['sort_by'] == false) echo lang('comp_sort_by_name'); 
					else {
						if($sort_array['title']['sort_as'] == 'asc') echo lang('comp_sort_by_name_desc'); 
						else echo lang('comp_sort_by_name');
					}  
				?>
				<?php if($sort_array['title']['sort_by'] == true) { ?><img src="<?php if($sort_array['title']['sort_as'] == 'asc') echo static_url()."images/m_main_arrow.gif"; else echo static_url()."images/m_main_arrow_desc.gif"; ?>" /><?php } ?>
			</a> |
			<a <?php if($sort_array['popular']['sort_by'] == true) echo 'class= "sort_type_bold"'; ?> href="<?php echo $url_part.$user->user_id; ?>/popular/<?=$sort_array['popular']['sort_as']?>"><?php echo lang('comp_sort_by_rate'); ?> 
				<?php if($sort_array['popular']['sort_by'] == true) { ?><img src="<?php if($sort_array['popular']['sort_as'] == 'asc') echo static_url()."images/m_main_arrow.gif"; else echo static_url()."images/m_main_arrow_desc.gif"; ?>" /><?php } ?>
			</a>
		</div> 

	    <!-- Start Image box-->
	    <div class="picsHolder">
		<?php
			if(!empty($photos)) { 
				foreach ($photos as $photo) :
		?>
			<div class="item">      
				<a onfocus="this.blur()" class = "pic" href="<?php echo base_url() . 'photo/view/' . $photo->photo_id?>"> 
					<img style =" margin-top:<?=$photo->margin_top?>px;
						margin-bottom:<?=$photo->margin_bottom?>px;
						margin-left:<?=$photo->margin_left?>px;
						margin-right:<?=$photo->margin_right?>px;
						width:<?=$photo->img_width?>px;
						height:<?=$photo->img_height?>px;
						overflow:hidden;" 
						alt="<?=$photo->title?>" border="0" src="<?=$photo->urlImg?>" /> 
				</a>		
				<div class="info">
					<i class="views" title="<?= lang("user_views"); ?>">  <?php echo (empty($photo->see_cnt)? 0 : $photo->see_cnt) ?></i>
					<i class="pin" title="<?= lang("rate"); ?>">  <?php echo (empty($photo->num_votes)? 0 : $photo->num_votes) ?></i>
					<i class="comment" title="<?= lang("user_comments"); ?>"> <?php echo (empty($photo->comcnt)? 0 : $photo->comcnt) ?></i>
				</div>
					<a href="<?php echo base_url(). 'profile/view/' . $photo->user_id ?>" class="author">  <?=$photo->login?> </a>
					<p><?=((strlen($photo->title) > 100)? substr($photo->title, 0, 97).'...': $photo->title);?>  </p>
					<input type="hidden" id="ph_title_<?php echo $photo->photo_id;?>" value="<?php echo $photo->title;?>" />
		  			<input type="hidden" id="ph_category_<?php echo $photo->photo_id;?>" value="<?php echo $photo->category_id;?>" />
		  			<input type="hidden" id="ph_album_<?php echo $photo->photo_id;?>" value="<?php echo $photo->album_id;?>" />
		  			<input type="hidden" id="ph_competition_<?php echo $photo->photo_id;?>" value="<?php echo $photo->competition_id;?>"/>
		  			<input type="hidden" id="ph_m_album_<?php echo $photo->photo_id;?>" value="<?php echo (($photo->id_album_main)?'yes':'no');?>" />
		  			<input type="hidden" id="ph_erotic_<?php echo $photo->photo_id;?>" value="<?php echo (($photo->erotic_p)?'yes':'no');?>" />
		  			<input type="hidden" id="ph_descr_<?php echo $photo->photo_id;?>" value="<?php echo $photo->description;?>" />
		  			<input type="hidden" id="ph_allowed_<?php echo $photo->photo_id;?>" value="<?php echo $photo->view_allowed;?>" />		  			
		  			<div class="picOptions">
						<span>
      			<?php
                    if($my):
                    	if (isset($view_deleted) && ($view_deleted === TRUE)):                    		
				?>
                    		<a href="<?=base_url()?>profile/imrevert/<?=$user->user_id?>/<?=$photo->photo_id.'/'.$sort_array['sort_order'].'/'.$sort_array['sort_as'].'/'.$cpage;?>"><img title="<?=lang('restore_photo')?>" src="<?=static_url()?>/images/ic_recover.gif" style="position:relative;top:1px;"/></a>
                    		<a href="#" onclick="javascript: if(confirm('<?=lang('remove_photo')?>?')) remove_photo('<?=$photo->photo_id?>'); return false; "><img title="<?=lang('remove_photo')?>" src="<?=static_url()?>/images/ic_del.gif"/></a>
                <?php	else: ?>
                			<a id="photo_<?=$photo->photo_id;?>" href="#" onclick="return ShowDetalis(this); return false"><img src="<?=static_url()?>images/ic_edit.gif"/></a>
                			<a onclick="return DelConfirm();" href="<?=base_url()?>profile/imdel/<?=$user->user_id?>/<?=$photo->photo_id.'/'.$sort_array['sort_order'].'/'.$sort_array['sort_as'].'/'.$cpage;?>"><img src="<?=static_url()?>images/ic_close.gif"/></a>
                <?php
                		endif;
                	endif;
                ?>         
						</span>
					</div>
			</div>
		<?php
					endforeach;
				}
		?>
	    <?php
			if (!empty($paginate_args)) echo paginate($paginate_args);
		?>
	</div> 
</div>

   