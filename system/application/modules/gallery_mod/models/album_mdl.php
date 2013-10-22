<?php

class Album_mdl extends Model {

	/**
	 * Error string
	 *
	 * @var
	 */
	var $_error = null;

	/**
	 * _setError
	 *
	 * Sets error in class
	 *
	 * @class Album_mdl
	 * @access private
	 * @param string $error_str
	 */
	function _setError($error_str) {
		$this->_error = $error_str;
	}

	/**
	 * getError
	 *
	 * Returns error string
	 *
	 * @class Album_mdl
	 * @access public
	 * @return string Error string
	 */
	function getError() {
		return $this->_error;
	}

	function get_category_albums($cat_id, $moderation_state = 1, $registered = 0, $erotic = -1, $sort_type=1, $cpage=1, $sort_order='', $perpage=''){

    $query = 'select a.* from albums a, album_category_map m where a.album_id=m.album_id and m.category_id='.clean($cat_id);
		if($moderation_state == "all") {
			$query .= " AND (a.moderation_state = '".MOD_NEW."' OR a.moderation_state = '".MOD_APPROVED."' OR a.moderation_state = '".MOD_FEATURED."')";

		} else {
			$query .= ' AND a.moderation_state >= '.$moderation_state;
		}

        $query .= " AND (view_allowed != 0 ) ";

        if ($registered == 0)
            $query.= " AND a.view_allowed !=1 ";
        if ($erotic == -1)
            $query.= " AND a.erotic_p !=1 ";

        $query .= ' order by date_added' ;

     //	if($orderby) {
	  //		$query .= ' order by date_added '.$orderby;
	  //	} else {
	  //		$query .= ' order by date_added desc';
	  //	}
		$query = $this->db->query($query);

		$albums = array();
		$i = 0;
    	if ($query->num_rows() > 0){

   			foreach ($query->result() as $row){
   				$albums[$i]["album"] = $row;
   				$albums[$i]["photos"] = $this->get_album_photos($albums[$i]["album"]->album_id, 2, $moderation_state, 0, $registered,  $erotic, $sort_type , $cpage, $sort_order, $perpage);
                $albums[$i]["count_photos"] = $this->get_album_photos_count($albums[$i]["album"]->album_id, $registered, $erotic, $moderation_state) ;
   				$i++;
   			}

		}
        //  exit();
		return $albums;
	}

	function get_user_albums($user_id, $orderby, $moderation_state = '1', $registered = 0, $erotic = -1){
	    $query = '';
     if(strtolower($moderation_state) == "nodel") {
			$query .= 'SELECT * FROM albums WHERE user_id='.clean($user_id).' AND moderation_state <> -2 ';
		} elseif (strtolower($moderation_state) == "all") {
			$query = 'SELECT * FROM albums WHERE user_id='.clean($user_id);
		} else {
			$query = 'SELECT * FROM albums WHERE user_id='.clean($user_id).' AND moderation_state >= '.$moderation_state;
		}
      //  $query .= " AND !(view_allowed = 0  AND view_password = '') " ;
        if ($registered == 0)
            $query.= " AND view_allowed !=1 ";
        if ($erotic == -1)
            $query.= " AND erotic_p !=1 ";

		if($orderby) {
			$query .= ' ORDER BY date_added '.$orderby;
		} else {
			$query .= ' ORDER BY date_added DESC';
		}
		$query = $this->db->query($query);
		if ($query->num_rows() == 0) return FALSE;

		$albums = $query->result();

		$where = "";
		if(strtolower($moderation_state) == "nodel") {
			$where = " AND moderation_state <> -2 ";
		} elseif (strtolower($moderation_state) == "all") {
			$where = " ";
		} else {
			$where = " AND moderation_state >= $moderation_state ";
		}
		foreach ($albums as $album) {
			$query = "SELECT photos.* FROM photos, photo_album_map WHERE photo_album_map.photo_id = photos.photo_id AND photo_album_map.album_id = ".$album->album_id.$where." ORDER BY photos.date_added desc ";
			$album->photos = $this->db->query($query)->result();
		}
		return $albums;
	}

	/**
	 * get_album_category
	 *
	 * get albums by category
	 *
	 * @author  Popov
	 * @class   Album_mdl
	 * @access  public
	 * @param   int     $album_id
	 * @return  array  array of photos objects
	 */
	function get_album_category($album_id = null) {

		$query = "SELECT
						c.*
					FROM
						albums a,
						categories_tree c,
						album_category_map acm
					WHERE
						acm.category_id = c.id
					AND	acm.album_id = a.album_id";
		if($album_id != null) {
			$query .= " AND	a.album_id = ".$album_id;
		}
		$query = $this->db->query($query);

		$category = array();
		if ($query->num_rows() > 0){
    		$category = $query->result();
		}
		return $category;
	}

	/**
	 * get_album
	 *
	 * get album
	 *
	 * @author  Popov
	 * @class   Album_mdl
	 * @access  public
	 * @param   int     $album_id
	 * @return  object  $album
	 */
	function get_album($album_id) {
		$this->db->where('album_id', $album_id);
		$data['album'] = $this->db->get('albums');
		$album = $data['album']->row();
		return $album;
	}
	
	function get_albums($moderation_state, $registered = 0, $erotic = -1, $user_id = ''){
		
		$query = 'select * from albums';

		if($moderation_state == "all") {
			$query .= " where (moderation_state = '".MOD_NEW."' OR moderation_state = '".MOD_APPROVED."' OR moderation_state = '".MOD_FEATURED."' OR moderation_state = '".MOD_DECLINED."')";

		} else {
			$query .= ' where moderation_state >= '.$moderation_state;
		}

       // $query .= " AND !(view_allowed = 0 AND view_password = '') ";

        if ($registered == 0)
            $query.= " AND view_allowed !=1 ";
        if ($erotic == -1)
            $query.= " AND erotic_p !=1 ";
            
        if (!empty ($user_id))
        	$query.= " AND user_id = " . $user_id;

		$albums = $this->db->query($query)->result();

		foreach ($albums as $album) {
			$query = "SELECT photos.* FROM photos, photo_album_map WHERE photo_album_map.photo_id = photos.photo_id AND photo_album_map.album_id = ".$album->album_id." AND photos.moderation_state >= ".$moderation_state." ORDER BY photos.date_added desc ";
			$album->photos = $this->db->query($query)->result();
		}
		return $albums;
	}

