<script language="JavaScript" type="text/javascript">
var ajax_actions_path = "<?php echo base_url(); ?>/comments_mod/comments_ctr/ajax_actions";

function comment_update_state(id, mod_state)
{
	if(confirm('Your really want to update state?'))
	{
		$.ajax({
			type: "POST", 
			url: ajax_actions_path,
			dataType: "html",
			data: { 'action':'update_moderation_state', 
				'id': id, 
				'change_state': mod_state
			},
			success: function(data)
			{
				$("#show_list").html(data);
			}
		});
	} else {
		return void(0);
	}
}

function comment_filter()
{
	$.ajax({
		type: "POST",
		url: ajax_actions_path,
		dataType: "html",
		data: {"action":"filter", 
				"filters[body]": document.getElementById("comment_body").value, 
				"filters[comment_date]": document.getElementById("comment_date").value,
				"filters[login]": document.getElementById("comment_author").value,
				"filters[moderation_state]": document.getElementById("comment_mod_state").value
			},
		success: function(data)
		{
			$("#show_list").html(data);
		}
	});
}

function comment_filter_reset()
{
	document.getElementById("comment_body").value = '';
	document.getElementById("comment_date").value = '';
	document.getElementById("comment_author").value = '';
	document.getElementById("comment_mod_state").value = '.';
	comment_filter();
}

function comment_delete(lft, rgt, commented_object_type)
{
	if(confirm('Your really want to delete this comment?'))
	{
		$.ajax({
		type: "POST",
		url: ajax_actions_path,
		dataType: "html",
		data: {'action':'delete', 'lft': lft, 'rgt': rgt, 'commented_object_type': commented_object_type},
		success: function(data)
		{
			$("#show_list").html(data);		
		}
		});

	} else {
			return void(0);
			}
}

</script>