<?php

class User_mdl extends Model {

	function get_user_id_by_login($login) {
		$query = $this->db->query('select user_id from users where login="'.$login.'"');
		if ($query->num_rows() > 0) {
			$rs = $query->result();
			return $rs[0]->user_id;
		}
	}

	function get_user_id_by_email($email) {		
		$query = $this->db->query('select user_id from users where email="'.$email.'"');		
		if ($query->num_rows() > 0) {
			$rs = $query->result();
			return $rs[0]->user_id;
		}
	}

	function get_group_id_by_name($group_name) {
		$query = $this->db->query("select id from khacl_aros where name = ".clean($group_name));
		if ($query->num_rows() > 0) {
			$rs = $query->result();
			return $rs[0]->id;
		}
	}

	function pre_activate($login, $email, $language, $activation_code) {
		$registration_ip = $_SERVER['REMOTE_ADDR'];
		$group_id = $this->get_group_id_by_name('users');
		$query = 'insert into users (login, email, activation_code, registration_date, registration_ip, group_id, language) 
				  values ('.clean($login).', '.clean($email).', '.clean($activation_code).', now(), '.clean($registration_ip).', '.clean($group_id).', '.clean($language).')';
		$query = $this->db->query($query);
		return $this->db->insert_id();
	}

	function get_userdata_by_activation_info($activation_user_id, $activation_code) {
		$query = $this->db->query('select login, email,user_id from users where user_id = '.clean($activation_user_id).' and activation_code='.clean($activation_code));
		if ($query->num_rows() > 0) {
			$rs = $query->result();
			return array('login'=>$rs[0]->login,'email'=>$rs[0]->email,'user_id'=>$rs[0]->user_id);
		} else {
			return false;
		}
	}

	function get_user($user_id) {
		$query = $this->db->query('select * from users where user_id = '.clean($user_id));
		if ($query->num_rows() > 0) {
			$rs = $query->result();
			return $rs[0];
		} else {
			return false;
		}
	}
	/**
	 * Generate random 7-digit password (l and 1 not used in generation for better readability)
	 * @return string $password
	 */
	function create_random_password() {
		$chars = "abcdefghijkmnopqrstuvwxyz023456789";
		srand((double)microtime()*1000000);
		$i = 0;
		$pass = '';
		while ($i <= 7) {
			$num = rand() % 33;
			$tmp = substr($chars, $num, 1);
			$pass = $pass . $tmp;
			$i++;
		}
		return $pass;
	}

	function activate($activation_user_id, $activation_code) {
		$new_password = $this->create_random_password();
		$query = 'update users u, khacl_aros g set 
					u.password=md5('.$this->db->escape($new_password).'), 
					u.activation_code="",
					u.group_id=g.id  
					where g.name="reg_users" 
					and u.user_id = '.clean($activation_user_id).' and u.activation_code='.clean($activation_code);
		$query = $this->db->query($query);
		if ($this->db->affected_rows() > 0) {
			return $new_password;
		} else {
			return FALSE;
		}
	}

	function authorize($login, $password) {
		$query = 'select
						u.user_id,
						u.login as user_login,
                        u.birthdate as user_age,
						g.name as user_group, u.language
					from users u, khacl_aros g
					where g.id = u.group_id
						and u.login='.clean($login).'
						and u.password=md5('.$this->db->escape($password).')';
        $query = $this->db->query($query);
		if ($query->num_rows() > 0) {
			$rs = $query->result();
			return $rs[0];
		} else {
			return 0;
		}
	}
	
	function authorize_from_cookie($login, $password) {
		$query = 'select
						u.user_id,
						u.login as user_login,
                        u.birthdate as user_age,
						g.name as user_group, u.language
					from users u, khacl_aros g
					where g.id = u.group_id
						and u.login='.clean($login).'
						and u.password = "'.$password.'"';
		$query = $this->db->query($query);
		if ($query->num_rows() > 0) {
			$rs = $query->result();
			return $rs[0];
		} else {
			return 0;
		}
	}

	function update($data) {
		if ( empty($data) || ! is_array($data) || empty($data['user_id']) ) return FALSE;

		$this->db->where('user_id', $data['user_id']);
		return $this->db->update('users', $data);
	}

	function save_userdata($data) {
		$user_id=$this->db_session->userdata('user_id');
		$this->db->where('user_id', $user_id);
		$res=$this->db->update('users', $data);
		return  $res;
	}

