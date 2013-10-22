<?php
function sort_ord($sort_order, $new_sort_type, $sort_type)
{
	if ($new_sort_type == $sort_type) 
	{
		if ((empty ($sort_order))||($sort_order == "a"))	
	    	$sort_order = "d";
		else
	    	$sort_order = "a";
	}
	else 
	{
		if ($new_sort_type!=1) //po alfavity
			$sort_order = "d";
		else 
			$sort_order = "a";
	}
	return   $sort_order;
}

if (empty ($sort_order)) $sort_order = "d" ;
if (empty ($sort_type)) $sort_type=1;

?>

<div class="middCol">
	<div class="titleFilter">
		<strong><a href="<?php echo base_url()?>" class="zag_big_txt" >Pinpix</a>
		</strong> / <a href="<?php echo base_url().'catalog/category/'. $cat_id ?>"><?=$category_name?> </a> 
	</div>
				  
    <!-- Start sort_menu_box-->
    <?php      if(!empty($photos)) { ?> 
		<div class="titleFilter">
		<?php if ($sort_type == 1) echo '<strong>'?>
			<a href="<?php echo base_url().'catalog/category/'. $cat_id . '/1/'. sort_ord($sort_order, 1, $sort_type); ?>"><?php echo lang('comp_sort_by_name'); ?> </a>
		<?php if ($sort_type == 1) echo '</strong>'?>
		<?php if ($sort_type == 2) echo '<strong>'?>
			| <a href="<?php echo base_url().'catalog/category/'. $cat_id . '/2/'. sort_ord($sort_order, 2, $sort_type); ?>"><?php echo lang('comp_sort_by_date'); ?></a> 
		<?php if ($sort_type == 2) echo '</strong>'?>
		<?php if ($sort_type == 3) echo '<strong>'?>
			| <a href="<?php echo base_url().'catalog/category/'. $cat_id. '/3/'. sort_ord($sort_order, 3, $sort_type); ?>"><?php echo lang('comp_sort_by_rate'); ?></a>
		<?php if ($sort_type == 3) echo '</strong>'?>
		</div>				
    <?php } //if(!empty($photos)) ?>
  
 <!-- Start Image box-->
   <div class="picsHolder">
      <?php
      if(!empty($photos)) {
          if(isset($photos) && !empty($photos)) 
          {          	
              foreach ($photos as $photo) :
            // if ( (isset($password) && $password != true) && ($photo->view_allowed == 0) && ($photo->user_id != $this->db_session->userdata('user_id') ) )
             	
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
             <i class="pin" title="<?= lang("rate"); ?>"> <?php echo (empty($photo->vote)? 0 : $photo->vote) ?></i>
             <!-- <li class="li_time"> <?php //echo date("d.m.y", strtotime($photo->date_added)) ?> </li> -->
             <!-- <li class="li_com"> <?php //echo $coms[$i]?> </li> -->
             <i class="comment" title="<?= lang("user_comments"); ?>" > <?php echo (empty($photo->comcnt)? 0 : $photo->comcnt) ?></i>
        </div>
            <a href="<?php echo base_url(). 'profile/view/' . $photo->user_id ?>" class="author">  <?=$photo->login?> </a>
            <p><?=((strlen($photo->title) > 100)? substr($photo->title, 0, 97).'...': $photo->title);?>  </p>
        
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


</div>
