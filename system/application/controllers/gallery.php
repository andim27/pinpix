<?php
	/**
	 * Class Gallery
	 *
	 * class_definition
	 *
	 * @author   Popov
	 * @access   public
	 * @package  Gallery2.class.php
	 * @created  Tue Mar 09 11:30:30 EET 2010
	 */
	class Gallery extends Controller 
	{
		private $count_images_file = 100;
		private $current_page = 1;
		private $gallery_type = "all";
		
		/**
		 * Constructor of Gallery2
		 *
		 * @access  public
		 */
		function Gallery() {
			parent::Controller();
		}
		
		function index(){
			$this->view();
		}
		
		function view(){
			$path = dirname(BASEPATH);
			
			$data = array();			
			$data['path'] = $path;
			$data['gallery_type'] = $this->gallery_type;
			$data['paginate_args'] = "";			
						
			if($this->gallery_type != "voted") {
				
				$photos_all_count = 0;
				
				$data['index_page'] = ($this->current_page-1);

				//$competition = modules::run('competition_mod/competition_ctr/get_all_competitions_new');
                $competition = modules::run('competition_mod/competition_ctr/get_competition_estimate');

                if($competition) {
					if(is_array($competition)) $competition = $competition[0];

                    $competition->photos = modules::run('competition_mod/competition_ctr/get_competition_photos_new', $competition->competition_id, $this->count_images_file, $this->current_page, 1, true);
					$photos_all_count = $competition->photos['count'];
					unset($competition->photos['count']);
				}				
				$data['paginate_args'] = array(
					'total_rows' => $photos_all_count,
					'per_page' => $this->count_images_file,
					'num_links' => 10,
					'cur_page' => $this->current_page,
					'uri_segment' => 3,
					'base_url' => base_url().'gallery/page/'
				);
			} elseif($this->gallery_type == "voted" || $this->gallery_type == "novoted") {
				$data['index_page'] = "_".$this->gallery_type;
			}
            $data['competition']=$competition;
			$this->load->view('_gallery', $data);
		}
		
		function page(){
			$this->load->helper('url');			
			$page = $this->uri->segment(3);
			$page = intval($page);
			if($page > 0) $this->current_page = $page;
			$this->view();
		}
		
		function resize_photos($photos){
			if(empty($photos)) return false;
			
			$photo_uploads = dirname(BASEPATH)."/uploads/photos";
			$path = dirname(BASEPATH)."/static/files/imgs/";
			
			foreach ($photos as $index=>$photo) {				
				$orig_path = $photo_uploads."/".date("m", strtotime($photo->date_added))."/".$photo->photo_id."-md".$photo->extension;
				$new_path = $path.$photo->photo_id."-gal".$photo->extension;				
				
				if (copy($orig_path, $new_path)) {
					if($index > 0) {
						$config['source_image'] = $new_path;
						$this->image_lib->initialize($config); 
						
					} else {
					    $config['image_library'] = 'gd2';
						$config['source_image'] = $new_path;
						$config['create_thumb'] = TRUE;
						$config['maintain_ratio'] = false;
						$config['width'] = 80;
						$config['height'] = 80;
						$this->load->library('image_lib', $config);						
					}
					$this->image_lib->resize();

					if(!file_exists($path."big/big_".$photo->photo_id.$photo->extension)) rename($new_path, $path."big/big_".$photo->photo_id.$photo->extension);
					else unlink($new_path);
					if(!file_exists($path."small/prew_".$photo->photo_id.$photo->extension)) rename($path.$photo->photo_id."-gal_thumb".$photo->extension, $path."small/prew_".$photo->photo_id.$photo->extension);
					else unlink($path.$photo->photo_id."-gal_thumb".$photo->extension);
				}
			}
			return ;
		}
		
		function create_xml_file($photos, $index_file){
			if(empty($photos)) return false;
			
			$dom = new DOMDocument('1.0');
			$datas = $dom->createElement("datas");
			$dom->appendChild($datas);
								
			$datas_attr = array(
				"back_to_gallaries" => base_url()."gallery",
				"link_nravitco" => base_url()."gallery/photo_like/", 
				"link_for_coment" => base_url()."gallery/add_comment_photo",  
				"text_0_link_galary" => "", 
				"text_1_link_galary" => ""
			);
			
			foreach ($datas_attr as $fieldname => $fieldvalue) {
		        $node = $dom->createAttribute($fieldname);
		        $datas->appendChild($node);
		        $nodeValue = $dom->createTextNode($fieldvalue);
		        $node->appendChild($nodeValue);
		    }
		    
		    foreach ($photos as $index=>$photo) {
		    	
		    	$photo->big = base_url()."static/files/imgs/big/big_".$photo->photo_id.$photo->extension;
				$photo->prew = base_url()."static/files/imgs/small/prew_".$photo->photo_id.$photo->extension;
				$photo->original = photo_location().date('m', strtotime($photo->date_added)).'/'.$photo->photo_id.'-lg'.$photo->extension;
		    	
		    	$img = $dom->createElement("img");
		    	$datas->appendChild($img);
		    	
				$node = $dom->createAttribute("id");
				$img->appendChild($node);
				$nodeValue = $dom->createTextNode($photo->photo_id);
				$node->appendChild($nodeValue);
				
				$node = $dom->createAttribute("prew");
		        $img->appendChild($node);
		        $nodeValue = $dom->createTextNode($photo->prew);
		        $node->appendChild($nodeValue);
		        
		        $node = $dom->createAttribute("big");
		        $img->appendChild($node);
		        $nodeValue = $dom->createTextNode($photo->big);
		        $node->appendChild($nodeValue);
		        
		        $title = $photo->title;
		        if(mb_strlen($title, 'utf-8') > 50) $title = mb_substr($title, 0, 50, 'utf-8')."...";
		        
		        $node = $dom->createAttribute("title");
		        $img->appendChild($node);
		        $nodeValue = $dom->createTextNode($title);
		        $node->appendChild($nodeValue);
		        
		        $node = $dom->createAttribute("autor");
		        $img->appendChild($node);
		        $nodeValue = $dom->createTextNode($photo->login);
		        $node->appendChild($nodeValue);
		        
		        $node = $dom->createAttribute("link");
		        $img->appendChild($node);
		        $nodeValue = $dom->createTextNode($photo->original);
		        $node->appendChild($nodeValue);
		        
		        $node = $dom->createAttribute("coments");
		        $img->appendChild($node);
		        $nodeValue = $dom->createTextNode($photo->comcnt);
		        $node->appendChild($nodeValue);		       
		    }
		    
		    $xml_data = $dom->saveXML();
		    
			$path = dirname(BASEPATH)."/static/";
		    
        	$file = $path.'data'.$index_file.'.xml';
        	if(is_file($file)) unlink($file);
        	
    		$fp = fopen($file, 'w');
			fwrite($fp, $xml_data);
			fclose($fp);
			
			return true;
		}
	
		function update(){
			$competition = modules::run('competition_mod/competition_ctr/get_all_competitions_new');
			if($competition) {
				if(is_array($competition)) $competition = $competition[0];
				
				$photos_all = modules::run('competition_mod/competition_ctr/get_competition_photos_new', $competition->competition_id);
				
				$this->resize_photos($photos_all);
				
				$new_photo_arrays = array_chunk($photos_all, $this->count_images_file);
				foreach ($new_photo_arrays as $index=>&$photos_array) {
					$this->create_xml_file($photos_array, $index);
				}
			}
			redirect(base_url()."gallery");
		}
		
		function photo_like(){
			$result = modules::run('gallery_mod/gallery_ctr/photo_like', $this->input->post('id'));
			if($result) echo  '<datas><answer>1</answer></datas>';
			else echo  '<datas><answer>0</answer></datas>';
			exit;
		}
		
		function gallery_type(){
			$this->load->helper('url');			
			$gallery_type = $this->uri->segment(3);
			
			if(!empty($gallery_type)) {
				
				$competition = modules::run('competition_mod/competition_ctr/get_all_competitions_new');						
				if($competition) {
					if(is_array($competition)) $competition = $competition[0];
					
					$extras = "";
					switch ($gallery_type) {
						case "all":
							$this->view();
						break;
						
						case "voted":
							$extras = " AND p.score > 0 ";
							$this->gallery_type = "voted";
						break;
						
						case "novoted":
							$extras = " AND p.score = 0 ";
							$this->gallery_type = "novoted";
						break;
					}
					
					$competition->photos = modules::run('competition_mod/competition_ctr/get_competition_photos_new', $competition->competition_id, 0, 1, 1, true, 'ASC', $extras);
					$photos_all_count = $competition->photos['count'];
					unset($competition->photos['count']);
					if($photos_all_count > 0)
						$this->create_xml_file($competition->photos, "_".$this->gallery_type);
					else
						$this->gallery_type = "all";
					$this->view();
				}
			}
		}
		
		function add_comment_photo(){
			$res = modules::run('comments_mod/comments_ctr/add_comment_photo_gallery', $this->input->post('id'), $this->input->post('comment'));
			if($res) echo  '<datas><answer>1</answer></datas>';
			else echo  '<datas><answer>0</answer></datas>';
			exit;
		}

		function ajax_actions(){
			$action = $this->input->post('action');
			
			$data = '';
			switch ($action) {
				case "check_jury":
					$user_jury = modules::run('users_mod/users_ctr/get_jury', $this->db_session->userdata('user_id'));
					if($user_jury && !empty($user_jury)) $data = 1;
					else $data = 0;
					break;			
			}
			$this->output->set_output($data); 
		}
		
		/**
		 * Destructor of Gallery2 
		 *
		 * @access  public
		 */
		function _Gallery() {}
	}
?>