	function get_sitebar_albums($moderation_state, $registered = 0, $erotic = -1, $user_id = ''){
		
		$query = 'SELECT albums.*, category_id, if (on_what=\'album\' and on_what_id = albums.album_id, balls, 0 ) as balls  from albums 
					left join album_category_map on albums.album_id = album_category_map.album_id ';

		$query .= " LEFT JOIN rating_totals on (albums.album_id = rating_totals.on_what_id and on_what='album' ) WHERE ";
               //."  (rating_totals.on_what='album' or rating_totals.on_what is null)" ;
		if($moderation_state == "all") {
			$query .= "  (moderation_state <> " .MOD_DELETED .")";

		} 
		elseif($moderation_state == MOD_DELETED) {
			$query .= "  (moderation_state = ".MOD_DELETED.")";

		}
		else {
			$query .= '  moderation_state >= '.$moderation_state;
		}

        if ($registered == 0)
            $query.= " AND view_allowed !=1 ";
        if ($erotic == -1)
            $query.= " AND erotic_p !=1 ";
            
        if (!empty ($user_id))
        	$query.= " AND user_id = " . $user_id;

        $query.= " ORDER BY user_id ";

		$albums = $this->db->query($query)->result();
		
		if (!empty ($albums))
		{
			foreach($albums as $album)
			{
	    		$albums_id[] = $album->album_id;
	    	}
	    	$albums_id = array_unique($albums_id);
	    	
			if ($album->user_id == $this->db_session->userdata('user_id') )
			{
				$moderation_state = -1;
				$registered = 2;
				$erotic = 0;
			}
			$query = "SELECT count(*) as ptcnt  FROM photos, photo_album_map 
					  WHERE photo_album_map.photo_id = photos.photo_id 
					  AND photo_album_map.album_id IN (".implode(', ', $albums_id ).") 
					  AND photos.moderation_state >= ".$moderation_state." 
					  AND (photos.view_allowed = 0 OR photos.view_allowed >= " . (2 - $registered) . ") 
					  AND photos.erotic_p <= " . ($erotic+1) .
					  " GROUP BY album_id ";
					
			$rez = $this->db->query($query)->result();
			if(!empty($rez)) {
				for ($i = 0 ; $i<count($albums); $i++ )
				{
				if(isset($rez[$i]->ptcnt)) 
					$albums[$i]->works = $rez[$i]->ptcnt;
				else
					$albums[$i]->works = 0;		
				}
			}		
			foreach ($albums as $album) {
					
				if (!empty ($album->photo_id))
				{										
						
					$single = $this->get_cover_photo( $album->photo_id,$moderation_state, $registered,	$erotic);
						 
					if (!empty($single->photo_id)) 
						{
							$album->img = photo_location().date("m", strtotime($single->date_added))."/".$single->photo_id."-sm".$single->extension;
				            $album->p_link = base_url().'photo/view/'. $single->photo_id;
							$album->land = $single->land;
				            if (($single->view_allowed == 0) && ($album->user_id != $this->db_session->userdata('user_id')))
				        	{
				        		$album->img = photo_location()."lock.jpg";
				        		$album->p_link = base_url() . "album/view/" . $album->album_id;
				        		$album->land = true;
				        		$album->nheight = 60;	
				        		$album->nwidth = 65;
				        		$album->margin_top = 0; 
			 	            	$album->margin_bottom = 0; 
			 	            	$album->margin_left = 0 ; 
			 	            	$album->margin_right = 0 ; 
				        	}
				        	else
				        	{
					        	if ($single->land) 
				 	            {
				 	            	$album->nheight = $single->sm_height/2.23;
				 	            	$album->nwidth = 65;
				 	            	$album->margin_top = 0.33 * (60 - $album->nheight) ; 
				 	            	$album->margin_bottom = 0.66 * (60 - $album->nheight) ; 
				 	            	$album->margin_left = 0 ; 
				 	            	$album->margin_right = 0 ; 
				 	            }  
				 	          	else
				 	          	{	
				 	          		$album->nheight = 60;
				 	          		$album->nwidth = $single->sm_width/2.3;
				 	          		$album->margin_top = 0; 
				 	            	$album->margin_bottom = 0; 
				 	            	$album->margin_left = 0.25 * (65 - $album->nwidth) ; 
				 	            	$album->margin_right = 0.75 * (65 - $album->nwidth) ; 			 	            	
				 	            } 	
				        	}	//if !(($single->view_allowed == 0) && ($album->user_id != $this->db_session->userdata('user_id')))	 	            
						}	//if (!empty($single->photo_id)) 
						else
							{
								$album->img = "";
						        $album->p_link = "";
							}
				}
				else
				{
					$album->img = "";
			        $album->p_link = ""; 
				}			
			}
		}
		return $albums;
	}
	/**
	 * get_album
	 *
	 * get declined albums
	 *
	 * @param int $user_id
	 * @param atring $moderation_state
	 * @return object
	 *
	 * @todo добавить проверку прав пользователя на просмотр удаленных альбомов
	 */
	function get_declined_albums($per_page = 0, $page = 1, $user_id, $moderation_state = null){
		if(!$user_id) return false;
		$page = empty($page)?1:$page;
		$limit = empty($per_page)?'':' limit '.$per_page*($page-1).','.$per_page;

		$query = "select a.*, c.category_id as category from albums a, album_category_map acm, categories c where a.moderation_state = '".MOD_DECLINED."' and a.album_id = acm.album_id and acm.category_id = c.category_id".$limit;
		$result = $this->db->query($query)->result();
		foreach ($result as $album) {
			$query = "SELECT photos.* FROM photos, albums, photo_album_map WHERE photo_album_map.photo_id = photos.photo_id AND photo_album_map.album_id = albums.album_id AND photo_album_map.album_id = ".$album->album_id." ORDER BY photos.date_added desc ";
			$album->photos = $this->db->query($query)->result();
		}
		return $result;
	}

