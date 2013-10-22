<script language="javascript" type="text/javascript">
	function add_answer(parent_title,parent_id){
    //---------
    document.getElementById('add_comment_label').innerHTML = '<?php echo lang('add_answer'); ?> "'+parent_title+'"';
	document.getElementById('parent_id').value = parent_id;
	}
</script>

<?php extract(get_app_vars()); ?>
<?php if (!empty($errors)) error($errors);?>
<div class="commentsList">
<?php if ( $comments ) : foreach ( $comments as $comment ) : $level = $comment->level-1; ?>

         <?php if ( $level>1 ) : ?>
          <div class="item" style="margin-left:65px;">
         <?php else: ?>
          <div class="item">
         <?php endif; ?>

          <!-- b:avatar -->
          <a class="avatara" href="#">
            <img src="<?php echo modules::run('users_mod/users_ctr/get_avatar_src', $comment->user_id); ?>"/>
          </a>
          <!-- e:avatar -->
          <div class="head">
            <strong><a href="<?php echo  url('profile_view_url',$comment->user_id); ?>"><?php echo  $comment->login; ?></a></strong>
            <!-- b:date of comment -->
            | <?php echo  $comment->comment_date; ?> |
            <!-- e:date of comment -->
            <?php //if ($user_id) :?>
                <a href="#comment_body" onclick="add_answer('<?php echo substr($comment->body,0,20).'...'; ?>','<?php echo  $comment->id; ?>');" ><?php echo  lang('answer'); ?></a>
                <!-- b: user can delete own comment -->
           
                <?php if (($comment->user_id==$user_id) ||($user_id==$photo->user_id)) :?>
                  |
                       <a href="javascript: if(confirm('<?=lang('confirm_delete_comment')?>')) $('#remove_comment_<?php echo  $comment->id; ?>').submit();"><?php echo  lang('remove'); ?></a>
                        <form method="post" id="remove_comment_<?php echo  $comment->id; ?>" name="remove_comment_<?php echo  $comment->id; ?>">
				    		<input type="hidden" name="removed_lft" value="<?php echo  $comment->lft; ?>" />
				    		<input type="hidden" name="removed_rgt" value="<?php echo  $comment->rgt; ?>" />
				    	</form>
                <?php endif; ?>
                <!-- e: user can delete own comment -->
                <p><?php echo  $comment->body; ?></p>
           	<?php //endif; ?>
          </div>
        </div>
<?php endforeach; endif; ?>

<!-- Start pagination -->
<?php echo paginate($paginate_args); ?>
<!-- End pagination -->

<!-- b:form send comment -->
<?php  //if ($user_id) : ?>
<div class="roundedBlock">
    <div class="tr">
        <div class="br">
            <div class="bl">
                <div class="addOwnComment" id="box_kod_kom">

                 <h2 id="add_comment_label"><?php echo  lang('add_comment'); ?></h2>
                 <?php if ($user_id) :?>
                    <form id="f_s" method="post" name="comment_form" >
    				  <input type="hidden" id="parent_id" name="parent_id" />
                      <?php echo form_error('comment_body', '<br /><div class="error_message">','</div>'); ?>
                      <textarea class="t" id="comment_body" rows="" cols="" name="comment_body" ><?php if (isset($_POST)) echo set_value('comment_body'); ?></textarea>
                      <input class="button"  type="submit" value="ok" name=""/>
                     </form>
                    <br/>
                    <br/>
                 <?php endif; ?>
                 <?php if (!$user_id) :?>
                    <em>
                        <a href="<?php  echo base_url(); ?>register">
                           <strong>
                           <?php echo lang('comment_need_login'); ?>
                           </strong>
                        </a>
                    </em>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php //endif;  ?>
<!-- e:form send comment -->
</div>



