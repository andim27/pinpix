function personifyPage(user_login)
{
	$('#h_r_p_people').show(); //no such block in new design - replace
	$('#user_login').html(user_login);
	$('#place_txt_people').show();
	$('#p_h_link_2').show();
	$('#menu_l1').show();
	
	if($('#admin_menu_box')!=null && $('#admin_menu_box')!='undefined') 
	{	
		get_admin_menu();
		$('#admin_menu_box').show();
	}
	
} 
	
function get_admin_menu()
{
	$.ajax({
			type: "POST", 
			url: location+"/ajax_actions",
			dataType: "html",
			data: { 'action':'get_admin_menu' },
			success: function(data)
			{ 
				if (data == '')
				{
					data = 'You have no access to admin area!';
				}
				$('#admin_menu').html(data);
		   }
		});
}

function toggleVisibility(id)
{
   var e = document.getElementById(id); 
   if(e.style.display != 'block')
      e.style.display = 'block';
   else
      e.style.display = 'none';
      
	$('li').removeClass('active');
	$('.hasSubmenu').addClass("active");
}

$(function(){
	$("#detalis_close").click(function(){
		$("#ph_sv_box").css("display", "none");
		$("#overlay").remove();
	});
});

$.fn.check = function(mode) {
	var mode = mode || 'on'; // if mode is undefined, use 'on' as default
	return this.each(function() {
		switch(mode) {
			case 'on':
				this.checked = true;
				break;
			case 'off':
				this.checked = false;
				break;
			case 'toggle':
				this.checked = !this.checked;
				break;
		}
	});
};
$.fn.enable = function(mode) {
	var mode = mode || 'on'; // if mode is undefined, use 'on' as default
	return this.each(function() {
		if (mode == 'on') {
			this.disabled = false;
		} else {
			this.disabled = true;
		}
	});
};

function ShowDetalis(el){
	var _id = el.id.substr(6); // Get photo id
// Set id
	$('#pp_id').val(_id);
// Set title
	$("#pp_title").val($("#ph_title_"+_id).val());
// Set description
	$('#pp_descr').attr('defaultValue', $('#ph_descr_'+_id).val());
// Set view allowed field
	$("#pp_allowed").attr('selectedIndex', $('#pp_allowed option[value="'+$('#ph_allowed_'+_id).val()+'"]').attr("index"));
// Set album
	$("#pp_album").attr("selectedIndex", $("#pp_album option[value='"+$("#ph_album_"+_id).val()+"']").attr("index"));
// Set category
	$("#pp_category").attr("selectedIndex", $("#pp_category option[value='"+$("#ph_category_"+_id).val()+"']").attr("index"));
// Set competition
	var _selectedIndex = $("#pp_competition option[value='"+$("#ph_competition_"+_id).val()+"']").attr("index");
	$("#pp_competition").attr("selectedIndex", _selectedIndex);
	if (_selectedIndex != 0) {
		$("#pp_competition").enable('off');
	} else {
		$("#pp_competition").enable();
	}
// Set "album main" checkbox
	if ($("#ph_m_album_"+_id).attr("value") == 'yes') {
		$("#pp_album_main").check();
	} else {
		$("#pp_album_main").check('off');
	}
// Set erotic flag
		if ($("#ph_erotic_"+_id).attr("value") == 'yes') {
			$("#pp_erotic").check();
		} else {
			$("#pp_erotic").check('off');
		}

	var pos = $(el).offset();
	$("#ph_sv_box").css("display", "block");
	$("#ph_sv_box").css("top", pos.top+"px");
	$("#ph_sv_box").css("left", (pos.left-30)+"px");
	propAlovedChange(document.getElementById('pp_allowed'));

// Create overlay div
 	var overlay = document.createElement("div");
	overlay.id = "overlay";
	$("body").append(overlay);
	$("#overlay").css("opacity", .5);
	$("#overlay").css("height", $(document).height()+"px");
	return false;
}
function propAlovedChange(el){
	if (el.value == 0){
		$("#hidden_pwd").show();
	} else {
		$("#hidden_pwd").hide();
	}
}
function propAlovedChangeUpload(el){
	if (el) {
		if (el.value == 0){
			$("#hidden_pwd_upload").show();
		} else {
			$("#hidden_pwd_upload").hide();
		}
	}
}
function ShowAlbumPwd(el){
	if (el.value == 0) {
		$("#album_pwd").show();
	} else {
		$("#album_pwd").hide();
	}
}
function HideDetalis() {
	$("#ph_sv_box").css("display", "none");
	$("#overlay").remove();
	return false;
}
function CheckPassword(pwd_selector, confirm_selector) {
	var pwd = $(pwd_selector).val();
	var cfm = $(confirm_selector).val();
	if ((pwd == cfm) && (pwd.length == 0))
		return true;
	if (pwd.length == 0) {
		if (typeof __LabelPasswordEmpty == "undefined") {
			__LabelPasswordEmpty = "Password is empty";
		}
		alert(__LabelPasswordEmpty);
		return false;
	}
	if (cfm.length == 0) {
		if (typeof __LabelConfirmationEmpty == "undefined") {
			__LabelConfirmationEmpty = "Confirmation is empty";
		}
		alert(__LabelConfirmationEmpty);
		return false;
	}
	if (pwd != cfm) {
		if (typeof __LabelPwsConfDifferent == "undefined") {
			__LabelPwsConfDifferent = "Password and confirmation are diferrent";
		}
		alert(__LabelPwsConfDifferent);
		return false;
	}
	return true;
}

	var title = '';
	var descr = '';
	var alloved = '';
	var erotic = '';
	var cat = '';
	var album = '';
	var pwd = '';

