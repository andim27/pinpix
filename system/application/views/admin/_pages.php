<?php include('head.php'); ?>
<body class="pix">
	<div id="pix">
		<?php include('header.php'); ?>
		<?php display_errors(); ?>
		<div id="mainContent">
			<div id="main_side_right">
				<!-- start main_enter_box -->
				<?php if ( ! $user_id) echo $auth_block; ?>
				<!-- end main_enter_box -->

				<!-- Start Main_side_und_box-->
				<div style="display:none;" id="main_side_und_box">
					<!-- top -->
					<div class="box_top">
						<div class="bt_leftfix"></div>
						<div class="bt_rightfix"></div>
					</div>
					<!-- End top -->
					<!--Center-->
					<div id="ms_center">
						<div id="ms_cen_zag" align="left"><span class="m_zag_txt">&nbsp;</span></div>
						<div id="ms_cen_box">
							<?php // include('admin_menu.php'); ?>
						</div>
					</div>
					<!-- End center-->
					<!-- bot -->   
					<div class="box_bot">
						<div class="bb_leftfix"></div>
						<div class="bb_rightfix"></div>
					</div>      
					<!-- End bot --> 
				</div>   
				<!-- End Main_side_und_box-->
			</div>  
			<!-- End main_side_right -->
			   
			<!-- main_cont_body -->
			<div id="main_cont_body">
				<!-- Start center main-->
				<div id="c_box">
					<table width="120" border="1" cellspacing="0" class="pages-contents">
						<!--  Help start  -->
						<tr valign="top">
							<td rowspan="3"<?=(($page=="help")?' class="active"':'')?>>Help</td>
							<td><a class="<?=(($page=='help' && $lang=='ru')?'head_link':'link_na')?>" href="<?=url('admin_pages_url');?>/help/ru">Ru</a></td>
						</tr>
						<tr>
							<td><a class="<?=(($page=='help' && $lang=='en')?'head_link':'link_na')?>" href="<?=url('admin_pages_url');?>/help/en">En</a></td>
						</tr>
						<tr>
							<td><a class="<?=(($page=='help' && $lang=='kz')?'head_link':'link_na')?>" href="<?=url('admin_pages_url');?>/help/kz">Kz</a></td>
						</tr>
						<!--  Help end  -->
						<!--  faq start  -->
						<tr valign="top" style="background-color:#ccc;">
							<td rowspan="3">Faq</td>
							<td><a class="<?=(($page=='faq' && $lang=='ru')?'head_link':'link_na')?>" href="<?=url('admin_pages_url');?>/faq/ru">Ru</a></td>
						</tr>
						<tr style="background-color:#ccc;">
							<td><a class="<?=(($page=='faq' && $lang=='en')?'head_link':'link_na')?>" href="<?=url('admin_pages_url');?>/faq/en">En</a></td>
						</tr>
						<tr style="background-color:#ccc;">
							<td><a class="<?=(($page=='faq' && $lang=='kz')?'head_link':'link_na')?>" href="<?=url('admin_pages_url');?>/faq/kz">Kz</a></td>
						</tr>
						<!--  faq end  -->
						<!--  contacts start  -->
						<tr valign="top">
							<td rowspan="3">Contacts</td>
							<td><a class="<?=(($page=='contacts' && $lang=='ru')?'head_link':'link_na')?>" href="<?=url('admin_pages_url');?>/contacts/ru">Ru</a></td>
						</tr>
						<tr>
							<td><a class="<?=(($page=='contacts' && $lang=='en')?'head_link':'link_na')?>" href="<?=url('admin_pages_url');?>/contacts/en">En</a></td>
						</tr>
						<tr>
							<td><a class="<?=(($page=='contacts' && $lang=='kz')?'head_link':'link_na')?>" href="<?=url('admin_pages_url');?>/contacts/kz">Kz</a></td>
						</tr>
						<!--  contacts end  -->
						
						<tr valign="top" style="background-color:#ccc;">
							<td rowspan="3">Jury1</td>
							<td><a class="<?=(($page=='jury1' && $lang=='ru')?'head_link':'link_na')?>" href="<?=url('admin_pages_url');?>/jury1/ru">Ru</a></td>
						</tr>
						

						<tr style="background-color:#ccc;">
							<td><a class="<?=(($page=='jury1' && $lang=='en')?'head_link':'link_na')?>" href="<?=url('admin_pages_url');?>/jury1/en">En</a></td>
						</tr>
						<tr style="background-color:#ccc;">
							<td><a class="<?=(($page=='jury1' && $lang=='kz')?'head_link':'link_na')?>" href="<?=url('admin_pages_url');?>/jury1/kz">Kz</a></td>
						</tr>
						
								<tr valign="top">
							<td rowspan="3">agreement</td>
							<td><a class="<?=(($page=='agreement' && $lang=='ru')?'head_link':'link_na')?>" href="<?=url('admin_pages_url');?>/agreement/ru">Ru</a></td>
						</tr>
						

						<tr>
							<td><a class="<?=(($page=='agreement' && $lang=='en')?'head_link':'link_na')?>" href="<?=url('admin_pages_url');?>/agreement/en">En</a></td>
						</tr>
						<tr>
							<td><a class="<?=(($page=='agreement' && $lang=='kz')?'head_link':'link_na')?>" href="<?=url('admin_pages_url');?>/agreement/kz">Kz</a></td>
						</tr>
						
						<tr valign="top" style="background-color:#ccc;">
							<td rowspan="3">Jury3</td>
							<td><a class="<?=(($page=='jury3' && $lang=='ru')?'head_link':'link_na')?>" href="<?=url('admin_pages_url');?>/jury3/ru">Ru</a></td>
						</tr>
						

						<tr style="background-color:#ccc;">
							<td><a class="<?=(($page=='jury3' && $lang=='en')?'head_link':'link_na')?>" href="<?=url('admin_pages_url');?>/jury3/en">En</a></td>
						</tr>
						<tr style="background-color:#ccc;">
							<td><a class="<?=(($page=='jury3' && $lang=='kz')?'head_link':'link_na')?>" href="<?=url('admin_pages_url');?>/jury3/kz">Kz</a></td>
						</tr>
						
					</table>
					<div class="pages-editable">
						<form action="<?=url('admin_pages_url').'/'.$page.'/'.$lang;?>" method="post">
							<fieldset>
								<input type="hidden" name="save" value="save"/>
								Title: <input name="title" type="text" value="<?=$page_info['title']?>" style="width:500px;"/><br/>
								Content: <textarea id="content" name="content" rows="15" cols="" style="width:500px;"><?=$page_info['content']?></textarea><br/><br/>
								<input type="submit" value="<?=lang('btn_ok')?>" />
								<input type="reset" value="<?=lang('btn_cancel')?>" />
							</fieldset>
						</form>
					</div>
				</div>
				<!--End Center main-->
				<br /><br /><br /><br />
			</div>
			<!-- end #main_cont_body -->
		</div>
        <!--  force_p_newlines: "false",
        force_br_newlines: "true",
        language : "ru_CP1251?, -->
<script type="text/javascript" src="<?php echo base_url(); ?>static/js/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({
		// General options
		mode : "textareas",
		elements : "content",
		theme : "advanced",
		plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
		height : "530",
		width : "765",
        force_p_newlines: "true", 
		// Theme options
		theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
		theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,

		// Example content CSS (should be your site CSS)
		content_css : "css/content.css",

		// Drop lists for link/image/media/template dialogs
		template_external_list_url : "lists/template_list.js",
		external_link_list_url : "lists/link_list.js",
		external_image_list_url : "lists/image_list.js",
		media_external_list_url : "lists/media_list.js",

		// Replace values for the template plugin
		template_replace_values : {
			username : "Some User",
			staffid : "991234"
		}
	});
</script>
		<!-- end #mainContent -->
		<?php include('footer.php'); ?>
	</div>
	<!-- end #pix -->
</body>
</html>