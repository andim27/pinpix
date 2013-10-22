<?php include('head.php'); ?>
<script type="text/javascript" src="<?php echo static_url(); ?>js/tablesorter/jquery-latest.js"></script>
<script type="text/javascript" src="<?php echo static_url(); ?>js/tablesorter/jquery.tablesorter.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo static_url(); ?>/css/ui.all.css">
<script type="text/javascript" src="<?php echo static_url(); ?>js/jquery-ui-1.6.custom.min.js"></script>
<script type="text/javascript" src="<?php echo static_url(); ?>js/phh.javascript.js"></script>

<script type="text/javascript">
$(function(){
	$("#start").datepicker({
		showOn: 'button',
		buttonImage: '/css/images/calendar.png',
		buttonImageOnly: true,
		dateFormat: 'dd M yy',
		duration: '',
		yearRange: '-10:+10'
	});
	$("#end").datepicker({
		showOn: 'button',
		buttonImage: '/css/images/calendar.png',
		buttonImageOnly: true,
		dateFormat: 'dd M yy',
		duration: '',
		yearRange: '-10:+10'
	});
});
</script>
<script type="text/javascript">

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
	var cp_actions_val = $('#cp_actions option:selected').val();
	var competition_id = $('#competition_id').val();	
	var num = $('input[id^="cb_"]').length;
	var photos = new Object();
	var checkboxes = new Array(num);
	
	var j=0;
	if(num > 0) {
	$('input[id^="cb_"]').each(function () {
		if($(this).attr('checked') == 1) {
		
			var ch_id = $(this).attr('id');
			var id = ch_id.split("_");
			id = id[1];
			var photoobj = new Object();
			
			photoobj.photo_id = $('#photo_id_'+id).val();
			photoobj.ms = cp_actions_val;
			
			checkboxes[j] = photoobj;
			j++;
		}		
	});
	if(j == 0) {
		alert('Необходимо выбрать хотя бы один элемент');
		return;
	}
	photos.chb = checkboxes;
	photos.competition_id = competition_id;
	} else {
		photos.chb = 0;
	}
	
	apply_action(serialize(photos));
}

function apply_one(index){
	var photo_data = "";
	var ms = $('#moderation_state_'+ index +' option:selected').val();
	var place = $('#place_'+ index +' option:selected').val();
	var competition_id = $('#competition_id').val();
	var photo_id = $('#photo_id_'+index).val();
	
	var photos = new Object();
	
	photos.photo_id = $('#photo_id_'+index).val();
	photos.competition_id = $('#competition_id').val();
	photos.moderation_state = $('#moderation_state_'+ index +' option:selected').val();

	apply_action(serialize(photos));
}

