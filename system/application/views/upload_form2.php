<?php modules::load_file('ajax_requests_js.php',MODBASE.'gallery_mod/scripts/'); ?>
<script type="text/javascript">
	var __LabelFileEmpty = "<?=lang('js_label_FileEmpty')?>";
	var __LabelPasswordEmpty = "<?=lang('js_label_PasswordEmpty')?>";
	var __LabelConfirmationEmpty = "<?=lang('js_label_ConfirmationEmpty')?>";
	var __LabelPwsConfDifferent = "<?=lang('js_label_PwsConfDifferent')?>";
	var __LabelImgSubmitFailed = "<?=lang('js_label_ImgSubmitFailed')?>";
	var __LabelEmptyAlbumTitle = "<?=lang('js_label_EmptyAlbumTitle')?>";
	var __LabelEmptyTitle = "<?=lang('js_label_EmptyTitle')?>";
	var __LabelNotValidTitle = "<?=lang('js_label_NotValidTitle')?>";
	var __LabelDeleteConfirmation = "<?=lang('js_label_DeleteConfirmation')?>";

	var __queueID = false;
	var __filename = false;
	$(function(){
		$("#fileInput").uploadify({
			'uploader'       : '<?=base_url()?>static/js/uploadify_v.swf',
			'script'         : '<?=base_url()?>profile/imupload',
			'scriptAccess'   : 'always',
			'queueID'        : 'fileQueue',
			'auto'           :  false,
			'multi'          :  false,
            'folder'    : '<?=photo_location()?>photos/temp/',
            'wmode'     : 'transparent',
            'fileDesc'  : 'jpg;gif;png',
            'buttonImg': '<?=static_url()?>images/butt_select_<?=$this->db_session->userdata('user_lang')?>.gif',
			'fileDesc'       : '<?=lang('user_photo_uploaded_types')?>',
			'fileExt'        : '<?=$allowed_types?>*.zip;*.rar;',
			'sizeLimit'      : '<?=$file_max_size?>',// Make sure POST_MAX_SIZE and UPLOAD_MAX_FILESIZE set up
			'width'			 : 68,
			'height'		 : 22,
			onSelect: function(event, queueID, fileObj){
				var byteSize = Math.round(fileObj.size / 1024 * 100) * .01;
				var suffix = 'KB';
				if (byteSize > 1000) {
					byteSize = Math.round(byteSize *.001 * 100) * .01;
					suffix = 'MB';
				}
				var sizeParts = byteSize.toString().split('.');
				if (sizeParts.length > 1) {
					byteSize = sizeParts[0] + '.' + sizeParts[1].substr(0,2);
				} else {
					byteSize = sizeParts[0];
				}
				if (fileObj.name.length > 20) {
					fileName = fileObj.name.substr(0,20) + '...';
				} else {
					fileName = fileObj.name;
				}
				$('#upload_progress').html(fileName + "&nbsp;(" + byteSize + suffix + ")");
				__queueID = queueID;
				return false;
			},
			onError : function (a, b, c, d) {
                if (d.status == 404)
                   alert('Could not find upload script. Use a path relative to: '+'<?php echo getcwd() ?>');
                else if (d.type === "HTTP")
                   alert('error '+d.type+": "+d.status);
                else if (d.type ==="File Size")
                   alert(c.name+': Размер файла превышает допустимый предел '+Math.round(<?=$file_max_size?>/1048576)+'MB');
                else
                    //alert('Ошибка канала связи ('+d.type+"): попробуйте еще раз !");
                    alert('Ошибка при передачи файла, попробуйте еще раз !');

            	window.location = "<?php echo base_url().'profile/view/'.$user->user_id ;?>";
                
               },
			onProgress: function(event, queueID, fileObj, data){      
            	document.getElementById	('warn').style.display = 'block';
				$('#upload_progress').css("width", data.percentage+"%");
				$('#loader').show();
			},
			onComplete: function(event, queueID, fileObj, response, data){
				var result = window["eval"]("(" + response + ")");
                $("#warn").show().html("<?php echo lang ('upload_in')?>");
                $.post(
					"<?=base_url()?>profile/imadd",
					{
						p_filename: unescape(result.name),
						p_tmp_name: unescape(result.tmp_name),
						p_filesize: fileObj.size,
						p_filetype: unescape(result.type),
						p_title: title,
						p_descr: descr,
						P_alloved: alloved,
						p_erotic: erotic,
						p_cat_id: cat,
						p_album_id: album,
						p_pwd: pwd,
						u_id : <?php echo $this->db_session->userdata('user_id')?>
					},
					function(data){
						var result = window["eval"]("(" + data + ")");
                        $('#upload_progress').css("width","1px");
                        if (result.mes !=undefined && result.mes != "") {
                           $("#warn").show().html(result.mes);
                        }
                        if(result.err != undefined && result.err != "") alert(result.err);
						$('#loader').hide();
						window.location = "<?php echo base_url().'profile/view/'.$user->user_id.'/sort/2/desc/page/1';?>";
					}
				)
			}
		});
		AJAXImagesUploadCancel();
		$('form').each(function(){
			this.reset();
		});
	});
