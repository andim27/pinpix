<?php include('head_new.php'); ?>

<body class="pix">
	<?php include('header_new.php'); ?>
	<?php display_errors(); ?>
		<div class="content">
              <div class="righCol">
				<?php include('user_info_block.php'); ?>		
				 <?php echo $ads_block['right_ads']; ?>		
			</div>
			
			<div class="middCol">
			<div class="titleFilter">
				<strong><a href="<?=base_url()?>profile/view/<?=$user->user_id?>"><?= $user->login?></a></strong> / <a href="#"><?=lang('all_albums')?></a>
			</div>
			<?php echo $all_user_albums ?>
			</div>		
		</div>
		
<?php include('footer.php'); ?>
