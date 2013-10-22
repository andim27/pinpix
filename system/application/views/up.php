<?php if ( (uri_string()!=base_url()) && ! url_access(uri_string()) ) redirect(base_url(), 'location'); ?>
<?php extract(get_app_vars()); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php echo $site_title; ?></title>
		<link rel="shortcut icon" href="<?php echo static_url(); ?>images/favicon.ico" />
		<link href="<?php echo static_url(); ?>css/main.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo static_url(); ?>css/uploadify.css" rel="stylesheet" type="text/css"/>
		
		<script type="text/javascript" src="<?php echo static_url(); ?>js/menu.js"></script>

		<script type="text/javascript" src="<?php echo static_url(); ?>js/jquery.form.js"></script>
		<script type="text/javascript" src="<?php echo static_url(); ?>js/common.js"></script>
		<script type="text/javascript" src="<?php echo static_url(); ?>js/swfobject.js"></script>
		<script type="text/javascript" src="<?php echo static_url(); ?>js/nifty.js"></script>
		<script type="text/javascript" src="<?php echo static_url(); ?>js/browserCSSdetector.js"></script>

<script type="text/javascript" src="<?=static_url()?>js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="<?=static_url()?>js/jquery.uploadify.v2.1.0.min.js"></script>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Uploadify Example Script</title>



<script type="text/javascript">

var uplodify_path     = "<?php echo static_url(); ?>js/";





$(document).ready(function() {
	$("#uploadify").uploadify({
	    'uploader'  :uplodify_path+'uploadify.swf',
		'script'         : '<?=base_url()?>profile/imupload',
	    'scriptAccess'   : 'always',
		'cancelImg'      : 'cancel.png',
		'queueID'        : 'fileQueue',
		'auto'           : false,
		'multi'          : true,
	    'width'     :70,
	    'height'    :20,
	    'rollover'  :false,
	    'fileDesc'  : 'jpg;gif;png',
	    'fileExt'   : '*.jpg;*.png;*.gif'
		
	});
});

</script>
</head>

<body>


			
			<div id="fileQueue"></div>
<input type="file" name="uploadify" id="uploadify" />
<p><a href="javascript:jQuery('#uploadify').uploadifyClearQueue()">Cancel All Uploads</a></p>
<p><a href="javascript:jQuery('#uploadify').uploadifyUpload()">do Uploads</a></p>


</body>
</html>
