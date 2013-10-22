<script type="text/javascript">
var ajax_cp_path = "<?php echo base_url(); ?>/comments_mod/comments_ctr/ajax_actions";

function comment_update_state(id, mod_state)
{
	$.ajax({
		type: "POST", 
		url: ajax_cp_path,
		dataType: "html",
		data: { 'action':'update_moderation_state', 
				'id': id, 
				'change_state': mod_state
			  },
		success: function(data)
		{
			alert('Статус обновлен');
	   	}
	});
}

function comment_filter()
{
	$.ajax({
		type: "POST",
		url: ajax_cp_path,
		dataType: "json",
		data: {"action":"filter", 
				"filters[body]": $("#comment_body").val(),
				"filters[comment_date][0]": $("#start").val(),
				"filters[comment_date][1]": $("#end").val(),
				"filters[login]": $("#comment_author").val(),
				"filters[moderation_state]": $("#comment_mod_state").val()
			},
		success: function(data)
		{
			$("#comments_list_block").html(data.template);
			$("#comments_list_pagination").html(data.paginate_args);
			$(document).ready(function() {
				$("#jTable").tablesorter(); 
			});
		}
	});
}

function comment_filter_reset()
{
	$('#comment_body').val('');
	$('#start').val('');
	$('#end').val('');
	$('#comment_author').val('');
	$('#comment_mod_state').removeAttr('selected');
	
	comment_filter();
}

function comment_delete(lft, rgt, commented_object_type)
{
	if(confirm('Your really want to delete this comment?'))
	{
		$.ajax({
			type: "POST",
			url: ajax_cp_path,
			dataType: "html",
			data: {'action':'delete', 'lft': lft, 'rgt': rgt, 'commented_object_type': commented_object_type},
			success: function(data)
			{
				alert('Комментарий удален');
			}
		});

	} else {
		return void(0);
	}
}

function checkAll(){	
	$('input[id^="cb_"]').each(function () {
		if($(this).attr('checked') == 0 || $(this).attr('checked') == undefined)
			$(this).attr('checked', 'checked');
		else
			$(this).removeAttr('checked');
	});
}

function isChecked(isitchecked){
	if (isitchecked == true){
		document.cmForm.boxchecked.value++;
	}
	else {
		document.cmForm.boxchecked.value--;
	}
}

