<?php
if (isset ($my))
	$user_link = base_url() . "profile/edit/" . $user->user_id;
else
	$user_link = base_url() . "profile/view/" . $user->user_id;

?>

<div class="userInfoListing">
					<a href="<?=$user_link?>" class="avatara"><img src="<?=$avatar;?>?<?= rand(10,9999); ?>" /></a>
					<ul>
						<li class="nick"><a href="<?=$user_link?>"><?= $user->login; ?></a></li>
						<li class="rating"><?=lang('album_of_rate')?>: <b><?=$Balls?></b> </li>
						<li class="albums">
                           <?php if ($all_albums_cnt <> 0) : ?>
                             <a  href="<?=base_url() . 'album/view_user_albums/' . $user->user_id ?>"><?=lang('albums')?>:</a>
                           <?php else : ?>
                             <?=lang('albums')?>:
                            <?php endif; ?>
                            <b><?=$all_albums_cnt?></b>
                        </li>
						<li class="photo">
                           <?php if ($cnt_photos <> 0) : ?>
                             <a href="<?=base_url() . 'profile/view/' . $user->user_id ?>"><?=lang('album_of_photos')?>:</a>
                            <?php else : ?>
                             <?=lang('album_of_photos')?>:
                            <?php endif; ?>
                           <b><?=$cnt_photos?></b>
                        </li>
					</ul>
                    <?=$user->about;?>
</div>