function AJAXImagesUploadSubmit() {

	var r = new RegExp("[\<|\>|\"|\'|\%|\;|\(|\)|\&|\+|\-]", "i");
	title = $('#upload_img_title').val();

	if  (r.exec(title) ) 
	{
		alert( __LabelNotValidTitle); 
		return false;
	}
	if (title=="")
	{		
		alert(__LabelEmptyTitle);
		return false;	
	}
	
	fi = $('#upload_progress').html();
	if (fi=="")
	{
		alert(__LabelFileEmpty);
		return false;
	}
	descr = $('#upload_img_descr').val();
	alloved = $('#upload_img_allowed').val();
	erotic = (document.getElementById('upload_img_erotic').checked)? "yes": "no";
	cat = $('#upload_img_cat').val();
	
	album = $('#upload_img_alb').val();
	
	if (album == null) 
	{
		alert(__LabelEmptyAlbumTitle);
		return false;
	}	
	pwd = $('#upload_img_pwd').val();
	if (title.length && __queueID){
		if (alloved == 0){
			if (!CheckPassword('#upload_img_pwd', '#upload_img_pwd_cfm')){
				alert(__LabelPwsConfDifferent);
				return false;
			}
		}
		$("#fileInput").uploadifyUpload();
	} else {
		alert(__LabelImgSubmitFailed);
		return false;
	}
	return false;
}
function PackJSON2Query(data) {
	var result = '';
	for (var val in data) {
		result += '&'+val+'='+data[val];
	}
	return result;
}
function AJAXImagesUploadCancel() {
	try {
		$("#fileInput").uploadifyClearQueue();
	} catch(e) {return false};
	__queueID = false;
	$('#upload_progress').html('');
	$('#upload_img_title').val('');
	$('#upload_img_descr').val('');
	$('#upload_img_allowed').attr('selectedIndex', 0);
	$('#upload_img_erotic').check('off');
	$('#upload_img_cat').attr('selectedIndex', 0);
	$('#upload_img_alb').attr('selectedIndex', 0);
	propAlovedChangeUpload(document.getElementById('upload_img_allowed'));
	return false;
}
function NewAlbumSubmit() {
	if ($('#album_title').val() == '') {
		alert(__LabelEmptyTitle);
		return false;
	}
	if ($('#new_album_allowed').val() == 0){
		return CheckPassword("#new_album_pass", '#new_album_confirm');
	}
	return true;
}
function AlbumFormReset(index){
	document.getElementById('album_invisible_'+index).style.display = 'none';
	ShowAlbumPwdById(document.getElementById('album_allowed_'+index), index);
}

