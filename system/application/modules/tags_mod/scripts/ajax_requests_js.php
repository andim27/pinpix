<script language="JavaScript" type="text/javascript">

var ajax_actions_path = "<?php echo base_url(); ?>/tags_mod/tags_ctr/ajax_actions";

// to get tag id for update
function update_tag_state(tag_id, mod_state, filter)
{
if(confirm('Your really want to update this tag?'))
	{	
/*
	var Objselect = document.getElementById('state_'+tag_id);
	
 for (var i=0; i < Objselect.options.length; i++)
  {
      if (Objselect.options[i].selected)
      { 
      	// to get indexselect
      	var Item_select = i;
      	if (Item_select == 3) 
		{
			Item_select ='-1';
		}
      	break;
      }
  }
*/
$.ajax({
	type: "POST", 
	url: ajax_actions_path,
	dataType: "html",
	data: { 'action':'update_moderation_state', 
			'tag_id': tag_id, 
			'change_state': mod_state,
			'filter': filter
		  },
	success: function(data)
	{
		/*
		if(data == 1) 
		{ 
		$("#update_success").slideDown();
		$("#update_success").css({'width':'700px','padding':'3px 0px 3px 0px','margin':'5px 15px 0px 15px','position':'absolute','text-align':'center','background-color':'yellow','font-weight':'bolder'}).html("Your changes were saved");
     	setTimeout('$("#update_success").slideUp()',5000);		
		}
		*/
		$("#show_tags").html(data);
   }
});
	} else {
			return void(0);
			}
}
// filters tags
function tag_filter(filter_value)
{
$.ajax({
	type: "POST",
	url: ajax_actions_path,
	dataType: "html",
	data: {"action":"filter_tags", "filter": filter_value},
	success: function(data)
	{
		$("#show_tags").html(data);
	}
});
}
// delete tag
function delete_tag(tag_id, filter)
{
	if(confirm('Your really want to delete this tag?'))
	{
		$.ajax({
		type: "POST",
		url: ajax_actions_path,
		dataType: "html",
		data: {'action':'delete_tag', 'tag_id': tag_id, 'filter': filter},
		success: function(data)
		{
			/*
			if(data == 1)
			{
			var Objselect = document.getElementById('tags_filter');
			for (var i=0; i < Objselect.options.length; i++)
  			{
      			if (Objselect.options[i].selected)
      			{ 
      				var Item_select = i;
			      	break;
      			}
  			}
  			
  			// load update tags
			tag_filter(Objselect.options[Item_select].value);
			}
			*/
			$("#show_tags").html(data);		
		}
		});

	} else {
			return void(0);
			}
}
// search tag
function search_tag()
{
	var text = $("#text_search").val();
	// trim spaces
	text = text.replace(/(^\s+)|(\s+$)/g, '');
	// replace several spaces by one
	text = text.replace(/(\s+)/g, ' ');
	
	if(text != '') 
	{
	$.ajax({
		type: "POST",
		url: ajax_actions_path,
		dataType: "html",
		data: {"action":"search_tag", "text_search": text},
		success: function (data)
		{
			$("#show_tags").html(data);
		}
	});
	} else {
			alert("Please enter text for searching");
			return false;
			}
}

</script>