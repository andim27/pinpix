<?php

class Photo_mdl extends Model {

	function get($photo_id, $moderation_state, $registered = 0, $erotic = -1, $user_id = null) {

		$query = 'select *,if( photos.md_width >photos.md_height,true,false) as land from photos where ';

		if($photo_id) {
			$query .= ' photo_id='.clean($photo_id).' AND';
		}

		if($user_id) {
			$query .= ' user_id = '.$user_id. ' AND';
		}

        if($moderation_state == "all") {
			$query .= " (moderation_state = '".MOD_NEW."' OR moderation_state = '".MOD_APPROVED."' OR moderation_state = '".MOD_FEATURED."')";
		} else {
			$query .= ' moderation_state >= '.$moderation_state;
		}

        $query = $this->db->query($query);
    	$rs = $query->result();

    	if(!empty($rs)) {
    		if(!empty($photo_id)) {
	    		$photo = $rs[0];
                //checking for registration to view some photos
                if (($photo->view_allowed==1) && ($registered == 0))
                    {
                       set_error(lang('error_view_register'));
                       return FALSE;
                    }
                if (($photo->erotic_p==1) && ($erotic == -1))
                    {
                       set_error(lang('error_view_erotic'));
                       return FALSE;
                    }
				$query = "SELECT albums.* FROM photos, photo_album_map, albums WHERE photo_album_map.photo_id = photos.photo_id AND photo_album_map.album_id = albums.album_id AND photos.photo_id = ".clean($photo_id);
	    		$photo->albums = $this->db->query($query)->result();

	    		return $photo;
    		}
    		return $rs;
    	}
		else return FALSE;
	}

