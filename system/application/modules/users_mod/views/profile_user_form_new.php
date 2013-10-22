<form method="post" id="user_form" name="user_form" action="<?=base_url()?>profile/save" onsubmit="return CheckPassword('#user_pwd', '#user_cfm');">
	<input type="hidden" name="action" value="profile_attrib_edit" />
	<input type="hidden" name="user_id" value="<?php echo $user->user_id; ?>" />
	<h2>
		<strong><?=$user->login?></strong>. <?= lang('user_profile'); ?>
	</h2>
	<div style="color:#86BC3F;font-weight:bold;margin-bottom:10px;margin-top:-7px;" > 
		<?php if($this->db_session->flashdata('message')) echo $this->db_session->flashdata('message'); ?>
	</div>
	<!-- b:NEW VERSTKA -->
	<table class="registration">
		<tbody>
			<tr><!-- b:field name -->
				<td class="lCol">
					<?= lang("user_nic-fio"); ?>
					<input id="user_login" class="i" name="user_login" type="text"  value="<?php echo $user->login?>" disabled style="background-color:#CCCCCC;" />
				</td>
				<td>
					<?php
					if (!empty($errors))
					foreach($errors as $error):
						?>
						<div class="error_mes" style="margin-left:17px"><?=$error?></div>
					<?php endforeach;?>
				</td>
			</tr><!-- e:field name -->	
			<tr><!-- b:field email -->
				<td class="lCol">
					<?= lang("user_email"); ?> *
					<input id="email" class="i" name="email" type="text"  value="<?php echo $user->email?>" disabled="disabled"/>
				</td>
			</tr><!-- e:field email -->	
			<tr>
				<td class="lCol">
					<?= lang("user_cur_psw"); ?> *
					<input id="user_pwd_cur" class="i" name="cur_psw" type="password"  value="" />
				</td>
			</tr>
				
			<tr><!-- b:field psw -->
				<td class="lCol">
					<?= lang("user_new_psw"); ?> *
					<input id="user_pwd" class="i" name="new_psw" type="password"  value="" />
				</td>
			</tr><!-- e:field psw -->
			<tr> <!-- b:field psw_confirm -->
				<td class="lCol">
					<?= lang("user_confirm_psw"); ?> *
					<input id="user_cfm" class="i" type="password" name="confirm_psw"  onblur="checkPsw();"/>
				</td>
			</tr><!-- e:field psw_confirm -->
			<tr> <!-- b:field birfday -->
				<td class="lCol">
					<?= lang("user_birthdate"); ?>
					<div>
						<select class="s1" style="width:75px;" name="birth_day">
							<option value="0"><?= lang("user_birth_day"); ?></option>
							<?php for($i=1;$i<32;$i++){?>
							<option value="<?php echo $i;?>" <?php if($i==$user->birth_day) echo "selected";?>><?php echo $i;?></option>
							<?php } ?>
						</select>
						<select class="s1" style="width:105px;" name="birth_month">
							<option value="0"><?= lang("user_birth_month"); ?></option>
							<?php for($i=1;$i<13;$i++){?>
							<option value="<?php echo $i;?>" <?php if($i==$user->birth_month) echo "selected";?>><?php echo lang("month_".$i);?></option>
							<?php } ?>
						</select>
						<select class="s2" style="width:75px;" name="birth_year">
							<option value="0"><?= lang("user_birth_year"); ?></option>
							<?php
							$min_year = date( 'Y' ) - 93; $max_year = date( 'Y' ) - 7;
							for($i=$min_year;$i<$max_year;$i++){
							?>
							<option value="<?php echo $i;?>" <?php if($i==$user->birth_year) echo "selected";?>><?php echo $i;?></option>
							<?php } ?>
						</select>
					</div>
				</td>
			</tr><!-- e:field birfday -->
			<tr> <!-- b:field country -->
				<td  class="lCol">
					<?= lang('user_country'); ?>
					<div id="div_country">
						<select  class="s3"  name="country" id="country_list" onchange="javascript:ajax_fill_region(this.value);">
							<option value=0><?php echo  lang('choose_country'); ?></option>
							<?php if(isset($countries) && !empty($countries)) { foreach ($countries as $country) : ?>
							<option value="<?php echo $country->id; ?>" <?php if($country->id==$user->country_id) echo "selected";?>><?php echo $country->country; ?></option>
							<?php endforeach; } ?>
						</select>
					</div>
				</td>
			</tr><!-- e:field country -->
			<tr> <!-- b:field region -->
				<td  class="lCol">
					<?= lang('user_region'); ?>
					<div id="div_region">
						<select class="s3" name="region" id="region_list" maxlength="30" onchange="javascript:ajax_fill_city(this.value);">
							<option value=0><?php echo  lang('choose_region'); ?></option>
							<?php if(isset($regions) && !empty($regions)) { foreach ($regions as $region) : ?>
							<option value="<?php echo $region->id; ?>" <?php if($region->id==$user->region_id) echo "selected";?>><?php echo $region->region; ?></option>
							<?php endforeach; } ?>
						</select>
					</div>
				</td>
			</tr> <!-- e:field region -->
			<tr > <!-- b:field city -->
				<td class="lCol">
					<?= lang('user_city'); ?>
					<div id="div_city">
						<select class="s3" id="city_list" name="city"  >
							<option value=0><?php echo  lang('choose_city'); ?></option>
							<?php if(isset($cities) && !empty($cities)) { foreach ($cities as $city) : ?>
							<option value="<?php echo $city->id; ?>" <?php if($city->id==$user->city_id) echo "selected";?>><?php echo $city->city; ?></option>
							<?php endforeach; } ?>
						</select>
					</div>
				</td>
			</tr> <!-- e:field city -->
			<tr>  <!-- e:field about -->
				<td class="lCol">
					<?= lang('user_info'); ?>
					<textarea id="user_about"  name="about" ><?php echo $user->about; ?></textarea>
				</td>
			</tr> <!-- e:field about -->
			<tr>  <!-- b:field interes -->
				<td class="lCol">
					<?= lang('user_interests'); ?>
					<textarea id="user_interests" name="interests" ><?php echo $user->interests; ?></textarea>
				</td>
			</tr> <!-- e:field interes -->
           	<tr>  <!-- b:field interes -->
				<td class="lCol">
                   <input type="checkbox" name="comment_can" <?php if (!empty($user->comment_can)) echo "checked='checked'" ?> />
                   <?= lang('comment_can'); ?><br><br>
				</td>
			</tr> <!-- e:field interes -->
			<tr>  <!-- b:controll btn -->
				<td class="lCol">
					<input type="button" class="button" value="<?= lang('cancel'); ?>" />
					<input class="button"  type="submit" name="" value="ok"/>			
				</td>
			</tr> <!-- e:controll btn  -->
		</tbody>
	</table>
	<!-- e:NEW VERSTKA -->
	<!-- OLD -->
</form>