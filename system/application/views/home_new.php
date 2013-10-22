	<div class="middCol">
		<div class="titleFilter">
			<a <?php if($sort_array['date']['sort_by'] == true) echo 'class= "sort_type_bold"'; ?> href="<?php echo base_url() ?>main/view/date/<?=$sort_array['date']['sort_as']?>"><?php echo lang('comp_sort_by_date'); ?> 
			<?php if($sort_array['date']['sort_by'] == true) { ?><img src="<?php if($sort_array['date']['sort_as'] == 'asc') echo static_url()."images/m_main_arrow.gif"; else echo static_url()."images/m_main_arrow_desc.gif"; ?>" /><?php } ?>
			</a> |
			<a <?php if($sort_array['title']['sort_by'] == true) echo 'class= "sort_type_bold"'; ?> href="<?php echo base_url() ?>main/view/title/<?=$sort_array['title']['sort_as']?>">
			<?php
				if($sort_array['title']['sort_by'] == false) echo lang('comp_sort_by_name'); 
				else {
					if($sort_array['title']['sort_as'] == 'asc') echo lang('comp_sort_by_name_desc'); 
					else echo lang('comp_sort_by_name');
				}  
			?>
			<?php if($sort_array['title']['sort_by'] == true) { ?><img src="<?php if($sort_array['title']['sort_as'] == 'asc') echo static_url()."images/m_main_arrow.gif"; else echo static_url()."images/m_main_arrow_desc.gif"; ?>" /><?php } ?>
			</a> |
			<a <?php if($sort_array['popular']['sort_by'] == true) echo 'class= "sort_type_bold"'; ?> href="<?php echo base_url() ?>main/view/popular/<?=$sort_array['popular']['sort_as']?>"><?php echo lang('comp_sort_by_rate'); ?> 
			<?php if($sort_array['popular']['sort_by'] == true) { ?><img src="<?php if($sort_array['popular']['sort_as'] == 'asc') echo static_url()."images/m_main_arrow.gif"; else echo static_url()."images/m_main_arrow_desc.gif"; ?>" /><?php } ?>
			</a>
		</div>				

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
             <i class="views" title="<?= lang("user_views"); ?>">  <?php echo (empty($photo->see_cnt)? 0 : $photo->see_cnt) ?></i>
             <i class="pin" title="<?= lang("rate"); ?>">  <?php echo (empty($photo->num_votes)? 0 : $photo->num_votes) ?></i>
             <i class="comment" title="<?= lang("user_comments"); ?>"> <?php echo (empty($photo->comcnt)? 0 : $photo->comcnt) ?></i>             
        </div>
             <a href="<?php echo base_url(). 'profile/view/' . $photo->user_id ?>" class="author">  <?=$photo->login?> </a>
             <p><?=((strlen($photo->title) > 100)? substr($photo->title, 0, 97).'...': $photo->title);?>  </p>        
      </div>
        <?php
	            endforeach;
	        }
        }
        ?>
    </div> <!-- <div id = "pic_holder">  -->
 
  <?php //$this->fl->setImg($urlImg_1,"fl_container"); ?>
  <!-- End Image box-->
<!-- Start pagination -->
<?php
if (!empty($paginate_args)) echo paginate($paginate_args);
?>

</div>   