function dump(obj, step) {
	if (typeof step == 'undefined') {
		step = -1;
	}
	step++;
	var pad = new Array(2*step).join('   ');
	var str = typeof(obj)+":\n";
	for(var p in obj){
		if (typeof obj[p] == 'object') {
			str += pad+'   ['+p+'] = '+dump(obj[p], step);
		} else {
			str += pad+'   ['+p+'] = '+obj[p]+"\n";
		}
	}
	return str;
} 	
</script>

<script type="text/javascript" src="<?php echo static_url(); ?>js/highslide/highslide-with-html.js"></script>
<script type="text/javascript" src="<?php echo static_url(); ?>js/highslide/highslide.js" charset="utf-8"></script>

<script type="text/javascript">
	hs.graphicsDir = '<?php echo static_url(); ?>js/highslide/graphics/';
	hs.showCredits = false;
	hs.wrapperClassName = 'draggable-header';
</script>

<!--<form method="post" action="#" onSubmit="return AJAXImagesUploadSubmit();" onReset="return AJAXImagesUploadCancel();" enctype="multipart/form-data">-->
<form method="post" action="<?=base_url()?>profile/upload" onReset="return AJAXImagesUploadCancel();" enctype="multipart/form-data">

 
<div class="roundedBlock"><div class="tr"><div class="br"><div class="bl">  
   <div class="photoUpload">
		<h2><?=lang('user_photo_upload')?></h2>
		<div style="color:#FF0000;font-weight:bold" > 
			<?php if($this->db_session->flashdata('error')) echo $this->db_session->flashdata('error'); ?>
		</div>
		<div style="color:#86BC3F;font-weight:bold" > 
			<?php if($this->db_session->flashdata('message')) echo $this->db_session->flashdata('message'); ?>
		</div>
		<div id="warn" style="display:none ;color:#86BC3F;font-weight:bold" > 
		<?php 
			echo lang ('upload_warn');
		?>
		</div>
				<div class="uploadNew">
						<div><?=lang('user_photo_file')?></div>
<!--						<div class="ent_area_zp progress"><div id="upload_progress"><div id="fileQueue"></div></div></div>															-->
						
						<input type="file" id="fileupload" name="userfile" size="32" />
				</div>

				<div class="uploadNew">			
				<span> <?=lang('user_photo_title')?>
	  			<input id="upload_img_title" class="i" type="text" name="photo_title" /> </span>
	  			</div>	
	  			
	  			<div class="uploadNew">
	  			<span> <?php echo lang('album_desc');?> 
	 			<textarea id="upload_img_descr" rows="" cols="" name="photo_desc" class="i" ></textarea>
	 			</span>
	 			</div>		
	 				  		    
	  		    <div class="placeSelector">
				<span><?php echo lang('album_rule');?>
				<select id="upload_img_allowed"  class="s" name="view_allowed"  onChange="propAlovedChangeUpload(this);">
				<?php if(!empty($view_allowed)) { foreach($view_allowed as $idx => $restrict):?>
			  	<option value="<?=$idx?>"><?=$restrict?></option>
				<?php endforeach; } ?>
	  			</select></span>
	  			</div>
	  							  			 		
	  			<div  id="hidden_pwd_upload"  style="display:none;">
		 		  <span><?php echo lang('New_password');?> 
				  <input id="upload_img_pwd" class="i" type="text" name="photo_password"  />
		   		  </span>
		   		  <span><?php echo lang('New_password_confirmation');?> 
				  <input id="upload_img_pwd_cfm" class="i" type="text" name="photo_password_confirm" />						 
		   		  </span>
		   		</div>
	  
				<span><?=lang('user_photo_erotic')?>
					<input  id="upload_img_erotic" name="erotic" class="ch" type="checkbox" /></span>
				<br />
				
				<div class="placeSelector">
	 			<span><?php echo lang('album_cat');?> 
				<select id="upload_img_cat" name="cat"  class="s">
				<?php if(!empty($categories)) { foreach($categories as $category):?>
	  			<option value="<?php echo $category->id;?>">
	  			<?php echo lang_translate($category->name,"kz");?>
				</option>
				<?php endforeach; } ?>
	 			</select></span>
	  		    </div>

	  		    <div class="sprite">
					<div class="profil_txt_height">
						<div class="im_znak">
							<img alt="" src="<?php echo static_url(); ?>images/m_box_plus.gif" onclick="return hs.htmlExpand(this, { contentId: 'highslide-html' } )"/>
						</div>
						<a class="im_txt_profil" onclick="return hs.htmlExpand(this, { contentId: 'highslide-html' } )"><?=lang('album_new')?></a>
						<div class="im_return"></div><br />
					</div>
				</div>
		
	  		    <div class="placeSelector">
	 			<span><?=lang('user_photo_album')?>
				<select id="upload_img_alb" name = "upload_img_alb" class="s">
				<?php if(!empty($albums)) { foreach($albums as $album):?>
	  			<option value="<?php echo $album->album_id;?>"><?php echo $album->title?></option>
				<?php endforeach; } ?>
	 			</select></span>
	  		    </div>
						  
				<input name="" type="reset" value="<?= lang('cancel'); ?>" class="button" />
				<input name=""  type="submit" value="<?= lang('upload'); ?>" class="button" onclick="javascript:$('#loader').show();$('#warn').show();" />
				<img id="loader" alt="loading..." border="0" src="<?php echo base_url() ?>static/images/add-note-loader.gif" style="display:none;" />
				<br /><br />
				<em><?=lang('user_photo_bulk_upload')?></em>
				 
   </div>
   
   <!-- End center-->
  