	function set_predef_avatar($avatar_choice) {
		if (!empty($avatar_choice)) {
			$user_id=$this->db_session->userdata('user_id');
			$data['predefined_avatar']="a_".$avatar_choice.".jpg";
			$data['avatar']="";
			$this->db->where('user_id', $user_id);
			$res=$this->db->update('users', $data);
			if ($res) {
				$res=1;
			} else {
				$res=2;
			}
		} else {
			$res=0;
		}
		return $res;
	}

	function set_personal_avatar($user_id, $ext) {
		$ext = (!empty($ext))? $ext: 'jpg';
		$avatar_file_name=$user_id."_ico.".$ext;
		$avatar_dir= $this->config->item('user_avatars_dir');
		$filename=$avatar_dir."/".$avatar_file_name;

        if (file_exists($filename)) {
			$data['avatar']=$avatar_file_name;
			$data['predefined_avatar']="";
			$this->db->where('user_id', $user_id);
			$res=$this->db->update('users', $data);
			return  $res;
		}
	}

	function get_avatar_src($user_id) {
		$path=static_url()."images/predef_avatars/";
		$query = $this->db->query('select avatar,predefined_avatar from users where user_id="'.$user_id.'"');
		if ($query->num_rows() > 0) {
			$rs = $query->result();
			$user_avatar=$rs[0]->avatar;
			$predef_avatar=$rs[0]->predefined_avatar;
            $config = $this->load->config('users_config');
			if (empty($user_avatar)) {
				$path=$this->config->item('predef_avatars_http');
                if (empty($predef_avatar)){
                   $avatar_name="a_def.png";
                } else {
                   $avatar_name = $predef_avatar;
                }
			} else {
				$path=$this->config->item('user_avatars_http');
				$avatar_name =$user_avatar;
			}
		} else {
             $avatar_name="a_def.png";
		}
		return $path."/".$avatar_name."?".strval(time());
	}

	function get_count_all_users() {
		return $this->db->count_all('users');
	}

	function get_all_users_list($page = 1, $per_page = 0, $search_criteria=array()) {
			
		$limit = empty($per_page)?'':' limit '.$per_page*($page-1).','.$per_page;
		
		$query = "SELECT user_id, login, first_name, last_name, email, registration_date, groups.name, moderation_state   
				  FROM users, groups 
				  WHERE (users.group_id = groups.group_id) ";
		
		if (!empty($search_criteria)) {
				if (!empty($search_criteria['login'])) {
					$query .= "AND login LIKE '%".$search_criteria['login']."%' ";
				}
				if (!empty($search_criteria['username'])) {
					$query .= "AND first_name LIKE '%".$search_criteria['username']."%' ";
				}
				if (!empty($search_criteria['usersname'])) {
					$query .= "AND last_name LIKE '%".$search_criteria['usersname']."%' ";
				}
				if (!empty($search_criteria['useremail'])) {
					$query .= "AND email LIKE '%".$search_criteria['useremail']."%' ";
				}
				if (!empty($search_criteria['date']['start']) && !empty($search_criteria['date']['end'])) {
					$query .= "AND registration_date BETWEEN '".$search_criteria['date']['start']."' AND '".$search_criteria['date']['end']."' ";
				}
				if ($search_criteria['moderation'] != -999) {
					$query .= "AND moderation_state = '".$search_criteria['moderation']."' ";
				}
		}
			$test = $this->db->query($query)->result();
			$cnt = $this->db->query($query)->num_rows();
			$query .= $limit;
			return array(
				'cnt' => $cnt,
				'photos' => $this->db->query($query)->result()
			);
	
	}

	function get_groups_list() {
		$query = "select * from groups";
		$query = $this->db->query($query);

		 $Out_array = array();
		 if ($query->num_rows() > 0) {
		 	foreach ($query->result() as $row) {
		 		$Out_array[$row->group_id] = $row->name;
			}
		} // 	/if
		return $Out_array;
	}

	function create_user() {
		$data = $this->parse_user_form();
		$this->db->insert('users',$data);
	}

	function save_user_details() {
		if ( isset($_POST['user_id']) &&  $_POST['user_id'] === "") {
			// INSERT
			$this->create_user();
			return;
		}
		
		// UPDATE
		if ( isset($_POST['user_id']) )
			$user_id = $this->input->post('user_id');

		$data = $this->parse_user_form();
		$this->db->where( array("user_id" => $user_id));
		$this->db->update('users',$data);
	}