function serialize (mixed_value) {
  var _getType = function (inp) {
        var type = typeof inp, match;
        var key;
        if (type == 'object' && !inp) {            return 'null';
        }
        if (type == "object") {
            if (!inp.constructor) {
                return 'object';            }
            var cons = inp.constructor.toString();
            match = cons.match(/(\w+)\(/);
            if (match) {
                cons = match[1].toLowerCase();            }
            var types = ["boolean", "number", "string", "array"];
            for (key in types) {
                if (cons == types[key]) {
                    type = types[key];                    break;
                }
            }
        }
        return type;    };
    var type = _getType(mixed_value);
    var val, ktype = '';
    
    switch (type) {        case "function": 
            val = ""; 
            break;
        case "boolean":
            val = "b:" + (mixed_value ? "1" : "0");            break;
        case "number":
            val = (Math.round(mixed_value) == mixed_value ? "i" : "d") + ":" + mixed_value;
            break;
        case "string":            mixed_value = this.utf8_encode(mixed_value);
            val = "s:" + encodeURIComponent(mixed_value).replace(/%../g, 'x').length + ":\"" + mixed_value + "\"";
            break;
        case "array":
        case "object":            val = "a";
           
            var count = 0;
            var vals = "";
            var okey;
            var key;            for (key in mixed_value) {
                ktype = _getType(mixed_value[key]);
                if (ktype == "function") { 
                    continue; 
                }                
                okey = (key.match(/^[0-9]+$/) ? parseInt(key, 10) : key);
                vals += this.serialize(okey) +
                        this.serialize(mixed_value[key]);
                count++;            }
            val += ":" + count + ":{" + vals + "}";
            break;
        case "undefined": // Fall-through
        default: // if the JS object has a property which contains a null value, the string cannot be unserialized by PHP            val = "N";
            break;
    }
    if (type != "object" && type != "array") {
        val += ";";    }
    return val;
}

function utf8_encode ( argString ) {
    // Encodes an ISO-8859-1 string to UTF-8  
    // 
    // version: 909.322
    // discuss at: http://phpjs.org/functions/utf8_encode    // +   original by: Webtoolkit.info (http://www.webtoolkit.info/)
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: sowberry
    // +    tweaked by: Jack
    // +   bugfixed by: Onno Marsman    // +   improved by: Yves Sucaet
    // +   bugfixed by: Onno Marsman
    // +   bugfixed by: Ulrich
    // *     example 1: utf8_encode('Kevin van Zonneveld');
    // *     returns 1: 'Kevin van Zonneveld'    
    var string = (argString+''); // .replace(/\r\n/g, "\n").replace(/\r/g, "\n");
 
    var utftext = "";
    var start, end;
    var stringl = 0; 
    start = end = 0;
    stringl = string.length;
    for (var n = 0; n < stringl; n++) {
        var c1 = string.charCodeAt(n);        var enc = null;
 
        if (c1 < 128) {
            end++;
        } else if (c1 > 127 && c1 < 2048) {            enc = String.fromCharCode((c1 >> 6) | 192) + String.fromCharCode((c1 & 63) | 128);
        } else {
            enc = String.fromCharCode((c1 >> 12) | 224) + String.fromCharCode(((c1 >> 6) & 63) | 128) + String.fromCharCode((c1 & 63) | 128);
        }
        if (enc !== null) {            if (end > start) {
                utftext += string.substring(start, end);
            }
            utftext += enc;
            start = end = n+1;        }
    }
 
    if (end > start) {
        utftext += string.substring(start, string.length);    }
 
    return utftext;
}

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

function apply_all(){
	
	var cm_actions_val = $('#ms_all option:selected').val();	
	var num = $('input[id^="cb_"]').length;
	var comments = new Object();
	var checkboxes = new Array(num);
	
	var j=0;
	if(num > 0) {
	$('input[id^="cb_"]').each(function () {
		if($(this).attr('checked') == 1) {
		
			var ch_id = $(this).attr('id');
			var id = ch_id.split("_");
			id = id[1];
			var commentobj = new Object();
			
			commentobj.comment_id = $('#comment_id_'+id).val();
			commentobj.ms = cm_actions_val;
			
			checkboxes[j] = commentobj;
			j++;
		}		
	});
	if(j == 0) {
		alert('Необходимо выбрать хотя бы один элемент');
		return;
	}
	comments.chb = checkboxes;
	} else {
		comments.chb = 0;
	}
	
	apply_action(serialize(comments));
}

function apply_one(comment_id){
	var comment = new Object();
	
	comment.comment_id =$('#comment_id_'+comment_id).val();
	comment.moderation_state = $('#state_'+ comment_id +' option:selected').val();

	apply_action(serialize(comment));
}

function apply_action(comment_data){
	$.ajax({
		type: "POST",
		url: ajax_cp_path,
		dataType: "html",
		data:{ 
			'action': 'update_moderation_state',
			'comments': comment_data
		},
		beforeSend: function()
		{
		},
		success: function(data)
		{
			var cp_actions_val = $('#ms_all option:selected').val();		
			if(data == 1){
				$('input[id^="cb_"]').each(function () {
					if($(this).attr('checked') == 1) {
						var id = $(this).attr('id').split("_");
						id = id[1];
						$(this).removeAttr('checked');
						$("#state_"+ id +" option:selected").removeAttr('selected');
						$('#state_'+ id +' [value="'+ cp_actions_val +'"]').attr('selected', 'selected');
					}
				});
			}
			alert('Данные успешно сохранены');
		},
	    error: function(data)
		{
	    }
	});
	return true;
}
</script>

<?php if ($list) : ?>

	<form id = "cmForm" name = "cmForm" action="" method="post" >
		<table id="jTable" cellpadding="4" cellspacing="0" border="1">
			<caption><b><?php echo lang('comments'); ?></b></caption>
			<thead>
				<tr align="center" style="background-color:#373D41;color:#fff;text-align:center;">
					<td width="20">&nbsp;</td>
					<th><b><?php echo lang('comment_date_field'); ?></b></th>
					<th><b><?php echo lang('comment_author_field'); ?></b></th>
					<th><b><?php echo lang('comment_body_field'); ?></b></th>
					<th><b><?php echo lang('commented_object_field'); ?></b></th>
					<th><b><?php echo lang('comment_mod_state_field'); ?></b></th>
					<td><b><?php echo lang('comment_update_field'); ?></b></td>
					<td><b><?php echo lang('comment_delete_field'); ?></b></td>
				</tr>
			</thead>
			<tbody id="comments_list_block">
			
			<?php foreach ($list as $i=>$comment) : ?>
			
				<tr align="center">
					<td width="20"><input type="checkbox" id="cb_<?=$comment->id?>" name="cid[]" value="<?=$comment->id?>" onclick="isChecked(this.checked);" /> </td>
					<td width="100"><?php echo $comment->comment_date; ?></td>
					<td>
						<a class="ulink" target="_blank" href="<?php echo url('profile_view_url',$comment->user_id); ?>"><?php echo $comment->login; ?></a>
					</td>
					<td align="left"><?php echo $comment->body; ?></td>
					<td>
						<?php if( $comment->commented_object_type=='photo') { ?>
						<a class="ulink" href="<?php echo url('view_photo_url',$comment->commented_object_id); ?>">
							<img alt = <?php echo $comment->title ?> src = "<?php echo photo_location().date("m", strtotime($comment->date_added))."/".$comment->commented_object_id."-sm".$comment->extension; ?>"></img>
						</a>
						<?php } ?>
					</td>
					<td width="120">
						<select id="state_<?php echo $comment->id; ?>" size="1" style="width:100px;">
							<?php foreach ($states as $key=>$value) : ?>
							<?php $selected = ($key == $comment->moderation_state)? 'selected' : ''; ?>
							<option value="<?php echo $key; ?>" <?php echo $selected; ?>><?php echo $value; ?></option>
							<?php endforeach; ?>
						</select>
					</td>
					<td width="120"><a href="#" onclick="javascript: apply_one('<?=$comment->id?>'); return false;" style="text-decoration: underline;"><b><?php echo lang('comment_update_field'); ?></b></a></td>
					<td width="100">
						<a href="#" onclick = "comment_delete('<?php echo $comment->lft; ?>','<?php echo $comment->rgt; ?>','<?php echo $comment->commented_object_type; ?>'); return false" style="text-decoration: underline;"><b><?php echo lang('comment_delete_field'); ?></b></a>
						<input type="hidden" id="comment_id_<?=$comment->id?>" value="<?=$comment->id?>" />
					</td>
				</tr>
				
			<?php endforeach;  ?>
			
			</tbody>
			<tfoot>
				<tr style="text-align:center;">
					<td width="20"><input type="checkbox" name="toggle" value="" onClick="checkAll();" /></td>
					<td width="582" colspan="4">Выберите комментарии из списка и новое состояние</td>
					<td width="120">	
						<select id="ms_all" style="width:120px;" name="ms_all">
						<?php foreach ($states as $key=>$value) : ?>
							<?php $selected = ($key == $comment->moderation_state)? 'selected' : ''; ?>
							<option value="<?php echo $key; ?>"><?php echo $value; ?></option>
						<?php endforeach; ?>
						</select>
					</td>
					<td colspan = 2> <input type="button" value="Состояние для всех выбранных"  onclick = "javascript: if (document.cmForm.boxchecked.value == 0) { alert('Не выбран ни один комментарий из списка!'); return false} else {apply_all();}" ></td>
				</tr>
			</tfoot>
		</table>
		<input type="hidden" name="boxchecked" value="0" />
	</form>
		
<?php else : ?>
<div align="left" id="no_data"><?php echo lang('comment_no_data'); ?></div>
<?php endif; ?>

<div id="comments_list_pagination">
	<?php echo paginate($paginate_args); ?>
</div>