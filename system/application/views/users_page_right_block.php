<!-- start #users_page_right_box -->
<div id="users_page_right_box">
<?php if (isset($view_deleted) && ($view_deleted === TRUE)):?>
	<div class="roundedBlock"><div class="tr"><div class="br"><div class="bl">
		<h2><?=lang('deleted_warnung_title')?></h2>
		<em class="green"><?=lang('deleted_warnung')?></em>
	</div></div></div></div>			
<?php endif;?>

<?php 
if (!isset($view_deleted) || ($view_deleted === FALSE))
 	include('user_info_block.php'); 

if ($my && (!isset($view_deleted) || $view_deleted === FALSE))	  
 	include('upload_form.php'); 	
  
	include(MODBASE.'gallery_mod/views/albums_sitebar.php'); 
?>		
		
<?php if ($my && (!isset($view_deleted) || $view_deleted === FALSE)):?>
	<div class="trashBox">
		<a href="<?=base_url()?>profile/viewdeleted">(<?=(int)$deleted_cnt['albums']?>/<?=(int)$deleted_cnt['photos']?>) <?=lang('deleted_photos_albums')?> </a>
	</div>							
<?php endif;?> 
<!-- end users_page_right_box --></div>