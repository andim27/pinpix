<script type="text/javascript">
function EditAlbum(index) {
	document.getElementById('opis_field_box_'+index).style.display = 'block';
	var overlay = document.createElement("div");
	overlay.id = "overlay";
	$("body").append(overlay);
	$("#overlay").css("opacity", .5);
	$("#overlay").css("height", $(document).height()+"px");
}

function CloseEditBox(index) {
	document.getElementById('opis_field_box_'+index).style.display = 'none';
	$("#overlay").remove();
}

var __LabelDeleteConfirmation = "<?=lang('js_label_DeleteAlbumConfirmation')?>";
var base_url = "<?=base_url()?>";

</script>
<?php
function sort_ord($sort_order)
{
if ((empty ($sort_order))||($sort_order == "a"))
    $sort_order = "d";
else
    $sort_order = "a";
return   $sort_order;
}

if (empty ($sort_order)) $sort_order = "d" ;
if (empty ($sort_type)) $sort_type=1;

?>
<!-- Start sort_menu_box-->
<?php      if(!empty($albums )) { ?> 

		<div class="titleFilter">
		<?php if ($sort_type == 1) echo '<strong>'?>
			<a href="<?php echo base_url().'album/view_user_albums/'. $user_id . '/1/'. sort_ord($sort_order); ?>"><?php echo lang('comp_sort_by_name'); ?> </a>
		<?php if ($sort_type == 1) echo '</strong>'?>
		<?php if ($sort_type == 2) echo '<strong>'?>
			| <a href="<?php echo base_url().'album/view_user_albums/'. $user_id . '/2/'. sort_ord($sort_order); ?>"><?php echo lang('comp_sort_by_date'); ?></a> 
		<?php if ($sort_type == 2) echo '</strong>'?>		
		</div>				

<?php } //if(!empty($photos)) ?>
    
<div class="albumsHolder">
<?php
$i = 1;
foreach ($albums as $a_single) :	
if 	(isset($a_single->works)) {
?>

<div class="item">
		<div class="thumb"><div>
	          <?php
	          if ( $user_id == $this->db_session->userdata('user_id') ) {            
	          ?>
	          <a id="album_<?=$a_single->album_id ?>" href="#" onclick="EditAlbum(<?=$i?>); 	return false; "><img src="<?=static_url()?>images/ic_edit.gif"/></a>
	          <a onclick="return DelConfirm();" href="<?=base_url()?>profile/albdel1/<?=$user_id.'/'.$a_single->album_id; ?>"><img src="<?=static_url()?>images/ic_close.gif"/></a>
	          <?php
	          }
	          ?>
	          </div>   
                               
			<?php if (!empty ($a_single->p_link)) {?>
 					<div class="im_profil_photo">
                           <a href="<?=base_url()?>album/view/<?=$a_single->album_id?>" class="thumb">
	                			<img height="<?=$a_single->nheight?>" width="<?=$a_single->nwidth?>"  border="0"  src="<?=$a_single->img?>" alt="" style = "margin-top: <?=$a_single->margin_top?>px; margin-bottom: <?=$a_single->margin_bottom?>px; margin-left: <?=$a_single->margin_left?>px; margin-right: <?=$a_single->margin_right?>px " />
                           </a>
					 </div>
		     <?php } else { ?> 
		     <a href="<?=base_url()?>album/view/<?=$a_single->album_id?>"><img src="<?=static_url()?>data/_album_pic_01.gif" /></a>
		 <?php } ?>
		 </div>
		 
		<span class="count"><?=$a_single->works?></span>
		<a href="<?=base_url()?>album/view/<?=$a_single->album_id?>" class="name"><?=$a_single->title?></a>
		<p><?= $a_single->description?></p>
		
			
<!-- ********************************************************* -->
<!-- Start opis_field_box-->

<div id="opis_field_box_<?=$i?>" style = "display:none;">
<form action="<?=base_url().'profile/albumedt1/'.$user_id?>" method="post" onreset="AlbumFormReset(<?=$i?>);" id="album_form_<?=$i?>" onsubmit="return AlbumEditSubmit(<?=$i?>);">
<input type="hidden" name="album_id" value="<?=$a_single->album_id?>">

<div class="popUp" ><div class="wrap">
		<div class="photoOptions">
			<h2><?php echo lang('album_edit_title');?></h2>
			<span> <?php echo lang('album_title');?> <input id="album_title_<?=$i?>" class="i" type="text" name="title" value="<?=$a_single->title?>" /> </span>
			<span> <?php echo lang('album_desc');?> <textarea class="i" name="short_description" cols="" rows=""><?=$a_single->description?></textarea></span>
			
			<span><?php echo lang('album_cat');?> 
			<select name="category_id" class="s">
			<?php if(!empty($categories)): foreach($categories as $category):?>
  			<option value="<?php echo $category->id;?>"<?=(($a_single->category_id == $category->id)?' selected="selected"':"")?>>
  			<?php echo $category->name?>
			</option>
			<?php endforeach; endif;?>
 			</select></span>
  		
			<span><?php echo lang('album_rule');?>
			<select id="album_allowed_<?=$i?>" class="s" name="view_allowed" onchange="ShowAlbumPwdById(this, <?=$i?>);">
			<?php if(!empty($view_allowed)): foreach($view_allowed as $idx => $view):?>
		  	<option value="<?=$idx?>"<?=(($a_single->view_allowed == $idx)?' selected="selected"':"")?>>
		  	<?=$view?>
			</option>
			<?php endforeach; endif; ?>
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
				<input class="col" name="erotic_p" type="checkbox"<?=(($a_single->erotic_p == 1)? ' checked=checked': "")?> /></span>
			<br />
			
			<span><input type="submit" value="Ok" class="button" /> 
			<input type="button" value="Отмена" class="button" onclick = "CloseEditBox(<?=$i?>); " />
			<!--  <input  type="reset"  value="Отмена" class="button" />  -->
			</span>
		</div>
		<div class="topBg"><!-- --></div>
		<div class="bottBg"><!-- --></div>
	</div></div>
<!-- End opis_field_box --></form></div>
<!-- ********************************************************* -->	
								
</div>
	
<?php $i++; } endforeach; ?>	
</div>
<br /><br /><br /><br /><br /><br />

<?php echo paginate($paginate_args); ?>