function apply_action(photo_data){
	var ajax_cp_path = "<?=base_url()?>competition_mod/competition_ctr/ajax_actions";
	
	$.ajax({
		type: "POST",
		url: ajax_cp_path,
		dataType: "html",
		data:{ 
			'action': 'apply_action',
			'photos': photo_data
		},
		beforeSend: function()
		{
		},
		success: function(data)
		{
			var cp_actions_val = $('#cp_actions option:selected').val();		
			if(data == 1){
				$('input[id^="cb_"]').each(function () {
					if($(this).attr('checked') == 1) {
						var id = $(this).attr('id').split("_");
						id = id[1];
						$(this).removeAttr('checked');
						$("#moderation_state_"+id+" option:selected").removeAttr('selected');
						$('#moderation_state_'+id+' [value="'+ cp_actions_val +'"]').attr('selected', 'selected');
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

function check_all(param){
	if(param == 'all'){
		
		$('input[id^="cb_"]').each(function () {
			$(this).attr('checked', 'checked');
		});
		$('#check_all').attr('checked', 'checked');
		
	} else if(param == 'notall') {
		
		$('input[id^="cb_"]').each(function () {
			$(this).removeAttr('checked');
		});
		$('#check_all').removeAttr('checked');
	}
	
}

function check_all2(){	
	$('input[id^="cb_"]').each(function () {
		if($(this).attr('checked') == 0 || $(this).attr('checked') == undefined)
			$(this).attr('checked', 'checked');
		else
			$(this).removeAttr('checked');
	});
}

function clear_all(){
	$("#start").val('');
	$("#end").val('');
	$("#cp_users_list option:selected").removeAttr('selected');
	$("#cp_status_list option:selected").removeAttr('selected');
	
	filter_photos();
}

function filter_photos(cur_page){
	var ajax_cp_path = "<?=base_url()?>competition_mod/competition_ctr/ajax_actions";
	
	if(cur_page == undefined) cur_page = 1;

	$.ajax({
		type: "POST",
		url: ajax_cp_path,
		dataType: "json",
		data: {
			'action':'filter_photos',
			'competition_id': $('#competition_id').val(),
			'user_id': $("#cp_users_list option:selected").val(),
			'date_start': $("#start").val(),
			'date_end': $("#end").val(),
			'status_id': $("#cp_status_list option:selected").val(),
			'cur_page': cur_page
		},
		beforeSend: function()
		{			
//			$('#loader_'+item_id).show();
		},
		success: function(data)
		{
//			$('#loader_'+item_id).hide();
			$('#competition_photos_body').html(data.template);
			$('.paginator').html(data.paginate);
		},
		error: function(data)
		{
//			$('#loader_'+item_id).hide();
			$('#competition_photos').html('');
		}
	});
}
	
</script>
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
				<div style="display:none;" id="main_side_und_box" class="nifty">
 
					<!--Center-->
					<div id="ms_center">
						<div id="ms_cen_zag" align="left"><span class="m_zag_txt"></span></div>
						<div id="ms_cen_box">
							<?php // include('admin_menu.php'); ?>
						</div>
					</div>
					<!-- End center-->
					  
				</div>   
				<!-- End Main_side_und_box-->
			   
			</div>  
			<!-- End main_side_right -->
<script type="text/javascript">
$(function(){
	$("#start").datepicker({showOn: 'button', buttonImage: '<?=base_url()?>images/icons/calendar.png', buttonImageOnly: true});
	$("#end").datepicker({showOn: 'button', buttonImage: '<?=base_url()?>images/icons/calendar.png', buttonImageOnly: true});
});
</script>
<style type="text/css">
.pix #c_box{
	top:0px;
}
.paginator{	
	float:left;
	font-size:12px;
	font-weight:bold;
	margin:0;
	width:100%;
}
.paginator a {
	margin-left:3px;
	margin-right:2px;
	color:#114170;
}
</style>
			<!-- main_cont_body -->
			<div id="main_cont_body" style="width:965px;">
			   	
			   	<!-- Start center main-->
				<div id="c_box">
				<strong><a href = "<?php echo url('admin_comps_url')?>"> <?php echo lang ('back_to_comp') ?></a></strong>
				<br><br>
				<div align = "center"><strong><?php echo lang ('comp_photos') . $name?></strong></div><br>
				<?php if (empty($photos))
							echo lang('no_photos');
					  else {
					  	$paginate_args = paginate_ajax($paginate_args);
				?>					
					<div style="float:left;font-size:12px;margin-bottom:10px;width:100%;">
						<div style="float:left;">
							<div style="margin-bottom:5px;">
								<span>Выбрать:</span>
								<span style="cursor:pointer;color:#114170;" onclick="javascript: check_all('all');">Все</span>,
								<span style="cursor:pointer;color:#114170;" onclick="javascript: check_all('notall');">Ни одного</span>
							</div>
							<div style="font-weight:bold;float:left;">
								<span>Действия:</span>
								<select id="cp_actions" style="font-size:12px;">
									<option value="null">Установить статус</option>
									<option value="<?=MOD_NEW?>"><?=lang('mod_new');?></option>
									<option value="<?=MOD_APPROVED?>"><?=lang('mod_approved');?></option>
									<option value="<?=MOD_FEATURED?>"><?=lang('mod_featured');?></option>
									<option value="<?=MOD_DECLINED?>"><?=lang('mod_declined');?></option>
									<option value="<?=MOD_DELETED?>"><?=lang('mod_deleted');?></option>
								</select>
							</div>
							<div style="float:left;font-weight:bold;margin-left:5px;position:relative;top:1px;">
								<span id="cp_actions_apply" style="cursor:pointer;color:#114170;" onclick="javascript:apply_all(); return false;">Применить</span>
							</div>
						</div>
					</div>
					<div style="float:left;width:965px;">
						<input type="hidden" id="competition_id" value="<?=$comp_id?>" />
						<div style="float:left;width:963px;">
							<div style="float: left; font-size: 12px; font-weight: bold; position: relative; top: 14px;">Фильтровать по:</div>							
							<div style="float: left; width: 130px; position: relative; padding-left: 35px; top: 14px;">
								<select id="cp_users_list" style="width: 135px; font-size: 12px;">
									<option value="0">имя пользователя</option>
									<?php
										$users_str = "";
										if(!empty($competition_users)) { 
											foreach($competition_users as $user) {
												$users_str .= '<option value='.$user->user_id.'>'.$user->login.'</option>';
											}
										}
										echo $users_str;
									?>
								</select>
							</div>
							<div style="float: left; font-size: 12px; text-align: left; width: 180px; margin-left: 85px;margin-bottom:3px;">
								<?=lang('date_from')?><input value="" name="start" id="start" type="text" style="width:100px;height:12px;" /><br />
								<?=lang('date_till')?><input value="" name="end" id="end" type="text" style="width:90px;height:12px;" />								
							</div>
							<div style="float:left;position:relative;top:12px;">
								<div>
									<select id="cp_status_list" style="float: left;">
										<option value="null">Состояние</option>
										<option value="0">Новый</option>
										<option value="1">Одобрен</option>
										<option value="2">Хорошие</option>
										<option value="-1">Отклонен</option>
										<option value="-2">Удален</option>
									</select>
								</div>								
							</div>
							<div style="float: right; position: relative; top: 10px;">
								<input type="button" value="Применить" onclick="javascript: filter_photos();" />								
								<input type="button" value="Сброс" onclick="javascript: clear_all();" />
							</div>
						</div>
					</div>
					<div style="color:#373D41;font-family:Verdana,Geneva,sans-serif;font-size:11px;width:965px;float:left;">
						<table id="jTable" border="1" cellpadding="0" cellspacing="0" style="table-layout:fixed;border-collapse:collapse;">
						<thead>
							<tr style="background-color:#373D41;color:#fff;text-align:center;">
								<td width="20"><input type="checkbox" id="check_all" onclick="javascript:check_all2();"/></td>
								<th width="150"><?=lang('preview');?></th>
								<th width="100"><?=lang('username');?></th>
								<th width="100"><?=lang('title');?></th>
								<th width="130"><?=lang('date_added');?></th>
								<th width="60"><?=lang('balls');?></th>
								<th width="90"><?=lang('moderation_state');?></th>								
								<th width="60"><?=lang('place');?></th>
								<th width="140"><?=lang('descr');?></th>
								<th width="130"><?=lang('action');?></th>
							</tr>
						</thead>
						<tbody id="competition_photos_body">
						
						<?php 
							foreach($photos as $index=>$photo): 
						?>
							<tr valign="center" align="center" height="150"<?=(($index%2 == 1)?' style="background-color:#ccc;"':'');?>>
								<td width="20"><input type="checkbox" id="cb_<?php echo ($index); ?>" value="<?php echo $photo->photo_id; ?>" /> </td>
								<td style="text-align:center;">
									<?php $urlImg = photo_location().date("m", strtotime($photo->date_added))."/".$photo->photo_id."-sm".$photo->extension	?>																	
									<img alt="<?php echo $urlImg ?>" src="<?=$urlImg ?>" />
								</td>
								<td><?=$photo->login?></td>
								<td><?=((strlen($photo->title)>16)?substr($photo->title, 13).'...':$photo->title);?></td>
								<td><?=$photo->date_added?></td>
								<td><?=$photo->balls?></td>
								<td width="90">
									<select name="moderation_state" id="moderation_state_<?=$index?>">
										<option value="<?=MOD_NEW?>" <?=(($photo->moderation_state == MOD_NEW)?' selected="selected"':'');?>><?=lang('mod_new');?></option>
										<option value="<?=MOD_APPROVED?>" <?=(($photo->moderation_state == MOD_APPROVED)?' selected="selected"':'');?>><?=lang('mod_approved');?></option>
										<option value="<?=MOD_FEATURED?>" <?=(($photo->moderation_state == MOD_FEATURED)?' selected="selected"':'');?>><?=lang('mod_featured');?></option>
										<option value="<?=MOD_DECLINED?>" <?=(($photo->moderation_state == MOD_DECLINED)?' selected="selected"':'');?>><?=lang('mod_declined');?></option>
										<option value="<?=MOD_DELETED?>" <?=(($photo->moderation_state == MOD_DELETED)?' selected="selected"':'');?>><?=lang('mod_deleted');?></option>
									</select>
								</td>
								<td width="64">
									<select name="place" id="place_<?=$index?>">
										<option value="0" <?=(empty($photo->place_taken)?' selected="selected"':'');?>><?php echo lang('no')?></option>
										<?php for ($t=0; $t<10; $t++) {?>
										<option value="<?=$t+1?>" <?=(($photo->place_taken == $t+1)?' selected="selected"':'');?>><?=$t+1?></option>
										<?php } ?>
									</select>
								</td>
								<td width="64">
									<textarea id="place_description" name="place_description" cols="" rows="" style = "width:130px; height:150px;" ><?php if (isset ($photo->place_description)) echo $photo->place_description ?></textarea>	
								</td>
								<td width="130" align="center">
									<input type="submit" value="<?=lang('btn_ok')?>" onclick="javascript: apply_one('<?=$index?>');" />
									<input type="reset" value="<?=lang('btn_cancel')?>" />
									
									<input type="hidden" id="photo_id_<?=$index?>" value="<?=$photo->photo_id?>"/>
								</td>								
							</tr>
							<?php endforeach;?>
						</tbody>
						</table>
						
						<script>
						$(document).ready(function() 
						    { 
						        $("#jTable").tablesorter(); 
						    } 
						); 
						</script>
						
						<div style="float:right;margin-top:5px;">
							<?php echo $paginate_args; ?>
						</div>
					</div>
  					
				<?php 
					  } //if !(empty($photos))					  
				?>	
				</div>
				<!--End Center main-->
				   
				<br /><br /><br /><br />
			</div>
			<!-- end #main_cont_body -->
		   
		</div>   
		<!-- end #mainContent -->
		  
		<?php include('footer.php'); ?>
		
	</div>
	<!-- end #pix -->

</body>
</html>