	private function parse_user_form() {
		$data = array();
		if ( isset($_POST['user_firstname']) )
			$data['first_name'] = $this->input->post('user_firstname');
		if ( isset($_POST['user_lastname']) )
			$data['last_name'] = $this->input->post('user_lastname');
		if ( isset($_POST['user_login']) )
			$data['login'] = $this->input->post('user_login');
		if ( isset($_POST['user_email']) )
			$data['email'] = $this->input->post('user_email');
		if ( isset($_POST['user_password']) &&  $_POST['user_password'] != '') {
			$data['password'] = md5($_POST['user_password']);
		} else {
			unset($data['password']);
		}
		if ( isset($_POST['user_m_state']) )
			$data['moderation_state'] = $this->input->post('user_m_state');
		if ( isset($_POST['user_group']) )
			$data['group_id'] = $this->input->post('user_group');

		return $data;
	}

	function delete_user() {
		$id = $this->input->post('cid');
		if ( count($id) == 1 ) {
			$this->db->delete('users', array('user_id' => $id[0]));
		} else {
			foreach ($id as $value ) {
				$this->db->delete('users', array('user_id' => $value));
			}
		}
	}

	function get_age($user_id) {
		$query = $this->db->query('SELECT DATEDIFF( NOW() , (select birthdate from users where user_id = '.$user_id .' ) ) as age');
		if ($query->num_rows() > 0) 
		{
			$rs = $query->result();
		} 
		else 
		{
			return -1;
		}						
		$t = $rs[0]->age;		
		if ($t / (365) >= 18)    return 1;    else  return (-1);
	}

	function get_name($user_id) {
		$query = $this->db->query('select login from users where user_id = '.clean($user_id));
		if ($query->num_rows() > 0) {
			$rs = $query->result();
			return $rs[0]->login;
		} else {
			return "no name";
		}
	}
		
	function get_user_email ($photo_id){
		$query = $this->db->query('select login, email, language from users left join photos on photos.user_id = users.user_id  where photo_id  = '.clean($photo_id));
		if ($query->num_rows() > 0) {
			$rs = $query->result();
			return $rs[0];
		} else {
			return false;
		}
	}
	
	function update_pass($id,$password)
	{
		$data = array('password' => md5($password));
		$this->db->where('user_id', $id);
		if ($this->db->update('users', $data))
		{
			return TRUE;
			
		}
		else 
		{
			return FALSE;
		}
	
	}
	
	function get_user_psw  ($user_id){
        $query = "select password from users where user_id = " . $user_id;
        $query = $this->db->query($query);
      	$row = $query->row();
        return $row->password;
    }
    
	function get_user_email_album ($album_id){
		$query = $this->db->query('select login, email, language from users left join albums on albums.user_id = users.user_id  where album_id  = '.clean($album_id));
		if ($query->num_rows() > 0) {
			$rs = $query->result();
			return $rs[0];
		} else {
			return false;
		}
	}
	
	function registration ($login, $email, $password, $language, $group_id, $birthdate, $country_id, $region_id, $city_id, $userinfo) {
		try {
			$registration_ip = $_SERVER['REMOTE_ADDR'];
			
			$birthdate = empty($birthdate) ? 'NULL' : clean($birthdate);
			$country_id = empty($country_id) ? 'NULL' : clean($country_id);
			$city_id = empty($city_id) ? 'NULL' : clean($city_id);
			$region_id = empty($region_id) ? 'NULL' : clean($region_id);
			$group_id = empty($group_id) ? 'NULL' : clean($group_id);
			
			$query = 'insert into users (login, email, password, birthdate, country_id, region_id, city_id, registration_date, registration_ip, about, group_id, language,comment_can)
					  values ('.clean($login).', '.clean($email).', md5('.clean($password).'), '.$birthdate.', '.$country_id.', '.$region_id.', '.$city_id.', \''.date("Y-m-d").'\', '.clean($registration_ip).', '.clean($userinfo).', '.$group_id.', '.clean($language).',1)';
			$query = $this->db->query($query);
			return $this->db->insert_id();
			
		} catch (Exception $e) {
    		log_message('error',$e->getMessage()."\n".
    							"file: ".$e->getFile()."\n".
    							"code: ".$e->getCode()."\n".
    							"line: ".$e->getLine());
    	}
	}
}

/* End of file test.php */
/* Location: ./models/test.php */