function create_new_album(baseurl) {
	alert (baseurl);
	var profile_actions_path = baseurl + "gallery_mod/gallery_ctr/ajax_actions";
		
	if ($('#album_title1').val() == '') {
		alert(__LabelEmptyTitle);
		return false;
	}
	if ($('#new_album_allowed1').val() == 0){
		if (!CheckPassword("#new_album_pass1", '#new_album_confirm1'))
			return false;
	}
	var erotic_flag = 0;
	if ($('#erotic_p1:checked').val() == 'on')
		erotic_flag = 1;
	
	$.ajax({
			type: "POST", 
			url: profile_actions_path,
			dataType: "html",
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
				//todo -- append the album in list of albums	
				alert ('Album Created!');
				$('select[@name=upload_img_alb]').append('<option selected value="'+data+'">'+$('#album_title1').val()+'</option>');
		    }
		   
		    
		});

	return true;
}
function ShowAlbumPwdById(el, index) {
	if (el) {
		if (el.value == 0) {
			$("#album_pwd_"+index).show();
		} else {
			$("#album_pwd_"+index).hide();
		}
	}
}
function AlbumEditSubmit(index) {
	if ($('#album_title_'+index).val() == '') {
		alert(__LabelEmptyAlbumTitle);
		return false;
	}
	if ($('#album_allowed_'+index).val() == 0){
		return CheckPassword("#album_pass_"+index, '#album_confirm_'+index);
	}
	return true;
}
function DelConfirm() {
	return confirm(__LabelDeleteConfirmation);
}
function SetUserLang(lang, usr_id, baseurl) {
	
	var ajax_actions_path = baseurl + "users_mod/users_ctr/ajax_actions";
	
	var __lang = 'ru';
	switch(lang) {
		case 'en': __lang = 'en';break;
		case 'kz': __lang = 'kz';break;
	}
	
	$.post(ajax_actions_path,
		{action: 'setlang', language: __lang, user_id: usr_id},
		function(){
			window.location.reload(true);
	});
}

$(function(){
	if(NiftyCheck()) {
		Rounded("div.nifty","#b5bac0","#373d41");
	}
});


function AJAXImagesUploadSubmit_new() {
	
	if (flag == -1) //for bibb: no more 5 photos at competition
	{
		alert(__LabelToManyPhotos);
		return false;
	}
	
	var r = new RegExp("[\<|\>|\"|\'|\%|\;|\(|\)|\&|\+|\-]", "i");
	title = $('#upload_img_title').val();
	
	if  (r.exec(title) ) 
	{
		alert( __LabelNotValidTitle); 
		return false;
	}
	
	if (title=="")
	{
		alert(__LabelEmptyTitle);
		return false;
	}
	fi = $('#upload_progress').html();
	if (fi=="")
	{
		alert(__LabelFileEmpty);
		return false;
	}
	descr = $('#upload_img_descr').val();
	alloved = 2;//$('#upload_img_allowed').val();
	erotic = "no";//(document.getElementById('upload_img_erotic').checked)? "yes": "no";
	cat = 96;//$('#upload_img_cat').val();
	album = 0; //$('#upload_img_alb').val();
	pwd = '';//$('#upload_img_pwd').val();
	if (title.length /*&& __queueID*/){
		if (alloved == 0){
			if (!CheckPassword('#upload_img_pwd', '#upload_img_pwd_cfm')){
				return false;
			}
		}
		$("#fileInput").fileUploadStart();
	} else {
		alert(__LabelImgSubmitFailed);
		return false;
	}
	return false;
}
function fl_v(){
  //AndMak code:
  var d, n = navigator, m, f = 'Shockwave Flash';
  if((m = n.mimeTypes) && (m = m["application/x-shockwave-flash"]) && (m.enabledPlugin) && (n = n.plugins) && (n[f])) {d = n[f].description}
  else if (window.ActiveXObject) { try { d = (new ActiveXObject((f+'.'+f).replace(/ /g,''))).GetVariable('$version');} catch (e) {}}
  return d ? d.replace(/\D+/,'').split(/\D+/) : [0,0];
};
