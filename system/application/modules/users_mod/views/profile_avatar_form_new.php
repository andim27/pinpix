<?php
//--форма загрузки аватара
?>
<script language="JavaScript" type="text/javascript" >
initAvatarUserUpload();
</script>
<div class="roundedBlock">
    <div class="tr"><div class="br"><div class="bl">
					<div class="userPics">
						<h2><strong><?=$user->login?></strong>. <?= lang("pict_avatar"); ?></h2>
						<img id="user_avatar_img" src="<?php echo $user->avatar_src; ?>" class="current" /> <a href="javascript:showPredef();" class="show"><?= lang('ready_avatar'); ?></a>
                        <div id="div_avatar_predef_message_id" class="ok_mes" style="display:none" ></div>
                        <div class="list" id="zagrug_image">
							<div class="img_podgruz"><img id="av_predef_id_1" src="<?= static_url(); ?>images/predef_avatars/a_1.jpg" onclick="javascript:avatarPredefChoice(1);" /></div>
                            <div class="img_podgruz"><img id="av_predef_id_2" src="<?= static_url(); ?>images/predef_avatars/a_2.jpg" onclick="javascript:avatarPredefChoice(2);" /></div>
                            <div class="img_podgruz"><img id="av_predef_id_3" src="<?= static_url(); ?>images/predef_avatars/a_3.jpg" onclick="javascript:avatarPredefChoice(3);" /></div>
							<div class="img_podgruz"><img id="av_predef_id_4" src="<?= static_url(); ?>images/predef_avatars/a_4.jpg" onclick="javascript:avatarPredefChoice(4);" /></div>
                            <div class="img_podgruz"><img id="av_predef_id_5" src="<?= static_url(); ?>images/predef_avatars/a_5.jpg" onclick="javascript:avatarPredefChoice(5);" /></div>
                            <div class="img_podgruz"><img id="av_predef_id_6" src="<?= static_url(); ?>images/predef_avatars/a_6.jpg" onclick="javascript:avatarPredefChoice(6);" /></div>
							<div class="img_podgruz"><img id="av_predef_id_7" src="<?= static_url(); ?>images/predef_avatars/a_7.jpg" onclick="javascript:avatarPredefChoice(7);" /></div>
                            <div class="img_podgruz"><img id="av_predef_id_8" src="<?= static_url(); ?>images/predef_avatars/a_8.jpg" onclick="javascript:avatarPredefChoice(8);" /></div>
                            <div class="img_podgruz"><img id="av_predef_id_9" src="<?= static_url(); ?>images/predef_avatars/a_9.jpg" onclick="javascript:avatarPredefChoice(9);" /></div>
							<div class="img_podgruz"><img id="av_predef_id_10" src="<?= static_url(); ?>images/predef_avatars/a_10.jpg" onclick="javascript:avatarPredefChoice(10);" /></div>
                            <div class="img_podgruz"><img id="av_predef_id_11" src="<?= static_url(); ?>images/predef_avatars/a_11.jpg" onclick="javascript:avatarPredefChoice(11);" /></div>
                            <div class="img_podgruz"><img id="av_predef_id_12" src="<?= static_url(); ?>images/predef_avatars/a_12.jpg" onclick="javascript:avatarPredefChoice(12);" /></div>
                            <form method="post" id="<?php echo $avatar_predef_form['id']; ?>">
                              <input type="button" class="button" onclick="<?php echo $avatar_predef_save_url; ?>" value="      ok      " />
                              <input type="hidden" name="action" value="predefavatar" />
                              <input type="hidden" name="av_predef" id="av_predef_id" value=1 />
                            </form>
						</div>
						<div class="uploadNew" >
							<?= lang('load_avatar'); ?><br>
                            <?= lang('max_s_avatar');?> (640 x 480px)<br>
                            <?= lang('max_v_avatar');?> (100K)
                            <div id="div_avatar_pm"  style="display:none"></div>
                            <div class="uploadBar">
                                <div id="progress_div" style="position:absolute;left:0px;top:0px;width:1px;height:17px;background:#777777" ></div>
                                <div id="f_name_div" style="display:none;z-index:100;position:absolute;left:10px;top:0px;"></div>
                            </div>
                            <form method="post" ENCTYPE="multipart/form-data" name="avatar_personal_form_name" id="<?php echo $avatar_personal_form['id']; ?>">
                                <input type="hidden" name="action" value="personalavatar"/>


                                 <div id="uploadify_div" class="button" style="width:70px;height:20px;padding:0px;">
                                    <input id="user_avatar" style="display:none;"  class="uploadBar" name="user_avatar" size="10"  type="file"/>
                                 </div>
    <!--                           <input  class="button" type="button" value="Загрузить" onclick="<?php echo $avatar_personal_save_url; ?>"  />   -->
                               <span style="display:none" id="message_id"></span>

                               </form>
						</div>
					</div>
	</div></div></div>
</div>
