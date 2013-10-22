<?php if (!defined('BASEPATH')) exit('Нет доступа к скрипту');
/*
 * Libraries Rating
 *
 * @package		Rating
 * @subpackage	Libraries
 * @category	Rating
 * @author		Andrey Makarevich
*/
class Rating {
static $CI;
var $user_id;
var $day_see_cnt;     //----разрешенное кол-во просмотров в день в одного ip
var $remember_day_see;//----сколько дней хранить историю просмотров
var $bals_arr;        //----начисляемые балы за действия пользоталеля
var $actions_arr;     //----действия,которые учитываются для рейтинга
  function rating ()
  {
    $this->CI =& get_instance();
    $this->user_id=$this->CI->db_session->userdata('user_id');
    $this->user_id=$this->user_id==""?0:$this->user_id;
    $this->initRatingVars();
  }
  function initRatingVars()
  { $this->remember_day_see=1;
    $this->day_see_cnt=1;
    $this->balls_arr   =array("see_foto"=>$this->CI->config->item('see_foto'),
                              "comment_foto"=>$this->CI->config->item('comment_foto'),
                              "vote_foto"=>$this->CI->config->item('vote_foto'),
                              "bal_down"=>$this->CI->config->item('bal_down')
                              );
    $this->actions_arr =array("vote_foto"    =>array("id"=>1,"name"=>"Голосование"),
                              "comment_foto" =>array("id"=>2,"name"=>"Комментарий"),
                              "see_foto"     =>array("id"=>3,"name"=>"Просмотр фото")
                              );
  }
  function addAction($action_name,$on_what_id,$bal=1)
  {
   $this->user_id=$this->CI->db_session->userdata('user_id');
   $this->user_id=$this->user_id==""?0:$this->user_id;
   switch ($action_name) {
    case "vote":
      $this->addVoteRating($on_what_id,$bal);
    break;
    case "comment":
      $this->addCommentRating($on_what_id);
    break;
    case "see":
      $this->addSeeRating($on_what_id);
      $this->addSeeCnt($on_what_id);
      $this->calcTotalAlbumSeeCnt($this->get_album_id($on_what_id));
    break;
    }
    $this->calcTotalRating($on_what_id);
  }
  function addVoteRating($on_what_id,$vote_bal=1)
  {
   $bal       =($vote_bal==1)?$this->balls_arr["vote_foto"]:$vote_bal;
   $action_id =$this->actions_arr["vote_foto"]["id"];
   $query_str="INSERT INTO rating_actions (action_id,on_what_id,bal,user_id,date) VALUE ('".$action_id."',".$on_what_id.",".$bal.",".$this->user_id.",NOW())";
   $this->CI->db->query($query_str);
  }
  function addCommentRating($on_what_id)
  {
   $bal       =$this->balls_arr["comment_foto"];
   $action_id =$this->actions_arr["comment_foto"]["id"];
   $query_str="INSERT INTO rating_actions (action_id,on_what_id,bal,user_id,date) VALUE ('".$action_id."',".$on_what_id.",".$bal.",". $this->user_id.",NOW())";
   $this->CI->db->query($query_str);
  }
  function addSeeRating($on_what_id)
  {
   $bal       =$this->balls_arr["see_foto"];
   $action_id =$this->actions_arr["see_foto"]["id"];
   $ip=$this->CI->input->ip_address();
   if ($this->ratingActionExist($action_id,$on_what_id)) {
       $query_str="UPDATE rating_actions SET bal=bal+".$bal.",date=NOW(),ip='".$ip."' WHERE action_id=".$action_id." AND on_what_id=".$on_what_id." AND user_id=".$this->user_id;;
   } else {
       $query_str="INSERT INTO rating_actions (action_id,on_what_id,bal,user_id,date,ip) VALUE ('".$action_id."',".$on_what_id.",".$bal.",". $this->user_id.",NOW(),'".$ip."')";
   }
   $this->CI->db->query($query_str);
  }
  function addSeeCnt($on_what_id)
  {
    $on_what="foto";
    //---протоколировать просмотры------------
    $user_id=$this->user_id;
    $ip=$this->CI->input->ip_address();
    if ($this->seeAllow($user_id,$ip,$on_what_id)){
        $query_str="INSERT INTO photo_views (photo_id,user_id,view_date,ip) VALUE ('".$on_what_id."',".$user_id.",NOW(),'".$ip."')";
        $this->CI->db->query($query_str);
        //--контроль просмотра для рейтинга----
        $bal=1;
        $query_str="UPDATE rating_totals SET see_cnt=see_cnt+".$bal." WHERE on_what_id=".$on_what_id." AND on_what='".$on_what."'";
        $this->CI->db->query($query_str);
    }
  }
  function seeAllow($user_id,$ip,$on_what_id)
  {
   $day_see_cnt=$this->day_see_cnt;
   $query_str="SELECT user_id FROM photo_views WHERE photo_id=".$on_what_id." AND user_id=".$user_id." AND ip='".$ip."' AND view_date >= CURDATE()";
   $query=$this->CI->db->query($query_str);
   //var_dump($query->num_rows());
   if ($query->num_rows() < $day_see_cnt) {
         return TRUE;
    } else {
         return FALSE;
    }
  }
  function ratingActionExist($action_id,$on_what_id)
  {
   $query_str="SELECT action_id FROM rating_actions WHERE action_id=".$action_id." AND on_what_id=".$on_what_id." AND user_id=".$this->user_id;
   $query=$this->CI->db->query($query_str);
   if ($query->num_rows() > 0) {
         return TRUE;
    } else {
         return FALSE;
    }
  }
  function ratingExist($action_name,$on_what_id)
  {
   $query_str="SELECT balls FROM rating_totals WHERE on_what='".$action_name."' AND on_what_id=".$on_what_id;
   $query=$this->CI->db->query($query_str);
   if ($query->num_rows() > 0) {
         return TRUE;
    } else {
         return FALSE;
    }
  }
  function calcTotalRating($on_what_id)
  {
   $this->calcTotalFotoBalls($on_what_id);
   $this->calcTotalAlbumBalls($this->get_album_id($on_what_id));
   $this->calcTotalUserBalls();
  }
  function calcTotalUserBalls()
  {
  //---посчитать итоговый рейтинг для конкретного пользователя
  $on_what='user';
  if ($this->user_id <>0) {
     //$query_str="SELECT sum(bal) as balls FROM rating_actions WHERE user_id=".$this->user_id;
     $query_str="SELECT ph.photo_id,(sum(rt.balls)/count(ph.photo_id)) as balls FROM photos as ph,rating_totals as rt WHERE user_id=".$this->user_id." and ph.photo_id=rt.on_what_id and rt.on_what='foto' and ph.moderation_state >= 0";
     $query=$this->CI->db->query($query_str);
     if ($query->num_rows() > 0) {
        $sum_balls=intval($query->row()->balls);
        if ($this->ratingExist($on_what,$this->user_id )){
            $query_str="UPDATE rating_totals SET balls=".$sum_balls." WHERE on_what_id=".$this->user_id." AND on_what='".$on_what."'";
        }else {
            $query_str="INSERT INTO rating_totals (on_what,on_what_id,balls)  VALUE ('".$on_what."','".$this->user_id."','".$sum_balls."')";
        }
        $this->CI->db->query($query_str);
     }
  }
  }
  function calcTotalFotoBalls($on_what_id)
  {
  //---посчитать итоговый рейтинг для конкретного фото
  //--on_what_id=photo_id
  $on_what='foto';
  if ($this->ratingExist($on_what,$on_what_id )){
        $query_str="SELECT sum(bal) as balls FROM rating_actions WHERE on_what_id=".$on_what_id;
        $query    =$this->CI->db->query($query_str);
        if ($query->num_rows() > 0) {
              $sum_balls=$query->row()->balls;
        }
        $query_str="UPDATE rating_totals SET balls=".$sum_balls." WHERE on_what_id=".$on_what_id." AND on_what='".$on_what."'";
  }else {
        $sum_balls=1;
        $query_str="INSERT INTO rating_totals (on_what,on_what_id,balls)  VALUE ('".$on_what."','".$on_what_id."','".$sum_balls."')";
  }
        $this->CI->db->query($query_str);



  }
  function calcTotalAlbumBalls($on_what_id)
  {
  //---посчитать итоговый рейтинг для конкретного альбома
  //--on_what_id=album_id--s
  if ($on_what_id == 0) {return;}
  $on_what  ='album';
  $query_str="SELECT sum(bal) as balls FROM rating_actions WHERE on_what_id in (select photo_id from photo_album_map where album_id=".$on_what_id.")";
  $query=$this->CI->db->query($query_str);
  if ($query->num_rows() > 0) {
       $sum_balls=$query->row()->balls;
       if ($this->ratingExist($on_what,$on_what_id )){
            $query_str="UPDATE rating_totals SET balls=".$sum_balls." WHERE on_what_id=".$on_what_id." AND on_what='".$on_what."'";
       }else {
            $query_str="INSERT INTO rating_totals (on_what,on_what_id,balls)  VALUE ('".$on_what."','".$on_what_id."','".$sum_balls."')";
       }
       $this->CI->db->query($query_str);
  }
  }
  function calcTotalAlbumSeeCnt($on_what_id)
  {
  //--вычислить колличество просмотров альбомов как сумму колличества просмотров фото альбома
  //--on_what_id = album_id
  if ($on_what_id == 0) {return;}
    $on_what  ='album';
    $sum_see_cnt=1;
    $query_str="SELECT sum(see_cnt) as sum_see_cnt FROM rating_totals WHERE (on_what='foto') AND (on_what_id in (select photo_id from photo_album_map where album_id=".$on_what_id."))";
    $query=$this->CI->db->query($query_str);
    if ($query->num_rows() > 0) {
       $sum_see_cnt=$query->row()->sum_see_cnt;
       if (! empty($sum_see_cnt)) {
            $query_str="UPDATE rating_totals SET see_cnt=".$sum_see_cnt." WHERE on_what_id=".$on_what_id." AND on_what='".$on_what."'";
            $this->CI->db->query($query_str);
       }
    }

  }
  function getSeeCnt($on_what,$on_what_id,$period='total')
  {
    $see_cnt=1;
    if (empty($on_what_id)) {return 1;}
    if ($period == 'total'){
        $query_str="SELECT see_cnt FROM rating_totals WHERE on_what_id=".$on_what_id." AND on_what='".$on_what."'";
        $query=$this->CI->db->query($query_str);
        if ($query->num_rows() > 0) {
           $see_cnt=$query->row()->see_cnt;
        }
        return $see_cnt;
    }
    if (($period == 'today') and ($on_what == 'album')){
        //$query_str="SELECT sum(rt.see_cnt) FROM rating_totals as rt,rating_actions as ra WHERE (rt.on_what='foto') and (rt.on_what_id=ra.on_what_id) and (ra.date >= CURDATE()) and (rt.on_what_id in (select photo_id from photo_album_map where album_id=".$on_what_id."))";
        //------ $on_what_id=album_id
        $query_str="SELECT count(user_id) as see_cnt FROM photo_views WHERE (view_date >= CURDATE()) AND photo_id in (select photo_id from photo_album_map where album_id=".$on_what_id.")";
        $query=$this->CI->db->query($query_str);
        if ($query->num_rows() > 0) {
           $see_cnt=$query->row()->see_cnt;
        }
        return $see_cnt;
    }
  }
  function getBalls($on_what,$on_what_id)
  {
    $balls=0;
    if (empty($on_what_id)) {return 0;}
    $query_str="SELECT balls FROM rating_totals WHERE on_what_id=".$on_what_id." AND on_what='".$on_what."'";
    $query=$this->CI->db->query($query_str);
    if ($query->num_rows() > 0) {
       $balls=$query->row()->balls;
    }
    return $balls;
  }
  function getBallsHtml($on_what,$on_what_id)
  {
    $balls=0;
    if (empty($on_what_id)) {return "";} 
    $query_str="SELECT balls FROM rating_totals WHERE on_what_id=".$on_what_id." AND on_what='".$on_what."'";
    $query=$this->CI->db->query($query_str);
    if ($query->num_rows() > 0) {
       $balls=$query->row()->balls;
    }
    return "<span id='rating_balls_".$on_what_id."'>".$balls."</span>";
  }
  function clearSeeCnt()
  {
   $query_str="DELETE FROM photo_views";
   $query=$this->CI->db->query($query_str);
  }
  function crtDel() {
    $query_str="DELETE FROM rating_actions WHERE on_what_id in (SELECT photo_id FROM photos WHERE moderation_state=".MOD_DELETED.")";
    $query=$this->CI->db->query($query_str);
    $query_str="DELETE FROM rating_totals WHERE on_what='foto' AND on_what_id in (SELECT photo_id FROM photos WHERE moderation_state=".MOD_DELETED.")";
    $query=$this->CI->db->query($query_str);
  }
  function seeCntControl()
  {
   $query_str="SELECT (TO_DAYS(CURDATE()) - TO_DAYS(max(view_date)) ) as lastdays FROM photo_views";
   $query=$this->CI->db->query($query_str);
   if ($query->num_rows() > 0) {
       $days=$query->row()->lastdays;
       if ($days >= $this->remember_day_see) {
           $this->clearSeeCnt();
       }
   }
  }
  function ratingControl()
  {
  //--Опустить рейтин на (down_bal) балов  если нет просмотров
  //--Пересчитать рейтиг фото->альбом для опущенных
   $query_str="SELECT option_dt FROM rating_options WHERE (option_name='action_down') AND (option_dt >= NOW())";
   $query=$this->CI->db->query($query_str);
   if ($query->num_rows() > 0) {
       $needdown=TRUE;
   } else {
       $needdown=FALSE;
   }
   if ($needdown){
      //--понизить рейтинг------
      $bal_down=$this->balls_arr['bal_down'];
      $query_str="UPDATE rating_totals SET balls=balls-".$bal_down." WHERE balls >= 1";
      $query=$this->CI->db->query($query_str);
      //--запомнить дату корректировки--
      $query_str="UPDATE  rating_options SET option_dt=NOW() WHERE option_name='action_down'";
      $query=$this->CI->db->query($query_str);
   }
   $this->seeCntControl();
   $this->crtDel();
  }
  function get_album_id($on_what_id)
  {
  //--получить id альбома которому принадлежит фото за номером on_what_id
  $res=0;
  $query_str="SELECT album_id FROM photo_album_map WHERE photo_id=".$on_what_id;
  $query=$this->CI->db->query($query_str);
  if ($query->num_rows() > 0) {
       $res=$query->row()->album_id;
  }
  return $res;
  }
}