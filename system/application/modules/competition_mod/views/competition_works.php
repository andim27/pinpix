<style type="text/css">
.filter_selected{
	font-weight:bold;
}
</style>
<div class="middCol">
	<?php if($competition) { ?>
	<div class="titleFilter">
		<a href="<?=base_url()?>competition/"><strong><?=lang('competitions')?></strong></a> / <a href="<?=base_url()?>competition/view/<?=$competition->competition_id?>"><?=$competition->title?></a>
	</div>
	<div class="title" style="font-size:12px;">
		<?php if (!(isset ($curent_comp_opened) && $curent_comp_opened == true )) { ?>
		<a <?php if($filter == "winners") echo 'class="filter_selected"'; ?> href="<?=base_url()?>competition/details/<?=$competition->competition_id?>/winners"><?=lang('competition_winners')?></a> / 
		<?php } ?>
		<a <?php if($filter == "all") echo 'class="filter_selected"'; ?> href="<?=base_url()?>competition/details/<?=$competition->competition_id?>/all"><?=lang('competition_competitors')?></a>
	</div>
	<?php } ?>
	<div class="picsHolder">
	<?php
	if($competition_works) {
		foreach ($competition_works as $photo) {
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
                <i class="views" title="<?= lang("user_views"); ?>"> <?= empty ($photo->see_cnt) ? 0 : $photo->see_cnt?></i>
                <i class="pin" title="Рейтинг"> <?= empty ($photo->balls) ? 0 : $photo->balls?></i>
				<i class="comment" title="<?= lang("user_comments"); ?>"> <?= empty ($photo->comcnt)? 0 : $photo->comcnt?> </i>
			</div>		
			<a href="<?php echo base_url(). 'profile/view/' . $photo->user_id ?>" class="author">  <?=$photo->login?> </a>
			<p><?=((strlen($photo->title) > 100)? substr($photo->title, 0, 97).'...': $photo->title);?>  </p>		
		</div>
	<?php } ?>
	</div>
	<?php 
		if (!empty($paginate_args)) echo paginate($paginate_args);
	} else {
		echo lang('comp_nophotos');
	}
	?>
</div>