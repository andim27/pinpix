<script type="text/javascript">
function open_window(link,w,h) {
	var win = "width="+w+",height="+h+",menubar=no,location=no,resizable=yes,scrollbars=yes";
	newWin = window.open(link,"newWin",win);
	newWin.focus();
}
var __opened_id = null;
var __opened_id_mini0 = null;
var __opened_id_mini1 = null;

function ShowResultPhoto(__id) {
	$('#opened_'+__opened_id).hide();
	$('#closed_'+__opened_id).show();
	$('#opened_'+__id).show();
	$('#closed_'+__id).hide();
	$('#photo_title').text($('#photo_title_'+__id).text());
	__opened_id = __id;
}
function ShowResultPhotoMini0( __id ) {
	try{
		if (__opened_id_mini0 !== null) {
		$('#'+'opened_mini0_' + __opened_id_mini0).hide();
		$('#'+'closed_mini0_' + __opened_id_mini0).hide();
		$('#opened_mini0_'+__opened_id_mini0).hide();
		$('#closed_mini0_'+__opened_id_mini0).show();
		}
		$('#opened_mini0_'+__id).show();
		$('#closed_mini0_'+__id).hide();
		__opened_id_mini0 = __id;
	} 
	catch(e) {} 
	finally {}
}
function ShowResultPhotoMini1( __id) {
	if (__opened_id_mini1 != null) {
		$('#closed_mini1_'+__opened_id_mini1).show();
		$('#opened_mini1_'+__opened_id_mini1).hide();
	}
	$('#opened_mini1_'+__id).show();
	$('#closed_mini1_'+__id).hide();
	__opened_id_mini1 = __id;
}

$(function(){try{ShowResultPhoto(0)}catch(e){}try{ShowResultPhotoMini0(0)}catch(e){}try{ShowResultPhotoMini1(0)}catch(e){}});

</script>
<style type="text/css">
.bord_line{
	border-bottom:1px solid #868C90;
	cursor:pointer;
	padding:4px 0;
}
.paginator a{
	color:#373D41;
}
.filter_selected{
	font-weight:bold;
}
</style>
<div class="middCol">
<?php
	if(!empty($competitions)) {
		$fisrt_work_title = empty($competitions[0]->works) ? '' : $competitions[0]->works[0]->title;
?>
<div class="titleFilter">
	<strong><a href="<?php echo base_url().'competition/'?>"><?=lang('competition')?></a></strong> / <a href="<?php echo base_url().'competition/details/'.$competitions[0]->competition_id ?>"><?=lang_translate($competitions[0]->title,"kz")?></a>  / <span id="photo_title"><?=$fisrt_work_title?></span>
</div>
<?php } ?>
<div class="title" style="font-size:12px;margin-bottom:15px;">
	<a <?php if($filter == "open") echo 'class="filter_selected"'; ?> href="<?=base_url()?>competition/view/open"><?=lang('competition_open')?></a> / 
	<a <?php if($filter == "close") echo 'class="filter_selected"'; ?> href="<?=base_url()?>competition/view/close"><?=lang('competition_close')?></a>
