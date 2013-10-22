<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-11579441-2");
pageTracker._trackPageview();
} catch(err) {}</script>
<?php 
$ci = &get_instance();
$admin_email = $ci->config->item('admin_email'); ?>
<div class="footer" id = "footer">
 <?php echo lang('user_support') . $admin_email?>
</div>
</body>
</html><!-- end #footer -->
<!-- end footer -->


