<?php
 class Residence_mdl extends Model {
 /* Местонахождение пользователя
    Страна,Регион,Город
  */
  	function Residence_mdl()
    {
        parent::Model();
    }
    
    function get_regions($country_id)
    {
		$query = "SELECT r.id,r.region
					FROM region r,country_region cr
                	WHERE (r.id=cr.id_region) and (cr.id_country='$country_id')
					ORDER BY r.region ASC";
		$query = $this->db->query($query);
		if ($query->num_rows() == 0) return FALSE; 
		return $query->result();	 
    }
    
 	function get_cities($region_id)
    {
		$query = "SELECT c.id,c.city
					FROM city c,region_city rc
                	WHERE (rc.id_region='$region_id') and (c.id=rc.id_city)
					ORDER BY c.city ASC";
		$query = $this->db->query($query);
		if ($query->num_rows() == 0) return FALSE; 
		return $query->result();	 
    }
    
 	function get_countries()
    {
		$query = 'SELECT * FROM country c ORDER BY c.country ASC';
   		$query = $this->db->query($query);
		if ($query->num_rows() == 0) return FALSE;
		return $query->result();
    }
    
    function get_residence_user($user_id)
    {
    	$query = 'SELECT c.country,r.region,ct.city FROM country c,region r,city ct,users u WHERE (u.user_id="$user_id") and (u.country_id=c.id) and (u.region_id=r.id) and (u.city_id=ct.id)';
   		$query = $this->db->query($query);
		if ($query->num_rows() == 0) return FALSE;
		return $query->result();
    }
	
	function get_country_cities($country_id)
	{
		$query = 'select city.* from city inner join region_city on city.id  =  id_city 
					inner join country_region on region_city.id_region = country_region.id_region
					where id_country = '.$country_id." order by city.city ";
   		$query = $this->db->query($query);
		if ($query->num_rows() == 0) return FALSE;
		return $query->result();
	}
    function save_city($city_id,$city_name)
    {
       $query = "UPDATE city SET city ='".$city_name."' WHERE id=".$city_id;
       $query = $this->db->query($query);
       return 1;
       if ($query->num_rows() <= 0) {
          return 0;
       } else {
          return 1;
       }
    }
    function add_city($city_id,$city_name,$region_id)
    {
       $query = "INSERT INTO city (city) VALUES (".clean($city_name).")";
       //pr($query);
       $query = $this->db->query($query);
       $city_id = $this->db->insert_id();
       $query = "INSERT INTO region_city (id_region,id_city) VALUES (".clean($region_id).",".clean($city_id).")";
       //pr("\n>>".$query);
       $query = $this->db->query($query);
       return 1;
    }
    function del_city($city_id)
    {
       $query = "DELETE FROM city WHERE id = ".clean($city_id)."";
       //pr($query);
       $query = $this->db->query($query);
       return 1;
    }
    //------------------------ region ----------------------------
    function save_region($region_id,$region_name)
    {
       $query = "UPDATE region SET region ='".$region_name."' WHERE id=".$region_id;
       $query = $this->db->query($query);
       return 1;
       if ($query->num_rows() <= 0) {
          return 0;
       } else {
          return 1;
       }
    }
    function add_region($region_id,$region_name,$country_id)
    {
       $query = "INSERT INTO region (region) VALUES (".clean($region_name).")";
       $query = $this->db->query($query);
       $region_id = $this->db->insert_id();
       $query = "INSERT INTO country_region (id_country,id_region) VALUES (".clean($country_id).",".clean($region_id).")";
       $query = $this->db->query($query);
       return 1;
    }
    function del_region($region_id)
    {
       $query = "DELETE FROM region WHERE id = ".clean($region_id)."";
       $query = $this->db->query($query);
       $query = "DELETE FROM region_city WHERE id_region = ".clean($region_id)."";
       $query = $this->db->query($query);
       return 1;
    }
}

/* end of file */
