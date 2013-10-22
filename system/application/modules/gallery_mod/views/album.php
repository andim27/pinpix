<?php modules::load_file('ajax_requests_js.php',MODBASE.'gallery_mod/scripts/'); ?>
<?php
function sort_ord($sort_order)
{
if ((empty ($sort_order))||($sort_order == "a"))
    $sort_order = "d";
else
    $sort_order = "a";
return   $sort_order;
}

if (empty ($sort_order)) $sort_order = "d" ;
if (empty ($sort_type)) $sort_type=1;

?>
			
<?php
if(isset($password) && $password == false) {
?>

<div id="main_cont_body">
		     <?php if (isset ($pwd_wrong_mess))  
		      echo '<h3>'.lang('wrong_pwd') . '</h3>'; 
			 ?> 
    <div align="center">
       <div id="box_kod" class="nifty">

		      <!-- center-->
		      <div align="left">
		        <div class="place_pwd_txt"><span class="m_zag_txt"><strong><?php echo lang('enter_alb_pwd'); ?></strong></span></div>
		        <div class="place_ser_otsup_txt_sp">
                <form method="post" name="album_protected" id="album_protected" />
		        <input type="password" name="album_password" id="album_password" />
		        </div>
                <div class="place_ok_otstup">
		        <input class="ent_butt" type="submit" name="protected" value=""/>  		            
		        </form>
		        </div>		      
		    </div><!-- end box kod-->
    </div>
</div>

<?php
}   else {   //if album is not private or the correct password was entered
?>

<div class="middCol">
	<div class="titleFilter">
		<strong><a href="<?php echo base_url() .  'profile/view/' .  $album->user_id ?>" class="zag_big_txt" >  <?= modules::run('users_mod/users_ctr/get_name_by_id', $album->user_id);?></a>
		</strong> / <a href="<?php echo base_url() .  'album/view_user_albums/' .  $album->user_id ?>"><?=lang('gallery')?> </a> / <a href="#"><?=$album->title?> </a>
	</div>
				  
    <!-- Start sort_menu_box-->
    <?php      if(!empty($photos)) { ?> 
		<div class="titleFilter">
		<?php if ($sort_type == 1) echo '<strong>'?>
			<a href="<?php echo base_url().'album/view/'. $album->album_id . '/1/'. sort_ord($sort_order); ?>"><?php echo lang('comp_sort_by_name') ; ?> </a>
		<?php if ($sort_type == 1) echo '</strong>'?>
		<?php if ($sort_type == 2) echo '<strong>'?>
			| <a href="<?php echo base_url().'album/view/'. $album->album_id . '/2/'. sort_ord($sort_order); ?>"><?php echo lang('comp_sort_by_date'); ?></a> 
		<?php if ($sort_type == 2) echo '</strong>'?>
		<?php if ($sort_type == 3) echo '<strong>'?>
			| <a href="<?php echo base_url().'album/view/'. $album->album_id . '/3/'. sort_ord($sort_order); ?>"><?php echo lang('comp_sort_by_rate'); ?></a>
		<?php if ($sort_type == 3) echo '</strong>'?>
		</div>				
    <?php } //if(!empty($photos)) ?>
  
 <!-- Start Image box-->
   <div class="picsHolder">
      <?php
      if(!empty($photos)) {
          if(isset($photos) && !empty($photos)) {
          
              foreach ($photos as $photo) :
              
      ?>
     <div class="item">
     
      	 <a onfocus="this.blur()" class = "pic" href="<?php echo base_url() . 'photo/view/' . $photo->photo_id?>"> 
			 <img style =" margin-top:<?=$photo->margin_top?>px;
							margin-bottom:<?=$photo->margin_bottom?>px;
							margin-left:<?=$photo->margin_left?>px;
							margin-right:<?=$photo->margin_right?>px;
                            width:<?=$photo->img_width?>px;
                            height:<?=$photo->img_height?>px;
							overflow:hidden;" 
					alt="<?=$photo->title?>" border="0" src="<?=$photo->urlImg?>" /> 
		</a> 
			
        <div class="info">
             <i class="pin"> <?php echo (empty($photo->vote)? 0 : $photo->vote) ?> </i>
             <!-- <li class="li_time"> <?php //echo date("d.m.y", strtotime($photo->date_added)) ?> </li> --> 
             <!-- <li class="li_com"> <?php //echo $coms[$i]?> </li> -->
              <i class="comment"> <?php echo (empty($photo->comcnt)? 0 : $photo->comcnt) ?></i>
        </div>
            <a href="<?php echo base_url(). 'profile/view/' . $photo->user_id ?>" class="author">  <?= $photo->login ?> </a>
            <p><?=((strlen($photo->title) > 100)? substr($photo->title, 0, 97).'...': $photo->title);?>  </p>
        
        <div class="picOptions">
			<span>
	      		<?php if($my) { ?>
	                <a onclick="delete_photo(<?php echo $photo->photo_id?>);" href="#"><img src="<?=static_url()?>images/ic_close.gif"/></a>
	            <?php } // if my?>         
			</span>
		</div>
			 
      </div> <!-- <div id = "item">   -->
        <?php
	            endforeach;
	        }
        ?>
    </div> <!-- <div id = "pic_holder">  -->
    
    
<?php echo paginate($paginate_args); ?>
    <?php } else {
  		echo lang('comp_nophotos');
  	}
?>
    <!--End Center main-->
  </div>
  <?php
}
?>

</div>
<!-- end #mainContent -->