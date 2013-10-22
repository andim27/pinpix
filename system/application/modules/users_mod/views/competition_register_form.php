<?php
	modules::load_file('ajax_requests_js.php',APPPATH.'modules/users_mod/scripts/');
	 
	$lng = $this->db_session->userdata('user_lang');
	$this->lang->load('bibb',$lng);
?>
<script type="text/javascript">
jQuery().ready(function(){
	jQuery("#country_list").change(function(){
		$('#div_city').removeAttr("disabled");
	});
});

</script>
<a name='reg'></a>
<div class="roundedBlock">
	<div class="tr">
		<div class="br">
			<div class="bl">
				<div class="reg_intro">
					<h3 style="color:#8BC53F; font-weight:bold;"><?=lang('bibb_register')?></h3>
						<form method="post" id="user_form" name="user_form" action = "javascript: register_full();">
							<input id="from_where" name="from_where" type="hidden" value = "<?=$from_where?>" />
							<div style="float:right;"><span style="float:right;margin-top:-15px;">* - <?=lang('req')?></span></div>
							<div style="width:280px; margin:10px auto 0;">								
								<em><div id = 'error_msg'></div></em>
								<table class="registration">
									<tr>
										<td><?=lang('user_login')?> * <input name="username" id="username" type="text" class="i" /></td>
									</tr>
									<tr>
										<td><?=lang('user_email')?> * <input name="email" id = "email" type="text" class="i" /></td>
									</tr>
									<tr>
										<td><?=lang('user_password')?> * <input name="pass1" id = "pass1" type="password" class="i" /></td>
									</tr>
									<tr>
										<td><?=lang('user_password_confirm')?> * <input name="pass2" id = "pass2" type="password" class="i" /></td>
									</tr>
									<tr>
										<td><?=lang('user_birtdate')?>
											<div>								
												<select class="s1" name="birth_day" id="birth_day">
													<option value="1">01</option>
													<?php for($i=2;$i<32;$i++){?>
													<option value="<?php echo $i;?>" ><?php if ($i<10) echo '0'; echo $i;?></option>
													<?php } ?>
												</select>
												<select class="s1" name="birth_month" id = "birth_month">
													<option value="1">01</option>
													<?php for($i=2;$i<13;$i++){?>
													<option value="<?php echo $i;?>" ><?php if ($i<10) echo '0'; echo $i;?></option>
													<?php } ?>
												</select>
												<select class="s1" name="birth_year" id = "birth_year">		
													<?php
													$min_year = date( 'Y' ) - 90; $max_year = date( 'Y' ) /*- 7*/; ?>
													<option value="<?=$max_year?>"><?=$max_year?></option>
													<?php for($i=$max_year-1; $i>$min_year; $i--){
													?>
													<option value="<?php echo $i;?>" ><?php echo $i;?></option>
													<?php } ?>
												</select>
											</div>
											</td>
										</tr>
										<tr>
											<td>
												<div><?=lang('choose_country')?></div>
												<div id="div_country" style="float:left;">
													<select name="country" id="country_list" maxlength="30" onchange="javascript:get_regions();" style="width:190px;">
														<option value=0><?=lang('chouse_contry')?></option>
														<?php foreach ($countries as $country) : ?>
														<option value="<?php echo $country->id; ?>"><?php echo $country->country; ?></option>
														<?php endforeach; ?>
													</select>
												</div>
												<div style="float:left;left:5px;position:relative;top:3px;display:none;" id="ajax_loader_country"><img src="<?=static_url()?>images/add-note-loader.gif" /></div>
											</td>
										</tr>
										<tr>
											<td>
												<div><?=lang('choose_region')?></div>
												<div id="div_region" style="float:left;">
													<select disabled id="region_list" name="region"  maxlength="30" onchange="javascript:get_cities();" style="width:190px;">
														<option value=0><?=lang('choose_region')?></option>														
													</select>
												</div>
												<div style="float:left;left:5px;position:relative;top:3px;display:none;" id="ajax_loader_region"><img src="<?=static_url()?>images/add-note-loader.gif" /></div>
											</td>
										</tr>
										<tr>
											<td>
												<div><?=lang('choose_city')?></div>
												<div id="div_city" style="float:left;">
													<select disabled id="city_list" name="city"  maxlength="30" style="width:190px;">
														<option value=0><?=lang('choose_city')?></option>
													</select>
												</div>
											</td>
										</tr>
										<tr>
											<td><?=lang('user_info')?>
												<textarea name="userinfo" id="userinfo" cols="" rows=""></textarea>
											</td>
										</tr>
										<tr>
											<td>
												<input type="button" class="button" value="<?=lang('cancel')?>" />
												<input type="submit" class="button" value="<?=lang('registration')?>" />
											</td>													
										</tr>
									</table>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>