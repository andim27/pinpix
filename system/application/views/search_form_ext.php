<script type="text/javascript">
ShowSelCat = function() {
	$('#cat_mult').css('display', "block");
	$('#cat_mult').css('z-index', 999);
	$('#cat_mult').focus();
}
ClickSelCat = function(eventObject){
	var selected_opt = '';
	for(var i=0, scount=this.options.length; i<scount; i++) {
		var text = this.options[i].text;
		if (this.options[i].selected) {
			selected_opt +=", "+text;
		}
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
<form  action="<?=base_url();?>search/searching" style="padding:0px; margin:0px;" name="search_form" method="post">
<div style="float:left; width:308px; text-align:left;">
	
	<div style="margin:38px 0 0 17px;"><span class="ent_txt"><?php echo lang('search_key_word'); ?></span></div>
	<input class="search_area_sec" name="keyword" type="text" />

    <div style="margin-left:17px;"><span class="ent_txt"><?php echo lang('search_in_section'); ?></span></div>
    <div id="SelPopUper" class="select_area">
    <div id="cat_mult_choise"></div>
    </div>
    <select id="cat_mult" multiple="multiple" class="select_area_right_box" name="categories[]" style="width:290px;">
   	<option value=""></option>
    	<?php
    		$categories = modules::run('catalog_mod/catalog_ctr/get_tree');
    		foreach ($categories as $cat){
				if($cat->id == 1) continue;
    			if(empty($cat->parent_id)) continue;
    	?>
    	<option value="<?=$cat->id?>"><?=$cat->name?></option>
    	<?php
    		}
    	?>
    </select>
	<div style="margin-left:15px;">
      	<input class="ent_butt" name="search" type="submit" value="" />
	</div>
</div>
</form>