	function get_declined_albums_count(){
		$query = "select a.*, c.category_id as category from albums a, album_category_map acm, categories c where a.moderation_state = '".MOD_DECLINED."' and a.album_id = acm.album_id and acm.category_id = c.category_id";

		$result = $this->db->query($query)->result();

		return count($result);
	}

	/**
	 * get_album_photos
	 *
	 * get album photos
	 *
	 * @author  Popov
	 * @class
	 * @access  public
	 * @param   int     $album_id
	 * @return  array  $photos	array of objects
	 */

	function get_album_photos($album_id, $per_page=0, $moderation_state, $user_id = null, $registered = 0,  $erotic = -1,  $sort_type=1 , $page=1, $sort_order='', $perpage ='') {
		$page = empty($page)?1:$page;
		$limit = empty($per_page)?'':' limit '.$per_page*($page-1).','.$per_page;

	 	if ( $this->get_album_user ($album_id) == $this->db_session->userdata('user_id') )
        {
	        $moderation_state = "all";
	        $registered = 1;
	        $erotic = 1;
        }
        
		$query = "SELECT photos.*, login,  count(commented_object_id) as comcnt,  
					rating_totals.see_cnt, 
					rating_totals.balls,
					if (NOT ISNULL(nv.num_votes), nv.num_votes, 0)  AS vote, 
					if( photos.md_width >photos.md_height,true,false) as land "
                ." FROM photos left join rating_totals on photos.photo_id = rating_totals.on_what_id "
                ." LEFT JOIN comments_tree ct 
                   ON (ct.commented_object_id = photos.photo_id AND ct.commented_object_type = 'photo')
                   LEFT JOIN votes nv ON  photos.photo_id = nv.on_what_id "   
                . ", users, albums, photo_album_map "                
                ." WHERE photo_album_map.photo_id = photos.photo_id AND users.user_id = photos.user_id AND photo_album_map.album_id = albums.album_id AND photo_album_map.album_id = ".$album_id
                ." AND (rating_totals.on_what='foto' or rating_totals.on_what is null)" ;
                  	
        if($moderation_state == "all") {
			$query .= " AND ( photos.moderation_state = '".MOD_NEW."' OR photos.moderation_state = '".MOD_APPROVED."' OR photos.moderation_state = '".MOD_FEATURED."')";
		} else {
			$query .= ' AND photos.moderation_state >= '.$moderation_state;
		}
        if ($registered == 0)
            $query .= ' AND photos.view_allowed !=1';
        if ($erotic == -1)
            $query .= ' AND photos.erotic_p !=1';
		if($user_id) {
			$query .= ' AND photos.user_id = '.$user_id;
		}
		
		$query.= " GROUP BY photos.photo_id ";
			
		switch ($sort_type) {
			case 2: $query.= ' ORDER BY photos.date_added '; //sort by date
				break;
			case 1: $query.= ' ORDER by photos.title '; //sort by title
				break;
			case 3: $query.= ' ORDER BY vote '; //sort by rating
				break;
            //  default: $query.= $limit; // 'ORDER BY RAND()'.$limit;     //default sort
		}
		if ($sort_order == "d")
		$query.= 'DESC ';
		$query.= $limit;

		$photos = $this->db->query($query)->result();
		return $photos;
	}

	/**
	 * get_declined_album_photos
	 *
	 * get declined album photos
	 *
	 * @author  Popov
	 * @class   
	 * @access  public
	 * @param   int     $album_id  
	 * @return  array  $photos	array of objects
	 */
	function get_declined_album_photos($album_id, $per_page = 0, $page = 1, $user_id) {
		if(!$user_id) return false;
		
		$page = empty($page)?1:$page;
		$limit = empty($per_page)?'':' limit '.$per_page*($page-1).','.$per_page;
		
		$query = "SELECT photos.* FROM photos, albums, photo_album_map WHERE photo_album_map.photo_id = photos.photo_id AND photo_album_map.album_id = albums.album_id AND photo_album_map.album_id = ".$album_id;
		$query .= ' AND photos.moderation_state = '.MOD_DECLINED;
		$query .= ' AND photos.user_id = '.$user_id;		
		$query .= " ORDER BY photos.date_added desc ".$limit;

		$query = $this->db->query($query);
		if ($query->num_rows() > 0) {
    		$photos = $query->result();
    		return $photos;
		} else {
			return null;
		}
		return $photos;
	}
	
	/**
	 * get_album_photos_count
	 *
	 * get album photos
	 *
	 * @author  Popov
	 * @class   
	 * @access  public
	 * @param   int     $album_id  
	 * @return  array  $photos	array of objects
	 */
	function get_album_photos_count($album_id, $registered = 0, $erotic = -1, $moderation_state = "all" ) {

	 if ( $this->get_album_user ($album_id) == $this->db_session->userdata('user_id') )
        {
	        $moderation_state = "all";
	        $registered = 1;
	        $erotic = 1;
        }
        
	   //	$query = "SELECT photos.* FROM photos, albums, photo_album_map WHERE photo_album_map.photo_id = photos.photo_id AND photo_album_map.album_id = albums.album_id AND photo_album_map.album_id = ".$album_id." AND photos.moderation_state <> -1 ORDER BY photos.date_added desc ";
        	$query = "SELECT photos.* FROM photos, albums, photo_album_map WHERE photo_album_map.photo_id = photos.photo_id AND photo_album_map.album_id = albums.album_id AND photo_album_map.album_id = ".$album_id;

	  if($moderation_state == "all") {
			$query .= " AND (photos.moderation_state = '".MOD_NEW."' OR photos.moderation_state = '".MOD_APPROVED."' OR photos.moderation_state = '".MOD_FEATURED."' OR photos.moderation_state = '".MOD_DECLINED."')";

		} else {
			$query .= ' AND photos.moderation_state >= '.$moderation_state;
		}
      if ($registered == 0)
       $query .= ' AND photos.view_allowed != 1 ';
      if ($erotic == -1)
       $query .= ' AND photos.erotic_p != 1 ';

      $photos = $this->db->query($query)->result();
	  return count($photos);
	}
	
