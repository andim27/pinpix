<?php
	/**
	 * Class Competition_mdl
	 *
	 * module for competition
	 *
	 * @author   Popov
	 * @access   public
	 * @package  Competition_mdl.class.php
	 * @created  Thu Feb 05 14:00:55 EET 2009
	 */
	class Competition_mdl extends Model {
		
		function Competition_mdl() {
			parent::Model();
		}

		function get_competition($competition_id = null, $per_page = 10, $page = 1) {
			
			$limit = " LIMIT 1";
			$query = "SELECT * FROM competitions";
			if($competition_id != null && intval($competition_id)) {
				$query .= " WHERE competition_id = ".$competition_id;;
			}
			$query .= " ORDER BY end_date DESC, start_date DESC ".$limit;
			$competition = $this->db->query($query)->result();

			foreach ($competition as $comp) {
				$comp->works = $this->get_competition_photos($comp->competition_id);
			}
			return $competition;
		}
		
		function get_competition2($competition_id = null, $per_page = 0, $page = 1, $extras='', $with_count=false) {
			$page = empty($page)?1:$page;
			$limit = empty($per_page)?'':' limit '.$per_page*($page-1).','.$per_page;

			$query = "SELECT SQL_CALC_FOUND_ROWS * FROM competitions WHERE 1 ";
			if($competition_id) $query .= " AND competition_id = ".$competition_id;
			$query .= ' '.$extras;
			
			$query .= " ORDER BY end_date DESC, start_date DESC ".$limit;
			
			$query = $this->db->query($query);
			if ( ! $query) return FALSE;
			$result = $query->result();

			if($with_count) {
				$query = $this->db->query("select found_rows() as count");
				if ( ! $query) return FALSE;
				$result['count'] = $query->row()->count;				
			}
			return $result; 
		}

		function get_all_competitions($limit = 10, $page = 1, $sort_type=1, $sort_order = '', $per_page = 16) {
			
			$query = "SELECT * FROM competitions";
			
			$query .= " ORDER BY end_date DESC, start_date DESC LIMIT ".$limit;
			$competition = $this->db->query($query)->result();

			// works of competition
			foreach ($competition as $comp) {
				$comp->works = $this->get_competition_photos($comp->competition_id, $page, $sort_type, $sort_order, $per_page);
			}
			return $competition;
		}

		function get_competition_photos_new($competition_id, $per_page=0, $page=1, $with_count=false, $extras="", $order_by="place_taken", $group_by="p.photo_id"){
			$page = empty($page)?1:$page;
			$limit = empty($per_page)?'':' limit '.$per_page*($page-1).','.$per_page; 
			
			$query = "SELECT DISTINCT SQL_CALC_FOUND_ROWS 
						cp.moderation_state as comp_mod_state, 
						p.*, count(commented_object_id) as comcnt, 
						login, 
						nv.num_votes as num_votes, 
						rating_totals.balls,
						if( p.md_width >p.md_height,true,false) as land
						FROM photos p 
						LEFT JOIN votes nv ON  p.photo_id = nv.on_what_id
						LEFT JOIN rating_totals ON (p.photo_id = rating_totals.on_what_id AND rating_totals.on_what='foto')
						LEFT JOIN comments_tree ct ON (ct.commented_object_id = p.photo_id AND ct.commented_object_type = 'photo'),
						competition_photos cp, competitions c, users u
						WHERE p.moderation_state > 0
                        AND c.competition_id = cp.competition_id
                        AND u.user_id = p.user_id
						AND cp.photo_id = p.photo_id
						AND c.competition_id = ".$competition_id;
			$query .= " ".$extras." ";
			if(!empty($group_by)) $query .= " GROUP BY ".$group_by;
			if(!empty($order_by)) $query .= " ORDER BY ".$order_by;			
			$query.= $limit;
			
			$query = $this->db->query($query);
			if ( ! $query) return FALSE;
			$result = $query->result();

			if($with_count) {
				$query = $this->db->query("select found_rows() as count");
				if ( ! $query) return FALSE;
				$result['count'] = $query->row()->count;
			}
			return $result;
		}
		
		/**
		 * get_competition_photos
		 *
		 * get competition photos
		 *
		 * @author  Popov
		 * @class   Competition_mdl
		 * @access  public
		 * @param   int     $competition_id
		 * @return  object  $comp_photos
		 */
		function get_competition_photos($competition_id, $page = 1, $sort_type=1, $sort_order = '', $per_page = 16) {
			$comp_photos = null;
			if ($page==0) {
				$page=1;
			}
			$limit = empty($per_page)?'':' limit '.$per_page*($page-1).','.$per_page;

			$query = "SELECT DISTINCT p.*, count(commented_object_id) as comcnt, login, rating_totals.see_cnt, rating_totals.balls,if( p.md_width >p.md_height,true,false) as land 
						FROM photos p LEFT JOIN rating_totals ON (p.photo_id = rating_totals.on_what_id 
						AND rating_totals.on_what='foto')
						LEFT JOIN comments_tree ct ON (ct.commented_object_id = p.photo_id AND ct.commented_object_type = 'photo'),
						competition_photos cp, competitions c, users u
									WHERE p.moderation_state > 0
                                    AND c.competition_id = cp.competition_id
                                    AND u.user_id = p.user_id
									AND cp.photo_id = p.photo_id
									AND c.competition_id = ".$competition_id.
									" GROUP BY p.photo_id" ;
	
			switch ($sort_type) {
				case 1: $query.= ' ORDER BY p.title ';      //sort by date
					break;
				case 2: $query.= ' ORDER BY p.date_added '; //sort by user
					break;
				case 3: $query.= ' ORDER BY balls ';        //sort by rating
					break;
				default:
					$query.= ' ORDER BY place_taken '; 
			}
			if ($sort_order == "d") {
				$query.= 'DESC ';
			}
			$query.= $limit;
			$comp_photos = $this->db->query($query)->result();

			return ($comp_photos);
		}

		

		/**
		 * set_work_to_comp
		 *
		 * set competition
		 *
		 * @author  Popov
		 * @class   Competition_mdl
		 * @access  public
		 * @param   array     $info
		 * @param   int       $user_id
		 * @param   array     $upload_file	array of file uploaded
		 * @return  object  $competition
		 */
		function set_work_to_comp($info, $user_id, $photo_id) {
			log_message('error', "set_work_to_comp" . $photo_id);
			$photo_id = intval($photo_id);
			$competition_id = intval($info['competition_id']);
			if (empty($competition_id) || empty($photo_id)) return FALSE;
			$date = date("Y-m-d H:i:s");
			$query = "DELETE FROM competition_photos WHERE competition_photos.photo_id = '$photo_id'";
			$query = $this->db->query($query);
			$query = "INSERT INTO competition_photos (competition_id, photo_id, date_added) VALUES (".$competition_id.", ".$photo_id.", '$date')";
			$query = $this->db->query($query);

			return true;
		}
		function set_work_to_comp2($photo_id, $comp_id = 1) {
			$photo_id = intval($photo_id);
			$competition_id = intval($comp_id);
			$date = date("Y-m-d H:i:s");
			$query = "INSERT INTO competition_photos (competition_id, photo_id, date_added) VALUES (".$competition_id.", ".$photo_id.", '$date')";
			$query = $this->db->query($query);

			return true;
		}
		/**
			 * update_work_to_comp
			 *
			 * function_description
			 *
			 * @author  Popov
			 * @class   Competition_mdl
			 * @access  public
			 * @param   array     $data
			 * @return  boolean
			 */
		function update_work_to_comp($data) {
			if ( empty($data) || ! is_array($data) || empty($data['photo_id']) ) return FALSE;
			
			$this->db->where('photo_id', $data['photo_id']);
			$res = $this->db->update('photos', $data);

			if ( ! $res) return FALSE;

			return TRUE;
		}

		/**
		 * get_competitions_list
		 *
		 * @author Tsapenko
		 * @class Competition_mdl
		 * @access  public
		 * @return array of object (competition_id, title)
		 */
		function get_competitions_list(){
			$query = "SELECT competitions.competition_id, competitions.title, IF((ISNULL(competitions.end_date) OR NOW() < competitions.end_date),1,0) AS active FROM competitions";
			return $this->db->query($query)->result();
		}
		
		function get_last_closed_comp ()
		{
			$query = "SELECT competition_id FROM competitions ".
									" WHERE NOT ISNULL(competitions.end_date) ".
									" AND NOW() > competitions.end_date ".
									" ORDER BY competitions.end_date DESC ";
			$comp_id = $this->db->query($query)->result();
			if (!empty ($comp_id[0]->competition_id))
				return $comp_id[0]->competition_id;		
			else
			{
				$query = "SELECT competition_id FROM competitions ".
									" ORDER BY competitions.end_date DESC ";
				$comp_id = $this->db->query($query)->result();
				if (!empty ($comp_id[0]->competition_id))
					return $comp_id[0]->competition_id;	
				else
					return 0;	
			}
					
		}

		function get_closed_competition($count = null, $competition_id = null) {
			$query = "SELECT * FROM competitions ".
									"LEFT JOIN competition_photos ON competitions.competition_id = competition_photos.competition_id ".
									"WHERE NOT ISNULL(competitions.end_date) ".
									"AND NOW() > competitions.end_date ".
									"AND competitions.competition_id != ".$competition_id.
								" GROUP BY competitions.competition_id ".
								" HAVING COUNT(competition_photos.photo_id) > 0 ".
								" ORDER BY competitions.end_date DESC";
			if ($count !== NULL) {
				$query .= " LIMIT $count";
			}
			return $this->db->query($query)->result();
		}

		/**
		 * get_competitions_by_photos
		 *
		 * @author Tsapenko
		 * @class Competition_mdl
		 * @access public
		 * @return array
		 * @param array of id $photos_ids
		 */
		function get_competitions_by_photos($photos_ids){
			if(empty($photos_ids)) return false;
			if(is_array($photos_ids)) $photos_ids = implode(", ", $photos_ids);
			
			$query = "SELECT cp.competition_id, cp.photo_id 
						FROM competition_photos cp
						WHERE cp.photo_id IN(".$photos_ids.")";
			
			$query = $this->db->query($query);
			if ( ! $query) return FALSE;
			return $query->result();
		}

		/**
		 * Destructor of Competition_mdl
		 *
		 * @access  public
		 */
		 function _Competition_mdl() {}

		 function get_competition_photo_count($competition_id) {
		 	$query = "SELECT
							distinct p.*
						FROM
							photos p, competition_photos cp, competitions c
						WHERE
                            p.moderation_state > ".MODERATION_STATE."
                        AND c.competition_id = cp.competition_id
						AND cp.photo_id = p.photo_id
						AND c.competition_id = ".$competition_id;
		 	$query = $this->db->query($query);
			return $query->num_rows();
		}
	
		// get competition name for photo with photo_id, if photo belongs to any competition
		function get_compname_for_photo($photo_id){
			if (empty ($photo_id))  return NULL;
			$query = "SELECT
							distinct (competition_id)
						FROM
							competition_photos 
						WHERE
                           photo_id = " . $photo_id ;
								 	
		 	$res = $this->db->query($query)->result();
			if (empty ($res))
				return NULL;
		 	if ($res[0]->competition_id == 0 )	 
		 		return NULL;	
			else
			{
				$comp_id = $res[0]->competition_id; 
				$query = "SELECT title FROM competitions WHERE competition_id = " . $comp_id;
				$res = $this->db->query($query)->result();
				return $res[0]->title;
			}
			
		}
//------------------------------------------------------------	
		function delete_comp() 
		{
			$id = $this->input->post('cid');
			if ( count($id) == 1 ) 		
			{
				$this->db->delete('competitions', array('competition_id' => $id[0]));	
				$this->db->delete('competition_photos', array('competition_id' => $id[0]));		
			}	
			else 			
				foreach ($id as $value ) 	
				{	
					$this->db->delete('competitions', array('competition_id' => $value));	
					$this->db->delete('competition_photos', array('competition_id' => $value));		
				}		
		}
		
		function get_competitions($comp_id) 
		{
			$this->db->where('competition_id', $comp_id);			
			$query = $this->db->get('competitions');
			if ($query->num_rows() > 0) 
			{
				$rs = $query->result();
				return $rs[0];
			}
			else 
				return false;		
		}
		
		function get_all_comp_list()
		{
			$query = "SELECT comp.*, p.date_added, p.extension, p.title as ptitle 
					 FROM competitions comp
					 LEFT JOIN photos p on comp.photo_id = p.photo_id";
			$query = $this->db->query($query);
			if ($query->num_rows() > 0) 
				return $query->result();
		}
	
		
	function create_comp() {
		$data = $this->parse_comp_form();
		if ($data != 0)
			$this->db->insert('competitions',$data);
	}
	
		
	function save_comp_details() 
	{
		if ( isset($_POST['comp_id']) &&  $_POST['comp_id'] === "") {
			// INSERT
			$this->create_comp();
			return;
		}
		
		// UPDATE
		if ( isset($_POST['comp_id']) )
			$comp_id = $this->input->post('comp_id');

		$data = $this->parse_comp_form();
		if ($data != 0)
		{
			$this->db->where(array("competition_id" => $comp_id));
			$this->db->update('competitions',$data);
		}
	}
	
	function parse_comp_form() 
	{
		$data = array();
		
		if ( isset($_POST['way'])&&($_POST['way'] == 0))
		{			
			if ( !empty($_FILES['userfile']['name']) ) 
			{
				$config['upload_path'] = $this->config->item('banners_upload_dir');//dirname(BASEPATH).'/uploads/banners/';
				$config['allowed_types'] =$this->config->item('img_types'); // 'bmp|gif|jpg|png|jpeg|swf|BMP|GIF|JPG|PNG|JPEG|SWF';		
				$config['max_size']	= 100000000;//$this->config->item('file_max_size'); 
				$this->load->language('upload',$this->db_session->userdata('user_lang'));
				$this->load->library('upload', $config);
		
				if ( ! $this->upload->do_upload())
				{
					$error = array('error' => $this->upload->display_errors());		
					echo $error['error'];
					return 0;
				}	
				else
				{
					$info = array('upload_data' => $this->upload->data());
					$data['file_url'] = $info['upload_data']['file_name'] ;
					$data['file_type'] = $info['upload_data']['image_type'];
				}
			}
			if ( isset($_POST['onclick_url']) )
				$data['onclick_url'] = $this->input->post('onclick_url');
			if ( isset($_POST['alt_text']) )
				$data['alt_text'] = $this->input->post('alt_text');
			if ( isset($_POST['title']) )
				$data['title'] = $this->input->post('title');	
		}
		
		if ( isset($_POST['way'])&&($_POST['way'] == 1))
		{
			if ( isset($_POST['file_url']) )
			{
				$data['file_url'] = $this->input->post('file_url');		
				$data['file_type'] = "string";	
			}
			
		}
					
		if ( isset($_POST['active_state']) )
			$data['active_state'] = 1;	
		else
			$data['active_state'] = 0;								
		if ( isset($_POST['block_id']) )
			$data['block_id'] = $this->input->post('block_id');
		if ( isset($_POST['description']) )
			$data['description'] = $this->input->post('description');

		return $data;
	}
	
		
	/**
		 * set_competition
		 *
		 * create a new competition
		 *
		 * @author  Popov
		 * @class   Competition_mdl
		 * @access  public
		 * @param   array     $info  array of $_REQUEST
		 * @return  int  	$competition_id
		 */
		function set_competition($info, $photo_id) {
			$this->db->from('competitions');
			$this->db->where('title', $info["competition_title"]);
			$competition = $this->db->get()->row();

			if(empty($competition)) {

				$competition = array(
					'title' => $info["competition_title"],
					'start_date' => $info["competition_date_start"],
					'end_date' => $info["competition_date_end"],
//					'preview' => $info["competition_title"],
//					'full_size' => $info["competition_title"],
					'short_description' => $info["competition_desc"],
					'description' => $info["competition_desc"],
					'rules' => $info["competition_rules"],
//					'partners' => $info["competition_title"],
//					'judges' => $info["competition_title"],
					'type' => $info["competition_type"],
					'photo_id' => $photo_id
				);
				$this->db->insert('competitions', $competition);
				$competition_id = $this->db->insert_id();

				return $competition_id;
			}
		}

		function photo_cnt_per_user ($user_id, $comp_id)
		{
			$query = " SELECT COUNT(*) as cnt from photos p 
					   INNER JOIN competition_photos cp on p.photo_id = cp.photo_id
					   WHERE user_id = " . $user_id . " AND competition_id = " .$comp_id;
			$query = $this->db->query($query);
			$res =  $query->result();
			if ( ! $res)  
				return 0;
			else		
				return $res[0]->cnt;
		}
		
		function get_count_all_comps() 
		{
			return $this->db->count_all('competitions');
		}
	
		function simple_add($data)
		{
			$this->db->insert('competitions', $data);
		}
		
		function simple_update($comp_id, $data)
		{
			try {
				if(empty($comp_id) || empty($data)) 
					throw new Exception('data for simple_update are empty');
				
				$this->db->where(array("competition_id" => $comp_id));
				
				if(!$this->db->update('competitions',$data))
					throw new Exception($this->db->_error_message());
			
				return true;
				
			} catch (Exception $e) {
	    		log_message('error',$e->getMessage()."\n".
									"file: ".$e->getFile()."\n".
									"code: ".$e->getCode()."\n".
									"line: ".$e->getLine());
	    	}
		    return false;
		}
		
		function get_admin_competition_photos($competition_id, $per_page = 0, $page = 1, $extras='') 
		{
			$result = null;			
			$limit = empty($per_page)?'':' limit '.$per_page*($page-1).','.$per_page;
			
			$query = "SELECT SQL_CALC_FOUND_ROWS 
						cp.photo_id, cp.date_added, cp.place_taken, cp.place_description, cp.moderation_state, 
						p.extension, p.title,
						u.login, u.user_id,
						rt.balls
						FROM competition_photos cp 
						left join rating_totals rt on rt.on_what_id = cp.photo_id,
						photos p, users u
						WHERE p.moderation_state > 0 and cp.photo_id = p.photo_id AND p.user_id = u.user_id
						AND rt.on_what = 'foto' AND cp.competition_id = ".$competition_id;
			$query .= $extras;
			$query .= " order by cp.date_added desc ";			
			$query .= $limit;

			$query = $this->db->query($query);
			$result = $query->result();
			
			$query = $this->db->query("select found_rows() as count");
			if ( ! $query) return FALSE;
			$result['count'] = $query->row()->count;
			
			return $result;		
		}
		
		function get_competition_users($competition_id){
			$query = "SELECT distinct u.*
						FROM competition_photos cp, photos p, users u
						WHERE p.moderation_state > 0 and cp.photo_id = p.photo_id AND p.user_id = u.user_id AND cp.competition_id = ".$competition_id;
			$query .= " ORDER BY u.login";
			$query = $this->db->query($query);
			if ( ! $query) return FALSE;
			return $query->result();
		}
		
		function get_competition_name($competition_id)
		{
			$this->db->from('competitions');
			$this->db->where('competition_id', $competition_id);
			$competition = $this->db->get()->row();
			if ($competition)
				return ($competition->title);
			else
				return "";
		}
		
		function update_comp($data)
		{
			if ( empty($data) || ! is_array($data) || empty($data['competition_id']) ) return FALSE;
			$this->db->where('competition_id', $data['competition_id']);
			$this->db->where('photo_id', $data['photo_id']);
			$res = $this->db->update('competition_photos', $data);
			if ( ! $res) return FALSE;

			return TRUE;
		}
		
		function set_1_place($data)
		{
			if ( empty($data) || ! is_array($data) || empty($data['competition_id']) ) return FALSE;
			$this->db->where('competition_id', $data['competition_id']);
			$res = $this->db->update('competitions', $data);
			if ( ! $res) return FALSE;
	
			return TRUE;
		}
		function set_estimate($id)
        {
           	$query ="UPDATE competitions SET type=0 WHERE type=".CMP_ESTIMATED;
            $res = $this->db->query($query);
            $query ="UPDATE competitions SET type=".CMP_ESTIMATED." WHERE competition_id =".$id;
            $res = $this->db->query($query);
           	if ( ! $res)    return FALSE;
            return TRUE;

        }
        function get_competition_estimate()
        {
          $query ="SELECT * FROM competitions WHERE type=".CMP_ESTIMATED;
          $query = $this->db->query($query);
          return $query->result();
        }
		
	}
?>