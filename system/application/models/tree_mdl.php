<?php
/*
CREATE TABLE `tree` (                                                                                                 
            `id` int(11) NOT NULL auto_increment, 
	    	`lft` int(11) default NULL,                                                                                             
            `rgt` int(11) default NULL, 
			# any more fields  						
            PRIMARY KEY  (`id`),  
		    UNIQUE KEY (`lft`), 
		    UNIQUE KEY (`rgt`) 
			# , any more keys                                                                                    
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8 
*/

function _field_replace($matches){
	return $matches[1].'$item->'.$matches[2].$matches[3];
}

class Tree_mdl extends Model {
	
	var $_table;
	var $_found_rows;
	var $_show_root = FALSE;
	/*
	function Tree_mdl($tree_table)
    {
        parent::Model();  
        $this->_table = $tree_table;  
    }
	*/	
	
	function set_table($tree_table, $id_field='id')
    {
        $this->_table = $tree_table;  
        $this->_id = $id_field;  
    }
	
	function set_root_visibility($show_root)
    {
        $this->_show_root = $show_root;  
    }
    
	function add_item($parent_id=null, $branch_fields=array(), $item_fields=array())
	{ 
		$where = '1';
		if (is_array($branch_fields)) 
		{
			foreach ($branch_fields as $key=>$value) 
			{
				$where .= ' and '.$key.'='.$value;
			}
		}
		
		if (empty($parent_id))
		{
			$query = 'select max(rgt) as parent_rgt from '.$this->_table.' where '.$where;
		}
		else
		{
			$query = 'select rgt as parent_rgt from '.$this->_table.' where '.$this->_id.'='.$parent_id;
		}
		$query = $this->db->query($query);
		if ($query->num_rows()==0) return FALSE;
		$rs = $query->result();
		$parent_rgt = $rs[0]->parent_rgt;

		
		$query = 'update '.$this->_table.' set lft=lft+2 
				  where '.$where.' and lft>'.$parent_rgt.' order by lft desc';
    	$query = $this->db->query($query);
    	
    	$query = 'update '.$this->_table.' set rgt=rgt+2 
				  where '.$where.' and rgt>='.$parent_rgt.' order by rgt desc';
    	$query = $this->db->query($query);
		
    	$fields = '';
    	$values = '';
		if (is_array($branch_fields)) {
			foreach ($branch_fields as $key=>$value) {
				if (!empty($fields)) $fields .= ', ';
				$fields .= $key;
				if (!empty($values)) $values .= ', ';
				$values .= $value;
			}
		}
		//log_message('debug',var_export($item_fields,true));
		if (is_array($item_fields)) {
			foreach ($item_fields as $key=>$value) {
				if (!empty($fields)) $fields .= ', ';
				$fields .= $key;
				if (!empty($values)) $values .= ', ';
				$values .= $value;
			}
		}
    	if (!empty($fields)) $fields = ', '.$fields;
    	if (!empty($values)) $values = ', '.$values;
    	
		$query = 'insert into '.$this->_table.' (lft,rgt '.$fields.') 
				  values ('.$parent_rgt.', '.($parent_rgt+1).' '.$values.')'; 
		log_message('debug',$query);
    	$query = $this->db->query($query);
    	if ($this->db->affected_rows()==0) return FALSE;
    	
    	$id = $this->db->insert_id();
    	return $id;
	}
	
	function delete_branch($lft, $rgt, $branch_fields=array())
	{
		//log_message('debug', '^^^ tree_mdl.php -- delete_branch <<-------- ');
		$where = '1';
		if (is_array($branch_fields)) {
			foreach ($branch_fields as $key=>$value) {
				$where .= ' and '.$key.'='.$value;
			}
		}
		
		$query = 'delete from '.$this->_table.' where '.$where.' and lft between '.$lft.' and '.$rgt;
    	//log_message('debug', '^^^ tree_mdl.php -- delete_branch --- '.$query);
		$query = $this->db->query($query);
		 
    	if ($this->db->affected_rows()==0) return FALSE;
		
		$size = 1 + $rgt - $lft; 
		$query = 'update '.$this->_table.' set lft=lft-'.$size.' 
				  where '.$where.' and lft>'.$rgt.' order by lft';
    	//log_message('debug', '^^^ tree_mdl.php -- delete_branch --- '.$query);
		$query = $this->db->query($query);
    	
    	$query = 'update '.$this->_table.' set rgt=rgt-'.$size.' 
				  where '.$where.' and rgt>'.$rgt.' order by rgt';;
    	//log_message('debug', '^^^ tree_mdl.php -- delete_branch --- '.$query);
		$query = $this->db->query($query);
		
    	//log_message('debug', '^^^ tree_mdl.php -- delete_branch -------->> ');
    	return TRUE;
	}
	
