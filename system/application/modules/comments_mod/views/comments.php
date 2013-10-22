<script language="javascript" type="text/javascript">
	function add_answer(parent_title,parent_id){
		document.getElementById('add_comment_label').innerHTML = '<?php echo lang('add_answer'); ?> "'+parent_title+'"';
		document.getElementById('parent_id').value = parent_id;
	}
</script>

<?php extract(get_app_vars()); ?>
<?php if (!empty($errors)) error($errors);?>

<!-- Start otz block -->
<div id="otz">

<?php if ( $comments ) : foreach ( $comments as $comment ) : $level = $comment->level-1; ?>
<br>
		  <table border="0" cellspacing="0" cellpadding="0">
	        <tr valign="top">
	          <?php if ( $level>1 ) : ?>
		          <?php $w = ($level-1)*60; ?>
		          <td width="<?php echo $w; ?>">&nbsp;</td>
	          <?php endif; ?>
	          <td width="65"><a href="<?php echo url('profile_view_url',$comment->user_id); ?>"><img alt="" border="0" src="<?php echo modules::run('users_mod/users_ctr/get_avatar_src', $comment->user_id); ?>" width="65" height="65"/></a></td>
	          <td width="15">&nbsp;</td>
	          <td width="303" align="left" valign="top"><div class="nickdate_txt"><a href="<?php echo url('profile_view_url',$comment->user_id); ?>"><?php echo $comment->login; ?></a><?php echo $comment->comment_date; ?></div>
	            <span class="im_txt"><?php echo $comment->body; ?></span></td>
	        </tr>
	        <tr height="15">
	          <td>&nbsp;</td>
	        </tr>
	        
	          	<?php if ($user_id) :?>
					<tr>
					  <?php if ( $level>1 ) : ?>
				          <td width="<?php echo $w; ?>">&nbsp;</td>
			          <?php endif; ?>
				  	<td><span class="im_txt_profil"><a href="#box_kod_kom" onclick="add_answer('<?php echo substr($comment->body,0,20).'...'; ?>','<?php echo $comment->id; ?>');"><?php echo lang('answer'); ?></a></span></td>
				    <td>&nbsp;</td>
				    <td>
				    	<?php if ($comment->user_id==$user_id) :?>
				    	<form method="post" id="remove_comment_<?php echo $comment->id; ?>" name="remove_comment_<?php echo $comment->id; ?>">
				    		<input type="hidden" name="removed_lft" value="<?php echo $comment->lft; ?>" />
				    		<input type="hidden" name="removed_rgt" value="<?php echo $comment->rgt; ?>" />
				    	</form>
				    	<span class="im_txt_profil"> <a href="javascript: document.getElementById('remove_comment_<?php echo $comment->id; ?>').submit();"><?php echo lang('remove'); ?></a></span>
				    	<?php endif; ?>
				    </td>
				    </tr>
					<tr height="15">
		          		<td>&nbsp;</td>
		        	</tr>	
				<?php endif; ?>
	          
	      </table>
<?php endforeach; endif; ?>

<!-- Start pagination -->
<?php echo paginate($paginate_args); ?>
<!-- End pagination -->

<?php  if ($user_id) : ?>
<div id="box_kod_kom" class="nifty">

	        
	        
	        <div align="left"> 
		        <span class="m_zag_txt">
		        	<strong>
		        		<span id="add_comment_label"><?php echo lang('add_comment'); ?></span>
		        	</strong>
		        </span>
		        
		        <form method="post" name="comment_form">
				
				  <input type="hidden" id="parent_id" name="parent_id" />
				
				  <?php echo form_error('comment_body', '<br /><div class="error_message">','</div>'); ?>
				  <textarea class="box_area_odfkom" id="comment_body" name="comment_body" cols=""><?php if (isset($_POST)) echo set_value('comment_body'); ?></textarea>
		          <div class="place_ok_otstup">
		            <input class="ent_butt" type="submit" value="" />
		          </div>
		          
		          <div class="place_ser_otsup_txt_sp"><a href="<?php  echo base_url(); ?>" class="head_link"><?php echo lang('comment_need_login'); ?></a></div>
	        	
	        	</form>
	        </div>
	        
	       
</div>
<?php endif;  ?>

</div>
<!-- End otz block -->