	function get_photos($photo_id=null, $user_id=null, $moderation_state, $registered=0, $erotic=-1, $per_page=0, $page=1, $with_count=false, $order_by='date_added DESC', $period='', $order_in=""){
		$date_condition = 1;
		$limit = empty($per_page)?'':' limit '.$per_page*($page-1).','.$per_page;
	   	$order_by = empty($order_by) ? ' date_added DESC ' : trim($order_by);
	   	
    	if (is_array($period))
    	{
    		if(count($period) < 2) return FALSE;
    		$date_condition = ' photos.date_added between '.clean($period[0]).' and '.clean($period[1]);
    	}
    	elseif(is_numeric($period)) 
    	{
    		$date_condition = ' date_sub(curdate(),INTERVAL '.$period.' DAY) >= photos.date_added';
    	}
    	
    	//title asc
    	$query ="SELECT users.login, 
		photos.*,
		see_cnt,
		photos.land as land, 
		nv.num_votes as num_votes,
		albums.album_id, photo_category_map.category_id
		from (
			SELECT photos.*,
			rating_totals.see_cnt as see_cnt,
			if( md_width >md_height,true,false) as land 
			from photos";
    	
    	if($moderation_state == -2)
    		$query .=" left join rating_totals on (photos.photo_id = rating_totals.on_what_id and rating_totals.on_what='foto') ";
    	else
			$query .=" inner join rating_totals on (photos.photo_id = rating_totals.on_what_id and rating_totals.on_what='foto') ";           
		
			$query .= " where 1=1";
    	
			if($moderation_state < 0) $query .= " and photos.moderation_state <= ".$moderation_state; 
			elseif($moderation_state >= 0) $query .= " and photos.moderation_state >= ".$moderation_state; 
			
			if ($registered == 0) $query .= ' AND photos.view_allowed !=1 ';
			$query .= " AND photos.user_id !=0 AND photos.erotic_p !=1 AND ".$date_condition;
			
			if($photo_id) $query .= " and photos.photo_id=".clean($photo_id);
			if(!empty($user_id)) $query .= " and photos.user_id = '".$user_id."'";
			
			$query .= " order by ".$order_by;
			
			$query .= $limit;
            			
		$query .= " ) photos		
		inner join users on photos.user_id = users.user_id
		inner join photo_album_map on photos.photo_id = photo_album_map.photo_id
		inner join albums on photo_album_map.album_id = albums.album_id
		LEFT JOIN photo_category_map on photo_category_map.photo_id = photos.photo_id
		LEFT JOIN votes nv ON  photos.photo_id = nv.on_what_id

		GROUP BY photos.photo_id, see_cnt order by ".$order_by;
       	
//       	echo $query;exit;
    	$query = $this->db->query($query);
    	$result = $query->result();
    	
    	if(!empty($result)) {
    		
    		$juries = $this->config->load('../modules/gallery_mod/config/config');
			$jury_logins = $this->config->item('jury_logins');
			$user_login = $this->db_session->userdata('user_login');
			
			$juries_str = "";
			if(!empty($jury_logins)) {
				if(in_array($user_login, $jury_logins)) {
					$key = array_search($user_login, $jury_logins);
					unset ($jury_logins[$key]);				
				}
				foreach ($jury_logins as &$login) {
					$login = "'".$login."'";
				}
				$juries_str = ' AND users.login not in('.implode(",", $jury_logins).')';
			}
			
    		foreach ($result as $photo) {
    			$query = "select 
					count(comments_tree.body) as comcnt
				from
					photos,
					users,
					comments_tree
				where
					comments_tree.commented_object_type = 'photo'
				and	comments_tree.user_id=users.user_id
				and	comments_tree.commented_object_id = photos.photo_id
				and comments_tree.moderation_state >= 0 ";
			
				$query .= " AND comments_tree.commented_object_id = '".$photo->photo_id."'";
    			$query .= $juries_str;
    		
    			$query = $this->db->query($query);
    			$photo->comcnt = $query->row()->comcnt;
    		}
    	}
    	
    	if($with_count) {
    		$query = "SELECT SQL_CALC_FOUND_ROWS photos.*,
			if( md_width >md_height,true,false) as land 
			from photos            
			where 1=1";
    	
			if($moderation_state < 0) $query .= " and photos.moderation_state <= ".$moderation_state; 
			elseif($moderation_state >= 0) $query .= " and photos.moderation_state >= ".$moderation_state; 
			
			if ($registered == 0) $query .= ' AND photos.view_allowed !=1 ';
			$query .= " AND photos.user_id !=0 AND photos.erotic_p !=1 AND ".$date_condition;
			
			if($photo_id) $query .= " and photos.photo_id=".clean($photo_id);
			if(!empty($user_id)) $query .= " and photos.user_id = '".$user_id."'";
							
//			echo $query;exit;
    		$this->db->query($query);
    		
    		$query = $this->db->query("select found_rows() as count");
			if ( ! $query) return FALSE;
			$result['count'] = $query->row()->count;
    	}
    	return $result;
	}
	
    //get for one photo
    function get_prop_photo($photo_id, $registered = 0, $erotic = -1) {

		$query = "SELECT *,if( photos.md_width >photos.md_height,true,false) as land,photo_category_map.category_id,photo_album_map.album_id FROM photos,photo_category_map,photo_album_map WHERE (photo_album_map.photo_id=photos.photo_id) AND (photo_category_map.photo_id=photos.photo_id) AND  photos.photo_id=".clean($photo_id);

        $query = $this->db->query($query);
		if ( ! $query) return FALSE;
		$rs = $query->result();


    	if(!empty($rs)) {
    		if(!empty($photo_id)) {
	    		$photo = $rs[0];

				$query = "SELECT albums.* FROM photos, photo_album_map, albums WHERE photo_album_map.photo_id = photos.photo_id AND photo_album_map.album_id = albums.album_id AND photos.photo_id = ".clean($photo_id);
	    		$photo->albums = $this->db->query($query)->result();

	    		return $photo;
    		}
    		return $rs;
    	}
		else return FALSE;


	}

    function get_single_photo($photo_id, $registered = 0, $erotic = -1) {

		$query = 'SELECT *,if( photos.md_width >photos.md_height,true,false) as land FROM photos WHERE  photos.photo_id='.clean($photo_id);
        
        $query = $this->db->query($query);
    	$rs = $query->result();


    	if(!empty($rs)) {
    		if(!empty($photo_id)) {
	    		$photo = $rs[0];

				$query = "SELECT albums.* FROM photos, photo_album_map, albums WHERE photo_album_map.photo_id = photos.photo_id AND photo_album_map.album_id = albums.album_id AND photos.photo_id = ".clean($photo_id);
	    		$photo->albums = $this->db->query($query)->result();

	    		return $photo;
    		}
    		return $rs;
    	}
		else return FALSE;


	}

    function get_main_album_photo ($photo_id, $moderation_state, $registered = 0, $erotic = -1) {
     	
     	if (empty ($photo_id) )
			return false;
		$query = 'select *,if( photos.md_width >photos.md_height,true,false) as land from photos where photo_id='.clean($photo_id);
        
        $query = $this->db->query($query);
    	$rs = $query->result();


    	if(!empty($rs)) {
    		if(!empty($photo_id))
	    		$photo = $rs[0];	    		
    		else
    			return false;
    	}
    	else
    			return false;
    	
    	if ($photo->user_id!=$this->db_session->userdata('user_id')) 
    	{
	    	if ($photo->moderation_state < $moderation_state)
	    		return FALSE;
	    	if ($photo->view_allowed == 1 && $registered == 0)
	    		return FALSE;
	    	if ($photo->erotic_p == 1 && $erotic == -1)
	    		return FALSE;	
    	}
		
    	return $photo;
    	
	}

	function add_to_album($photo_id, $category_id, $album_id) {

		if ( empty($photo_id)) return FALSE;
		
		if ( ! empty($category_id)) {
				// Start modify. Photo may be only in one category
				$query = "DELETE FROM photo_category_map WHERE photo_category_map.photo_id = '$photo_id'";
				$query = $this->db->query($query);
				// End modify
    		$query = 'insert into photo_category_map (photo_id, category_id) values ('.$photo_id.', '.clean($category_id).')';
	    	$query = $this->db->query($query);
    	}

    	if ( ! empty($album_id)) {
    		// Start modify. Photo may be only in one album
    		$query = "DELETE FROM photo_album_map WHERE photo_album_map.photo_id = '$photo_id'";
		$query = $this->db->query($query);
		// End modify
	    	$query = 'insert into photo_album_map (photo_id, album_id) values ('.$photo_id.', '.clean($album_id).')';
	    	$query = $this->db->query($query);
	}
		
		return $photo_id;
	}
	
	function add_photo($title, $user_id, $photo_extras=array()) {
		try {			
			if(!$title || !$user_id) 
				throw new Exception('data for add_photo are empty');
			
			$ip = $_SERVER['REMOTE_ADDR'];
	  		if(empty($ip)) $ip = '0.0.0.0';
	  		$date = date("Y-m-d H:i:s");
	  		
	  		$query = "INSERT INTO photos (title, user_id, date_added, added_from_ip, lg_width, lg_height, size, view_allowed, erotic_p, moderation_state) 
	  				VALUES (".clean($title).", ".clean($user_id).", '$date', INET_ATON('$ip'), '0', '0', '0', '0', '0', '0')";
	  		
	  		if(!$query = $this->db->query($query))
				throw new Exception($this->db->_error_message());	    	
	    	
	    	$photo_id = $this->db->insert_id();
	    	
	    	if(!$photo_id)
				throw new Exception("photo_id is null");
	    	
	    	if ( ! empty($photo_extras) && is_array($photo_extras)) {
	    		$this->db->where('photo_id', $photo_id);    		
	    		
	    		if(!$this->db->update('photos', $photo_extras))
					throw new Exception($this->db->_error_message());				
	    	}
	    		    	
	    	return $photo_id;
		    	
	    } catch (Exception $e) {
    		log_message('error',$e->getMessage()."\n".
								"file: ".$e->getFile()."\n".
								"code: ".$e->getCode()."\n".
								"line: ".$e->getLine());
    	}
	    return false; 
	}
	
	// function will be deleted
	function add($title, $user_id, $category_id, $album_id, $photo_extras=array())
	{
			$ip = $_SERVER['REMOTE_ADDR'];
	  		if(empty($ip)) $ip = '0.0.0.0';
	  		$date = date("Y-m-d H:i:s");

		 	$query = "INSERT INTO photos (title, user_id, date_added, added_from_ip) VALUES (".clean($title).", ".clean($user_id).", '$date', INET_ATON('$ip'))";
	    	$query = $this->db->query($query);
	    	
	    	if ($this->db->affected_rows() == 0) return FALSE;
	    	
	    	$photo_id = $this->db->insert_id();
	    	
	    	if ( empty($photo_id)) return FALSE;
	    	
	    	if ( ! empty($category_id))
	    	{
	    		$query = 'insert into photo_category_map (photo_id, category_id) values ('.$photo_id.', '.clean($category_id).')';
		    	$query = $this->db->query($query);
	    	}

	    	if ( ! empty($album_id)) 
	    	{
		    	$query = 'insert into photo_album_map (photo_id, album_id) values ('.$photo_id.', '.clean($album_id).')';
		    	$query = $this->db->query($query);
			}			 	
	    	
	    	if ( ! empty($photo_extras) && is_array($photo_extras))
	    	{
	    		$this->db->where('photo_id', $photo_id);
				$this->db->update('photos', $photo_extras);
	    	}
	    	
	    	return $photo_id;
	}
	 
	function update($data)
	{
		if ( empty($data) || ! is_array($data) || empty($data['photo_id']) ) return FALSE;
		/*
		foreach ($data as $key=>$value) {
			$data[$key] = clean($value);
		}
		*/
		$this->db->where('photo_id', $data['photo_id']);
		$res = $this->db->update('photos', $data);
		if ( ! $res) return FALSE;

		return TRUE;
	}
	
	function update_photo($photo_id, $data)
	{
		if ( empty($data) || ! is_array($data) || empty($photo_id) ) return FALSE;
				
		$photo_fields = array('title',                           
								'user_id',                          
								'date_added',  
								'date_modified',                          
								'added_from_ip',
								'lg_width',                         
								'lg_height',                       
								'md_width',                       
								'md_height',                          
								'sm_width',                           
								'sm_height',                   
								'size',
								'extension',
								'exif_camera',
								'exif_shooting_date',
								'exif_focal_length',
								'exif_exposure_time',
								'exif_aperture',
								'exif_focus_dist',       
								'google_map_point',       
								'view_allowed',        
								'view_password',
								'erotic_p',
								'moderation_state',
								'description',
								'score');									
		$photo_data = array();									
		foreach($data as $key=>$value) {			
			if (in_array($key, $photo_fields)) $photo_data[$key] = $value;			
		}
						
		$res = null;
		if ( ! empty($photo_data)) {
			$this->db->where('photo_id', $photo_id);
			$res = $this->db->update('photos', $photo_data);
		}
		
		return $res;
	}
	
	function decline($photo_id, $user_id) {
		if($photo_id == null || $user_id == null) return false;
		
		$photo = $this->get($photo_id, 'all');
		$data = array(
           'moderation_state' => -1
        );
		$this->db->where('photo_id', $photo_id);
		$this->db->update('photos', $data);
		
		return true;
	}
	
	function undecline($photo_id, $user_id) {
		if($photo_id == null || $user_id == null) return false;
		
		$photo = $this->get($photo_id, 'all');
		$data = array(
           'moderation_state' => 0
        );
		$this->db->where('photo_id', $photo_id);
		$this->db->update('photos', $data);
		
		return true;
	}
	
	function delete($photo_id, $user_id) {
		if($photo_id == null || $user_id == null) return false;
		$data = array(
			'moderation_state' => MOD_DELETED,
			'date_modified'    => date("Y-m-d H:i:s")
		);
		$this->db->where('photo_id', $photo_id);
		$this->db->update('photos', $data);
		return true;
	}
	
	function remove_photo($photo_id){
		try {
			$query = "select * from photos where photo_id=".clean($photo_id);
			$photo = $this->db->query($query)->row();			
			if($photo) {
				
				$this->db->where('photo_id', $photo_id);
				if(!$this->db->delete('competition_photos'))
					throw new Exception($this->db->_error_message());
				
				$this->db->where('photo_id', $photo_id);
				if(!$this->db->delete('photo_album_map'))
					throw new Exception($this->db->_error_message());
					
				$this->db->where('photo_id', $photo_id);
				if(!$this->db->delete('photo_category_map'))
					throw new Exception($this->db->_error_message());
				
				$this->db->where('photo_id', $photo_id);
				if(!$this->db->delete('photo_tag_map'))
					throw new Exception($this->db->_error_message());
				
				$this->db->where('photo_id', $photo_id);
				if(!$this->db->delete('photo_views'))
					throw new Exception($this->db->_error_message());
					
				$this->db->where('photo_id', $photo_id);
				if(!$this->db->delete('photos'))
					throw new Exception($this->db->_error_message());
				
				if($photo) {
    	           	$head = dirname(BASEPATH).'/uploads/photos/'.date('m', strtotime($photo->date_added)).'/'.$photo->photo_id.'-head'.$photo->extension;
    	           	$lg = dirname(BASEPATH).'/uploads/photos/'.date('m', strtotime($photo->date_added)).'/'.$photo->photo_id.'-lg'.$photo->extension;
    	           	$md = dirname(BASEPATH).'/uploads/photos/'.date('m', strtotime($photo->date_added)).'/'.$photo->photo_id.'-md'.$photo->extension;
    	           	$sm = dirname(BASEPATH).'/uploads/photos/'.date('m', strtotime($photo->date_added)).'/'.$photo->photo_id.'-sm'.$photo->extension;    	           
					/*if(is_file($head)) {
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
					}*/
				}
				return true;
			}
			return false;
		} catch (Exception $e) {
    		log_message('error',$e->getMessage()."\n".
								"file: ".$e->getFile()."\n".
								"code: ".$e->getCode()."\n".
								"line: ".$e->getLine());
    	}
    	return false;
	}
	
    function search_photos($keywords, $categories = null, $per_page = 0, $page = 0,  $sort_type=1, $sort_order='', $moderation_state=1, $registered=0, $erotic=-1) {

        if(empty($keywords)) return false;
    	// убираем пробелы в начале и в конце строки
    	$keywords = trim($keywords);

    	// убираем переводы строк
		$keywords = str_replace ("/n"," ", $keywords);
		$keywords = str_replace ("/r","", $keywords);

		// убираем хтмл-тэги
		$keywords = str_replace ('<br>', ' ', $keywords);
		$keywords = str_replace ('<p>', ' ', $keywords);
		$keywords = strip_tags ($keywords);

		// убираем знаки препинания и цифры
		// все эти строки работают быстрей, чем один eregi_replace!
		$keywords = str_replace (' -', ' ', $keywords);
		$keywords = str_replace ('.', ' ', $keywords);
		$keywords = str_replace (',', ' ', $keywords);
		$keywords = str_replace ('!', ' ', $keywords);
		$keywords = str_replace ('?', ' ', $keywords);
		$keywords = str_replace (':', ' ', $keywords);
		$keywords = str_replace (';', ' ', $keywords);
		$keywords = str_replace (')', ' ', $keywords);
		$keywords = str_replace ('(', ' ', $keywords);
		$keywords = str_replace ('"', ' ', $keywords);
        $keywords = str_replace ('  ', ' ', $keywords);

		// убираем заглавные буквы   - из-за этой функции возникали проблемы с кодировкой - и без нее все работает
	    //$keywords = strtolower ($keywords);

		// разбиваем на слова, убираем слова, короче 3х букв
		$keywords = explode (" ", $keywords);

		// убираем повторяющиеся слова
		$keywords = array_unique($keywords);

		$arr_words_count = count($keywords);

		$keywords_all = implode(" ", $keywords);

        //condition for categories
        $cat_str = "";
		if(!empty($categories)) {
            $cat_str = " AND (";
            $i = 0; $last = count($categories) - 1;
            while ($i < count($categories))
            {
    			$cat = intval($categories[$i]);
    			$cat_str.= " (pcm.category_id = '$cat') ";
                if($i != $last )
      			    $cat_str .= " OR ";
                $i++;
            }
             $cat_str.= ")";
		}

        // end condition for categories

		$page = empty($page)?1:$page;
		$limit = empty($per_page)?'':' limit '.$per_page*($page-1).','.$per_page;

        $par_list1 = " ";    //title and description from photos like
        $par_list2 = " ";    //tags like
        $par_list3 = " ";    //tags if
        $par_list4 = " ";    //title and description from photos if

        //form conditions with "like"
        $i = 0; $last = $arr_words_count - 1;
        while ($i < $arr_words_count) {
      		$par_list1 .= "(p.title LIKE '%".$keywords[$i]."%') OR (p.description LIKE '%".$keywords[$i]."%') ";
            $par_list2 .= " (t.tag LIKE '%".$keywords[$i]."%') ";
            $par_list4 .= "if (p.title LIKE '%".$keywords[$i]."%', 10, 0) + if (p.description LIKE '%".$keywords[$i]."%', 9, 0) ";
            if($i != $last ) {
      			$par_list1 .= " OR ";
                $par_list2 .= " OR ";
                $par_list4 .= " + ";
            	}
            $i++;
         }

         //select photo_id where tags contain searching information
         $query = "select p.photo_id from photos p  left join photo_tag_map ptm on (ptm.photo_id = p.photo_id) left join tags t on (ptm.tag_id = t.tag_id)  where " . $par_list2;

         $result = $this->db->query($query)->result();

         // form conditions of photo_id with tags that contain searchong info
         $i = 0; $last = count( $result) - 1;
         while ($i < count( $result)) {
            $par_list3 .= " (p.photo_id = ".$result[$i]->photo_id.") OR ";
            $par_list4 .= "+ if ((p.photo_id = ".$result[$i]->photo_id."), 8, 0) ";
            $i++;
         }

         $query = "SELECT DISTINCT p. *, ". $par_list4 ."  AS relevance,if( p.md_width >p.md_height,true,false) as land
            FROM photos p
            left join rating_totals on p.photo_id = rating_totals.on_what_id,
            photo_category_map pcm, categories_tree c
            where (on_what='foto' or on_what is null )
            and (
            pcm.photo_id = p.photo_id
            AND pcm.category_id = c.id  ". $cat_str." )
            AND (" . $par_list3 . $par_list1." ) ";

        $query.= ' AND p.moderation_state >= ' . $moderation_state;
        $query.= " AND p.user_id !=0 ";
        if ($registered == 0)
            $query.= " AND p.view_allowed !=1 ";
        if ($erotic == -1)
            $query.= " AND p.erotic_p !=1 ";

        switch ($sort_type) {

                  case 2: $query.= 'ORDER BY p.date_added ';       //sort by date
                          break;
                  case 1: $query.= 'ORDER by p.title ';        //sort by user
                          break;
                  case 3: $query.= 'ORDER BY balls ';    //sort by rating
                          break;
                  case 4: $query.= 'ORDER BY relevance ';
                          break;
                  default: $query.= 'ORDER BY relevance ';
                          if ($sort_order != "d")  $query.= 'DESC ';
          }
        if ($sort_order == "d")
        $query.= 'DESC ';

  		$result['all'] = $this->db->query($query)->result();

        $query.= $limit;
  		$result['search'] = $this->db->query($query)->result();

		return $result;
    }
		
	function get_deleted_photos_count_per_user($user_id) {
		$query = "SELECT COUNT(*) AS cnt FROM photos WHERE user_id = '$user_id' AND moderation_state = ".MOD_DELETED;
		$result = $this->db->query($query)->result();
		return $result[0]->cnt;
	}

    function get_user ($photo_id){
        if  (empty ($photo_id)){
        	$this->_setError(lang('error_empty_request'));
            return false;
            }
        $query = "select users.* from users inner join photos on photos.user_id = users.user_id where photo_id = " . $photo_id;

      	$query = $this->db->query($query);
      	return $query->result();
    }

	function get_photos_count_per_user ($user_id,  $moderation_state=1, $registered = 0, $erotic = -1){
		
		$query = "SELECT photo_id from photos where user_id = " . $user_id ;
		
		if($moderation_state == "all") {
			$query .= " AND (moderation_state = '".MOD_NEW."' OR moderation_state = '".MOD_APPROVED."' OR moderation_state = '".MOD_FEATURED."' OR moderation_state = '".MOD_DECLINED."')";
		
		} else {
			$query .= ' AND moderation_state >= '.$moderation_state;
		}
		
		if ($registered == 0)
			$query.= " AND view_allowed !=1 ";
		if ($erotic == -1)
			$query.= " AND erotic_p !=1 ";
		
		$query = $this->db->query($query);
		return $query->num_rows();
	}

    function get_photos_count ($registered = 0, $erotic = -1, $moderatoin_state = 1, $period = 7){
        $q =  "SELECT COUNT(*) AS `numrows` FROM `photos` where moderation_state >= " . $moderatoin_state . " and view_allowed != 0 and user_id!=0 and TO_DAYS(NOW()) - TO_DAYS(date_added) <= " . $period; ;
        if ($registered == 0)
            $q.= " AND view_allowed !=1 ";
        if ($erotic == -1)
            $q.= " AND erotic_p !=1 ";
        $q = $this->db->query($q);
    	$r = $q->result();
        if ($r)
            return  $r[0]->numrows;
        else
            return 0;
    }

	function revert_image($image_id, $user_id) {		
		try {
			if(empty($image_id) || empty($user_id))
				throw new Exception("revert_image runction. image_id or user_id is empty");
			
			$info = $this->get($image_id, MOD_DELETED, $user_id);
			if (empty($info)) 
				throw new Exception("revert_image runction. photo is absent in database");
			
			if ($info->albums->moderation_state == MOD_DELETED) {
				$data = array(
					'date_modified' => date("Y-m-d H:i:s"),
					'moderation_state' => MOD_NEW
				);
				$this->db->where('album_id', $info->albums->album_id);				
				if(!$this->db->update('albums', $data))
	        		throw new Exception($this->db->_error_message());				
			}
			$data = array(
				'date_modified' => date("Y-m-d H:i:s"),
				'moderation_state' => MOD_NEW
			);
			$this->db->where('photo_id', $image_id);			
			if(!$this->db->update('photos', $data))
	        	throw new Exception($this->db->_error_message());			
			
			$query = "INSERT INTO rating_totals (on_what, on_what_id, balls, see_cnt) VALUES ('foto', ".$image_id.", 0, 0)";
			if(!$this->db->query($query))
	        	throw new Exception($this->db->_error_message());
	        	
	        return true;		    
				
		} catch (Exception $e) {
    		log_message('error',$e->getMessage()."\n"."file: ".$e->getFile()."\n"."code: ".$e->getCode()."\n"."line: ".$e->getLine());
    	}
    	return false;
	}

    function get_next_photo_id ($photo_id, $moderation_state=1)        {
        $query = "SELECT album_id from photo_album_map where photo_id = " . $photo_id;
        $query = $this->db->query($query);
        $rs = $query->row();
        if (empty ($rs)	)	
        	return $photo_id;
        $query = "SELECT photos.photo_id from photos inner join photo_album_map on photos.photo_id = photo_album_map.photo_id where photos.photo_id > " . $photo_id . " and moderation_state>= ".$moderation_state." and album_id = " . $rs->album_id .  " order by photos.photo_id limit 1";
        $query = $this->db->query($query);
        $next =  $query->row();
        if (empty ($next)) {
          $query = "select min(photos.photo_id) as p_id  from photos inner join photo_album_map on photos.photo_id = photo_album_map.photo_id where moderation_state>= ".$moderation_state." and album_id = " . $rs->album_id ;
          $query = $this->db->query($query);
          $next =  $query->row();
          return $next->p_id;
        }
        return $next->photo_id;
    }

    function get_prev_photo_id ($photo_id, $moderation_state=1)        {
        $query = "SELECT album_id from photo_album_map where photo_id = " . $photo_id;
        $query = $this->db->query($query);
        $rs = $query->row();
        if (empty ($rs)	)	
        	return $photo_id;
        $query = "SELECT photos.photo_id from photos inner join photo_album_map on photos.photo_id = photo_album_map.photo_id where moderation_state>= ".$moderation_state." and photos.photo_id < " . $photo_id . " and album_id = " . $rs->album_id .  " order by photos.photo_id desc limit 1";
        $query = $this->db->query($query);
        $prev =  $query->row();
        if (empty ($prev)) {
          $query = "select max(photos.photo_id) as p_id  from photos inner join photo_album_map on photos.photo_id = photo_album_map.photo_id where moderation_state>= ".$moderation_state. " and album_id = " . $rs->album_id ;
          $query = $this->db->query($query);
          $prev =  $query->row();
          return $prev->p_id;
        }
        return $prev->photo_id;
    }

	function get_admin_photos_list($sort_type=1, $sort_order='desc', $cpage=1, $per_page, $search_criteria=array()) {
			$limit = empty($per_page)?'':'LIMIT '.$per_page*($cpage-1).','.$per_page;
			$query = "SELECT SQL_CALC_FOUND_ROWS p.*, u.login AS username, c.name as catname ".
								"FROM photos p, users u, photo_category_map p2c, categories_tree c ".
								"WHERE p.user_id = u.user_id ".
								"AND p.photo_id = p2c.photo_id ".
								"AND p2c.category_id = c.id ";
			if (!empty($search_criteria)) {
				if (!empty($search_criteria['category'])) {
					$query .= "AND c.id = '".$search_criteria['category']."' ";
				}
				if (!empty($search_criteria['username'])) {
					$query .= "AND u.login LIKE '%".$search_criteria['username']."%' ";
				}
				if (!empty($search_criteria['title'])) {
					$query .= "AND p.title LIKE '%".$search_criteria['title']."%' ";
				}
				if (!empty($search_criteria['date']['start']) && !empty($search_criteria['date']['end'])) {
					$query .= "AND p.date_added BETWEEN '".$search_criteria['date']['start']."' AND '".$search_criteria['date']['end']."' ";
				}
				if ($search_criteria['moderation'] != -999) {
					$query .= "AND p.moderation_state = '".$search_criteria['moderation']."' ";
				}
			}
			$query .= " AND p.moderation_state != '-2' ";
			$sort_order = (strtolower($sort_order) == "desc")? "DESC": "ASC";
			$query .= "ORDER BY p.date_added $sort_order ";
			$query .= $limit;
			
			$query = $this->db->query($query);
			if ( ! $query) return FALSE;
			$photos = $query->result();
			
			$query = $this->db->query("select found_rows() as count");
			if ( ! $query) return FALSE;
			$cnt = $query->row()->count;
			
			return array('cnt' => $cnt,'photos' => $photos);
		}
		
	function get_photo_title($photo_id)
	{
		$query = "SELECT title from photos where photo_id = " . $photo_id;
        $query = $this->db->query($query);
        $rs = $query->row();
        if (empty ($rs)	)	
        	return $photo_id;
        else
        	return  $rs->title;      
	}
		
	function regulate_search_photos($id_array, $categories = null, $per_page = 0, $page = 0,  $sort_type=1, $sort_order='', $moderation_state=1, $registered=0, $erotic=-1) {

		try {				
			if(empty($id_array)) throw new Exception('empty $id_array');
			if(empty($moderation_state)) throw new Exception('empty $moderation_state');
				
			$page = empty($page)?1:$page;
			$limit = empty($per_page)?'':' limit '.$per_page*($page-1).','.$per_page;
	  
	        $query = "SELECT p.photo_id, title, date_added, extension, view_password, p.user_id, users.login, balls, see_cnt , category_id,
						IF( md_width >md_height,true,false) as land  
						from photos p left join rating_totals on p.photo_id = rating_totals.on_what_id 
						left join users on p.user_id = users.user_id  
						left join photo_category_map pcm on p.photo_id = pcm.photo_id
						WHERE 
						(on_what='foto' or on_what is null )
						AND 
						p.photo_id in ( " .  implode(',', $id_array) . " )"; 
	        
	        if (!empty ($categories))
					$query.= "AND pcm.category_id in (" . implode(',', $categories) . " ) ";
	        
	
	        $query.= ' AND p.moderation_state >= ' . $moderation_state;
	        $query.= " AND p.user_id !=0 ";
	        if ($registered == 0)
	            $query.= " AND p.view_allowed !=1 ";
	        if ($erotic == -1)
	            $query.= " AND p.erotic_p !=1 ";
	
	        switch ($sort_type) {
	
	                  case 2: $query.= 'ORDER BY p.date_added ';       //sort by date
	                          break;              
	                  case 3: $query.= 'ORDER BY balls ';    //sort by rating
	                          break;
	                  default: $query.= 'ORDER by p.title ';        //sort by user
	                          break;
	                 // case 4: $query.= 'ORDER BY relevance ';
	                 //         break;
	                 // default: $query.= 'ORDER BY relevance ';                
	          }
	          
	        if ($sort_order == "d")
	        $query.= 'DESC ';
			
	        if(!$query_res = $this->db->query($query))
	        	throw new Exception($this->db->_error_message());
	        					
	  		$result['all'] = $query_res->result();
	
	        $query.= $limit;
	        
	        if(!$query_res = $this->db->query($query))
	        	throw new Exception($this->db->_error_message());
	        	
	  		$result['search'] = $query_res->result();
	  		
			return $result;
			
		} catch (Exception $e) {
    		log_message('error',$e->getMessage()."\n".
								"file: ".$e->getFile()."\n".
								"code: ".$e->getCode()."\n".
								"line: ".$e->getLine());
    	}
    	return false;
    }    
}

/* End of file */