	function delete_item($lft, $rgt, $branch_fields=array())
	{
		//log_message('debug', '^^^ tree_mdl.php -- delete_item <<-------- ');
		$where = '1';
		if (is_array($branch_fields)) 
		{
			foreach ($branch_fields as $key=>$value) {
				$where .= ' and '.$key.'='.$value;
			}
		}
		
		$query = 'delete from '.$this->_table.' where '.$where.' and lft='.$lft;
    	//log_message('debug', '^^^ tree_mdl.php -- delete_item --- '.$query);
		$query = $this->db->query($query);
		
    	if ($this->db->affected_rows()==0) return FALSE;
    	
		$query = 'update '.$this->_table.' set lft=lft-1   
				  where '.$where.' and lft between '.$lft.' and '.$rgt.' order by lft';
		//log_message('debug', '^^^ tree_mdl.php -- delete_item --- '.$query);
    	$query = $this->db->query($query);
    	
    	$query = 'update '.$this->_table.' set rgt=rgt-1  
				  where '.$where.' and lft between '.$lft.' and '.$rgt.' order by rgt';
		//log_message('debug', '^^^ tree_mdl.php -- delete_item --- '.$query);
    	$query = $this->db->query($query);
    	
    	$query = 'update '.$this->_table.' set lft=lft-2  
				  where '.$where.' and lft>'.$rgt.' order by lft';
    	//log_message('debug', '^^^ tree_mdl.php -- delete_item --- '.$query);
		$query = $this->db->query($query);
    	
    	$query = 'update '.$this->_table.' set rgt=rgt-2 
				  where '.$where.' and rgt>'.$rgt.' order by rgt';;
    	//log_message('debug', '^^^ tree_mdl.php -- delete_item --- '.$query);
		$query = $this->db->query($query);
		
    	//log_message('debug', '^^^ tree_mdl.php -- delete_item -------->> ');		
    	return TRUE;
	}
	
