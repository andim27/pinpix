<?php 
if(!empty($competitions)):
$competition = $competitions;
$comp_photos = $photos;

$num_competition = count($competition);
if(empty($num_competition)) $num_competition = lang('comp_nocomp');

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
		<div class="middCol">
	
		<div class="titleFilter">
			<strong><a href="<?php echo base_url() .  'competition' ?>" class="zag_big_txt" > <?=lang('competition')?></a></strong> 
			/ <a href="#"> <?=$competition->title?> </a>
		</div>
		<div class="titleFilter">
		<?php if ($sort_type == 1) echo '<strong>'?>
			<a href="<?php echo base_url().'competition/view/'.$competition->competition_id.'/1/'. sort_ord($sort_order); ?>"><?php echo lang('comp_sort_by_name'); ?> </a>
		<?php if ($sort_type == 1) echo '</strong>'?>
		<?php if ($sort_type == 2) echo '<strong>'?>
			| <a href="<?php echo base_url().'competition/view/'.$competition->competition_id.'/2/'. sort_ord($sort_order); ?>"><?php echo lang('comp_sort_by_date'); ?></a> 
		<?php if ($sort_type == 2) echo '</strong>'?>
		<?php if ($sort_type == 3) echo '<strong>'?>
			| <a href="<?php echo base_url().'competition/view/'.$competition->competition_id.'/3/'. sort_ord($sort_order); ?>"><?php echo lang('comp_sort_by_rate'); ?></a>
		<?php if ($sort_type == 3) echo '</strong>'?>
		</div>				
	</div>
	
    <?php if(!empty($comp_photos)) { ?>
    <!-- Start Image box-->
    
		<div class="picsHolder">    
		<?php
		   $i = 0;
			foreach ($comp_photos as $photo) {
			              	  
		?>
		 <div class="item">
		       				 
			  <a onfocus="this.blur()" class = "pic" href="<?php echo base_url() . 'photo/view/' . $photo->photo_id?>"> 
			  <img style =" margin-top:<?=$photo->margin_top?>px;
							margin-bottom:<?=$photo->margin_bottom?>px;
							margin-left:<?=$photo->margin_left?>px;
							margin-right:<?=$photo->margin_right?>px;
							overflow:hidden;" 
					alt="<?=$photo->title?>" border="0" src="<?=$photo->urlImg?>" /> 
			 </a> 
		 	  
		 	  <div class="info">
              <i class="pin"> <?= empty ($photo->balls) ? 0 : $photo->balls?></i>
              <!-- <li class="li_time"> <?php //echo date("d.m.y", strtotime($photo->date_added)) ?> </li> --> 
              <!-- <li class="li_com"> <?php //echo $coms[$i]?> </li> -->
              <i class="comment"> <?= empty ($photo->comcnt)? 0 : $photo->comcnt?> </i>
        	  </div>
        	
             <a href="<?php echo base_url(). 'profile/view/' . $photo->user_id ?>" class="author">  <?=$photo->login?> </a>
             <p><?=((strlen($photo->title) > 100)? substr($photo->title, 0, 97).'...': $photo->title);?>  </p>
        
      	</div> <!-- <div id = "item">   -->
        <?php
	            $i++;
        }
        ?>
        
<?php
	if (!empty($paginate_args))
		echo paginate($paginate_args);
} else 
{
  echo lang('comp_nophotos');
}  	
endif; ?>
    <!--End Center main-->

</div>
<!-- end #mainContent -->