<?php
	/**
	 * Class Search
	 *
	 * search class
	 *
	 * @access   public
	 * @package  Search.class.php
	 * @created  Wed Mar 18 10:40:57 EET 2009
	 */
	class Search extends Controller {
		
		var $info = null;
		
		var $photo = null;
		
		/**
		 * Constructor of Search
		 *
		 * @access  public
		 */
		function __construct() {
			parent::Controller();
			$this->photo = true;

			$lng = $this->db_session->userdata('user_lang');
         
         	$this->lang->load('phh',$lng);		
		}
		
		function index(){
			$this->searching();
		}

		function searching(){
			$keywords = null;
			$categories = null;
			$data = null;
			
			$this->config->load('ads');
			$cfg = $this->config->item('search');
			$data['ads_block'] = modules::run('ads_mod/ads_ctr/get_ads_content', $cfg);
		
			$data['allcategories'] = modules::run('catalog_mod/catalog_ctr/get_categories_list');
		
            if($this->db_session->userdata('keywords') == TRUE) {
				$keywords = $this->db_session->userdata('keywords');
				$this->photo = true;
			}
			if($this->db_session->flashdata('categories') == TRUE) {
				$categories = $this->db_session->userdata('categories');
				//$this->db_session->keep_flashdata('keywords');
			}

			if(isset($_POST)) $this->info = $_POST;
			if(!empty($this->info)){
				if(isset($this->info['keyword'])) {
					$keywords = $this->info['keyword'];
					$this->db_session->set_userdata('keywords', $keywords);
				}
				if(isset($this->info['categories'])) {
					$categories = $this->info['categories'];
					$this->db_session->set_userdata('categories', $categories);
				}
			}
			$data['keywords'] = $keywords;
			if (empty ($keywords ))
			{
					$this->load->view('_search', $data);
					return 0;
			}
			$per_page = 32;
            $sort_type = null;
            $sort_order = null;
            if(empty($page) || !intval($page)) $page = 1;

            if ($this->uri->segment(3)=='page')
                $cpage = $this->uri->segment(4);
            else
                {
                $sort_type = $this->uri->segment(3);

                if ($this->uri->segment(4)=='page')
                    $cpage = $this->uri->segment(5);
                else
                    {
                    $sort_order = $this->uri->segment(4);
                    $cpage = $this->uri->segment(6);
                    }
                }

            $data['sort_order']=$sort_order;
            $data['sort_type']=$sort_type;

            //permissions
            $moderation_state = MODERATION_STATE;  //=1 - const: system\application\config\constants.php       
            $registered = $this->db_session->userdata('user_id');

            if (empty($registered)) $registered = 0;

            if  ($registered == 0)
                $erotic = -1;
            else  {
                $erotic = $this->db_session->userdata('erotic_allow');
                if (!$erotic)
                  $erotic = modules::run('users_mod/users_ctr/get_age', $registered);
            }

            //end permissions
			//$id_array =  Array (173, 174, 175);
			//$result = modules::run('gallery_mod/gallery_ctr/regulate_search_photos',  $id_array, $categories, $per_page, $cpage, $sort_type, $sort_order, $moderation_state, $registered, $erotic);      

            $this->load->library('sphinx');
			
			$sphserver = $this->config->item('sphinx.connection');
            if($sphserver) 
            {
                $this->sphinx->SetServer($sphserver[0], $sphserver[1]);
            }
                        	
            $this->sphinx->SetArrayResult(TRUE);
            
            $sphinx_result = $this->sphinx->Query($keywords, 'photos');

		 	if(isset($sphinx_result['matches'])) 
            {
          	 	$i=0;
             	foreach ($sphinx_result['matches'] as $id => $row) 
             	{
                    $id_array[$i] = $row['id'];
                    $i++;
                }
            	$result = modules::run('gallery_mod/gallery_ctr/regulate_search_photos',  $id_array, $categories, $per_page, $cpage, $sort_type, $sort_order, $moderation_state, $registered, $erotic);      
            }
            else
            {
            	$this->load->view('_search', $data);
            	return 0; 
            } 
                    
	        if(isset($result) && !empty($result))
            {
    			$data['search_result'] = $result['search'];
    			$data['paginate_args'] = array(
    				'total_rows' =>  count($result['all']),
    				'per_page' => $per_page);
            }
			
			$user_id = $this->db_session->userdata('user_id');
			$data['user_id'] = $user_id;
			$this->load->view('_search', $data);
		}
		
		//test function to see just work of sphinks
		function dosearch($keyword) {
			
            if ($this->load->library('sphinx'))
            	echo "<b>Sphinx library loaded: </b><br /><PRE>";
            else
            	echo "<b>couldnt load library </b><br /><PRE>";
                       
            $sphserver = $this->config->item('sphinx.connection');
            if($sphserver) 
            {
                $t = $this->sphinx->SetServer($sphserver[0], $sphserver[1]);
            }
            
            $this->sphinx->SetArrayResult(TRUE);
            $result = $this->sphinx->Query($keyword, 'photo');
			
			
           // $keyword = $this->input->post('keyword');
            $this->sphinx->SetArrayResult(TRUE);
            
           
            echo "<b>Keyword: $keyword </b><br /><br />";
            $result = $this->sphinx->Query($keyword);
             
             
            echo "<b>Sphinx result struct: </b><br /><PRE>";
            print_r($result); echo "</PRE>"; //debug result view
            echo "<b>Sphinx matches array: </b><br /><br />"; //debug result view
            
            echo "<PRE>";
            
            if(isset($result['matches'])) 
            {
                foreach ($result['matches'] as $id => $row) {
                    $this->db->or_where('photo_id', $row['id']);
                }
            
                $data['result'] = $this->db->get('photos')->result();
                
                print_r($data['result']); echo "<br />"; //debug result view
            }
            else 
            {
                echo "<i>No mathes</i>";
            }
            
            echo "</PRE>";
           
        }
		/**
		 * Destructor of Search
		 *
		 * @access  public
		 */
	
		function __destruct() {

		}
		
	}
?>