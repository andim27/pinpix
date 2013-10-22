<script type="text/javascript">
ShowSelCat = function() {
	$('#cat_mult').css('display', "block");
	$('#cat_mult').css('z-index', 999);
	$('#cat_mult').focus();
}
ClickSelCat = function(eventObject){
	var selected_opt = '';
	var t = 0;
	for(var i=0, scount=this.options.length; i<scount; i++) {
		var text = this.options[i].text;
		if (this.options[i].selected) {
			selected_opt +=", "+text;
			t++;
		}
		if (t==5) break;
	}
	$('#cat_mult_choise').html(selected_opt.substr(1));
}
BlurSelCat = function(el) {
	$('#cat_mult').css('display', "none");
	$('#cat_mult').css('z-index', -999);
}
$(function(){
	$("form[name=search_form]").each(function(){
		this.reset();
	});
	$('#SelPopUper').click(ShowSelCat);
	$('#cat_mult').change(ClickSelCat);
	$('#cat_mult').blur(BlurSelCat);
});
</script>



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
		
<div class="middCol">
		<div class="roundedBlock"><div class="tr"><div class="br"><div class="bl">
			<div class="searchBlock">
	
     				<h2><?=lang('search')?></h2> 
      				<div id="search_body_right_box">
      				<form action="<?php echo base_url(). 'search/searching';?>" name="asearch_form" method="post">
	  			
	  					<?=lang('search_key_word')?>
						<input name="keyword" type="text" class="is" />
						
						<?=lang('search_in_section')?>
                    
                    <div id="SelPopUper" class="select_area_right_box">
                    <div id="cat_mult_choise" style = "color:black; overflow:hidden"></div>
                    </div>
                    
                    <select id="cat_mult" multiple="multiple" class="select_area_right_box" name="categories[]">
							<option value="0"></option>
							<?php foreach($allcategories as $category):?>
				  			<option value="<?php echo $category->id;?>">
				  			<?php echo $category->name?>
							</option>
							<?php endforeach;?>
					</select>
					</form>				
					<a href="javascript: document.asearch_form.submit();" class="go"><?=lang('search')?></a>
      			</div>  				
  			
  			</div>
		</div></div></div></div>		
						
		<div class="titleFilter">
			<strong><a href="#" > <?=lang('search')?>  </a>
			</strong> / <a href="#"><?=$keywords?> </a> 
		</div>
				  
    <!-- Start sort_menu_box-->
	<?php
	if(isset($search_result)&& (!empty ($search_result))) {
	?>
   <div class="middCol">
		<div class="titleFilter">
		<?php if ($sort_type == 1) echo '<strong>'?>
			<a href="<?php echo base_url().'search/searching/1/'. sort_ord($sort_order); ?>"><?php echo lang('comp_sort_by_name'); ?> </a>
		<?php if ($sort_type == 1) echo '</strong>'?>
		<?php if ($sort_type == 2) echo '<strong>'?>
			| <a href="<?php echo base_url().'search/searching/2/'. sort_ord($sort_order); ?>"><?php echo lang('comp_sort_by_date'); ?></a> 
		<?php if ($sort_type == 2) echo '</strong>'?>
		<?php if ($sort_type == 3) echo '<strong>'?>
			| <a href="<?php echo base_url().'search/searching/3/'.sort_ord($sort_order); ?>"><?php echo lang('comp_sort_by_rate'); ?></a>
		<?php if ($sort_type == 3) echo '</strong>'?>
		</div>				
	</div> 

  
 <!-- Start Image box-->
   <div class="picsHolder">
      <?php
              foreach ($search_result as $photo)  :
              if (empty ($photo->view_password))
                  $urlImg = photo_location().date("m", strtotime($photo->date_added))."/".$photo->photo_id."-sm".$photo->extension;
              else
                  $urlImg = photo_location()."/uploads/photos/lock.jpg";
              if (empty( $urlImg_1 )) {
                  $urlImg_1 =$urlImg ;
                  $urlImg_1=str_replace ( "-sm", "-md" ,$urlImg_1 );
              }
      ?>
     <div class="item">
       
         <div id = "white_space" >
          <?php   if ($photo->land ) :  ?>
          <!-- b:andMak Land width 136-->
          <div class="gor_img"> <a onfocus="this.blur()" class = "pic" href="<?php echo base_url() . 'photo/view/' . $photo->photo_id?>"  > <img alt="<?=$photo->title?>" border="0" src="<?=$urlImg?>" width="136" height="109" /> </a> </div>
          <!-- e:andMak Land -->
          <?php else : ?>
          <div class="vert_img"> <a onfocus="this.blur()" class = "pic"  href="<?php echo base_url() . 'photo/view/' . $photo->photo_id?>"  > <img alt="<?=$photo->title?>" border="0" src="<?=$urlImg?>" width="104" height="138" /> </a> </div>
          <?php endif;  ?>
        </div> <!--  <div id = "white_space" >   -->

         <div class="info" style="margin-top: 7px;">
             <i class="views" title="<?= lang("user_views"); ?>">  <?php echo (empty($photo->see_cnt)? 0 : $photo->see_cnt) ?></i>
             <i class="pin" title="<?= lang("rate"); ?>">  <?php echo (empty($photo->num_votes)? 0 : $photo->num_votes) ?></i>
             <i class="comment" title="<?= lang("user_comments"); ?>"> <?php echo (empty($photo->comcnt)? 0 : $photo->comcnt) ?></i>
        </div>
            <a href="<?php echo base_url(). '/profile/view/' . $photo->user_id ?>" class="author"> <?= $photo->login ?> </a>
            <p><?=((strlen($photo->title) > 100)? substr($photo->title, 0, 97).'...': $photo->title);?>  </p>
        
      </div> <!-- <div id = "item">   -->
        <?php
	            $i++;
	            endforeach;	       
        ?>
    </div> <!-- <div id = "pic_holder">  -->
    
    
<?php echo paginate($paginate_args); ?>

<?php 	} 
		else 
		{
		  	echo lang('comp_nophotos');
		}
?>
    <!--End Center main-->
  </div>


</div>


<!-- end #mainContent -->