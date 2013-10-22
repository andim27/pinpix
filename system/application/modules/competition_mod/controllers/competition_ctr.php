<?php
	/**
	 * Class Competition
	 *
	 * controller for competition class
	 *
	 * @author   Popov
	 * @access   public
	 * @package  Competition.class.php
	 * @created  Wed Feb 04 17:19:43 EET 2009
	 */
	class Competition_ctr extends Controller  {

		var $_lng = '';
		
		private $comp_per_page = 10;
		private $comp_num_links = 20;
		private $comp_per_page_sidebar = 10;

		/**
		 * Constructor of Competition
		 *
		 * @access  public
		 */
		function __construct() {
			parent::Controller();

			$this->_lng = $this->db_session->userdata('user_lang');
         	$this->load->language('competition',$this->_lng);         	         
		}

		function index() {}

		function ajax_actions() {
					
			$out_str="";
			$action=$this->input->post("action");

			switch ($action) {
				
				case "estimate":
					$id =$this->input->post("id");
				
					$this->load->model('competition_mdl','competition');
					$res = $this->competition->set_estimate($id);
					
					if (! $res) {
						$out_str="{'err':'1','mes':'Error!'}";
					} else {
						$out_str="{'err':'0','mes':'(Done!)'}";
					}
						
				break;
				
				case "filter_photos":
					$competition_id = $this->input->post("competition_id");
					$user_id = $this->input->post("user_id");
					$date_start = $this->input->post("date_start");
					$date_end = $this->input->post("date_end");
					$status_id = $this->input->post("status_id");
					$cur_page = $this->input->post("cur_page");
					
					$extras = "";
					
					if(!empty($user_id)) $extras .= " and u.user_id=".clean($user_id);
					if(!empty($date_start) && !empty($date_end)) {
						$date_start = date("Y-m-d", strtotime($date_start));
						$date_end = date("Y-m-d", strtotime($date_end));
						
						$extras .= " AND cp.date_added BETWEEN '".$date_start."' AND '".$date_end."' ";
					}
					if($status_id != '' && $status_id != 'null') {
						$extras .= " and cp.moderation_state=".clean($status_id);
					}
					
					$photos = $this->get_admin_competition_photos($competition_id, 10, $cur_page, $extras);		
					$photos_count = $photos['count'];
					unset($photos['count']);
					
					$moderations_arr = array(
						'mod_new' => MOD_NEW,
						'mod_approved' => MOD_APPROVED,
						'mod_featured' => MOD_FEATURED,
						'mod_declined' => MOD_DECLINED,
						'mod_deleted' => MOD_DELETED
					);
					
					$val = array();
					$val['photos'] = $photos;
					$val['moderations_arr'] = $moderations_arr;
					$photos_template = $this->load->view('_competition_photos', $val, true);
					
					$page_container = array(
						'total_rows' => $photos_count,
						'per_page' => $this->comp_per_page,
						'num_links' => $this->comp_num_links,
						'cur_page' => $cur_page,
						'js_function' => 'filter_photos',
						'base_url' => base_url().'admin/competitions_photos/page/'
					);			
					$page_container = paginate_ajax($page_container);
					
					$out_str = (Object)array('template' => $photos_template, 'paginate' => $page_container);
					$out_str = json_encode($out_str);
					
				break;
				
				case "apply_action":
					$photos = (object)unserialize($this->input->post('photos'));
					
					if(!empty($photos) && isset($photos->chb) && !empty($photos->chb)) {
						$photos_chb = $photos->chb;
						$competition_id = $photos->competition_id;
						
						foreach ($photos_chb as $chb) {
							$photo_data = array();
							
							$photo_data['photo_id'] = $chb['photo_id'];
							$photo_data['competition_id'] = $competition_id;
							$photo_data['moderation_state'] = $chb['ms'];
							
							$this->update_comp($photo_data);
						}
						$out_str = 1;
						
					} elseif (!empty($photos)) {						
						$photo_data = array();
							
						$photo_data['photo_id'] = $photos->photo_id;
						$photo_data['competition_id'] = $photos->competition_id;
						$photo_data['moderation_state'] = $photos->moderation_state;
						
						$out_str = $this->update_comp($photo_data);					
					}			
					
				break;
			}
			
			$this->output->set_output($out_str);
		}
		
		/**
		 * get_competition
		 *
		 * get competition
		 *
		 * @author  Popov
		 * @class   Competition_str
		 * @access  public
		 * @param   int     $competition_id  
		 * @return  object  $competitions
		 */
		function get_competition($competition_id = null, $per_page = 10, $page = 1) {
			$this->load->model('competition_mdl','competition');
			return $this->cache->process('all_competitions10', array($this->competition, 'get_competition'), array($competition_id, $per_page, $page), 3*60);			
		}
		
		function view_competition($competition_id, $comp_per_page = 0, $comp_page = 1, $filter='', $with_count=false, $concurs_mod_state = false){
			
			$this->load->model('competition_mdl','competition');
			
			// check opened competitions
			$opened_isset = $this->competition->get_competition2(null, 0, 1, " AND current_date() <= competitions.end_date ");
			if(!empty($opened_isset) && empty($filter)) $filter = 'open' ;
			elseif (empty($filter)) $filter = 'close';
			
			$data = array();
			$extras = '';
			if(!empty($filter)) {
				if($filter == 'open') {
					$extras = ' AND current_date() <= competitions.end_date ';
				} elseif ($filter == 'close') {
					$extras = ' AND current_date() > competitions.end_date ';
				}
			}

			$competitions = $this->competition->get_competition2(null, $comp_per_page, $comp_page, $extras, $with_count);
			$competitions_sidebar = $this->competition->get_competition2(null, $this->comp_per_page_sidebar);
			
			if ($competitions)  {
            	
				$count = $competitions['count'];
				unset($competitions['count']);
			
            	$config = $this->load->config();
                $h_min	=$this->config->item('h_min');
                $w_min	=$this->config->item('w_min');
                modules::load_file('constants',MODBASE.'gallery_mod/config/');
                $p_min=false;
                $config = $this->load->config();
                
                $extras = "";
                if($concurs_mod_state && is_int($concurs_mod_state)) {
					if($concurs_mod_state < 3 && $concurs_mod_state > -3)
						$extras = " and cp.moderation_state = '".$concurs_mod_state."'"; 
				}
                    
            	foreach ($competitions as $index=>$competition) {
            		$competition->works = $this->competition->get_competition_photos_new($competition->competition_id, 10, 1, false, $extras);
            		
            		if(!empty($competition->works)) {
            			if($index == 0) {
            				foreach ($competition->works as $work) {
								$src_md = photo_location().date('m', strtotime($work->date_added)).'/'.$work->photo_id.'-md'.$work->extension;
								$work->src_lg = photo_location().date('m', strtotime($work->date_added)).'/'.$work->photo_id.'-lg'.$work->extension;
								if (((intval(($work->md_width)) < $w_min) && ($work->md_height < $h_min)) ) {
									$p_min=true;
									$f_w = $work->md_width."px";
									$f_h = $work->md_height."px";
								}
								if ($work->land) {
									$h_str=strval($work->md_height)."px";
									$f_h="400px";
									$f_w="630px";
									$work->pad_top=(630-$work->md_height)*0.32;
									$work->fl_cont_html = $this->fl->get_block_html($src_md,$f_w,$h_str,"fl_container_".$work->photo_id);
									$work->fl_cont_js   = $this->fl->get_control_js($src_md,$f_w,$h_str);
								
								} else {
									$f_h="592px";
									$f_w="444px";
									$work->fl_cont_html = $this->fl->get_block_html($src_md,$f_w,$f_h,"fl_container_".$work->photo_id);
									$work->fl_cont_js   = $this->fl->get_control_js($src_md,$f_w,$f_h);
								}
            				}            				
            			} else {            				
            				foreach ($competition->works as $work) {
	            				$src_md = photo_location().date('m', strtotime($work->date_added)).'/'.$work->photo_id.'-md'.$work->extension;
	                            $work->src_lg = photo_location().date('m', strtotime($work->date_added)).'/'.$work->photo_id.'-lg'.$work->extension;	                             
	                            if ($work->land){
	                                $h_str=strval(intval($work->md_height/2))."px";
	                                $work->pad_top=(312-$work->md_height)*0.32;
	                                $work->fl_cont_html = $this->fl->get_block_html($src_md,"312px",$h_str,"fl_container_mini_".$work->photo_id);
	    						    $work->fl_cont_js = $this->fl->get_control_js($src_md,"312px",$h_str);
	    						    
	                            } else {
	                                $work->fl_cont_html = $this->fl->get_block_html($src_md,"222px","296px","fl_container_mini_".$work->photo_id);
	    						    $work->fl_cont_js = $this->fl->get_control_js($src_md,"222px","296px");
	                            }	                            
	    						$work->username = $work->login;
	    						$work->comments = $work->comcnt;
	    						$work->num_votes = $work->num_votes;
            				}
            			}
            		}            		
            	}
            	$data['p_min']=$p_min;
            	
            	// SIDEBAR
            	$cur_comp_id = -1;
	        	$current_date = date("Ymd");
	        	
		  		$t = 0;
		  		$competition = null;
		  		foreach ($competitions_sidebar as $index=>$competition) {
		  			$competition->works = $this->competition->get_competition_photos_new($competition->competition_id, 10);
		  			if ($competition->photo_id) 
					{
						$photo = modules::run('gallery_mod/gallery_ctr/get_photo', $competition->photo_id, 'lg', 'all');
						if(!empty($photo)) {
							$competition->urlImg = photo_location().date("m", strtotime($photo->date_added))."/".$photo->photo_id."-sm".$photo->extension;
						} else {
							$competition->urlImg = "";
							$photo->land = null;
							$photo->sm_height = 0;
							$photo->sm_width = 0;
							
						}
						
						if ($photo->land)
						{
							$competition->nheight = $photo->sm_height/2.23;
							$competition->nwidth = 65;
							$competition->margin_top = 0.33 * (60 - $competition->nheight) ; 
							$competition->margin_bottom = 0.66 * (60 - $competition->nheight) ; 
							$competition->margin_left = 0 ; 
							$competition->margin_right = 0 ; 
						}
						else
						{
							$competition->nheight = 60;
							$competition->nwidth = $photo->sm_width/2.3;
							$competition->margin_top = 0; 
							$competition->margin_bottom = 0; 
							$competition->margin_left = 0.25 * (65 - $competition->nwidth); 
							$competition->margin_right = 0.75 * (65 - $competition->nwidth);
						}
					}				
					if(!empty($competition->end_date)) 
					{				
						$comp_date = date("Ymd", strtotime($competition->end_date));
						if($current_date <= $comp_date)	{
							$competition->status = 1;
							$competition->status_word = lang('comp_date_execution_open');
							$competition->status_img = static_url().'images/ic_contest_active.gif';
						} 
						else 
						{
							$competition->status = 0;
							$competition->status_word = lang('comp_date_execution_close');
							$competition->status_img = static_url().'images/ic_contest_inpast.gif';
						}
					}
					if ($competition_id == $competition->competition_id )
		            {
			            $cur_comp_id = $i; 	
		            }
		            else {
		            	if ($t<2) {
							if($competition->status == 0 )
			    			{
			    				$last2[$t] = $competition;
			    				$t++;
			    			}
		            	}
		            }	
		  		}		  		
		  		$data['filter']	= $filter;	           
				$data['register_block'] = modules::run('users_mod/users_ctr/register');
				$data['allcompetitions'] = $competitions_sidebar;
				$data['competitions'] = $competitions;
				$data['paginate_args'] = array(
					'total_rows'  => $count,
					'per_page'    => $comp_per_page,
					'cur_page'    => $comp_page,
					'num_links' => $this->comp_num_links,
					'base_url'    => base_url().'competition/view/'.$filter.'/page/',
					'uri_segment' => 4
				);				
			}
			$this->load->view('competitions', $data); 			
		}

		/**
		 * view_competition_works
		 *
		 * display competition's fotos
		 *
		 * @author  Popov
		 * @class   Competition_ctr
		 * @access  public
		 * @param   int     $competition_id  
		 * @return  string  $competition_works
		 */
		function view_competition_works($competition_id, $photo_per_page=0, $photo_page=1, $filter='winners', $with_count=false, $concurs_mod_state = true) {
			if(empty($competition_id)) return false;
			if(empty($filter)) $filter = 'winners';
		
			$data = array();
			$this->load->model('competition_mdl','competition');
		
			$competitions = $this->competition->get_all_competitions();
			if (!$competitions)  {
	        	set_error(lang('comp_empty'));
	        	$this->load->view('competition');
	        }
	        $cur_comp_id = -1;  
	        $current_date = date("Ymd");	
	  		$t = 0;
	  		
	  		if(!empty($competitions)) {
	  			foreach ($competitions as $i=>$competition) {
	  				//for sitebar
					if (isset($competition->photo_id) && !empty($competition->photo_id)) 
					{
						if(!empty($competition->photo_id)) {
							$photo = modules::run('gallery_mod/gallery_ctr/get_photo', $competition->photo_id, 'lg', 'all');
							
							if(!empty($photo)) {
								$competition->urlImg = photo_location().date("m", strtotime($photo->date_added))."/".$photo->photo_id."-sm".$photo->extension;
								if ($photo->land) 
								{
									$competition->nheight = $photo->sm_height/2.23;
									$competition->nwidth = 65;
									$competition->margin_top = 0.33 * (60 - $competition->nheight) ; 
									$competition->margin_bottom = 0.66 * (60 - $competition->nheight) ; 
									$competition->margin_left = 0 ; 
									$competition->margin_right = 0 ; 
								}  
								else
								{	
									$competition->nheight = 60;
									$competition->nwidth = $photo->sm_width/2.3;
									$competition->margin_top = 0; 
									$competition->margin_bottom = 0; 
									$competition->margin_left = 0.25 * (65 - $competition->nwidth); 
									$competition->margin_right = 0.75 * (65 - $competition->nwidth);
								}
							} else {
								$competition->urlImg = "";
								$competition->nheight = 60;
								$competition->nwidth = 0/2.3;
								$competition->margin_top = 0; 
								$competition->margin_bottom = 0; 
								$competition->margin_left = 0.25 * (65 - $competition->nwidth); 
								$competition->margin_right = 0.75 * (65 - $competition->nwidth);
							}
						} else {
							$competition->urlImg = "";
							$competition->nheight = 60;
							$competition->nwidth = $photo->sm_width/2.3;
							$competition->margin_top = 0; 
							$competition->margin_bottom = 0; 
							$competition->margin_left = 0.25 * (65 - $competition->nwidth); 
							$competition->margin_right = 0.75 * (65 - $competition->nwidth);
						}					
					}
					if(!empty($competition->end_date)) 
					{				
						$comp_date = date("Ymd", strtotime($competition->end_date));
						if($current_date <= $comp_date)	{
							$competition->status = 1;
							$competition->status_word = lang('comp_date_execution_open');
							$competition->status_img = static_url().'images/ic_contest_active.gif';
						} 
						else 
						{
							$competition->status = 0;
							$competition->status_word = lang('comp_date_execution_close');
							$competition->status_img = static_url().'images/ic_contest_inpast.gif';
						}				
					}
		        	if ($competition_id == $competition->competition_id )
		            {
			            $cur_comp_id = $i; 	
		            }
		            else {
		            	if ($t<2) {
							if($competition->status == 0 )	    			
			    			{
			    				$last2[$t] = $competition;
			    				$t++; 	
			    			}
		            	}
		            }
	  			}
	  		}
	  		
	        if ($cur_comp_id == -1)
	        {
				$cur_competition = $this->competition->get_competition($competition_id);
				if ( empty ( $cur_competition) )
					show_404(lang('page_not_found'));	
						            
				$cur_comp_id = count($competitions) + 1;
				array_push ($competitions, $cur_competition);	          
	        }
	        
			if ($competitions[$cur_comp_id]->status == 1 ) 
	        {
	        	$filter = 'all';
	        	$data['curent_comp_opened']	= true;
	        }
			   	
			$competition = $this->get_competition($competition_id);
			if($competition && is_array($competition)) $competition = $competition[0];
			
			$order_by = "p.score";
			$extras = "";
			 				
	        if($concurs_mod_state && is_int($concurs_mod_state)) {
				if($concurs_mod_state < 3 && $concurs_mod_state > -3)
					$extras .= " and cp.moderation_state = '".$concurs_mod_state."'"; 
			}
			
			if(empty($filter)) {
				// check if isset WINNERS by default
				$_extras = $extras;
				$_extras .= " AND p.score between 1 and 3 ";
				
				$competition_works = $this->get_work_competition($competition_id, $photo_per_page, $photo_page, true, $_extras, $order_by);
				$count = $competition_works['count'];
				unset($competition_works['count']);
				
				// get ALL OTHER photos
				if(empty($competition_works)) {
					$competition_works = $this->get_work_competition($competition_id, $photo_per_page, $photo_page, true, $extras, $order_by);
					$count = $competition_works['count'];
					unset($competition_works['count']);
				}
				
			} else {
				if ($filter == 'winners') $extras .= " AND p.score between 1 and 3 ";				
				$competition_works = $this->get_work_competition($competition_id, $photo_per_page, $photo_page, true, $extras, $order_by);
				$count = $competition_works['count'];
				unset($competition_works['count']);
			}
			
			if(empty($competition_works)) {
				$_extras = $extras;
				if ($filter == 'winners') $_extras .= " AND p.score between 1 and 3 ";
				
				$competition_works = $this->get_work_competition($competition_id, $photo_per_page, $photo_page, true, $extras, $order_by);
			
				$count = $competition_works['count'];
				unset($competition_works['count']);
			}
			
			$competition_works = modules::run('gallery_mod/gallery_ctr/small_img_prepare', $competition_works);
	
			$data['paginate_args'] = array(
				'total_rows'  => $count,
				'per_page'    => $photo_per_page,
				'cur_page'    => $photo_page,
				'num_links'   => $this->comp_num_links,
				'base_url'    => base_url().'competition/details/'.$competition_id.'/'.$filter.'/page/',
				'uri_segment' => 6
			);
				
			$data['competition'] = $competition;
			$data['competition_works'] = $competition_works;
			$data['filter'] = $filter;
			$data['register_block'] = modules::run('users_mod/users_ctr/register');
	        $data['allcompetitions'] = $competitions;
			
			$this->load->view('competition_works', $data);
		}
		
	    function view_competition2($competition_id, $per_page = 10, $page = 1, $sort_type=1, $sort_order='') {
       
            $this->load->model('competition_mdl','competition');

         	//here we get 10 competitions with works to show at page and at sitebar		
            $competitions = $this->competition->get_all_competitions($per_page, $page, $sort_type, $sort_order, $works_per_page = 10);
            
            if (!$competitions)  {
            	set_error(lang('comp_empty'));
            	$this->load->view('competition');
            }
            
	        $cur_comp_id = -1;  
	        $current_date = date("Ymd");  
  				
	  		$t = 0;		      
            for ($i = 0; $i<count($competitions); $i++ )
            {
            	//for sitebar
				if ($competitions[$i]->photo_id) 
				{
					$photo = modules::run('gallery_mod/gallery_ctr/get_photo', $competitions[$i]->photo_id, 'lg', 'all');
					$competitions[$i]->urlImg = photo_location().date("m", strtotime($photo->date_added))."/".$photo->photo_id."-sm".$photo->extension;					
					if ($photo->land) 
				 	            {
				 	            	$competitions[$i]->nheight = $photo->sm_height/2.23;
				 	            	$competitions[$i]->nwidth = 65;
				 	            	$competitions[$i]->margin_top = 0.33 * (60 - $competitions[$i]->nheight) ; 
				 	            	$competitions[$i]->margin_bottom = 0.66 * (60 - $competitions[$i]->nheight) ; 
				 	            	$competitions[$i]->margin_left = 0 ; 
				 	            	$competitions[$i]->margin_right = 0 ; 
				 	            }  
				 	          	else
				 	          	{	
				 	          		$competitions[$i]->nheight = 60;
				 	          		$competitions[$i]->nwidth = $photo->sm_width/2.3;
				 	          		$competitions[$i]->margin_top = 0; 
				 	            	$competitions[$i]->margin_bottom = 0; 
				 	            	$competitions[$i]->margin_left = 0.25 * (65 - $competitions[$i]->nwidth) ; 
				 	            	$competitions[$i]->margin_right = 0.75 * (65 - $competitions[$i]->nwidth) ; 			 	            	
				 	            } 	
				}
				
				if(!empty($competitions[$i]->end_date)) 
				{				
					$comp_date = date("Ymd", strtotime($competitions[$i]->end_date));
					if($current_date <= $comp_date)	{
						$competitions[$i]->status = 1;
						$competitions[$i]->status_word = lang('comp_date_execution_open');
						$competitions[$i]->status_img = static_url().'images/ic_contest_active.gif';
					} 
					else 
					{
						$competitions[$i]->status = 0;
						$competitions[$i]->status_word = lang('comp_date_execution_close');
						$competitions[$i]->status_img = static_url().'images/ic_contest_inpast.gif';
					}				
				}
				if ($competition_id == $competitions[$i]->competition_id )
	            {
		            $cur_comp_id = $i; 	
	            }
	            else {
	            	if ($t<2) {
						if($competitions[$i]->status == 0 )	    			
		    			{
		    				$last2[$t] = $competitions[$i];
		    				$t++; 	
		    			}
	            	}
	            }				
            }
            if ( $cur_comp_id == -1)
            {
				$cur_competition = $this->competition->get_competition($competition_id);
				if ( empty ( $cur_competition) )
					show_404(lang('page_not_found'));	
						            
				$cur_comp_id = count($competitions) + 1;
				array_push ($competitions, $cur_competition);	          
            }

         	$data['register_block'] = modules::run('users_mod/users_ctr/register');
         	$data['allcompetitions'] = $competitions;    
            $data['keywords'] = $competitions[$cur_comp_id]->title;
                 
    		$is_closed = FALSE;
    		if ($competitions[$cur_comp_id]->status == 0) {
    			$is_closed = TRUE;			
    		}
    		
    	 /* ************************************************************************************************** */	
    		if ($is_closed) 
    		{
                    $config = $this->load->config();
	                $h_min	=$this->config->item('h_min');
                    $w_min	=$this->config->item('w_min');
                    modules::load_file('constants',MODBASE.'gallery_mod/config/');
                    $p_min=false;
                    $config = $this->load->config();
    				foreach ($competitions[$cur_comp_id]->works as $i => $work) 
    				{
						$src_md = photo_location().date('m', strtotime($work->date_added)).'/'.$work->photo_id.'-md'.$work->extension;
						$work->src_lg = photo_location().date('m', strtotime($work->date_added)).'/'.$work->photo_id.'-lg'.$work->extension;
                        if (((intval(($work->md_width)) < $w_min) && ($work->md_height < $h_min)) ) {
                            $p_min=true;
                            $f_w = $work->md_width."px";
                            $f_h = $work->md_height."px";
                         }
                        if ($work->land){
                            $h_str=strval($work->md_height)."px";
                            $f_h="400px";
                            $f_w="630px";
                            $work->pad_top=(630-$work->md_height)*0.32;
                            $work->fl_cont_html = $this->fl->get_block_html($src_md,$f_w,$h_str,"fl_container_".$work->photo_id);
						    $work->fl_cont_js   = $this->fl->get_control_js($src_md,$f_w,$h_str);

                        } else {
                            $f_h="592px";
                            $f_w="444px";
                            $work->fl_cont_html = $this->fl->get_block_html($src_md,$f_w,$f_h,"fl_container_".$work->photo_id);
						    $work->fl_cont_js   = $this->fl->get_control_js($src_md,$f_w,$f_h);
                        }
					}
    			
    				$data['competitions'] = $competitions[$cur_comp_id];
                    $data['p_min']=$p_min;
    				$i=0;
    				if (!empty ($last2))
    				{
	    				foreach($last2 as $idx => $work) 
	    				{			
	    					foreach($last2[$idx]->works as $i => $work) {
	    						$src_md = photo_location().date('m', strtotime($work->date_added)).'/'.$work->photo_id.'-md'.$work->extension;
	                            $work->src_lg = photo_location().date('m', strtotime($work->date_added)).'/'.$work->photo_id.'-lg'.$work->extension;
	                             //--b:AndMak -
	                            if ($work->land){
	                                $h_str=strval(intval($work->md_height/2))."px";
	                                $work->pad_top=(312-$work->md_height)*0.32;
	                                $work->fl_cont_html = $this->fl->get_block_html($src_md,"312px",$h_str,"fl_container_mini_".$work->photo_id);
	    						    $work->fl_cont_js = $this->fl->get_control_js($src_md,"312px",$h_str);
	
	                            } else {
	                                $work->fl_cont_html = $this->fl->get_block_html($src_md,"222px","296px","fl_container_mini_".$work->photo_id);
	    						    $work->fl_cont_js = $this->fl->get_control_js($src_md,"222px","296px");
	                            }
	
	    						$work->username = $work->login;
	    						$work->comments = $work->comcnt;//(int)modules::run('comments_mod/comments_ctr/get_object_comments_count_by_id', 'photo', $work->photo_id);
	    						$work->num_votes = $work->num_votes;
	    					}
	    				}
	    				$data['last2'] = $last2;
    				}
    				else
    					$data['last2'] = "";
    				$this->load->view('closed_competition', $data); 
    			} 
    			/* ************************************************************************************************** */
   
    			else   			
    			{   				
    				//pagination parameters
    				$data['paginate_args'] = array(
    					'total_rows'  =>  $this->competition->get_competition_photo_count($competition_id),
    					'per_page'    => 16,
    					'cur_page'    => $page,
    					'base_url'    => base_url().'competition/view/'.$competition_id.'/page/',
    					'uri_segment' => 5
    				);
    				//sort parameters
    				$data['sort_order']=$sort_order;
    				$data['sort_type']=$sort_type;
    				$i=0;
    				
    				foreach ($competitions[$cur_comp_id]->works as $photo) :
    					$data['coms'][$i] = $photo->comcnt;
    					$data['views'][$i] = $photo->num_votes;
    					$i++;
    				endforeach;
    				
    				//set white margins
					$data['photos'] = modules::run('gallery_mod/gallery_ctr/small_img_prepare', $competitions[$cur_comp_id]->works);
    	
    				
                    //--b:AndMak
                    //$data['pad_top']=(312-$photo->md_height)*0.32;
                    //--e:AndMak
                    $data['competitions'] = $competitions[$cur_comp_id];
    				$this->load->view('competition', $data);
    			}
    			
		}

		function get_closed_competition($count = null, $competition_id ) {
			$this->load->model('competition_mdl','competition');
			return $this->competition->get_closed_competition($count, $competition_id );
		}

		/**
		 * add_competition
		 *
		 * add competition
		 *
		 * @author  Popov
		 * @class   Competition_ctr
		 * @access  public
		 * @param   array     $photo_id is null by default 
		 * @return  object  $competition
		 */
		function add_competition($photo_id, $info = null) {
			$this->load->helper(array('form', 'url'));
	
			$this->load->library('validation');
				
			$rules['competition_title'] = "required";
			$rules['competition_desc'] = "required";
			$rules['competition_rules'] = "required";
			$rules['competition_type'] = "required";
			
			$this->validation->set_rules($rules);

			$fields['competition_title'] = lang('comp_name_comp');
			$fields['competition_date_start'] = lang('comp_date_start');
			$fields['competition_date_end'] = lang('comp_date_end');
			$fields['competition_photo'] = lang('comp_photo');
			$fields['competition_desc'] = lang('comp_desc_comp');
			$fields['competition_rules'] = lang('comp_rules');
			$fields['competition_type'] = lang('comp_type');
		
			$this->validation->set_fields($fields);
				
			if ($this->validation->run() == FALSE) {				
				$this->load->view('competition_add');
				
			} else {
				
				$this->load->model('competition_mdl','competition');
				
	    		$competition_id = $this->competition->set_competition($info, $photo_id);
	    		
	    		redirect('competition');
			}
			
		}

		/**
		 * add_work_to_competition
		 *
		 * add work to competition
		 *
		 * @author  Popov
		 * @class   Competition_ctr
		 * @access  public
		 * @param   array     $info  
		 * @return  object  $competition
		 */
		function add_work_to_competition($photo_id, $info = null) {
			$user_id = $this->db_session->userdata('user_id');
			if(!$user_id) return false;
			if(empty($photo_id)) return false;	

			if($info && $info['comp_work_submit'] == true) {
				$this->load->model('competition_mdl','competition');
				return $this->competition->set_work_to_comp($info, $user_id, $photo_id);
			} else {
				$data['competition_id'] = $this->uri->segment(3);
				$this->load->view('competition_work_add', $data);
			}
		}

		/**
		 * get_work_competition
		 *
		 * get works of competition
		 *
		 * @author  Popov
		 * @class   Competition_ctr
		 * @access  public
		 * @param   int     $competition_id  
		 * @return  object  $works
		 */
		function get_work_competition($competition_id, $per_page=0, $page=1, $with_count=false, $extras="", $order_by="place_taken", $group_by="p.photo_id") {
			$this->load->model('competition_mdl','competition');
			$competition = $this->competition->get_competition_photos_new($competition_id, $per_page, $page, $with_count, $extras, $order_by, $group_by);
			return $competition;
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
			$this->load->model('competition_mdl','competition');
			return $this->cache->process('competition_list', array($this->competition, 'get_competitions_list'), array(), 3*60);
			//return $this->competition->get_competitions_list();
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
			$this->load->model('competition_mdl','competition');
			return $this->competition->get_competitions_by_photos($photos_ids);
		}

		function get_competition_photo_count($competition_id) {
			$this->load->model('competition_mdl','competition');
			return $this->competition->get_competition_photo_count($competition_id);
		}

		/**
		 * Destructor of Competition
		 *
		 * @access  public
		 */
		function __destruct() {}
		
		function view_competition_new($competition_id, $per_page = 10, $page = 1, $sort_type=1, $sort_order='', $_user_id = '') {
			//временно сохраняем использованные ранее параметры
			$data = modules::run('users_mod/users_ctr/fill_reg_form');
			$data['_user_id'] = $_user_id; 
			$this->load->view('competition_new', $data);

		}

		/**
		 * simple_add
		 * 
		 * Add competition from front controller
		 * 
		 * @author Tsapenko
		 * @param String $start_date
		 * @param String $end_date
		 * @param String $description
		 * @param String $title
		 */
		function simple_add($data){
			$this->load->model('competition_mdl','competition');
			$this->competition->simple_add($data);
		}
		
		function simple_update($comp_id, $data){
			$this->load->model('competition_mdl','competition');
			return $this->competition->simple_update($comp_id, $data);
		}
						
		function add_work_to_competition2($photo_id, $comp_id) {
			$this->load->model('competition_mdl','competition');
			$this->competition->set_work_to_comp2($photo_id, $comp_id);
		}
	
		function get_last_closed_comp() {
			$this->load->model('competition_mdl','competition');
			//return $this->competition->get_last_closed_comp();
			 return $this->cache->process('closed_com', array($this->competition, 'get_last_closed_comp'), array(), 3*60 );
			}
		
		//if photo belongs to competition, returns competition name
		function get_compname_for_photo($photo_id){
					
			$this->load->model('competition_mdl','competition');
			return ($this->competition->get_compname_for_photo($photo_id));				
		}
	
		function get_comp_details()
		{
			$this->_lng = $this->db_session->userdata('user_lang');
			$this->load->language('competition',$this->_lng);
	
			$this->load->model('competition_mdl','competition');
			$this->load->helper('form');
	
			if ( $_POST['action'] == 'edit' ) {
				$curr_id = $_POST['cid'][0];
				$data['comp_info_'] = $this->competition->get_competitions($curr_id);
				$this->load->view('comp_details', $data);
			}
			else
				$this->load->view('comp_details');
			
		}
	
		function get_all_comp_list()
		{
			$this->load->model('competition_mdl','competition');
			$this->_lng = $this->db_session->userdata('user_lang');		
			$this->load->language('competition',$this->_lng);
						
			if (isset($_POST['action']) ) {
				switch ($_POST['action']) {
					case 'save': $this->competition->save_comp_details();
						break;
					case 'delete': $this->competition->delete_comp ();
						break;
					default : break;
				}
		}
		
		$data['table_rez'] = $this->competition->get_all_comp_list ();
		
		$this->load->helper('form');
		$this->load->language('competition',$this->_lng);
		$data['moder_state'] = array( "0" => "new", "1" => "approved", "2" => "featured", "-1" => "declined" );
		
		$this->load->view('all_comp_list', $data);

		// Show pagination
		$this->load->library('pagination');
		$config['base_url'] = base_url() . '/admin/competition';
		$config['total_rows'] = $this->competition->get_count_all_comps();
		$config['per_page'] = '20'; // Pagination count
		$this->pagination->initialize($config);
		
		echo $this->pagination->create_links();
	}
	
		//we use in bibb restriction: no more than 5 photos from one user at competition
		function photo_cnt_restriction($user_id, $comp_id)
		{
			if ($user_id == 121)
				return 1;
			$this->load->model('competition_mdl','competition');
			$cnt = $this->competition->photo_cnt_per_user ($user_id, $comp_id);
			return $cnt;
			//if ($cnt->cnt <5 ) //user can upload only 5 photos
			//	return 1;
			//else
			//	return -1;
		}
		
		function get_admin_competition_photos($competition_id, $per_page = 0, $page = 1, $extras='')
		{
			$this->load->model('competition_mdl','competition');
			return $this->competition->get_admin_competition_photos($competition_id, $per_page, $page, $extras);
		}
		
		function get_competition_users($competition_id) {
			$this->load->model('competition_mdl','competition');
			return $this->competition->get_competition_users($competition_id);
		}
		
		function get_competition_name($competition_id)	
		{
			$this->load->model('competition_mdl','competition');
			return ($this->competition->get_competition_name($competition_id));
		}
		
		function update_comp($properties) {
			$this->load->model('competition_mdl','competition');
			return $this->competition->update_comp($properties);
		}
		
		function set_1_place($properties) {
			$this->load->model('competition_mdl','photo');
			return $this->competition->set_1_place($properties);
		}
	
	    function get_competition_estimate() {
	       $this->load->model('competition_mdl','competition');
	       $competition = $this->competition->get_competition_estimate();//get_competition_photos_new($competition_id, $per_page, $page, $with_count, $extras, $order_by, $group_by);
	       return  $competition;
	    }

}
?>