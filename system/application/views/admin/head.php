<?php if ( (uri_string()!=base_url()) && ! url_access(uri_string()) ) redirect(base_url(), 'location'); ?>
<?php extract(get_app_vars()); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $site_title; ?></title>
<link href="<?php echo static_url(); ?>css/style.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="<?php echo static_url(); ?>js/jquery-1.3.2.min.js"></script>

<script type="text/javascript" src="<?php echo static_url(); ?>js/swfobject.js"></script>
<script type="text/javascript" src="<?php echo static_url(); ?>js/menu.js"></script>
<script type="text/javascript" src="<?php echo static_url(); ?>js/jquery.form.js"></script>
<script type="text/javascript" src="<?php echo static_url(); ?>js/common.js"></script>

<script type="text/javascript" src="<?php echo static_url(); ?>js/nifty.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo static_url () ?>css/ui.all.css">
<script type="text/javascript" src="<?php echo static_url () ?>js/jquery-ui-1.6.custom.min.js"></script>
 <?php if (! empty($page))  :?>
  <script type="text/javascript" src="<?php echo static_url(); ?>js/jquery.Jcrop.js"></script>
  <link type="text/css" rel="stylesheet" href="<?php echo static_url(); ?>css/jquery.Jcrop.css" />
 <?php endif; ?>
<!--[if IE]>
<style type="text/css"> 
.pix #p_h_s_butt {
    position:absolute; 
        width:17px; 
        height:17px; 
        left:223px;
        top:25px;
}

.pix #flash_ins_box {
    position:relative; 
        width:595px; 
        height:80px; 
        background:#575F65; 
        left:155px; 
        top:16px;
}

.pix #menu_box_left { 
    float:left;
        padding:6px 10px 0 30px; 
        height:26px;
        background:#8BC53F;
}

.pix #sb_podpis {
    position:relative;
        width:77px; 
        height:20px; 
        border-right:1px solid #747A7F; 
        padding:9px 13px 0 0px;
}
.pix #st_box_right {    
        float: right;   
        position: relative;     
        top: 6px;
}

.pix #place_gr_user {
    position:relative; 
        float:left; 
        top:5px;
}
.pix #place_gr_txt {
    position:relative; 
        float:left;
        top:5px; 
        margin-left:5px;
}


</style>
<![endif]-->
<!--[if IE 6]>
<style type="text/css"> 

a.mainlevel, a.mainlevel_active, a.mainlevel_current, span.mainlevel,
a.mainlevel:link, a.mainlevel_active:link, a.mainlevel_current:link,
a.mainlevel:visited, a.mainlevel_active:visited, a.mainlevel_current:visited,
a.mainlevel:hover, a.mainlevel_active:hover, a.mainlevel_current:hover,
a.sublevel, a.sublevel_active, a.sublevel_current, span.sublevel,
a.sublevel:link, a.sublevel_active:link, a.sublevel_current:link,
a.sublevel:visited, a.sublevel_active:visited, a.sublevel_current:visited,
a.sublevel:hover, a.sublevel_active:hover, a.sublevel_current:hover {
        font-family: Verdana, Geneva, sans-serif;
    font-size:10px;
        text-decoration: none;
        display: block;
        border-right:1px solid #747A7F;
        padding: 0 10px 0 10px;         /* top, right, bottom, left */
        height: 28px;
        width:auto;
        line-height: 2.9em;
}


</style>
<![endif]-->
</head>
