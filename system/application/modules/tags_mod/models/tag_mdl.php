<?php
class Tag_mdl extends Model {
	
	var $_tags_table = 'tags';
	var $_object_maps = array();
	var $_object_list_maps = array();
	var $_found_rows;
	
	/*
	var $_object_maps = array(
							'object_type'=>array(
										'table'=>'field1',
										'tag_id_field'=>'field1',
										'object_id_field'=>'field2'
										)
							);
	var $_object_list_maps = array(
							'object_type'=>array(
										'table'=>'field1',
										'list_id_field'=>'field1',
										'object_id_field'=>'field2'
										)
							);
	*/
	function set_maps($object_maps, $object_list_maps){
		$this->_object_maps = $object_maps;
		$this->_object_list_maps = $object_list_maps;
	}
	
	function add($object_type, $object_id, $tags){
		
		if (empty($this->_object_maps[$object_type]) || !is_array($tags) || empty($tags)) return FALSE;
		if ( ! is_array($tags)) return FALSE;
		foreach ($tags as $tag) {
			$tag = $this->db->escape($tag);
			$query = "update $this->_tags_table set tag_count=tag_count+1 where tag=$tag";
			//echo $query;
			$query = $this->db->query($query);
			
			if ($this->db->affected_rows() == 0) 
			{
				$query = "insert into $this->_tags_table (tag) values ($tag)";
				echo $query;
				$query = $this->db->query($query);
				if ($this->db->affected_rows() == 0) return FALSE;
			}
			$query = 'replace into '.$this->_object_maps[$object_type]['table'].' ('.$this->_object_maps[$object_type]['tag_id_field'].','.$this->_object_maps[$object_type]['object_id_field'].')
					  select tag_id,'.$object_id.' from '.$this->_tags_table.' where tag='.$tag;
			//echo $query;
			$query = $this->db->query($query);
		}
		return TRUE;
	}
		
	function save($object_type, $object_id, $tags){
		if (empty($this->_object_maps[$object_type]) || !is_array($tags) || empty($tags)) return FALSE;

		$object_id = clean($object_id);
		
		$map_table = $this->_object_maps[$object_type]['table'];
		$map_tag_id_field = $this->_object_maps[$object_type]['tag_id_field'];
		$map_object_id_field = $this->_object_maps[$object_type]['object_id_field'];
		
		$old_object_tags = $this->get_object_tags($object_type, $object_id, 0);
		if(is_array($old_object_tags)){
			foreach ($old_object_tags as $old_object_tag) 
			{
				$old_tag_value = $this->db->escape($old_object_tag->tag);
				$i = array_search($old_object_tag->tag,$tags);
				if ($i===FALSE) 
				{
					if ($old_object_tag->tag_count==1)
					{
						$query = "delete from m,t using $map_table m, $this->_tags_table t 
									where t.tag_id=m.$map_tag_id_field and t.tag=".$old_tag_value;
						//echo $query; echo '<br/>';
						$query = $this->db->query($query);
					}
					else 
					{
						$query = "delete from m using $map_table m, $this->_tags_table t 
									where t.tag_id=m.$map_tag_id_field 
									and m.$map_object_id_field=$object_id 
									and t.tag=".$old_tag_value;
						//echo $query; echo '<br/>';
						$query = $this->db->query($query);
						$query = "update $this->_tags_table set tag_count=tag_count-1 where tag=$old_tag_value";
						//echo $query; echo '<br/>';
						$query = $this->db->query($query);
					}
				} 
				else 
				{
					array_splice($tags,$i,1);
				}
			}
		}
		foreach ($tags as $tag) 
		{
			$tag = $this->db->escape($tag);
			$query = "update $this->_tags_table set tag_count=tag_count+1 where tag=$tag";
			//echo $query; echo '<br/>';
			$query = $this->db->query($query);
			
			if ($this->db->affected_rows() == 0) 
			{
				$query = "insert into $this->_tags_table (tag) values ($tag)";
				//echo $query; echo '<br/>';
				$query = $this->db->query($query);
				if ($this->db->affected_rows() == 0) return FALSE;
			}
			$query = 'replace into '.$map_table.' ('.$map_tag_id_field.','.$map_object_id_field.')
					  select tag_id,'.$object_id.' from '.$this->_tags_table.' where tag='.$tag;
			//echo $query; echo '<br/>';
			$query = $this->db->query($query);
		}
		//die();
		return TRUE;
	}
	
	function get_object_tags($object_type, $object_id, $limit=0, $moderation_state=array(), $moderation_state_negative=FALSE){
		
		if (empty($this->_object_maps[$object_type])) return FALSE;
		
		$object_id = $this->input->xss_clean($object_id);
		if ( ! empty($limit)) $limit = ' limit '.$this->input->xss_clean($limit);
		else $limit = '';
		
		if ( ! empty($moderation_state))
		{
			$moderation_state = is_array($moderation_state) ? $moderation_state : array($moderation_state);
			$condition = $moderation_state_negative ? 'not' : '';
			$moderation_state = ' and t.moderation_state '.$condition.' in ('.implode(',', $moderation_state).')';
		}
		else $moderation_state = '';
		
		$query = 'select t.* from '.$this->_tags_table.' t, '.$this->_object_maps[$object_type]['table'].' tm  
					where t.tag_id=tm.'.$this->_object_maps[$object_type]['tag_id_field'].' 
					and tm.'.$this->_object_maps[$object_type]['object_id_field'].'='.$object_id.' '.$moderation_state.' '.$limit;
		//echo $query;
		$query = $this->db->query($query);
		
		if ($query->num_rows() == 0) return FALSE;
		
		return $query->result();
	}
	