	//function update_item($lft, $rgt, $parent_id=null, $branch_fields=array(), $item_fields=array())
	function update_item($id, $parent_id=null, $branch_fields=array(), $item_fields=array())
	{
		/*
		if ( empty($lft) || empty($rgt) ) return FALSE;
		
		$items = $this->get_items(array('lft'=>$lft, 'rgt'=>$rgt));
		
		if ( ! is_array($items) || empty($items) ) return FALSE;
		
		$item = $items[0];
		*/
		if ( empty($id) ) return FALSE;
		
		$items = $this->get_items(array($this->_id=>$id));
		
		if ( ! is_array($items) || empty($items) ) return FALSE;
		
		$item = $items[0];
		
		$lft = $item->lft;
		$rgt = $item->rgt;
		
		/////////////////////////////////////////////
		$where = '1';
		if (is_array($branch_fields)) 
		{
			foreach ($branch_fields as $key=>$value) 
			{
				$where .= ' and '.$key.'='.$value;
			}
		}
		/////////////////////////////////////////////
		
		if ($item->parent_id != $parent_id) 
		{
			///////////// delete the item from its old branch //////////////
			$query = 'update '.$this->_table.' set lft=0, rgt=0    
				  where '.$where.' and lft='.$lft.' and rgt='.$rgt; 
    		$query = $this->db->query($query);
    		
    		if ( ! $query) return FALSE;
			
			$query = 'update '.$this->_table.' set lft=lft-1   
				  where '.$where.' and lft between '.$lft.' and '.$rgt.' order by lft';
	    	$query = $this->db->query($query);
	    	
	    	$query = 'update '.$this->_table.' set rgt=rgt-1  
					  where '.$where.' and lft between '.$lft.' and '.$rgt.' order by rgt';
	    	$query = $this->db->query($query);
	    	
	    	$query = 'update '.$this->_table.' set lft=lft-2  
					  where '.$where.' and lft>'.$rgt.' order by lft';
			$query = $this->db->query($query);
	    	
	    	$query = 'update '.$this->_table.' set rgt=rgt-2 
					  where '.$where.' and rgt>'.$rgt.' order by rgt';;
			$query = $this->db->query($query);
			
			///////////// add the item to the new branch //////////////
	
			if (empty($parent_id))
			{
				$query = 'select max(rgt) as parent_rgt from '.$this->_table.' where '.$where;
			}
			else
			{
				$query = 'select rgt as parent_rgt from '.$this->_table.' where '.$this->_id.'='.$parent_id;
			}
			$query = $this->db->query($query);
			if ($query->num_rows()==0) return FALSE;
			$rs = $query->result();
			$parent_rgt = $rs[0]->parent_rgt;
	
			
			$query = 'update '.$this->_table.' set lft=lft+2 
					  where '.$where.' and lft>'.$parent_rgt.' order by lft desc';
	    	$query = $this->db->query($query);
	    	
	    	$query = 'update '.$this->_table.' set rgt=rgt+2 
					  where '.$where.' and rgt>='.$parent_rgt.' order by lft desc';
	    	$query = $this->db->query($query);
	    	
	    	$lft = $rgt = 0;
	    	$item_fields['lft'] = $parent_rgt;
	    	$item_fields['rgt'] = $parent_rgt+1;
		}
		
		/////////////////////////////////////////////
		$updates = '';
    	if (is_array($branch_fields)) {
			foreach ($branch_fields as $key=>$value) {
				if (!empty($updates)) $updates .= ', ';
				$updates .= $key.'='.$value;
			}
		}
		if (is_array($item_fields)) {
			foreach ($item_fields as $key=>$value) {
				if (!empty($updates)) $updates .= ', ';
				$updates .= $key.'='.$value;
			}
		}
    	/////////////////////////////////////////////
		
		$query = 'update '.$this->_table.' set '.$updates.'      
				  where '.$where.' and lft='.$lft.' and rgt='.$rgt; 
		$query = $this->db->query($query);
    	
    	if ( ! $query) return FALSE;
		
		return $item->id;
	}
	
	function get_items($conditions=array(), $per_page=0, $page=1, $linked_tables=array(), $filters=array(), $sort_ord = '')
	{
		$query = $this->get_items_query($conditions, $per_page, $page, $linked_tables, $filters, $sort_ord);
		$query = $this->db->query($query);
		
		$this->_found_rows = $this->_get_found_rows();
		
		$items = array();
    	if ($query->num_rows() > 0){
    		foreach ($query->result() as $row){ 
   				$items[] = $row;
   			}
		}
		return $items;
	}
	