</div>
<div class="contestPage">
<?php
	if(!empty($competitions)) {		
		foreach ($competitions as $index=>$competition) {
		
		$header = '';
		$js_function = '';
		
		if($index == 0) {
			$header = '<div class="mainContest">';
			$js_function = 'ShowResultPhoto';
		}
		elseif ($index == 1) {
			$header = '<div class="subContest lCol"><div class="title"><strong>'.lang('competition').'</strong> / <a href="'.base_url().'competition/details/'.$competition->competition_id.'">'.$competition->title.'</a></div>';
			$js_function = 'ShowResultPhotoMini0';
		}
		elseif ($index == 2) {
			$header = '<div class="subContest rCol"><div class="title"><strong>'.lang('competition').'</strong> / <a href="'.base_url().'competition/details/'.$competition->competition_id.'">'.$competition->title.'</a></div>';
			$js_function = 'ShowResultPhotoMini1';
		}
		
		$photos = $competition->works;
		
		echo $header;
		
		if($index == 0) {
			if(!empty($photos)) {
					foreach ($photos as $j=>$photo) {
		?>
					<div class="bord_line" id="closed_<?=$j?>" onclick="javascript:<?=$js_function?>('<?=$j?>');return false;" style="cursor:pointer;">
						<b><?=str_pad($j+1, 2, '0', STR_PAD_LEFT)?></b>
						<strong><?=$photo->login?></strong>&nbsp;
						<span id="photo_title_<?=$j?>"><?=$photo->title?></span>
					</div>
					<div class="comp-closed-opened" id="opened_<?=$j?>">
						<span class="firstPlace"><?=str_pad($j+1, 2, '0', STR_PAD_LEFT)?></span>
						<div style = "background-color:white" class="gor_img">
							<div class="bigThumb">
							<?php if ($p_min == true): ?>
							<div class="bigThumb forFlashBox" onclick="javascript:open_window('<?php echo $photo->src_lg; ?>')">
							<div id="fl_over_id" class="flashBox">
								<?php echo $photo->fl_cont_html;  ?>
							</div>
							<div class="helper"/></div>
						</div>
							<?php else: ?>
								<?php if ($photo->land) :?>
						<div id="image_box_odf_land" style="background:white; height:481px" onclick="javascript:open_window('<?php echo $photo->src_lg; ?>')">
							<div id="fl_over_id" style="padding-top:27px;width:615px;height:400px;">
								<?php else :?>
							<div id="image_box_odf" style="background:white;width:622px;cursor: pointer; cursor: hand;" onclick="javascript:open_window('<?php echo $photo->src_lg; ?>')">
								<div id="fl_over_id" style="padding-left:45px;width:615px;height:592px;">
								<?php endif;?>
								<?php echo $photo->fl_cont_html;  ?>
								</div>
							</div>
						<?php endif;?>				
						</div>		
						<?php echo $photo->fl_cont_js;  ?>
						</div>
						<div class="info" style="margin-bottom:10px;">
							<ul>
								<li class="rating"><?=$photo->balls?></li>
								<li class="date"><?php echo date("d.m.Y", strtotime($photo->date_added)); ?></li>
								<li class="comments"><?=$photo->comcnt?></li>
								<li class="views"><?=$photo->num_votes?></li>
							</ul>
							<div class="conditions">
								<strong><?=lang('comp_conditions')?></strong>
								<?=$competition->description;?>
							</div>
							<div class="author">
								<strong><b><a href="<?php echo base_url() . 'profile/view/' . $photo->user_id ?>"><?=$photo->login?></a></b></strong>
								<?php echo $photo->description; ?>
							</div>
						</div>	
					</div>		
<?php
				}
			}
?>
<?php
	} else {
		foreach ($photos as $j=>$photo) {
?>
				<div class="bord_line" id="closed_mini0_<?=$j?>" onclick="javascript:<?=$js_function?>('<?=$j?>');return false;" style="cursor:pointer;">
					<b><?=str_pad($j+1, 2, '0', STR_PAD_LEFT)?></b>
					<strong><?=$photo->login?></strong>&nbsp;
					<?=$photo->title?>
				</div>
				<div class="comp-closed-opened" id="opened_mini0_<?=$j?>">				
					<span class="place"><?=str_pad($j+1, 2, '0', STR_PAD_LEFT)?></span>				
					<div style = "background-color:white" class="gor_img">
					<!-- Start Image box-->
					<?php if ($photo->land) :?>
						<!-- land foto --> <span>&nbsp;</span> <!-- andMak:Hack fo FF -->
						<div id="image_box_odf" style="cursor: pointer; position: inherit; height: 296px; width: 312px;" onclick="open_window('<?php echo $photo->src_lg; ?>')">
							<div id="fl_over_id" style="padding-top:18px;width:290px;height:296px;cursor: pointer; cursor: hand;">
					<?php else :?>
						<!-- vert foto -->
						<div id="image_box_odf" style="cursor: pointer; position: inherit; height: 296px; width: 312px;" onclick="open_window('<?php echo $photo->src_lg; ?>')">
							<div id="fl_over_id" style="padding-left:22px;width:290px;height:296px;cursor: pointer; cursor: hand;">
					<?php endif;?>
								<!-- e:AndMak last2 -->
								<?php echo $photo->fl_cont_html;  ?>
							</div>
							<?php echo $photo->fl_cont_js;  ?>
						</div>			
					</div>				
					<div class="info">
						<ul>
							<li class="rating"><?=$photo->balls?></li>
							<li class="date"><?php echo date("d.m.Y", strtotime($photo->date_added)); ?></li>
							<li class="comments"><?=$photo->comcnt?></li>
							<li class="views"><?=$photo->num_votes?></li>
						</ul>
						<div class="conditions">
							<strong><?=lang('comp_conditions')?></strong>
							<?=$competition->description;?>
						</div>
						<div class="author">
							<strong><b><a href="<?php echo base_url() . 'profile/view/' . $photo->user_id ?>"><?=$photo->login?></a></b></strong>
							<?php echo $photo->description; ?>
						</div>
					</div>	
				</div>
<?php
		}
	}	
	?>
			</div><script><?=$js_function?>(0);</script>
	<?php
}
?>
		</div>
	</div>
</div>
<?php
		if (!empty($paginate_args)) echo paginate_ajax($paginate_args);
	} else {
?>
	<div style="float:left;font-size:15px;margin:20px 0 0;">
		<?=lang('comp_empty')?>
	</div>
<?php
	}
?>