	/**
	 * add
	 *
	 * add new album
	 *
	 * @author  Popov
	 * @class   Album_mdl
	 * @access  public
	 * @param   arary   $info  array of $_REQUEST
	 * @param   int     $user_id
	 * @return  object  $album
	 */
    function add($info, $user_id) {
		$this->db->where('title', $info['album_title']);
		$this->db->where('user_id', $user_id);
		$album = $this->db->get('albums')->row();
        
		if(!$album) {
			$ip = $_SERVER['REMOTE_ADDR'];
	  		if(empty($ip)) $ip = '0.0.0.0';
	  		$date = date("Y-m-d H:i:s");
	  		$erotic_p = 0;
	  		
	  		if(isset($info['erotic_p']) && !empty($info['erotic_p'])) {
	  			$erotic_p = $info['erotic_p'];
	  		}
			
			$album = array(
				"title"	=> htmlspecialchars(trim($info['album_title'])),
				"short_description"	=>	htmlspecialchars(trim(substr($info['album_description'], 0, 20))),
				"description"	=>	htmlspecialchars(trim($info['album_description'])),
				"user_id"	=>	$user_id,
				"date_added"	=>	$date,
				"added_from_ip"	=>	$ip,
				"view_allowed"	=>	$info['view_allowed'],
				"erotic_p"	=>	$erotic_p
			);
			if (!empty($info['view_password']) && ($info['view_allowed'] == 0)) {
				$album['view_password'] = addslashes(trim($info['view_password']));
			}
			$this->db->insert('albums', $album);
			$album_id = $this->db->insert_id();
			
			$album_category_map = array(
				"album_id"	=>	$album_id,
				"category_id"	=>	$info['album_cat']			
			);
			$this->db->insert('album_category_map', $album_category_map);
						
			return $album_id;
		} else {
			return 0;
			//$this->_setError(lang('error_album_is_exist'));
		}
	}
	
	/**
	 * edit
	 *
	 * edit an album
	 *
	 * @author  Popov
	 * @class   Album_mdl
	 * @access  public
	 * @param   array     $info  array of $_REQUEST
	 * @param   int     $album_id
	 * @param   int     $user_id
	 * @return  object  $album
	 */
	function edit($info, $album_id, $user_id) {
		if($album_id == null) return null;

		$album = $this->get_album($album_id);

		$this->db->where('album_id', $album_id);
		$data['cat_map'] = $this->db->get('album_category_map');
		$cat_map = $data['cat_map']->row();
		
		if(!empty($album) && !empty($info)) {
			$ip = $_SERVER['REMOTE_ADDR'];
	  		if(empty($ip)) $ip = '0.0.0.0';
	  		$date = date("Y-m-d H:i:s");
	  		$erotic_p = 0;
	  		if(isset($info['erotic_p']) && !empty($info['erotic_p'])) {
	  			$erotic_p = $info['erotic_p'];
	  		}
	  		$password = null;
	  		if(!empty($info['album_password'])) {
	  			$password = addslashes(trim($info['album_password']));
	  		}
			
			$album_arr = array(
				"title"	=>	htmlspecialchars(trim($info['album_title'])),
				"short_description"	=>	htmlspecialchars(trim(substr($info['album_description'], 0, 20))),
				"description"	=>	htmlspecialchars(trim($info['album_description'])),
				"user_id"	=>	$user_id,
				"date_modified"	=>	$date,
				"view_allowed"	=>	$info['view_allowed'],
				"erotic_p"	=>	$erotic_p,
				"view_password"	=> $password
			);
			$this->db->where('album_id', $album->album_id);
			$this->db->update('albums', $album_arr);
			
			$album_category_map = array(
			"album_id"	=>	$album->album_id,
			"category_id"	=>	$info['album_cat']		
			);
			
			$this->db->where('album_id', $album->album_id);
			$this->db->where('category_id', $cat_map->category_id);
			$this->db->update('album_category_map', $album_category_map);
			
			return $album;
		}		
	}
	
	/**
	 * decline
	 *
	 * decline album
	 *
	 * @author  Popov
	 * @class   Album_mdl
	 * @access  public
	 * @param   int     $album_id  
	 * @param   int     $user_id  
	 * @return  boolean  
	 */
	function decline($album_id, $user_id) {
		if($album_id == null || $user_id == null) return false;
		
		$album = $this->get_album($album_id);

		if(!empty($album) && $album->user_id == $user_id) {			
			// marking album as decliened
			$this->db->where('album_id', $album->album_id);
			$this->db->update('albums', array('moderation_state' => -1)); 
			
			
			/*$tables = array('albums', 'album_tag_map', 'album_category_map');
			$this->db->where('album_id', $album_id);
			$this->db->delete($tables);*/
			
			// delete photo form album form photos, photo_views, photo_tag_map, photo_category_map, photo_album_map
			
			$album_photos = $this->get_album_photos($album_id, 0, 1, 'all');
			if(!empty($album_photos)) {
				foreach ($album_photos as $photo) {
					$this->photo->decline($photo->photo_id, $user_id);
				}
			}					
			return true;
			
		}
	}
	