	function get_items_query($conditions=array(), $per_page=0, $page=1, $linked_tables=array(), $filters=array(), $sort_ord = '')
	{
	
	/*
	$linked_tables=array(
						array(	
							'fields' => '<table>.field_k',
							'from' => '<table>',
							'where' => '<tree-table>.field_n=<table>.field_m'
						)
	);
	*/
		$page = empty($page)?1:$page;
		$limit = empty($per_page)?'':' limit '.$per_page*($page-1).','.$per_page;
		
		$where = '1';
		if (is_array($conditions)) {
			foreach ($conditions as $key=>$value) {
				$where .= ' and c1.'.$key.'='.$value;
			}
		}
		
		$fields = '';
		$from = '';
		if (is_array($linked_tables)) {
			for ($i=0; $i<count($linked_tables); $i++) {
				$fields .= ', '.str_replace($linked_tables[$i]['from'],'t'.$i,$linked_tables[$i]['fields']);
				$from .= ', '.$linked_tables[$i]['from'].' as t'.$i.' ';
//				$cond = $linked_tables[$i]['where'];
				if(is_array($linked_tables[$i]['where'])) $cond = implode(" AND ", $linked_tables[$i]['where']);
				else $cond = $linked_tables[$i]['where'];
				$cond = str_replace($linked_tables[$i]['from'].'.','t'.$i.'.',$cond);
				$cond = str_replace($this->_table.'.','c1.',$cond);
				$where .= ' and '.$cond;
			}
		}
		
		$having = '1';
		if (is_array($filters)) {
			foreach ($filters as $key=>$value) {
				if ( empty($key) || (empty($value) && ! is_numeric($value)) ) continue;
				if($key == 'comment_date') {
					
					$date_condition = 1;					
					if (is_array($value))
			    	{
			    		if(count($value) >= 2) {
			    			if(!empty($value[0]) && !empty($value[1])) {
				    			$date_from = $this->input->xss_clean($value[0]);
				    			$date_from = date("Y-m-d", strtotime($date_from));
				    			
				    			$date_to = $this->input->xss_clean($value[1]);
				    			$date_to = date("Y-m-d", strtotime($date_to));
				    						    		
				    			$date_condition = ' c1.comment_date between '.clean($date_from).' and '.clean($date_to);
			    			}
			    		}
			    	}
			    	elseif(is_numeric($value)) 
			    	{
			    		$date_condition = ' date_sub(curdate(),INTERVAL '.$value.' DAY) >= c1.comment_date ';
			    	}
			    	
			    	$where .= " and ".$date_condition;
			    	
				} else {
					$having .= ' and '.$key.' rlike '.clean($value);
				}
			}
		}
				
		//(select '.$this->_id.' from '.$this->_table.' where rgt=@parentrgt) as parent_id,
		if(empty($sort_ord)) $sort_ord = 'c1.lft';
 		$query = 'select SQL_CALC_FOUND_ROWS 
				count(c2.'.$this->_id.') as level, 
				@parentrgt:=min(coalesce(if(c1.rgt=c2.rgt,null,c2.rgt))) as parent_rgt,  
				c1.* '.$fields.' 
				from '.$this->_table.' as c1, '.$this->_table.' as c2 '.$from.' 
				where '.$where.'  
				and c1.lft between c2.lft and c2.rgt
				group by c1.'.$this->_id.'  
				having '.$having.' 
				order by '.$sort_ord . $limit;
// 		echo $query;exit;
		return $query;
	}
	
	function get_subtree_query($subtree_conditions=array(), $conditions=array(), $per_page=0, $page=1, $linked_tables=array(), $filters=array())
	{
		$page = empty($page)?1:$page;
		$limit = empty($per_page)?'':' limit '.$per_page*($page-1).','.$per_page;
		
		$where = '1';
		if (is_array($conditions)) {
			foreach ($conditions as $key=>$value) {
				$where .= ' and c1.'.$key.'='.$value;
			}
		}
		
		$subtree_where = '1';
		if (is_array($subtree_conditions)) {
			foreach ($subtree_conditions as $key=>$value) {
				$subtree_where .= ' and '.$key.'='.$value;
			}
		}
		
		$having = '1';
		if (is_array($filters)) {
			foreach ($filters as $key=>$value) {
				if ( empty($key) || (empty($value) && ! is_numeric($value)) ) continue;
				$having .= ' and '.$key.' rlike '.clean($value);
			}
		}
		
		$fields = '';
		$from = '';
		if (is_array($linked_tables)) {
			for ($i=0; $i<count($linked_tables); $i++) {
				$fields .= ', '.str_replace($linked_tables[$i]['from'],'t'.$i,$linked_tables[$i]['fields']);
				$from .= ', '.$linked_tables[$i]['from'].' as t'.$i.' ';
				$cond = $linked_tables[$i]['where'];
				$cond = str_replace($linked_tables[$i]['from'].'.','t'.$i.'.',$cond);
				$cond = str_replace($this->_table.'.','c1.',$cond);
				$where .= ' and '.$cond;
			}
		}
		
		//(select '.$this->_id.' from '.$this->_table.' where rgt=@parentrgt) as parent_id, 
		$query = 'select SQL_CALC_FOUND_ROWS 
				count(c2.'.$this->_id.') as level, 
				@parentrgt:=min(coalesce(if(c1.rgt=c2.rgt,null,c2.rgt))) as parent_rgt,  
				c1.* '.$fields.' 
				from '.$this->_table.' as c1, '.$this->_table.' as c2 '.$from.' 
				where '.$where.'  
				and c1.lft between c2.lft and c2.rgt 
				and c2.lft between 
					(select lft from '.$this->_table.' where '.$subtree_where.' limit 1) and 
					(select rgt from '.$this->_table.' where '.$subtree_where.' limit 1) 
				group by c1.'.$this->_id.'  
				having '.$having.' 
				order by c1.lft '.$limit; // sum(if(c2.'.$this->_id.'=c1.'.$this->_id.',0,1)) as level,
		
		return $query;
	}
	