</div></div></div></div>
   
<!-- End zagr_photo_box--></form>

<!-- highslide div for new album -->	
<div class="highslide-html-content" id="highslide-html">
	<div class="highslide-header">
		<h2><?=lang('album_new')?></h2>
		<img border="0" onclick="return hs.close(this)" src="<?php echo static_url() ?>images/ic_close.gif" title="Close" style="position: absolute; top: 3px; right: 3px;">
	</div>
	<div class="highslide-body">
		<div class="userAlbumsList"><div class="list"><div class="item">
			<div class="roundedBlock" style = "background:none; width: 250px; height:330px; overflow:hidden"><div><div><div>
		
				<form action="<?php echo base_url().'profile/albumedt1/'.$this->db_session->userdata('user_id');	?>">
					<input type="hidden" name="album_id" value="0" />

					<div class="edit">
					<div class="photoOptions">	
						<br />			
						<span> <?php echo lang('album_title');?> 
						<input class="i" type="text" name="title" id="album_title1"  /> </span>
						
						<span> <?=lang('user_photo_description')?>
						<textarea class="i" id="short_description1" name="short_description1" cols="" rows=""></textarea></span>
						  				  
						<span><?php echo lang('album_cat');?> 
						<select id="category_id1" name="category_id1" class="s">
						<?php foreach($categories as $category):?>
			  			<option value="<?php echo $category->id;?>">
			  			<?php echo $category->name?>
						</option>
						<?php endforeach;?>
			 			</select></span>
			  		
						<span><?php echo lang('album_rule');?>
						<select id="view_allowed1"  class="s" name="view_allowed1" onchange="ShowAlbumPwdById(this);">
						<?php foreach($view_allowed as $idx => $view):?>
					  	<option value="<?=$idx?>">
					  	<?=$view?>
						</option>
						<?php endforeach;?>
			  			</select></span>
			  			 		
			  			<div id="album_pwd1" style="display:none;">
				 		  <span><?php echo lang('New_password');?> 
						  <input id="new_album_pass1" class="i" type="text" name="password" />
				   		  </span>
				   		  <span><?php echo lang('New_password_confirmation');?> 
						  <input id="new_album_confirm1" class="s" type="text" name="password_confirm" />						 
				   		  </span>
				   		</div>
			  
						<span><?php echo lang('album_erotic');?>
							<input id="erotic_p1" name="erotic_p1" class="ch" type="checkbox" /></span>

						<span><input type="button" value="Ok" class="button" onclick = 'if( create_new_album("<?php echo base_url()?>")) return hs.close(this)'/> 
						<input type="button" value="Отмена" class="button" onclick="return hs.close(this)" />
						<!--  <input  type="reset"  value="Отмена" class="button" />  -->
						</span>
					</div>
		<div class="topBg"><!-- --></div>
		<div class="bottBg"><!-- --></div>
	</div>
<!-- End opis_field_box --></form>
</div></div></div>
</div></div></div></div>
</div>  
</div>
<!-- end highslide div for new album -->