	function get_object_list_tags($list_id, $limit=10, $moderation_state=array(), $moderation_state_negative=FALSE){
		
		if (empty($this->_object_list_maps) || empty($this->_object_maps)) return FALSE;
		if (empty($list_id)) return FALSE;
		
		$list_id = $this->input->xss_clean($list_id);
		if ( ! empty($limit)) $limit = ' limit '.$this->input->xss_clean($limit);
		else $limit = '';
		
		if ( ! empty($moderation_state))
		{
			$moderation_state = is_array($moderation_state) ? $moderation_state : array($moderation_state);
			$condition = $moderation_state_negative ? 'not' : '';
			$moderation_state = ' and t.moderation_state '.$condition.' in ('.implode(',', $moderation_state).')';
		}
		else $moderation_state = '';
		
		$query = '';
		foreach ($this->_object_list_maps as $key=>$map) {
			if (empty($this->_object_maps[$key])) return FALSE;
			$from = ', '.$map['table'].' lm_'.$key.', '.$this->_object_maps[$key]['table'].' m_'.$key.' ';
			$where = '  lm_'.$key.'.'.$map['list_id_field'].'='.$list_id.' 
						and lm_'.$key.'.'.$map['object_id_field'].'=m_'.$key.'.'.$this->_object_maps[$key]['object_id_field'].' 
						and t.tag_id=m_'.$key.'.'.$this->_object_maps[$key]['tag_id_field'].' 
						'.$moderation_state;
			
			if ( ! empty($query)) $query .= ' union ';
			$query .= '(select distinct t.* from '.$this->_tags_table.' t '.$from.' where '.$where.')';
		}
		if ( ! empty($query)) $query .= ' order by tag_count desc '.$limit;
		
		$query = $this->db->query($query);
		
		if ( ! $query ) return FALSE;
		
		return $query->result();
	}
	
	/*
	* This function return list of tags.
	* @param integer $filter
	* @return array objects
	*/
	
	function get_tags($filter='%', $page=1, $per_page=0)
	{
		$filter = empty($filter) && ! is_numeric($filter) ? '%' : $filter;
		$filter = clean($filter);
		
		$page = empty($page)?1:$this->input->xss_clean($page);
		$limit = empty($per_page)?'':' limit '.$per_page*($page-1).','.$per_page;
		
		$query = "SELECT SQL_CALC_FOUND_ROWS * FROM $this->_tags_table  
				  WHERE moderation_state like $filter $limit";
		$query = $this->db->query($query);
		
		$this->_found_rows = $this->_get_found_rows();
		
		return $query->result();
	}
	
	function _get_found_rows(){
		$query = 'select found_rows() as found';
		//echo $query;
		$query = $this->db->query($query);
		
		if ($query->num_rows() > 0){
    		
   			$rs = $query->result();
   			return $rs[0]->found;
			
		}
		return FALSE;
	}
	
	function found_rows(){
		return $this->_found_rows;
	}
	
	/*
	* This function delete selected tag from database.
	* @param integet $tag_id
	* @return boolean
	*/
	function delete_tag($tag_id)
	{
		if (empty($tag_id) || ! settype($tag_id,"integer")) return FALSE;
		
		$res = TRUE;
		foreach ($this->_object_maps as $object_type => $map)
		{
			$map_table = $map['table'];
			$map_tag_id_field = $map['tag_id_field'];
						
			$query = "delete from $map_table where $map_tag_id_field=$tag_id";
			//log_message('debug', $query);
			$res &= $this->db->query($query);
		}
		
		$query = "delete from $this->_tags_table where tag_id=$tag_id";	
		//log_message('debug', $query);
		$res &= $this->db->query($query);
		//log_message('debug', $res);
		return $res;		
	}
	
	/*
	* This function searching tags from database.
	* @param string $text_search
	* @return array
	*/
	function search_tag($text_search, $page=1, $per_page=0)
	{
		static $obj_ci;
		$obj_ci = &get_instance();
		
		if (is_object($obj_ci) && trim($text_search) !== '') 
		{
			$text_search = trim(ereg_replace("[[:space:]]+",' ',$text_search));
			$words = explode(' ',$text_search);
			$str_like = '';
			$count_words = count($words);
				if ($count_words === 1)
				{
					$str_like = "tag LIKE '%".addslashes($text_search)."%'";
				} else {
					
					for ($i=0; $i < $count_words; $i++)
					{
						if (($count_words-1) == $i)
						{
							$str_like .= "tag LIKE '%".addslashes($words[$i])."%'";
						} else {
								$str_like .= " tag LIKE '%".addslashes($words[$i])."%' OR ";
						}
					}
				}
			
			$page = empty($page)?1:$this->input->xss_clean($page);
			$limit = empty($per_page)?'':' limit '.$per_page*($page-1).','.$per_page;
			
			$query = "SELECT SQL_CALC_FOUND_ROWS * FROM tags WHERE $str_like $limit";
			$result = $obj_ci->db->query($query);
			
			$this->_found_rows = $this->_get_found_rows();
			
				if ( $result->num_rows() > 0 )
				{
					return $result->result();
				} else {
					return FALSE;
				}
		} else {
			return FALSE;
		}
	}
	
}

/* End of file */
