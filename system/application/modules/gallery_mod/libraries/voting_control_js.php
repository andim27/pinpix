<?php if (!defined('BASEPATH')) exit('Нет доступа к скрипту');  ?>
<script type="text/javascript">
var v_done=false;
var p_done=false;
var ajax_actions_path = "<?php echo base_url(); ?>photo/vote_action/";
var msg_cur="div_vote_message_id";
function ajax_vote_action(form_id,msg_id,vote_n) {
	$('#vote_n').val(1);
	ajax_save_vote(form_id,msg_id);
}
function ajax_save_vote(form_id,msg_id) {
if (v_done) {return;}
v_done=true;
if ($("#cur_user_id").val() != 0) {
   var options = {
		target:"#"+msg_id,
		url:ajax_actions_path,
		resetForm:true,
		dataType: "json",
		success:showResponse
	}
	msg_cur=msg_id;
	$('#'+msg_id).show();
	$('#'+msg_id).html("&nbsp;<?php echo lang('send_vote_msg'); ?>");
	$('#'+form_id).ajaxSubmit(options);
 } else {
    $('#'+msg_id).css('color', '#00FF00');
    $('#'+msg_id).html("&nbsp;<b><?php echo '<a href=\"'.base_url().'register\">'.lang('login_only').'</a>'; ?></b>");
 }
}
function showResponse(data, statusText)  {
 v_done=true;
 $('#'+msg_cur).show();
 res=data.res;
 on_what_id=data.on_what_id;
 $("#vote_action"+data.on_what_id).hide();
 votes_balls="<?= lang('ico_votes_txt'); ?>: <b>"+data.votes_num+"<b>";
 $('#'+msg_cur).css('color', '#00FF00');
 $('#'+msg_cur).html(" <b>"+data.mes+"</b>");
 $('#rating_balls_'+on_what_id).html(votes_balls);
}
function ajax_vote_place(form_id,msg_id) {
  if (p_done) {return;}
  p_done=true;
  var options = {
		target:"#"+msg_id,
		url:ajax_actions_path,
		resetForm:false,
		dataType: "json",
		success:showResponsePlace
  }
  msg_cur=msg_id;
  $('#'+msg_id).css('color', '#00FF00');
  $('#'+msg_id).show();
  $('#'+msg_id).html("&nbsp;<?php echo lang('do_place_msg'); ?>");
  $('#'+form_id).ajaxSubmit(options);
  //alert("Место выставлено!");
}
function showResponsePlace(data, statusText)  {
  p_done=true;
  $('#place_mes').show();
  $('#place_mes').html(" <b>"+data.mes+"</b>");
  $('#place_btn').hide();
}
</script>