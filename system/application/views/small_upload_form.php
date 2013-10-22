<script type="text/javascript" src="<?=static_url()?>js/jquery.uploadify.js"></script>
<link rel="stylesheet" type="text/css" href="<?=static_url()?>css/uploadify.css"/>

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
	var __LabelToManyPhotos  = "<?=lang('lot_of_photos')?>";

	var __queueID = false;
	var __filename = false;
	
	$(function(){
		$("#fileInput").fileUpload({
			'uploader': '<?=static_url()?>js/uploader.swf',
			'script': '/profile/imupload',
			'scriptAccess': 'sameDomain',
			'multi': false,
			'buttonImg': '<?=static_url()?>images/butt_select_<?=$this->db_session->userdata('user_lang')?>.gif',
			'auto': false,
			'fileDesc': '<?=lang('user_photo_uploaded_types')?>',
			'fileExt': '<?=$allowed_types?>*.zip;*.rar;',
			'sizeLimit': '<?=$file_max_size?>',// Make sure POST_MAX_SIZE and UPLOAD_MAX_FILESIZE set up
			//'width': 68,
			//'height': 16,
			'buttonText':"zasdfsd",
			'hideButton': true,
			'wmode': 'transparent',
			onSelect: function(event, queueID, fileObj){
				//alert (flag);
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
			onProgress: function(event, queueID, fileObj, data){
				$('#upload_progress').css("width", data.percentage+"%");
			},
			onAllComplete: function(event, data){			
	            $('#upload_progress').html('loading...'); 
				},
			onComplete: function(event, queueID, fileObj, response, data){
				var result = window["eval"]("(" + response + ")");
				if (result.error)
					alert (result.error); 
				else
				{	
					$.post(
						"<?=base_url()?>/profile/imadd",
						{							
							p_filename: unescape(result.name),
							p_tmp_name: unescape(result.tmp_name),
							p_filesize: result.size,
							p_filetype: result.type,
							p_title: title,
							p_descr: descr,
							P_alloved: alloved,
							p_erotic: erotic,
							p_cat_id: cat,
							p_album_id: album,
							p_pwd: pwd,
							p_comp: 1
						},
						function(data){
							var result = window["eval"]("(" + data + ")");
							
							if(result.err == "") {							
								$('#ent_txt').css("display", "block");
								$('#zp_cen_zag_new').css("display", "none");
								$('#zp_cen_zag_new1').css("display", "block");
								$('#upload_progress').css("width","0");
								$('#upload_progress').html("");
								$('#upload_img_descr').html("");
								document.getElementById('upload_img_title').value = "";
								document.getElementById('upload_img_descr').value = "";
								
							} else {
								$('#error').show();
							}
						}
					)
				}
			}
		});
		AJAXImagesUploadCancel();
		$('form').each(function(){
			this.reset();
		} );
	} );
</script>
	
	<!-- Start zagr_photo_box-->
   <div id="zagr_photo_box_new"><form method="post" action="#" onSubmit="return AJAXImagesUploadSubmit_new();" onReset="return AJAXImagesUploadCancel();" enctype="multipart/form-data">
   
	<div class="roundedBlock"><div class="tr"><div class="br"><div class="bl">
   
   <!--Center-->
   <div id="zp_center_new">
   <div id="zp_cen_zag_new" align="left" style="display:block">

   <div id="error" style = "margin-left: 30px; display:none">На конкурс можно загружать до 5 фотографий</div>
   <h3 style="color:#8BC53F; font-weight:bold; " ><?=lang('user_photo_upload')?></h3></div>
   <div id="zp_central_part_new">
		 <div>
		<div class="ent_txt" id="ent_txt" style = "margin-left: 30px; display:none"><?php echo lang ('upload_succ')?></div>
		</div>
		
		  <div id = "zp_cen_zag_new1" style = "margin: 20px 0 0 15px; display:none"> 
		  <h3 style="color:#8BC53F; font-weight:bold; " ><?=lang('user_photo_upload_again')?></h3>
		  </div>
		 
		<div class="zp_central_part_left">
   		<div class="place_ser_otsup_txt_zp">
		  <span class="ent_txt"><?=lang('user_photo_file')?></span>
		</div>
		<div class="ent_area_zp progress">
			<div id="upload_progress"></div>
		</div>
		 <div class="place_otsup_vib">
		 	<input type="file" name="fileInput" id="fileInput"/>
		  <input class="butt_select" type="button" value="" />
		 </div>
		<div class="place_ser_otsup_txt_zp">
		  <span class="ent_txt"><span class="ent_txt" ><?=lang('user_photo_title')?></span></span>
		</div>
		  <input id="upload_img_title" class="ent_area_zp_new" type="text" value=""/>
		</div>
		<div class="zp_central_part_right">
			<div class="place_ser_otsup_txt_zp">
				<span class="ent_txt"><?= lang ('user_photo_description')?></span>
			</div>
			<textarea id="upload_img_descr" class="zp_cp_txtarea" rows="" cols="" name="photo_desc" ><?php echo "" ?></textarea>
		</div>
		<div id="place_ser_otsup_2">
		<input style="margin-left:15px;" name="" type="button" class="but_69" value="<?=lang('cancel')?>" />							
		<input name="" type="submit" class="but_69" value="Ок" />
		  <!--<input class="canc_butt_sp" type="reset" value=""  style = "cursor: default;"/>
		  <input class="ent_butt" type="submit" value="" style = "cursor: default;"/>-->
		</div>
		<div class="place_txt_zp_in_the_box">
		<span class="ent_txt"><?=lang('user_photo_5_upload')?></span>
		</div>
   </div>
   <!-- End center--></div>
  
</div></div></div></div>
  
<!-- End zagr_photo_box--></form></div>

