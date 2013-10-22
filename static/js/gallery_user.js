var base_url = "http://" + window.location.hostname + "/";
var ajax_gallery_path = base_url + "gallery/ajax_actions";

function check_jury(){
	$.ajax({
			type: "POST", url: ajax_gallery_path, dataType: "html",
			data: { 'action':'check_jury'},			
			beforeSend: function()
			{
				$("#loader").show();
			},
			success: function(data)
			{
				$("#loader").hide();
                alert(data);
				if(data == 1) $('#main_page').show();
				else alert('У вас нет прав для просмотра галереи');//window.location = base_url;
		    },
		    error: function(data)
			{
				$("#loader").hide();
				alert('error');
//				window.location = base_url;
			}
		});
	return true;
}