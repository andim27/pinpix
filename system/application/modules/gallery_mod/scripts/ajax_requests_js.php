<script language="JavaScript" type="text/javascript">

var ajax_actions_path = "<?php echo base_url(); ?>gallery_mod/gallery_ctr/ajax_actions";

function create_new_album(baseurl) {

	if ($('#album_title1').val() == '') {
		alert(__LabelEmptyTitle);
		return false;
	}
	
	var erotic_flag = 0;
	if ($('#erotic_p1:checked').val() == 'on')
		erotic_flag = 1;
	
	$.ajax({
			type: "POST", url: ajax_actions_path, dataType: "html",
			data: { 'action':'albumedit', 
				'title' : $('#album_title1').val(),
				'short_description' : $('#short_description1').val(),				
				'category_id': $('#category_id1').val(),
				'view_allowed': $('#view_allowed1').val(),
				'password': $('#new_album_pass1').val(),				
				'erotic_p' : erotic_flag
			  },
			success: function(data)
			{
				//alert ('Album Created!');
				$('select[@name=upload_img_alb]').append('<option selected value="'+data+'">'+$('#album_title1').val()+'</option>');
		    },
		    error: function(data)
			{
			}
		});
	return true;
}

function delete_photo(photo_id)
{
	if (confirm ("<?=lang('js_label_DeleteConfirmation')?>")) 
	{	
		jQuery.post(ajax_actions_path, {
			action: "deletephoto",
			photo_id: photo_id
			},
		function(data){
				if (data == 0)
					alert ('error occured while deleting the photo')
				window.location.reload(true);
				},
				'html'
			);
		}
}

</script>