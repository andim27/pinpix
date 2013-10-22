<?php include("ajax_requests_js.php"); ?>
<script type="text/javascript">
	var __LabelPasswordEmpty = "<?=lang('js_label_PasswordEmpty')?>";
	var __LabelConfirmationEmpty = "<?=lang('js_label_ConfirmationEmpty')?>";
	var __LabelPwsConfDifferent = "<?=lang('js_label_PwsConfDifferent')?>";
</script>
<script type="text/javascript" src="<?php echo static_url(); ?>js/jquery.uploadify.v2.1.0.min.js" ></script>
<div class="righCol">
<!-- B:AVATAR -->
<?php include "profile_avatar_form_new.php"; ?>
<!-- E:AVATAR -->
</div>
<div class="middCol">
<?php if ($wellcom == true) : ?>
     <!-- B:Block wellcom -->
      <div class="roundedBlock">
       <div class="tr">
        <div class="br">
         <div class="bl">
          <h2>
          <strong><?= $user->login; ?></strong>
          , добро пожаловать на фотохостинг
          <strong>PinPix!</strong>
          </h2>
          <div class="OkMan">
          Все окей, пройди по ссылке на главную страницу
          <br/>
          <a href="<?= base_url(); ?>"><?= base_url(); ?></a>
          </div>
         </div>
        </div>
       </div>
      </div>
      <!-- E:Block wellcom -->
      <!-- B:YOU_CAN -->
      <div class="roundedBlock">
        <div class="tr">
        <div class="br">
        <div class="bl">
            <h2>
            <strong><?= $user->login; ?></strong>
            . Ты можешь начать прямо сейчас!
            </h2>
          <div class="userFeaturesDescription">
            <div>
            <img src="<?= static_url(); ?>images/feature_descriptions_01.gif"/>
            Добавлять свои картинки, создавать альбомоы и многое другое
            </div>
            <div>
            <img src="<?= static_url(); ?>images/feature_descriptions_02.gif"/>
            Искать и находить любые картинки
            </div>
            <div>
            <img src="<?= static_url(); ?>images/feature_descriptions_03.gif"/>
            Выставлять рейтинг другим участникам, общаться с ними, писать комментарии и участвовать в конкурсах
            </div>
            <div>
            <img src="<?= static_url(); ?>images/feature_descriptions_04.gif"/>
            Пополнить свой профиль новыми подробностями, и загрзуить свою картинку пользователя прямо на этой странице
            </div>
          </div>
         </div>
         </div>
         </div>
        </div>
      <!-- E:YOU_CAN -->
<?php endif; ?>
      <!-- B:PERSONAL_DATA -->
      <div class="roundedBlock">
        <div class="tr">
           <div class="br">
              <div class="bl">
                  <?php include("profile_user_form_new.php"); ?>
              </div>
           </div>
        </div>
      </div>
      <!-- E:PERSONAL_DATA -->
</div>