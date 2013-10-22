<script type="text/javascript">
function changeButton(cur, index) {
	sImg = cur.src.substring(cur.src.lastIndexOf('images/')+7);
	if (sImg == 'm_box_plus.gif') {
		cur.src = '<?php echo static_url(); ?>images/m_box_minus.gif';
		document.getElementById('album_invisible_'+index).style.display = '';
	}
	else {
		cur.src = '<?php echo static_url(); ?>images/m_box_plus.gif';
		document.getElementById('album_invisible_'+index).style.display = 'none';
	}	
}
var allopened = 0;
function changeAllButtons(amount) {
	if (allopened == 0)
		allopened = 1;
	else
		allopened = 0;
	
	if (allopened == 1)
	{
		document.getElementById ('op-cl').innerHTML = "<?php echo lang ('comp_closeall')?>"	;
		for (var i = 1; i<= amount; i++)
		{
		document.getElementById('album_invisible_'+i).style.display = '';
		var a = document.getElementById('im_znak_'+i);
		var sImg = a.src.substring(a.src.lastIndexOf('images/')+7);
		if (sImg == 'm_box_plus.gif') {
			a.src = '<?php echo static_url(); ?>images/m_box_minus.gif';			
			}	
		}
	}
	if (allopened == 0)
	{
		document.getElementById ('op-cl').innerHTML = "<?php echo lang ('comp_openall')?>"	;
		for (var i = 1; i<= amount; i++)
		{
		document.getElementById('album_invisible_'+i).style.display = 'none';
		var a = document.getElementById('im_znak_'+i);
		var sImg = a.src.substring(a.src.lastIndexOf('images/')+7);
		if (sImg == 'm_box_minus.gif') {
			a.src = '<?php echo static_url(); ?>images/m_box_plus.gif';			
			}	
		}
	}
	
}

function CloseEditBox(index) {
	document.getElementById('opis_field_box_'+index).style.display = 'none';
}

function remove_album(album_id) {   
	var remove_album_url = "<?=base_url()?>profile/alremove/<?=$user->user_id?>/"+ album_id +"/<?=$sort_array['sort_order']?>/<?=$sort_array['sort_as']?>/<?=$cpage;?>";						
	window.location = remove_album_url;
}

</script>

<div class="userAlbumsList">
<?php if (!empty ($albums)) :
   	
if (! isset ($opened_album_id))
	$opened_album_id = -1;
?>
	<div class="title">
		<strong> <a href="<?=base_url() . 'album/view_user_albums/' . $albums[0]->user_id?>" ><?=lang('all_albums')?></a></strong>		
		| <a href="#" onclick="changeAllButtons('<?=count ($albums)?>'); return false;"><span id ="op-cl"><?php echo lang ('comp_openall')?> </span></a>
	</div>
<?php endif //(!empty ($albums)) : ?>
		<div class="list">	
	<!-- if we includ this block to self userprofile page-->	
		<?php 
			if (isset($my) && ($my === TRUE) && (!isset($view_deleted) || ($view_deleted === FALSE)))
				include('new_album_block.php'); ?>		
<?php if (!empty ($albums)) : ?>			
		<?php
		$i = 1;
		foreach ($albums as $a_single) :		
		?>
        <!-- a:AlbumLists -->
		<div class="sprite">
			<div class="profil_txt_height">
				<div class="im_znak" >
			
					<img id = "im_znak_<?=$i?>" alt=""  src="<?php echo ($a_single->album_id!=$opened_album_id ? static_url().'images/m_box_plus.gif': static_url().'images/m_box_minus.gif')?>   " onclick="changeButton(this, '<?=$i?>')" />
				</div>
				<span class="im_txt_profil">
                   <a href="<?=base_url()?>album/view/<?=$a_single->album_id?>" class="albumName">
                    <?=$a_single->title?>
                   </a>  
                   <?php
                   if ((isset ($my) && ($my == TRUE)))
                   { 
	                   if  (!isset($view_deleted) || $view_deleted === FALSE):?>
	                   <a style = "position:absolute; right:35px; border-bottom:none;" onclick="return DelConfirm();" href="<?=base_url()?>profile/albdel1/<?=$a_single->user_id.'/'.$a_single->album_id?>">
							<img src="<?=static_url()?>images/ic_del.gif"/>
					   </a>  
					   <?php else :?>
					    <a style = "position:absolute; right:55px; border-bottom:none;" href="<?=base_url()?>profile/albrevert/<?=$user->user_id.'/'.$a_single->album_id.'/'.$sort_array['sort_order'].'/'.$sort_array['sort_as'].'/'.$cpage;?>">
							<img src="<?=static_url()?>images/ic_recover.gif"/>
					    </a>
					    <a style="float:right;position:relative;right:7px;top:1px;border:medium none;" onclick="javascript: if(confirm('<?=lang('remove_album')?>?')) remove_album('<?=$a_single->album_id?>'); return false; " href="#">
							<img src="<?=static_url()?>images/ic_del.gif"/>
					   </a>  
					   <?php 
					   endif;
                   	}
                    ?>          
                 </span>
				
				 <div class="im_lamp"></div>
				 
				<br />
			</div>
		</div>
					
		<div id="album_invisible_<?=$i?>" <?php if ($a_single->album_id!=$opened_album_id) { ?>style="display:none" <?php }?> class="active_sprite">
		
		<div class="item">			
 				<div class="description">
 					<?php if (!empty ($a_single->p_link)) {?>
 					<div class="im_profil_photo">
                           <a href="<?=$a_single->p_link ?>" class="thumb">
	                			<img height="<?=$a_single->nheight?>" width="<?=$a_single->nwidth?>"  border="0"  src="<?=$a_single->img?>" alt="" style = "margin-top: <?=$a_single->margin_top?>px; margin-bottom: <?=$a_single->margin_bottom?>px; margin-left: <?=$a_single->margin_left?>px; margin-right: <?=$a_single->margin_right?>px " />
                           </a>
					 </div>
	 	            <?php } ?>

					<ul>
						<li class="photo"><?=lang('album_of_photos')?>: <?php if(isset($a_single->works)) echo $a_single->works; ?></li>
						<li class="rating"><?=lang('album_of_rate')?>: <?=$a_single->balls?></li>
						<!-- we don't have functionality to comment album. place 0 to speed up page download instead of a_single->comms -->
						<li class="comments"><?=lang('album_of_comments')?>: 0 </li>
						<li class="date"><?=lang('album_of_created')?>: <?php echo date("d.m.Y", strtotime($a_single->date_added)); ?></li>
					</ul>              
					<p><strong><?=$a_single->title?></strong><?php echo $a_single->description //$a_single->short_description
					?></p>
										
					<!-- if we includ this block to self userprofile page-->				
					
					<?php if (isset($my) && ($my === TRUE) && (!isset($view_deleted) || ($view_deleted === FALSE))): ?>
					<?php include ('edit_album_round_block.php')?>	
					<?php endif ?>	
												
				 </div>				 
			</div>
		</div>
		<?php $i++; endforeach; ?>
		<?php endif //(!empty ($albums)) : ?>
	</div>

</div> 
<!-- End main_side_right -->


		
		
		
		
		
		
		