	function undecline($album_id, $user_id) {		
		if($album_id == null || $user_id == null) return false;
		
		$album = $this->get_album($album_id);
		
		if(!empty($album) && $album->user_id == $user_id) {			
			// marking album as decliened
			
			$this->db->where('album_id', $album->album_id);
			$this->db->update('albums', array('moderation_state' => 0));
			
			// delete photo form album form photos, photo_views, photo_tag_map, photo_category_map, photo_album_map
//			$album_photos = $this->get_album_photos($album_id, 0, 1, 'all');
			/*if(!empty($album_photos)) {
				foreach ($album_photos as $photo) {
					$this->photo->undecline($photo->photo_id, $user_id);
				}
			}*/					
			return true;
			
		}
	}
	
	/**
	 * delete
	 *
	 * delete album
	 *
	 * @author  Popov
	 * @class   Album_mdl
	 * @access  public
	 * @param   int     $album_id  
	 * @param   int     $user_id  
	 * @return  boolean  
	 */
	function delete($album_id, $user_id) {
		if($album_id == null || $user_id == null) return false;

		$data = array(
			'moderation_state' => '-2',
			'date_modified'    => date("Y-m-d H:i:s")
		);
		$this->db->where('album_id', $album_id);
		$this->db->update('albums', $data);
	}

	function remove_album($album_id){
		try {
			$query = "select * from albums where album_id=".clean($album_id);
			$album = $this->db->query($query)->row();			
			
			if($album) {
				
				$this->db->where('album_id', $album_id);
				if(!$this->db->delete('albums'))
					throw new Exception($this->db->_error_message());
					
				$this->db->where('album_id', $album_id);
				if(!$this->db->delete('album_category_map'))
					throw new Exception($this->db->_error_message());
					
				$this->db->where('album_id', $album_id);
				if(!$this->db->delete('album_tag_map'))
					throw new Exception($this->db->_error_message());
				
				$query = "select * from photo_album_map where album_id=".clean($album_id);
				$photos = $this->db->query($query)->result();
				
				if($photos){
					
					foreach ($photos as $photo) {
						
						$query = "select * from photos where photo_id=".clean($photo->photo_id);
						$photo = $this->db->query($query)->row();
						
						if($photo) {
						
							$this->db->where('photo_id', $photo->photo_id);
							if(!$this->db->delete('competition_photos'))
								throw new Exception($this->db->_error_message());
							
							$this->db->where('photo_id', $photo->photo_id);
							if(!$this->db->delete('photo_album_map'))
								throw new Exception($this->db->_error_message());
								
							$this->db->where('photo_id', $photo->photo_id);
							if(!$this->db->delete('photo_category_map'))
								throw new Exception($this->db->_error_message());
							
							$this->db->where('photo_id', $photo->photo_id);
							if(!$this->db->delete('photo_tag_map'))
								throw new Exception($this->db->_error_message());
							
							$this->db->where('photo_id', $photo->photo_id);
							if(!$this->db->delete('photo_views'))
								throw new Exception($this->db->_error_message());
								
							$this->db->where('photo_id', $photo->photo_id);
							if(!$this->db->delete('photos'))
								throw new Exception($this->db->_error_message());
													
		    	           	$head = dirname(BASEPATH).'/uploads/photos/'.date('m', strtotime($photo->date_added)).'/'.$photo->photo_id.'-head'.$photo->extension;
		    	           	$lg = dirname(BASEPATH).'/uploads/photos/'.date('m', strtotime($photo->date_added)).'/'.$photo->photo_id.'-lg'.$photo->extension;
		    	           	$md = dirname(BASEPATH).'/uploads/photos/'.date('m', strtotime($photo->date_added)).'/'.$photo->photo_id.'-md'.$photo->extension;
		    	           	$sm = dirname(BASEPATH).'/uploads/photos/'.date('m', strtotime($photo->date_added)).'/'.$photo->photo_id.'-sm'.$photo->extension;    	           
							
		    	           	if(is_file($head)) {
								unlink($head);
							}
							if(is_file($lg)) {
								unlink($lg);
							}
							if(is_file($md)) {
								unlink($md);
							}
							if(is_file($sm)) {
								unlink($sm);
							}
						}						
					}
				}
			}
			return true;
		} catch (Exception $e) {
    		log_message('error',$e->getMessage()."\n".
								"file: ".$e->getFile()."\n".
								"code: ".$e->getCode()."\n".
								"line: ".$e->getLine());
    	}
    	return false;
	}
	
	/**
	 * get_all_photos_per_user
	 * 
	 * @author Tsapenco
	 * @class  Album_mdl
	 * @return array
	 * @param  int     $user_id
	 * @param  int     $moderation_state
	 */
	
