<?php
 //--Photo one img block


?>
<script type="text/javascript">
function open_window(link,w,h) {
		var win = "width="+w+",height="+h+",menubar=no,location=no,resizable=yes,scrollbars=yes";
		newWin = window.open(link,"newWin",win);
		newWin.focus();
}
function outCatName() {
 lst=document.getElementById("pp_category"); 
 document.write(lst.options[lst.selectedIndex].text);
}
</script>
<?php  //if there is no photo
if (isset ($error_forbidden))
    echo $error_forbidden;
else
{
?>
<?php
if (isset($password) && $password == false) 
{
?>
    <div align="center">
    <?php if (isset ($pwd_wrong_mess))  
		      echo '<h3>'.lang('wrong_pwd'). '</h3>'; 
	?>
       <div id="box_kod" class="nifty">
		      <div align="left">
		        <div class="place_pwd_txt"><span class="m_zag_txt"><strong><?php echo lang('enter_pwd'); ?></strong></span></div>
		        <div class="place_ser_otsup_txt_sp">
                <form method="post" name="album_protected" id="album_protected" >
		        <input type="password" name="photo_password" id="photo_password" />
                <div class="place_ok_otstup">
		        <input class="ent_butt" type="submit" name="protected" value=""/> 	        
		        </div>
		        </form>
			</div>
			</div>		      
		    </div><!-- end box kod-->
    </div>
<?php
} else {
?>
<div class="listing">
   <a href="<?= $prev; ?>" class="larr" title="Назад"><!-- --></a>
   <a href="<?= $next; ?>" class="rarr" title="Вперед"><!-- --></a>
</div>
<div class="bigThumb" style="cursor: pointer; cursor: hand;">

    <div class="options">

    <?php if ($user_id == $photo->user_id)  : ?>
       <a  id="call_prop_<?= $photo->photo_id; ?>" href="javascript:ShowPhotoProp(<?= $photo->photo_id; ?>);" >
         <img src="<?= static_url(); ?>images/ic_edit.gif" width="12px" height="12px" title="<?= lang('photo_prop_title'); ?>" />
        </a>
        <a href="javascript:open_window('<?php echo $photo->src_lg; ?>')">
         <img src="<?= static_url(); ?>images/ic_full.png" title="<?= lang('photo_real'); ?>" width="18px" height="18px" />
        </a>
    <?php else: ?>
       <a href="javascript:open_window('<?php echo $photo->src_lg; ?>')">
         <img src="<?= static_url(); ?>images/ic_full.png" title="<?= lang('photo_real'); ?>" width="18px" height="18px" />
        </a>
        <br>
    <?php endif; ?>
    </div>

    <!--<img src="<?= static_url(); ?>data/_big_pic.gif" class="thumb" />   -->
    <?php if ($p_min ==true): ?>
       <div class="bigThumb forFlashBox" onclick="javascript:open_window('<?php echo $photo->src_lg; ?>')">
        <div id="fl_over_id" class="flashBox">
               <?php echo $fl_cont_html;  ?>
        </div>
       <div class="helper"/></div>
      </div>
    <?php else: ?>    
       <?php if ($land) :?>
            <div id="image_box_odf_land" style="background:white; width:<?=$w_fon_w;?>px;height:<?=$w_fon_h;?>px; cursor: pointer; cursor: hand;" onclick="javascript:open_window('<?php echo $photo->src_lg; ?>')">
            <div id="fl_over_id" style="padding-top:<?=$pad_top;?>px; cursor: pointer; cursor: hand;">
        <?php else :?>
            <div id="image_box_odf" style="background:white; width:<?=$w_fon_w;?>px;height:<?=$w_fon_h;?>px; cursor: pointer; cursor: hand;" onclick="javascript:open_window('<?php echo $photo->src_lg; ?>')">
            <div id="fl_over_id" style="padding-left:<?=$pad_left;?>px; cursor: pointer; cursor: hand;">
    <?php endif;?>
        <?php echo $fl_cont_html;  ?>
            </div></div>
    <?php endif;?>
<?php
 if ($my) {
   // include(MODBASE.'gallery_mod/views/photo_prop.php');
   echo $photo_prop_html;
   }
?>
</div>

<?php echo $fl_cont_js;  ?>
<div class="roundedBlock"><div class="tr"><div class="br">
 <div class="bl" id="bl_under">
  <img id="pinpix_pin" style="position:absolute;top:40px;right:50px;width:60px;height:60px;" src="<?= static_url(); ?>images/pinpix_pin.jpg" />
		<div class="ratingStats">
			<h2 style="display:inline"><b><?= lang('give_rate_txt'); ?></b></h2><span id="div_vote_message_id<?= $photo->photo_id; ?>"></span><br><br>
			<ul>
                <li title="<?= lang('ico_razdel_txt'); ?>"  class="razdel" id="razdel_<?= $photo->photo_id; ?>"><span><?= lang('ico_razdel_txt'); ?>: </span><b><script language="JavaScript" type="text/javascript">outCatName();</script></b></li>
                <li title="<?= lang('ico_votes_txt'); ?>"  class="rating" id="rating_balls_<?= $photo->photo_id; ?>"><span><?= lang('ico_votes_txt'); ?>: </span><b><?= $vote_cnt; ?></b></li>
				<li title="<?= lang('ico_dt_added_txt'); ?>" class="date"><span><?= lang('ico_dt_added_txt'); ?>: </span><b><?= dateHuman_str($photo->date_added); ?></b></li>
				<li title="<?= lang('ico_comments_txt'); ?>" class="comments"><span><?= lang('ico_comments_txt'); ?>: </span><b><?= $coments_count; ?></b></li>
				<li title="<?= lang('ico_see_txt'); ?>" class="views"><span><?= lang('ico_see_txt'); ?>: </span><b><?= $see_cnt_photo; ?></b></li>
			</ul>
			<b>
            <?php if (empty($user_vote_cnt)) :?>
             <a id="vote_action<?= $photo->photo_id; ?>" href="" title="Голосовать за фото" class="addPin"><?= lang('stick_pin_txt'); ?></a>
             <?php endif; ?>
            </b>
            <div class=clear></div>
		</div>
	</div>
 </div>
 </div>
    <script language="JavaScript" type="text/javascript">
    <!--
    var month_obj={"январь":"января","февраль":"февраля","март":"марта","апрель":"апреля","май":"мая","июнь":"июня","август":"августа","сентябрь":"сентября","октябрь":"октября","ноябрь":"ноября","декабрь":"декабря"};
    $(document).ready(function() {
       m_photo=month_obj[$("#month").html()];
       if (m_photo != ""){$("#month").html(m_photo);}
    }
    )
    //-->
    </script>
</div>
<?= $vote_action; ?>

<?php } }?>