<?php modules::load_file('ajax_requests_js.php',APPPATH.'modules/users_mod/scripts/'); ?>
<?php  
 $lng = $this->db_session->userdata('user_lang');
 $this->lang->load('bibb',$lng);
 ?>
<a name='ent'></a>
<div id="main_enter_box">
	<div class="roundedBlock">
		<div class="tr">
			<div class="br">
				<div class="bl"><div>
				<form method="post" action = "javascript: authorize_full();">
					<h3 style="margin-bottom:5px;"><?php echo lang('enter'); ?></h3>
					<div id="main_en_center">
						<div id="m_reg_box"></div>
						<div id="m_reg_box_1">
							<form id="login_form"  method="post" name="login_form">
								<input id="from_where" name="from_where" type="hidden" value = "<?=$from_where?>" />
								<div id="m_reg_nik_place" align="left"><span class="ent_txt_b"><?php echo lang('login'); ?></span></div>
								<div id="m_reg_ent_area" style="margin-bottom:5px;">
									<input class="i" style = "width:200px;" id="login" name="login" type="text" />
								</div>
								<div id="m_reg_pass_place" align="left"><span class="ent_txt_b"><?php echo lang('password'); ?></span></div>
								<div id="m_reg_pass_area">
									<input class="i" style = "width:200px;" name="assword" id="password" type="password" />
								</div>
								<div id="m_reg_butt" style = "margin-top:10px">
									<input name="" type="image" src="<?=static_url()?>images/but_ok.gif" style = "cursor: default;" onclick="javascript: authorize_full();"/>				
									<br><br>
								</div>
							</form>
							<span class="ent_txt" id='login_feedback' style = "color:#8BC53F;"><em></em></span>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div></div></div>
</div>