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
		$("#fileInput").fileUpload({
			'uploader': '<?=base_url()?>js/uploader.swf',
			'script': '/profile/imupload',
			'scriptAccess': 'sameDomain',
			'multi': false,
			'auto': false,
			'fileDesc': '<?=lang('user_photo_uploaded_types')?>',
			'fileExt': '<?=$allowed_types?>*.zip;*.rar;',
			'sizeLimit': '<?=$file_max_size?>',// Make sure POST_MAX_SIZE and UPLOAD_MAX_FILESIZE set up
			'width': 68,
			'height': 16,
			'hideButton': true,
			'wmode': 'transparent',
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
			onProgress: function(event, queueID, fileObj, data){
				$('#upload_progress').css("width", data.percentage+"%");
			},
			onComplete: function(event, queueID, fileObj, response, data){
				var result = window["eval"]("(" + response + ")");
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
						//alert ( "<?php echo $user->login;?>, спасибо, Ваша работа успешно загружена!");
						window.location = "<?php echo base_url().'competition/100001'?>";
					}
				)
			}
		});
		AJAXImagesUploadCancel();
		$('form').each(function(){
			this.reset();
		});
	});