	function get_all_photos_per_user($user_id, $per_page=0, $page=1, $moderation_state='all', $sort=1, $order = "desc", $registered=1, $erotic=1){
		$page = empty($page)?1:$page;
		$limit = empty($per_page)?'':' limit '.$per_page*($page-1).','.$per_page;

		$query = "SELECT SQL_CALC_FOUND_ROWS photos.*,if( photos.md_width > photos.md_height,true,false) as land,
							if (NOT ISNULL(rating_totals.balls), rating_totals.balls, 0)  AS balls, 
							if (NOT ISNULL(nv.num_votes), nv.num_votes, 0)  AS vote, 
							if (NOT ISNULL(rating_totals.see_cnt), rating_totals.see_cnt, 0) AS see_cnt, 
							photo_album_map.album_id, 
							photo_category_map.category_id 
								FROM photos 
								LEFT JOIN rating_totals ON (photos.photo_id = rating_totals.on_what_id AND rating_totals.on_what = 'foto')  
								LEFT JOIN photo_album_map USING (photo_id) 
								LEFT JOIN photo_category_map USING (photo_id) 
								LEFT JOIN votes nv ON  photos.photo_id = nv.on_what_id 
								WHERE photos.user_id = $user_id";
		
		if($moderation_state == MOD_DELETED) {
			$query .= ' AND photos.moderation_state = -2 ';
		}
		elseif ($moderation_state == "nodel") {
			$query .= ' AND photos.moderation_state <> -2 ';
		} elseif($moderation_state != "all") {
			$query .= ' AND photos.moderation_state >= '.$moderation_state.' ';
		}
		
		if ($registered == 0)
            $query .= ' AND photos.view_allowed !=1';
        if ($erotic == -1)
            $query .= ' AND photos.erotic_p !=1';
            
		$order = strtoupper(trim($order));
		$order = ($order == "ASC")? "ASC": "DESC";
		switch ($sort) {
			case 1://By title
				$query .= " ORDER BY photos.title $order ".$limit;
				break;
			case 2://By date
				$query .= " ORDER BY photos.date_added $order ".$limit;
				break;
			case 3://By rating
				$query .= " ORDER BY vote $order ".$limit;;
				break;
			default:
				$query .= " ORDER BY photos.date_added $order ".$limit;
				break;
		}//echo $query;exit;
		$query = $this->db->query($query);
		if ( ! $query) return FALSE;
		$photos = $query->result();
		
		if(!empty($photos)) {
    		
    		$juries = $this->config->load('../modules/gallery_mod/config/config');
			$jury_logins = $this->config->item('jury_logins');
			$user_login = $this->db_session->userdata('user_login');
			
			$juries_str = "";
			if(!empty($jury_logins)) {
				if(in_array($user_login, $jury_logins)) {
					$key = array_search($user_login, $jury_logins);
					unset ($jury_logins[$key]);
					foreach ($jury_logins as &$login) {
						$login = "'".$login."'";
					}		
					$juries_str = ' AND users.login in('.implode(",", $jury_logins).')';
					
				} else {
					foreach ($jury_logins as &$login) {
						$login = "'".$login."'";
					}		
					$juries_str = ' AND users.login not in('.implode(",", $jury_logins).')';
				}
			}
			
    		foreach ($photos as &$photo) {
    			$query = "select 
					count(comments_tree.body)
				from
					photos,
					users,
					comments_tree
				where
					comments_tree.commented_object_type = 'photo'
				and	comments_tree.user_id=users.user_id
				and	comments_tree.commented_object_id = photos.photo_id 
				AND comments_tree.commented_object_id = '".$photo->photo_id."'";
    			$query .= $juries_str;
    			
    			$query = $this->db->query($query);
    			$photo->see_cnt = $query->row()->comcnt;
    		}
    	}
		
		$query = $this->db->query("select found_rows() as count");
		if ( ! $query) return FALSE;
		$photos['count'] = $query->row()->count;
		
		return $photos;
	}

	function get_albums_main_photo_list($albums_ids){     
		if (!is_array($albums_ids)) return array();
		foreach($albums_ids as $idx => $id) {
			if (empty($id)) unset($albums_ids[$idx]);
		}
		if (empty($albums_ids)) return array(); 
		$query = "SELECT DISTINCT albums.photo_id FROM albums WHERE album_id IN (".implode(', ', $albums_ids).")";
		return $this->db->query($query)->result();
	}
	
	function get_my_albyms($user_id, $moderation_state="all", $registered=0, $erotic=-1, $private_my =0 ) {
		$query = "SELECT albums.*,if( photos.md_width >photos.md_height,true,false) as land, COUNT(photos.photo_id) as p_count,
							IF (ISNULL(album_category_map.category_id), 0, album_category_map.category_id) AS category_id
								FROM albums LEFT JOIN photo_album_map USING (album_id)
								LEFT JOIN photos ON (photo_album_map.photo_id = photos.photo_id)
								LEFT JOIN album_category_map ON (albums.album_id = album_category_map.album_id)
									WHERE albums.user_id = $user_id ";
        if ($private_my == 0)
            $query .= " AND !(albums.view_allowed = 0 AND albums.view_password = '') ";
        if ($registered == 0)
            $query.= " AND albums.view_allowed !=1 ";
        if ($erotic == -1)
            $query.= " AND albums.erotic_p !=1 ";

		if($moderation_state == "nodel") {
			$query .= ' AND albums.moderation_state <> -2 ';
		} elseif ($moderation_state != 'all') {
			$query .= " AND albums.moderation_state = '$moderation_state' ";
		}
		$query .= " GROUP BY albums.album_id";
		return $this->db->query($query)->result();
	}

	function set_album_main_photo($album_id, $photo_id){
		$query = "UPDATE albums SET albums.photo_id = '$photo_id' WHERE albums.album_id = '$album_id'";
		return $this->db->query($query);
	}

	function clear_album_main_photo($album_id, $photo_id){
		$query = "UPDATE albums SET albums.photo_id = '0' WHERE albums.album_id = '$album_id' AND albums.photo_id = '$photo_id'";
		return $this->db->query($query);
	}

	function get_deleted_albums_count_per_user($user_id) {
		$query = "SELECT COUNT(*) AS cnt FROM albums WHERE albums.user_id = '$user_id' AND albums.moderation_state = ".MOD_DELETED;
		$result = $this->db->query($query)->result();
		return $result[0]->cnt;
	}

	function revert_album($album_id, $user_id) {
		$info = $this->get_album($album_id);
		if (empty($info) || ($info->moderation_state != MOD_DELETED)) return FALSE;
		$data = array(
			'date_modified' => date("Y-m-d H:i:s"),
			'moderation_state' => MOD_NEW
		);
		$this->db->where('album_id', $album_id);
		$this->db->update('albums', $data);
		$photos = $this->album->get_album_photos($album_id, 999999999999, MOD_DELETED, $user_id);
		if (!empty($photos)) {
			foreach($photos as $photo) {
				$data = array(
					'date_modified' => date("Y-m-d H:i:s"),
					'moderation_state' => MOD_NEW
				);
				$this->db->where('photo_id', $photo->photo_id);
				$this->db->update('photos', $data);
			}
		}
	}

