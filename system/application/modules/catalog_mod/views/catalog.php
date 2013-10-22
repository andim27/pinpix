<?php
/*
$branch_before = '<ul class="level_'.($cat->level+1).'">';
$branch_after = '</ul>';
$item_before = '<li>';
$item_text = '<a href="'.url('category_url',array('id'=>$cat->id)).'" id="cat_'.$cat->id.'">'.$cat->name.'</a>';
$item_after = '</li>';

$branch_before = '<ul class="level_{level}">';
$branch_after = '</ul>';
$item_before = '<li>';
$item_text = '<a href="'.url('category_url').'{id}" id="cat_{id}">{name} <!-- level {level} --> </a>';
$item_after = '</li>';
$rgt = 0; 
foreach ($catalog as $cat) { 
	if ($cat->lft>1) {  
		if ($rgt && $cat->lft>$rgt) {   
			$rgt = 0; 
			echo $branch_after;
			echo $item_after;
		} 
			echo $item_before;
			echo $item_text;
	}
	if ($cat->rgt-$cat->lft>1) {   
			$rgt = $cat->rgt; 
			echo $branch_before;
	} else { 
			echo $item_after;
	}
}
*/
$branch_before = '<ul class="level_{level}">';
$branch_after = '</ul>';
$item_before = '<li>';
$item_text = '<a href="'.url('category_url').'{id}" id="cat_{id}">{name} <!-- level {level} --> </a>';
$item_after = '</li>';

echo $catalog->get_tree_view($branch_before, $branch_after, $item_before, $item_text, $item_after);

?>
