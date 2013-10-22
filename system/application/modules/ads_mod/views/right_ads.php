<?php
	$str_ads = "";
	if(!empty($ads_content)) {
		$index=1;		
		foreach ($ads_content as $ad_content) {
			$str_ads .= '<div id = "ads_block_'.$index.'"';
			if (empty ($ad_content)) $str_ads .= 'style = "display:none;"';
			$str_ads .= 'class="roundedBlock"><div class="tr"><div class="br"><div class="bl">'.lang('advertising').'<br /><br />'.$ad_content;
			$str_ads .= '</div></div></div></div>';
			
			$index++;
		}
	}
	echo $str_ads;
?>