<link rel="stylesheet" type="text/css" href="<?=static_url()?>css/ui.all.css">
<script type="text/javascript" src="<?=static_url()?>js/jquery-ui-1.6.custom.min.js"></script>

<script type="text/javascript">
function changeButton(cur, index) {

	sImg = cur.src.substring(cur.src.lastIndexOf('images/')+7);
	if (sImg == 'm_box_plus.gif') {
		cur.src = '<?php echo static_url(); ?>images/m_box_minus.gif';
		document.getElementById('comp_invisible_'+index).style.display = '';
	}
	else {
		cur.src = '<?php echo static_url(); ?>images/m_box_plus.gif';
		document.getElementById('comp_invisible_'+index).style.display = 'none';
	}	
}
var allopened = 0;
function changeAllButtons(amount) {
	if (allopened == 0)
		allopened = 1;
	else
		allopened = 0;
	
	if (allopened == 1)
	{
		document.getElementById ('op-cl').innerHTML = "<?php echo lang ('comp_closeall')?>"	;
		for (var i = 1; i<= amount; i++)
		{
			document.getElementById('comp_invisible_'+i).style.display = '';
			var a = document.getElementById('im_znak_'+i);
			var sImg = a.src.substring(a.src.lastIndexOf('images/')+7);
			if (sImg == 'm_box_plus.gif') {
				a.src = '<?php echo static_url(); ?>images/m_box_minus.gif';			
			}	
		}
	}

	if (allopened == 0)
	{
		document.getElementById ('op-cl').innerHTML = "<?php echo lang ('comp_openall')?>"	;
		for (var i = 1; i<= amount; i++)
		{
		document.getElementById('comp_invisible_'+i).style.display = 'none';
		var a = document.getElementById('im_znak_'+i);
		var sImg = a.src.substring(a.src.lastIndexOf('images/')+7);
		if (sImg == 'm_box_minus.gif') {
			a.src = '<?php echo static_url(); ?>images/m_box_plus.gif';			
			}	
		}
	}
}
</script>

	<div class="userAlbumsList">
	<div class="title"><strong><?=lang('competition')?></strong> | <a href="#" onclick="changeAllButtons('<?=count ($allcompetitions)?>'); return false;"><span id ="op-cl"> <?= lang("comp_openall"); ?> </span> </a></div>

<div class="list">
<?php
		//$allcompetitions = modules::run('competition_mod/competition_ctr/get_competition', null, 10, 1);
		$i = 1;
		if (!empty ($allcompetitions))	   
		{
		foreach ($allcompetitions as $competition) :
			//count of comments in competition
			$comms_count = 0;
			foreach ($competition->works as $photo) :
				$u = $photo->comcnt;
				$comms_count += $u;
			endforeach;
		?>
			<div class="item" style = "padding-left: 0" >	
			<img id = "im_znak_<?=$i?>" alt=""  src="<?php echo static_url(); ?>images/m_box_plus.gif" onclick="changeButton(this, '<?=$i?>')" />			
				<span class="contest"><img alt="" border="0" src="<?=$competition->status_img?>"/></span>
				<a href="<?=base_url()?>competition/details/<?=$competition->competition_id?>" class="albumName">
                   <? if(!$competition->status) echo '<s>' ?> <?=$competition->title?> <? if(!$competition->status) echo '</s>' ?>
                </a>
		
		    <div id="comp_invisible_<?=$i?>" style="display:none" class="active_sprite">

				<div class="description">
				
					<? if(isset($competition->urlImg)){?>
					<div class="im_profil_photo">               
	                	<img height="<?=$competition->nheight?>" width="<?=$competition->nwidth?>"  border="0"  src="<?=$competition->urlImg?>" alt="" style = "margin-top: <?=$competition->margin_top?>px; margin-bottom: <?=$competition->margin_bottom?>px; margin-left: <?=$competition->margin_left?>px; margin-right: <?=$competition->margin_right?>px " />                    
					</div>
	                <? } ?>
                	
					<ul>
						<li class="photo"><?=lang('comp_of_photos')?>: <?=count($competition->works)?></li>
						<li class="comments"><?=lang('comp_of_comments')?>:<?=$comms_count?> </li>
						<li class="date"><?=lang('comp_date_execution_end')?>: <?php echo date("d.m.Y", strtotime($competition->end_date)); ?></li>
					</ul>
					
					<p><strong><a href="<?=base_url()?>/competition/details/<?=$competition->competition_id?>"><?=$competition->title?>(<?=$competition->status_word?>)</a></strong><?=stripslashes($competition->short_description)?></p>
					
				 </div>	
		</div>		
		</div>
		<?php $i++; endforeach;
          }    //if (!empty ($allcompetitions))	    {   ?>
	</div>

</div>	

<div class="roundedBlock"><div class="tr"><div class="br"><div class="bl">
					<div class="contestRulesIntro">
						<h2><?=lang('comp_how')?></h2>
						<ul>
							<li><i>01</i> <?=lang('comp_how_rule_first')?> </li>
							<li><i>02</i> <?=lang('comp_how_rule_second')?> </li>

							<li><i>03</i> <?=lang('comp_how_rule_third')?> </li>
							<li><i>04</i> <?=lang('comp_how_rule_forth')?> </li>
						</ul>
					</div>
</div></div></div></div>