	function get_admin_albumss_list($sort_type=1, $sort_order='asc', $cpage=1, $per_page, $search_criteria=array()) {
		$limit = empty($per_page)?'':'LIMIT '.$per_page*($cpage-1).','.$per_page;
		$query = "SELECT a.*, u.login AS username, c.name as catname ".
							"FROM albums a, users u, album_category_map a2c, categories_tree c ".
							"WHERE a.user_id = u.user_id ".
							"AND a.album_id = a2c.album_id ".
							"AND a2c.category_id = c.id ";
		if (!empty($search_criteria)) {
			if (!empty($search_criteria['category'])) {
				$query .= "AND c.id = '".$search_criteria['category']."' ";
			}
			if (!empty($search_criteria['username'])) {
				$query .= "AND u.login LIKE '%".$search_criteria['username']."%' ";
			}
			if (!empty($search_criteria['title'])) {
				$query .= "AND a.title LIKE '%".$search_criteria['title']."%' ";
			}
			if (!empty($search_criteria['date']['start']) && !empty($search_criteria['date']['end'])) {
				$query .= "AND a.date_added BETWEEN '".$search_criteria['date']['start']."' AND '".$search_criteria['date']['end']."' ";
			}
			if ($search_criteria['moderation'] != -999) {
				$query .= "AND a.moderation_state = '".$search_criteria['moderation']."' ";
			}
		}
		$sort_order = (strtolower($sort_order) == "desc")? "DESC": "ASC";
		$query .= "ORDER BY a.date_added $sort_order ";
		$cnt = $this->db->query($query)->num_rows();
		$query .= $limit;
		return array(
			'cnt' => $cnt,
			'albums' => $this->db->query($query)->result()
		);
	}

	function update($data) {
		if ( empty($data) || ! is_array($data) || empty($data['album_id']) ) return FALSE;
		$this->db->where('album_id', $data['album_id']);
		$res = $this->db->update('albums', $data);
		if ( ! $res) return FALSE;

		return TRUE;
	}
	
	function get_cover_photo ( $photo_id, $moderation_state, $registered,	$erotic){
		$query = "SELECT *,if( photos.md_width >photos.md_height,true,false) as land FROM photos WHERE photos.photo_id = ". $photo_id ." AND photos.moderation_state >= ".$moderation_state ." AND ( photos.view_allowed = 0 OR photos.view_allowed >= " . (2 - $registered) . ") AND photos.erotic_p <= " . ($erotic+1);
		$result = $this->db->query($query)->result();
		if ($result) 
			return $result[0];
		else
			return false;
	}
	
 	function get_album_user ($album_id){
        if  (empty ($album_id)){
        	$this->_setError(lang('error_empty_request'));
            return false;
            }
        $query = "SELECT albums.user_id FROM albums WHERE album_id = " . $album_id;

      	$query = $this->db->query($query);
      	$user = $query->result();
        if ($user) 
			return ($user[0]->user_id);
    }
    
    
	function get_category_photos($cat_id, $per_page=0, $moderation_state, $registered = 0,  $erotic = -1,  $sort_type=1 , $page=1, $sort_order='', $perpage ='') {
		$page = empty($page)?1:$page;
		$limit = empty($per_page)?'':' limit '.$per_page*($page-1).','.$per_page;
       
		$query = " SELECT photos.*,  count(commented_object_id) as comcnt, login, 
		 			if (NOT ISNULL(nv.num_votes), nv.num_votes, 0)  AS vote, 
					rating_totals.balls, rating_totals.see_cnt, 
					if( photos.md_width >photos.md_height,true,false) as land "
                ." FROM photos left join rating_totals on photos.photo_id = rating_totals.on_what_id "
                ." LEFT JOIN comments_tree ct ON (ct.commented_object_id = photos.photo_id AND ct.commented_object_type = 'photo')
               	   LEFT JOIN votes nv ON  photos.photo_id = nv.on_what_id , "                               
                ." users, categories_tree, photo_category_map "
                ." WHERE photo_category_map.photo_id = photos.photo_id AND users.user_id = photos.user_id AND photo_category_map.category_id = categories_tree.id AND photo_category_map.category_id = ".$cat_id
                ." AND (rating_totals.on_what='foto' or rating_totals.on_what is null)" ;
                  	
        if($moderation_state == "all") {
			$query .= " AND ( photos.moderation_state > -1 )";
		} else {
			$query .= ' AND photos.moderation_state >= '.$moderation_state;
		}
        if ($registered == 0)
            $query .= ' AND photos.view_allowed !=1';
        if ($erotic == -1)
            $query .= ' AND photos.erotic_p !=1';
            
       $query.= " GROUP BY photos.photo_id ";
       
		switch ($sort_type) {
			case 2: $query.= ' ORDER BY photos.date_added '; //sort by date
				break;
			case 1: $query.= ' ORDER by photos.title '; //sort by title
				break;
			case 3: $query.= ' ORDER BY vote '; //$query.= ' ORDER BY rating_totals.balls '; //sort by rating
				break;
            //  default: $query.= $limit; // 'ORDER BY RAND()'.$limit;     //default sort
		}
		if ($sort_order == "d")
		$query.= 'DESC ';
		$query.= $limit;

		$photos = $this->db->query($query)->result();
		return $photos;
	}
       
	function get_category_photos_count($cat_id, $registered = 0, $erotic = -1, $moderation_state = "all" ) {

	  $query = "SELECT photos.photo_id FROM photos, categories_tree, photo_category_map WHERE photo_category_map.photo_id = photos.photo_id AND photo_category_map.category_id = categories_tree.id AND photo_category_map.category_id = ".$cat_id;

	  if($moderation_state == "all") {
			$query .= " AND (photos.moderation_state = '".MOD_NEW."' OR photos.moderation_state = '".MOD_APPROVED."' OR photos.moderation_state = '".MOD_FEATURED."' OR photos.moderation_state = '".MOD_DECLINED."')";

		} else {
			$query .= ' AND photos.moderation_state >= '.$moderation_state;
		}
      if ($registered == 0)
       $query .= ' AND photos.view_allowed != 1 ';
      if ($erotic == -1)
       $query .= ' AND photos.erotic_p != 1 ';

      $photos = $this->db->query($query);      
	  return $photos->num_rows(); 
	}


