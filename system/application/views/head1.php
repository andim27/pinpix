<?php if ( (uri_string()!=base_url()) && ! url_access(uri_string()) ) redirect(base_url(), 'location'); ?>
<?php extract(get_app_vars()); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $site_title; ?></title>
<link href="<?php echo static_url(); ?>css/style.css" rel="stylesheet" type="text/css" />
<link href="<?php echo static_url(); ?>css/main1.css" rel="stylesheet" type="text/css" />
<link href="<?php echo static_url(); ?>css/uploadify.css" rel="stylesheet" type="text/css"/>

		<script type="text/javascript" src="<?php echo static_url(); ?>js/menu.js"></script>
		<script type="text/javascript" src="<?php echo static_url(); ?>js/jquery-1.2.6.js"></script>
		<script type="text/javascript" src="<?php echo static_url(); ?>js/jquery.form.js"></script>
		<script type="text/javascript" src="<?php echo static_url(); ?>js/common.js"></script>
		<script type="text/javascript" src="<?php echo static_url(); ?>js/swfobject.js"></script>
		<script type="text/javascript" src="<?php echo static_url(); ?>js/nifty.js"></script>
		<script type="text/javascript" src="<?php echo static_url(); ?>js/browserCSSdetector.js"></script>
        <script type="text/javascript" src="<?php echo static_url(); ?>js/jquery.uploadify.v2.1.0.min.js"></script>

	
<!--[if IE]>
<link href="<?php echo static_url(); ?>css/ie_fix.css" rel="stylesheet" type="text/css" />
<![endif]-->
<!--[if IE 6]>
<link href="<?php echo static_url(); ?>css/ie_fix6.css" rel="stylesheet" type="text/css" />
<![endif]-->
</head>
