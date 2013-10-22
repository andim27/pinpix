<?php include('head_new.php'); ?>
<?php //echo $single_photo_block; ?>
<?php //echo $album_photos_block; ?>
<?php //echo $photo_comments_block; ?>
<body class="pix">
	<?php
	 include(MODBASE.'gallery_mod/libraries/voting_control_js.php');
	?>
	<?php include('header_new.php'); ?>
    <?php
          echo "<!-- log: ";
          echo " view_password=".$photo->view_password." view_allowed=".$photo->view_allowed." erotic_p=".$photo->erotic_p." moderation_state=".$photo->moderation_state." photo user_id=".$photo->user_id." user_id=".$this->db_session->userdata('user_id');
          echo " -->";
          display_errors();
    ?>
		<div class="content">
          <?php if(!empty($photo))
            {
              if ((empty ($photo->view_password)&& ($photo->view_allowed == 0 ))&& ($photo->user_id!=$this->db_session->userdata('user_id')))
                   echo lang('only_for_author');
                else  {
            ?>

          <div class="righCol">

				<div class="userInfoListing">
					<a href="<?=base_url() . "profile/view/" . $user[0]->user_id; ?>" class="avatara"><img src="<?=$avatar;?>?<?= rand(10,9999); ?>" /></a>
					<ul>
						<li class="nick" title="<?= $user[0]->login; ?>"><a href="<?=base_url().'profile/view/'. $user[0]->user_id; ?>"><?= $user[0]->login; ?></a></li>

						<li class="rating" title="<?=lang('album_of_rate')?>"><?=lang('album_of_rate')?>: <b><?=$Balls?></b> </li>


						<li class="albums" title="<?=lang('albums')?>" >
                            <?php if ($all_albums_cnt <> 0) : ?>
                             <a  href="<?=base_url() . 'album/view_user_albums/' . $user[0]->user_id ?>"><?=lang('albums')?>:</a>
                            <?php else : ?>
                               <?=lang('albums')?>:
                            <?php endif; ?>
                            <b> <?=$all_albums_cnt?></b>
                        </li>
						<li class="photo" title="<?=lang('album_of_photos')?>">
                            <?php if ($cnt_photos <> 0) : ?>
                               <a href="<?=base_url() . 'profile/view/' . $user[0]->user_id ?>"><?=lang('album_of_photos')?>:</a>
                            <?php else : ?>
                                <?=lang('album_of_photos')?>:
                            <?php endif; ?>
                            <b><?=$cnt_photos?></b>
                        </li>
					</ul>
                    <?=$user[0]->about;?>

				</div>
				<div class="roundedBlock"><div class="tr"><div class="br"><div class="bl">
					<div class="getLinkForItem">
                        <?php if  (! Empty($photo->description)) :?>
                        <?= lang('album_desc'); ?>
                        <textarea id="descr_id" name="" cols="" rows="" class="t" onfocus="this.blur();"><?= $photo->description; ?></textarea>
                        <?php endif; ?>
                        <?=lang('photo_code_link')?>
						<input name="" type="text" class="i" value="<?=current_url();?>" onclick="javascript:selectThis(this);" />
						<?=lang('photo_code_empbed');?>
						<textarea id="code_link_id" name="" cols="" rows="" class="t" onclick="javascript:selectThis(this);"><?= $photo->code_link; ?></textarea>
                        <?php
                            $phsrc_lg = date('m', strtotime($photo->date_added)).'/'.$photo->photo_id.'/'.$photo->extension;
                        ?>
                        <?=$download_str; ?>
					</div>
				</div></div></div></div>
                <?php  if (is_object($cur_user_info) && (($cur_user_info->group_id == USER_JURY) or (in_array($cur_user_info->login, $this->config->item('jury_logins'))))) :?>
                <!-- a: Jury -->
               	<div class="roundedBlock"><div class="tr"><div class="br"><div class="bl">

                  <div class="getLinkForItem">
                         <?= lang('vote_jury'); ?><br><br>
                         <form name="jury_vote_form" id="jury_vote_form" method="post">
                         <input type="hidden" name="action" value="placing" />
                         <input type="hidden" name="user_id" id="cur_user_id" value="<?= $cur_user_info->user_id; ?>"/>
                         <input type="hidden" name="on_what_id" value="<?= $photo->photo_id; ?>"/>
                         <input name="jury_place"  type="radio" value=1> 1 место</>
                         <input name="jury_place"  type="radio" value=2> 2 место</>
                         <input name="jury_place"  type="radio" value=3> 3 место</>
                         <br>
                         <span id='place_mes'></span><br>
                         <input id="place_btn" style="width:100%;height:30px;" type="button" title="Поставить место" value="Голосовать" onclick="ajax_vote_place('jury_vote_form','place_mes')"> </>
                         </form>
                        </div>
                </div></div></div></div>
                <?php endif; ?>
                <!-- a:Jury -->
               	<?php
                  if (!empty($albums)) {
                       include(MODBASE.'gallery_mod/views/albums_sitebar.php');
                  }
                 ?>
				 <?php echo $ads_block['right_ads']; ?>
			</div>
			<div class="middCol">
				<div class="titleFilter">
					<strong><a href="#"><?= $user[0]->login; ?></a></strong> / <a href="#"><?= $photo->title; ?></a>

				</div>
				<div class="choosedPhotoItem">


                    <?= $photo_one_img_html; ?>

                   <?php  if (!(isset($password) && $password == false))
                   { ?>
                   <!-- <strong>Комментарии:</strong>&nbsp;     -->
                    <?php //if (! empty($paginate_args['total_rows']))
                          if ($user[0]->user_id)
                     { ?>
                     <?php if (! empty($paginate_args['total_rows']))  { ?>
                      <strong><?=lang('title_comments');?></strong><br><br>
                      <?php }?>
                      <?= $photo_comments_block; ?>
                      <?php }; ?>

 					<?php }// if (!(isset($password) && $password == false))  ?>

				</div>
			</div>
            <?php }} ?>
		</div>

<?php $this->fl->set_fl_code("fl_over_id","code_link_id");  ?>
<?php include('footer.php'); ?>
