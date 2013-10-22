<div id="menu">
	
	<p>
	<?php if($user_id && ($user_group=='admins' || $user_group=='moderators') ) : ?>
		<a href="<?php echo url('admin_users_url'); ?>">Manage Users</a><br /><br />
		<a href="<?php echo url('admin_catalog_url'); ?>">Manage Catalog</a><br /><br />
		<a href="<?php echo url('admin_tags_url'); ?>">Manage Tags</a><br /><br />
		<a href="<?php echo url('admin_comments_url'); ?>">Manage Comments</a><br /><br />
		<a href="<?php echo url('admin_photos_url'); ?>">Manage Photos</a><br /><br />
		<a href="<?php echo url('admin_albums_url'); ?>">Manage Albums</a><br /><br />
		<a href="<?php echo url('admin_pages_url'); ?>">Manage Pages</a><br /><br />
	<?php endif; ?>
	</p>
	
	<!--
	<div id="menu_header"><p>Разделы</p></div>
	<?php //echo modules::run('catalog_mod/catalog_ctr/view_tree'); ?>
	-->
</div>
	
	