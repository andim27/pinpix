<?php
/*
static $ci;
if (!is_object($ci)) $ci = &get_instance();
$ci->load->file(APPPATH.'models/tree_mdl.php');
*/

$path = APPPATH;
modules::load_file('tree_mdl',$path.'/models/');
$path = MODBASE.modules::path();
modules::load_file('tree_mdl',$path.'/models/');

class Comment_mdl extends Tree_mdl {

	function Comment_mdl()
    {
        //parent::Tree_mdl('comments_tree');   
        $this->set_table('comments_tree'); 
    }
	
	function add($commented_object_type, $commented_object_id, $author_id, $comment_title, $comment_text, $parent_id=null){
		$commented_object_type = clean($commented_object_type);
		//$commented_object_id = clean($commented_object_id);
		//$author_id = clean($author_id);
		$comment_title = clean($comment_title);
		$comment_text = clean($comment_text);
		//if ( ! empty($parent_id)) $parent_id = clean($parent_id);
		
		$branch_fields = array('commented_object_type'=>$commented_object_type);
		$item_fields = array(
			'commented_object_id'=>$commented_object_id,
			'user_id'=>$author_id,
			'title'=>$comment_title,
			'body'=>$comment_text,
			'moderation_state'=>'1',
			'comment_date' => "'".date("Y-m-d H:i:s")."'"
		);
		$comment_id = $this->add_item($parent_id, $branch_fields, $item_fields);
    	return $comment_id;
	}
	
	function remove($lft, $rgt, $commented_object_type){
		$commented_object_type = clean($commented_object_type);
		$branch_fields = array('commented_object_type'=>$commented_object_type);
		return $this->delete_branch($lft, $rgt, $branch_fields);
	}
	
	function get_object_comments_admin($commented_object_type='', $commented_object_id=null, $per_page=0, $page=1, $filters=array(),$sort_ord = "", $moderation_state=""){

		if ( ! empty($page)) $page = $this->input->xss_clean($page);

		$conditions = array();
		if ( ! empty($commented_object_type)) $conditions['commented_object_type'] = clean($commented_object_type);
		if ( ! empty($commented_object_id)) $conditions['commented_object_id'] = clean($commented_object_id);
		if ( ! empty($moderation_state)) $conditions['moderation_state'] = clean($moderation_state);
		
		$juries = $this->config->load('../modules/gallery_mod/config/config');
		$jury_logins = $this->config->item('jury_logins');
		
		foreach ($jury_logins as &$login) {
			$login = "'".$login."'";
		}		
		$juries_str = 'users.login in('.implode(",", $jury_logins).')';
		
		$linked_tables=array(
							array(	
								'fields' => 'users.login, users.avatar',
								'from' => 'users',
								'where' => array('comments_tree.user_id=users.user_id', $juries_str)
							),
							array(	
								'fields' => 'photos.date_added, photos.extension, photos.title',
								'from' => 'photos',
								'where' => 'comments_tree.commented_object_id=photos.photo_id'
							)
		);
		

		$comments = $this->get_items($conditions, $per_page, $page, $linked_tables, $filters, $sort_ord, $moderation_state );
		return $comments;
	}

	function get_object_comments($commented_object_type='', $commented_object_id=null, $per_page=0, $page=1, $filters=array(),$sort_ord = "", $moderation_state=null, $filter_jury=true){

		if ( ! empty($page)) $page = $this->input->xss_clean($page);

		$conditions = array();
		if ( ! empty($commented_object_type)) $conditions['commented_object_type'] = clean($commented_object_type);
		if ( ! empty($commented_object_id)) $conditions['commented_object_id'] = clean($commented_object_id);
		if ( ! empty($moderation_state)) $conditions['moderation_state'] = clean($moderation_state);
		
		$juries = $this->config->load('../modules/gallery_mod/config/config');
		$jury_logins = $this->config->item('jury_logins');
		$user_login = $this->db_session->userdata('user_login');
		
		$juries_str = '1=1';
		if(!empty($jury_logins)) {
			if(in_array($user_login, $jury_logins)) {
				$key = array_search($user_login, $jury_logins);
				unset ($jury_logins[$key]);
			}
	
			foreach ($jury_logins as &$login) {
				$login = "'".$login."'";
			}
		
			if($filter_jury) {
				$juries_str = 'users.login not in('.implode(",", $jury_logins).')';			
			} else {
				$juries_str = '1=1';
			}
		}
		
		$moderation_state_str = "";
		if($moderation_state != null) {
			$moderation_state_str = 'comments_tree.moderation_state >= 0';			
		} else {
			$moderation_state_str = '1=1';
		}
		
		$linked_tables=array(
							array(	
								'fields' => 'users.login, users.avatar',
								'from' => 'users',
								'where' => array('comments_tree.user_id=users.user_id', $juries_str, $moderation_state_str)
							),
							array(	
								'fields' => 'photos.date_added, photos.extension, photos.title',
								'from' => 'photos',
								'where' => 'comments_tree.commented_object_id=photos.photo_id'
							)
		);
		
		$comments = $this->get_items($conditions, $per_page, $page, $linked_tables, $filters, $sort_ord, $moderation_state );
		return $comments;
	}

	function get_objects_comments_count($commented_objects_type='', $commented_object_ids = null) {
		if (empty($commented_object_ids) || !is_array($commented_object_ids) || empty($commented_objects_type))
			return FALSE;
		$query = "SELECT COUNT(comments_tree.id) AS c_count, comments_tree.commented_object_id AS id FROM comments_tree
								WHERE comments_tree.commented_object_id IN (".implode(', ', $commented_object_ids).")
								AND comments_tree.commented_object_type = '".$commented_objects_type."'
							GROUP BY comments_tree.commented_object_id";

		$result = $this->db->query($query)->result();
		
		return $result;
	}

	function get_comments_count_per_user($user_id){
		if (!$user_id) return 0;
		$query = "SELECT COUNT(comments_tree.id) AS cnt FROM comments_tree WHERE comments_tree.user_id = $user_id";
		$result = $this->db->query($query)->result();
		return $result[0]->cnt;
	}

    function get_object_comments_count_by_id($commented_object_type='', $commented_object_id=null){
 	    $query = $this->db->query('SELECT * from comments_tree where commented_object_type = "'.$commented_object_type. '" AND commented_object_id = '.$commented_object_id);
        echo $query->num_rows();
       	return ($query->num_rows());
	}
	function get_body_comments($commented_object_id=null,$user_id=null) {
        $commented_object_str="";
        $commented_user_str="";
        $where_str="";
        if (! empty($commented_object_id)){
            $commented_object_str="commented_object_id=".$commented_object_id;
	    }
        if (! empty($user_id)){
            $commented_user_str="user_id=".$user_id;
	    }
        $where_str=" WHERE ".$commented_object_str.((empty($commented_user_str))?"":" AND ".$commented_user_str);
        $query ="SELECT body FROM comments_tree".$where_str;
        return $this->db->query($query)->result();
	}
}

 // select count(*) from comments_tree where  commented_object_type = 'photo' AND commented_object_id = 6

/* End of file */