	function get_all_user_albums ($user_id, $moderation_state, $registered = 0, $erotic = -1, $per_page, $page, $sort_type, $sort_order){
		
		$page = empty($page)?1:$page;
		$limit = empty($per_page)?'':' limit '.$per_page*($page-1).','.$per_page;
        
		$query = 'select album_category_map.category_id, albums.*, album_category_map.category_id from albums left join album_category_map on album_category_map.album_id = albums.album_id ';

		if($moderation_state == "all") {
			$query .= " where (moderation_state = '".MOD_NEW."' OR moderation_state = '".MOD_APPROVED."' OR moderation_state = '".MOD_FEATURED."' OR moderation_state = '".MOD_DECLINED."')";

		} else {
			$query .= ' where moderation_state >= '.$moderation_state;
		}

        $query .= " and album_category_map.category_id is not null ";

        if ($registered == 0)
            $query.= " AND view_allowed !=1 ";
        if ($erotic == -1)
            $query.= " AND erotic_p !=1 ";
            
        if (!empty ($user_id))
        	$query.= " AND user_id = " . $user_id;
        	
        switch ($sort_type) {
			case 2: $query.= ' ORDER BY date_added '; //sort by date
				break;
			case 1: $query.= ' ORDER by title '; //sort by title
				break;
            //  default: $query.= $limit; // 'ORDER BY RAND()'.$limit;     //default sort
		}
		if ($sort_order == "d")
		$query.= 'DESC ';
		$query.= $limit;
		
		$albums = $this->db->query($query)->result();
		
		//count albums for pagination
		$query = 'select count(*) as acnt from albums';

		if($moderation_state == "all") {
			$query .= " where (moderation_state = '".MOD_NEW."' OR moderation_state = '".MOD_APPROVED."' OR moderation_state = '".MOD_FEATURED."' OR moderation_state = '".MOD_DECLINED."')";

		} else {
			$query .= ' where moderation_state >= '.$moderation_state;
		}

        if ($registered == 0)
            $query.= " AND view_allowed !=1 ";
        if ($erotic == -1)
            $query.= " AND erotic_p !=1 ";
            
        if (!empty ($user_id))
        	$query.= " AND user_id = " . $user_id;
        	
        $count_albums = $this->db->query($query)->result();
      
        if(!empty($albums)) {
			foreach ($albums as $album) {
			
				if ($album->user_id == $this->db_session->userdata('user_id') )
				{
					$moderation_state = -1;
					$registered = 2;
					$erotic = 0;
				}
				$query = "SELECT count(*) as ptcnt  FROM photos, photo_album_map WHERE photo_album_map.photo_id = photos.photo_id AND photo_album_map.album_id = ".$album->album_id." AND photos.moderation_state >= ".$moderation_state." AND (photos.view_allowed = 0 OR photos.view_allowed >= " . (2 - $registered) . ") AND photos.erotic_p <= " . ($erotic+1) ;
				$rez = $this->db->query($query)->result();
				$album->works = $rez[0]->ptcnt;
			
				if (!empty ($album->photo_id))
				{		
													
						$single = $this->get_cover_photo( $album->photo_id,$moderation_state, $registered,	$erotic);
						if (!empty($single->photo_id)) 
							{
								$album->img = photo_location().date("m", strtotime($single->date_added))."/".$single->photo_id."-sm".$single->extension;
					            $album->p_link = base_url().'photo/view/'. $single->photo_id;
								$album->land = $single->land;
					            if (($single->view_allowed == 0) && ($album->user_id != $this->db_session->userdata('user_id')))
					        	{
					        		$album->img = photo_location()."lock.jpg";
					        		$album->p_link = base_url() . "album/view/" . $album->album_id;
					        		$album->land = true;
					        		$album->nheight = 60;	
					        		$album->nwidth = 65;
					        		$album->margin_top = 0; 
				 	            	$album->margin_bottom = 0; 
				 	            	$album->margin_left = 0 ; 
				 	            	$album->margin_right = 0 ; 
					        	}
					        	else
					        	{
						        	if ($single->land) 
					 	            {
					 	            	$album->nheight = $single->sm_height/2.23;
					 	            	$album->nwidth = 65;
					 	            	$album->margin_top = 0.33 * (60 - $album->nheight) ; 
					 	            	$album->margin_bottom = 0.66 * (60 - $album->nheight) ; 
					 	            	$album->margin_left = 0 ; 
					 	            	$album->margin_right = 0 ; 
					 	            }  
					 	          	else
					 	          	{	
					 	          		$album->nheight = 60;
					 	          		$album->nwidth = $single->sm_width/2.3;
					 	          		$album->margin_top = 0; 
					 	            	$album->margin_bottom = 0; 
					 	            	$album->margin_left = 0.25 * (65 - $album->nwidth) ; 
					 	            	$album->margin_right = 0.75 * (65 - $album->nwidth) ; 			 	            	
					 	            } 	
					        	}	//if !(($single->view_allowed == 0) && ($album->user_id != $this->db_session->userdata('user_id')))	 	            
							}	//if (!empty($single->photo_id)) 
				}
				else
				{
					$album->img = "";
			        $album->p_link = ""; 
				}
			}			
		}
		$albums['albcnt'] = $count_albums[0]->acnt;
		return $albums;
	}
	
	function get_user_albums_info($user_id){
		$query = 'SELECT * FROM albums WHERE user_id='.clean($user_id);	
		$query = $this->db->query($query);
		if ($query->num_rows() == 0) return FALSE;
		$albums = $query->result();
		return $albums;
	}
}

	
/* End of file */