	function get_root($conditions=array())
	{
		$where = '1';
		if (is_array($conditions)) {
			foreach ($conditions as $key=>$value) {
				$where .= ' and c1.'.$key.'='.$value;
			}
		}
		
		$query = 'select c1.*  
					from '.$this->_table.' as c1  
					where '.$where.' 
					and (c1.rgt-c1.lft)=(select max(c1.rgt-c1.lft) from '.$this->_table.' as c1 where '.$where.')';
		$query = $this->db->query($query);
		
		if ($query->num_rows()==1){
			return $query->row();
    	}
		return FALSE;
	}
	
	function _get_found_rows(){
		$query = 'select found_rows() as found';
		$query = $this->db->query($query);
		
		if ($query->num_rows() > 0){
    		
   			$rs = $query->row();
   			return $rs->found;
			
		}
		return FALSE;
	}
	
	function found_rows(){
		return $this->_found_rows;
	}
	
	function _get_branch_end ($item_rgt, &$branch_rgt, $branch_after, $item_after, &$view='')
	{
		if ( ! is_array($branch_rgt) || empty($branch_rgt)) return $view;
		//log_message('debug', '1 item_rgt='.$item_rgt);
		//log_message('debug', '1 branch_rgt='.var_export($branch_rgt,TRUE));
		//log_message('debug', '1 view='.var_export($view,TRUE));
		if ( $item_rgt+1 == $branch_rgt[count($branch_rgt)-1] )
		{   
			$item_rgt = array_pop($branch_rgt);
			//log_message('debug', '2 item_rgt='.$item_rgt);
			//log_message('debug', '2 branch_rgt='.var_export($branch_rgt,TRUE));
			eval("\$_branch_after = \"$branch_after\";"); 
			$view .= $_branch_after;
			eval("\$_item_after = \"$item_after\";"); 
			$view .= $_item_after;
			
			$this->_get_branch_end($item_rgt, $branch_rgt, $branch_after, $item_after, $view );
		} 
		//log_message('debug', '3 view='.var_export($view,TRUE));
		return $view;
	}
	
	function get_tree_view ($tree, $branch_before='', $branch_after='', $item_before='', $item_text='', $item_after='', $view_root=FALSE, $active_item_id = NULL, $active_before = '', $active_after = '') {
			
		$branch_before = addslashes( preg_replace_callback('/(.*){(.*)}(.*)/U','_field_replace',$branch_before) );
		$branch_after = addslashes( preg_replace_callback('/(.*){(.*)}(.*)/U','_field_replace',$branch_after) );
		$item_before = addslashes( preg_replace_callback('/(.*){(.*)}(.*)/U','_field_replace',$item_before) );
		$item_text = addslashes( preg_replace_callback('/(.*){(.*)}(.*)/U','_field_replace',$item_text) );
		$item_after = addslashes( preg_replace_callback('/(.*){(.*)}(.*)/U','_field_replace',$item_after) );
        $active_before = addslashes( preg_replace_callback('/(.*){(.*)}(.*)/U','_field_replace',$active_before) );
        $active_after = addslashes( preg_replace_callback('/(.*){(.*)}(.*)/U','_field_replace',$active_after) );
			
		$view = '';
		
		$branch_root = FALSE;
		$branch_rgt = array(); 
		
		foreach ($tree as $item) 
		{ 
			$visible = TRUE;
			if (empty($item->parent_rgt))
			{
				$visible = $this->_show_root;
			}
			
			if ($item->rgt-$item->lft>1) 
			{   
				$branch_root = TRUE;
				array_push($branch_rgt, $item->rgt); 
			}
			else 
			{
				$branch_root = FALSE;
			}
            
            if($active_item_id == $item->id) 
            {
                eval("\$_item_before = \"$item_before\";"); 
                $view .= $_item_before;

                eval("\$_item_text = \"$active_before\";"); 
                $view .= $_item_text;
            }
            else if($visible)
            {
                eval("\$_item_before = \"$item_before\";"); 
                $view .= $_item_before;

				eval("\$_item_text = \"$item_text\";"); 
				$view .= $_item_text; 
			}
			if ( $branch_root) 
			{
				eval("\$_branch_before = \"$branch_before\";"); 
				$view .= $_branch_before;
			}
			else//if ($visible) 
			{ 
				eval("\$_item_after = \"$item_after\";"); 
				$view .= $_item_after;
			}
			
			$view .= $this->_get_branch_end($item->rgt, $branch_rgt, $branch_after, $item_after);
		}
		
		return $view;
	}
	
