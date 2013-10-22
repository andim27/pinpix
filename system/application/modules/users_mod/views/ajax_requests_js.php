<script language="JavaScript" type="text/javascript">

var ajax_actions_path = "<?php echo base_url(); ?>users_mod/users_ctr/ajax_actions";
var ajax_uploads_path = "<?php echo base_url(); ?>users_mod/users_ctr/ajax_uploads";
var uplodify_path     = "<?php echo static_url(); ?>js/";
var f_msg             = "<?php echo lang('bad_avatar');?>";
var av_predef_prev=1;
function ajax_fill_region (country_id)
{
	jQuery.post(ajax_actions_path, {
		action: "get_regions",
		country_id: country_id
		},
		function(data){
            $("#div_region").html("");
            $("#div_region").html(data);
			},
			'html'
	);
}

function ajax_fill_city (region_id)
{
	jQuery.post(ajax_actions_path, {
		action: "get_cities",
		region_id: region_id
		},
		function(data){
         $("#div_city").html("");
         $("#div_city").html(data);
		},
		'html'
	);
}

function checkPsw() {
 psw=$("#user_pwd").val();
 conf=$("#user_cfm").val();
 if ((psw.length < 3) || (conf.length < 3 ) || (conf != psw)) {
     $("#psw_error").css("color","red");
     document.getElementById("user_pwd").focus();
     return false;
  } else {
     $("#psw_error").css("color","#8BC53F");
     return true;
  }
}
function checkfilename(name_str) {
  var res=false;
  if (name_str.lastIndexOf(".jpg") != -1){
     return true;
  }
  if (name_str.lastIndexOf(".png") != -1){
     return true;
  }
  if (name_str.lastIndexOf(".gif") != -1){
     return true;
  }
  if (name_str.length == 0) {
     return false;
  }
  return res;
}
var msg_cur="div_avatar_pm";
function ajax_save_avatar(form_id,msg_id) {
if (form_id=="personal_form_id") {
    if (checkfilename($("#user_avatar").val()) ) {
       avatarAjaxFileUpload(msg_id);
    }
    return;
};

if (form_id=="predef_form_id") {
    var options = {
        target:"#"+msg_id,
           url:ajax_actions_path,
     resetForm:true,
      dataType: "json",
       success:showResponseAvatar
    }
    msg_cur=msg_id;
    $('#'+msg_id).addClass('ok_mes');
    $('#'+msg_id).show();
    $('#'+msg_id).html("<?php echo lang('change_avatar_process'); ?>");
    $('#'+form_id).ajaxSubmit(options);
}//-- i f
}
function showResponseAvatar(data, statusText)  {
 $('#'+msg_cur).show();
 res=data.res;
 if (res=="1") {
   $('#'+msg_cur).removeClass('error_mes');
   $('#'+msg_cur).addClass('ok_mes');
 } else {
   $('#'+msg_cur).removeClass('ok_mes');
   $('#'+msg_cur).addClass('error_mes');
 }
 $('#'+msg_cur).html(data.mes);
 $("#user_avatar_img").attr("src",data.a_name);

}
function avatarPredefChoice(n) {
 $("#av_predef_id_"+n).addClass('av_border ');
 $("#av_predef_id").val(n);
 $("#av_predef_id_"+av_predef_prev).removeClass('av_border ');
 av_predef_prev=n;
}
function showPredef() {
var pr=$("#zagrug_image").css("display");
if (pr=="block"){
 $("#zagrug_image").hide();
} else {
 $("#zagrug_image").show();
}
}
function initAvatarUserUpload(){
  $(document).ready(function() {
  $("#user_avatar").uploadify({
    'uploader'  : uplodify_path + <?php if  ($this->db_session->userdata('user_lang') =="kz") {echo "'uploadify_v.swf'";} else echo "'uploadify.swf'"; ?>,
    'script'    : ajax_uploads_path,
    'scriptAccess': 'always',
    'cancelImg' : 'cancel.png',
    'queueID'   : 'fileQueue',
    'auto'      : true,
    'multi'     : false,
    'width'     : 70,
    'height'    : 20,
    'rollover'  : false,
    'wmode'     : 'transparent',
    'fileDesc'  : 'jpg;gif;png',
    'fileExt'   : '*.jpg;*.png;*.gif',
    'folder'    : 'myFolder',
    'scriptData': {'user_id':'<?= $user->user_id; ?>','action':'personalavatar'},
    'onSelect'  : function(event, queueID, fileObj){
        $("#f_name_div").show().html(fileObj.name);
        $('#div_avatar_pm').hide();
    },
    'onProgress':function (event,queueID,fileObj,response,data) {
       $('#progress_div').css("width",data.percentage+"%");
    },
    'onComplete': function(event,queueID,fileObj,response,data) {
      result = window["eval"]("(" + response + ")");
      $('#progress_div').css("width","100%");
      $('#div_avatar_pm').css('color', '#00FF00');
      $('#div_avatar_pm').show();
      $('#div_avatar_pm').html(result.mes);
      if (result.a_name != ""){$("#user_avatar_img").attr("src",result.a_name);}
      $("#f_name_div").show().html(fileObj.name);
    }
  });
});
//$('#uploadify').uploadifySettings('scriptData',{'user_id':<?= $user->user_id; ?>,'action':'personalavatar'});
}
function avatarAjaxFileUpload(msg_id) {
		//starting setting some animation when the ajax starts and completes
		$("#"+msg_id)
		.ajaxStart(function(){
			$(this).show();
            $(this).html("<?php echo lang('change_avatar_process'); ?>!!!"); 
		})
		.ajaxComplete(function(){
			$(this).hide();
            //alert("uploading done!");
		});

		/*
			prepareing ajax file upload
        */
		$.ajaxFileUpload({
			url:ajax_uploads_path,
			secureuri:false,
			fileElementId:'user_avatar',
			dataType: 'json',
			success: function (data, status) {
				//alert("Server answer a_name:"+data.a_name);
				$("#user_avatar_img").attr("src",data.a_name);
                $('#progress_div').css("width","1%");//--clear user progress bar 
				if(typeof(data.error) != 'undefined') {
					if(data.error != '') {
						alert(data.error);
					} else {
						alert(data.mes);
					}
				}
			},
			error: function (data, status, e) {
				alert("Server error: "+data+","+status+" "+e);
			}
		})
		return false;
}

function authorize()
{
	//starting setting some animation when the ajax starts and completes
		$("#login_process")
		.ajaxStart(function(){
			$(this).show();
            $(this).html("<?php echo 'auth - process...'; ?>"); 
            $("#login_form").hide();
		})
		.ajaxComplete(function(){
			$("#login_form").show();
			$(this).hide();
		});
	
	$.ajax({
			type: "POST", 
			url: ajax_actions_path,
			dataType: "json",
			data: { 'action': 'authorize', 'login': $("#login").val(), 'password': $("#password").val() },
			success: function(data) {
				if(data.status<0)
				{
					var err = data.login_err+data.password_err;
					$("#login_feedback").html(err);	
					if( data.login_err!='' )
					{
						$("#login").css({'background-color':'yellow'});
					}
					if( data.password_err!='' )
					{
						$("#password").css({'background-color':'yellow'});
					}
				}
				else if(data.status<1)
				{
					var err = '<?php echo lang('error_auth'); ?>';
					$("#login_feedback").html(err);
				}
				else if(data.status==1)
				{
					$("#main_enter_box").hide();
				}
		   }
		});
}
</script>