	function get_tree_view_ext ($tree, $config, $view_root=FALSE) {

		$branch_before_arr = $branch_after_arr = $item_before_arr = $item_text_arr = $item_after_arr = array();
		
		foreach($config as $level => $cnf)
		{
			$branch_before_arr[$level] = addslashes( preg_replace_callback('/(.*){(.*)}(.*)/U','_field_replace',$cnf['branch_before']) );
			$branch_after_arr[$level] = addslashes( preg_replace_callback('/(.*){(.*)}(.*)/U','_field_replace',$cnf['branch_after']) );
			$item_before_arr[$level] = addslashes( preg_replace_callback('/(.*){(.*)}(.*)/U','_field_replace',$cnf['item_before']) );
			$item_text_arr[$level] = addslashes( preg_replace_callback('/(.*){(.*)}(.*)/U','_field_replace',$cnf['item_text']) );
			$item_after_arr[$level] = addslashes( preg_replace_callback('/(.*){(.*)}(.*)/U','_field_replace',$cnf['item_after']) );
		}
		
		$view = '';
		
		$branch_root = FALSE;
		$branch_rgt = array(); 
		$open_branch_level = array(); 
				
		foreach ($tree as $item) 
		{ 
			$visible = TRUE;
			$level = $item->level;
			
			if ( ! array_key_exists($level,$config)) $level = 'default';
			
			$branch_before = $branch_before_arr[$level];
			$branch_after = $branch_after_arr[$level];
			$item_before = $item_before_arr[$level];
			$item_text = $item_text_arr[$level];
			$item_after = $item_after_arr[$level];
			
			if (empty($item->parent_rgt))
			{
				$visible = $this->_show_root;
			}
			
			if ($item->rgt-$item->lft>1) 
			{   
				$branch_root = TRUE;
				array_push($branch_rgt, $item->rgt); 
				array_push($open_branch_level, $level); 
			}
			else 
			{
				$branch_root = FALSE;
			}
			
			
			eval("\$_item_before = \"$item_before\";"); 
			$view .= $_item_before;
			if ($visible)
			{
				eval("\$_item_text = \"$item_text\";"); 
				$view .= $_item_text; 
			}
			
			if ( $branch_root) 
			{
				eval("\$_branch_before = \"$branch_before\";"); 
				$view .= $_branch_before;
			}
			else//if ($visible) 
			{ 
				eval("\$_item_after = \"$item_after\";"); 
				$view .= $_item_after;
			}
			//log_message('debug', ' ------------------------  '.$item->rgt.'  |  '.var_export($branch_rgt,TRUE));
			if (count($open_branch_level)>0) 
			{
				$branch_level = $open_branch_level[count($open_branch_level)-1];
				$view .= $this->_get_branch_end($item->rgt, $branch_rgt, $branch_after_arr[$branch_level], $item_after_arr[$branch_level]);
			}
			
		}
		
		return $view;
	}
	
	
}

/* End of file */