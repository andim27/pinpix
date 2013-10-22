<?php if (!defined('BASEPATH')) exit('Нет доступа к скрипту');
/*
 * Voting Class
 * 
 * @package		Voting
 * @subpackage	Libraries
 * @category	Rating
 * @author		Andrey Makarevich
*/

class Voting {
static $CI;
var $is_block_title; //--показывать заголовок
var $is_avg_title;   //--показать заголовок средних
var $block_title;    //--название заголовка
var $avg_title;      //--название заголовка средних значений
var $btn_title;      //--название кнопки голосования
var $mes_done;       //--возвратное сообщение 1
var $mes_retry;      //--возвратное сообщение 2
var $mes_own;        //--возвратное сообщение для голосования своего фото
var $mes_place_done; //--про место
var $vote_url;       //--вызов линка при нажатии кнопки голосования
var $vote_rules;     //--правила голосования
var $vote_cur;       //--текущий голос пользователя
var $user_id;
    function Voting ()
    {
      // настройка по  $params
	  $this->CI =& get_instance();
      $this->user_id=$this->CI->db_session->userdata('user_id');
      $this->user_id=empty($this->user_id)?0:$this->user_id;
      $this->init_block_words();
      $this->init_control_vars();
      $this->init_vote_rules();
    }
    function init_control_vars()
    {
      $this->is_block_title=TRUE;
      $this->is_avg_title  =TRUE;
      $this->vote_url ="javascript:ajax_save_vote('voting_form_id','div_vote_message_id');";
      $this->vote_cur =0;
    }
    function init_block_words()
    {
      $this->avg_title  ="";//lang('avg_title');
      $this->block_title="";//lang('voting');//"Голосование";
      $this->btn_title  =lang('vote_btn_msg');//"Голосовать!";
      $this->mes_done   =lang('remember_vote_msg');//"Ваш голос защитан!";
      $this->mes_retry  =lang('repeat_vote_msg');//"Повторное голосование запрещено!";
      $this->mes_own    =lang('own_vote_msg');  //Голосование за свое фото
      $this->mes_place_done   =lang('done_place_msg');//"Ваш голос защитан!";

    }
    function init_vote_rules()
    {
     $this->vote_rules=array("many_from_ip" =>FALSE,
                             "vote_day_from_ip" =>1,
                             "time_min_from_ip" =>1);
    }
    function setVoteAction($on_what_id)
    {
     if (empty($on_what_id)) {return "";}
     $form_id_str        = "voting_form_id".$on_what_id;
     $vote_message_id_str="div_vote_message_id".$on_what_id;
     $vote_action        = "javascript:ajax_vote_action('".$form_id_str."','".$vote_message_id_str."',".$on_what_id.");";
     $out_str="";
     $out_str.='<form id="'.$form_id_str.'" name="voting_form"  method="post">  ';
     $out_str.='<input type="hidden" name="action" value="voting"/>';
     $out_str.='<input type="hidden" name="user_id" id="cur_user_id" value="'.$this->user_id.'"/>';
     $out_str.='<input type="hidden" name="votes[]" id=vote_n value="1"/>';
     $out_str.='<input type="hidden" name="on_what_id" value="'.$on_what_id.'"/>';
     $out_str.='</form>';

     $out_str.='<script type="text/javascript">';
     $out_str.='$(document).ready(function() { ';
     $out_str.='$("#vote_action'.$on_what_id.'").attr("href","'.$vote_action .'")';
     $out_str.='});';
     $out_str.='</script>';
     return  $out_str;
    }
    function get_view_html2($on_what_id)
    {
     if (empty($this->user_id)) {return "";}
     $out_str="";
     $out_str.='<div id="voting">';
     if  ($this->is_block_title ==FALSE) {
         $this->block_title="";
         //$out_str.='<span style="display:inline">'.$this->block_title.'</span>';
     }
     $form_id_str        ="voting_form_id".$on_what_id;
     $vote_message_id_str="div_vote_message_id".$on_what_id;
     $vote_url     ="javascript:ajax_vote_action('".$form_id_str."','".$vote_message_id_str."',this.id);";
     $v_src=base_url()."images/";

     $out_str.='<table width="385px" cellspacing="0" cellpadding="0" border="0">';
     $out_str.='<tr style="height: 10px ! important">';
     $out_str.='<td  width="10"><span class="reyt_txt">'.$this->block_title.'&nbsp;</span></td>';
     $out_str.='<td  width="75"><img alt="" src="'.$v_src.'v_1.jpg" title="'.lang('vote_btn_msg').' (+1)" width="100%" height="100%" id="1" onclick="'.$vote_url.'" /></td>';
     $out_str.='<td  width="75"><img alt="" src="'.$v_src.'v_2.jpg" title="'.lang('vote_btn_msg').' (+2)" width="100%" height="100%" id="2" onclick="'.$vote_url.'" /></td>';
     $out_str.='<td  width="75"><img alt="" src="'.$v_src.'v_3.jpg" title="'.lang('vote_btn_msg').' (+3)" width="100%" height="100%" id="3" onclick="'.$vote_url.'" /></td>';
     $out_str.='<td  width="75"><img alt="" src="'.$v_src.'v_4.jpg" title="'.lang('vote_btn_msg').' (+4)" width="100%" height="100%" id="4" onclick="'.$vote_url.'" /></td>';
     $out_str.='<td  width="75"><img alt="" src="'.$v_src.'v_5.jpg" title="'.lang('vote_btn_msg').' (+5)" width="100%" height="100%" id="5" onclick="'.$vote_url.'" /></td>';
     $out_str.='</tr>';
     $out_str.='<tr>';
     $out_str.='<td class="pos_reyt_txt" height="20" align="right"><span class="reyt_txt">0&nbsp;</span></td>';
     $out_str.='<td class="pos_reyt_txt" height="20" align="right"><span class="reyt_txt">1</span></td>';
     $out_str.='<td class="pos_reyt_txt" height="20" align="right"><span class="reyt_txt">2</span></td>';
     $out_str.='<td class="pos_reyt_txt" height="20" align="right"><span class="reyt_txt">3</span></td>';
     $out_str.='<td class="pos_reyt_txt" height="20" align="right"><span class="reyt_txt">4</span></td>';
     $out_str.='<td class="pos_reyt_txt" height="20" align="right"><span class="reyt_txt">5</span></td>';
     $out_str.='</tr>';
     $out_str.='</table>';
     $out_str.='<form id="'.$form_id_str.'" name="voting_form"  method="post">  ';
     $out_str.='<input type="hidden" name="action" value="voting"/>';
     $out_str.='<input type="hidden" name="votes[]" id=vote_n value="1"/>';
     $out_str.='<input type="hidden" name="on_what_id" value="'.$on_what_id.'"/>';
     $out_str.='</form>';
     $out_str.='<div class="ok_mes" id="'.$vote_message_id_str.'" style="display:none">';
     $out_str.='</div>';
     return $out_str;
    }
   function get_view_html($on_what_id)
    {
     $out_str="";
     $out_str.='<div id="voting">';
     if  ($this->is_block_title ==TRUE) {
         $out_str.='<span>'.$this->block_title.'</span>';
     }
     $form_id_str        ="voting_form_id".$on_what_id;
     $vote_message_id_str="div_vote_message_id".$on_what_id;
     $this->vote_url     ="javascript:ajax_save_vote('".$form_id_str."','".$vote_message_id_str."');";
     $out_str.='<form id="'.$form_id_str.'" name="voting_form"  method="post">  ';
     $out_str.='<input type="hidden" name="action" value="voting"/>';
     $out_str.='<input type="hidden" name="on_what_id" value="'.$on_what_id.'"/>';
     $out_str.='<input type="radio" name="votes[]" value="1"  checked="checked" />(+1)';
     $out_str.='<input type="radio" name="votes[]" value="2"  />(+2)';
     $out_str.='<input type="radio" name="votes[]" value="3"  />(+3)';
     $out_str.='<input type="radio" name="votes[]" value="4"  />(+4)';
     $out_str.='<input type="radio" name="votes[]" value="5"  />(+5)';
     $out_str.='<input type="button" value="'.$this->btn_title.'" title="'.$this->btn_title.'" onclick="'.$this->vote_url.'">';
     $out_str.='</form>';
     $out_str.='<div id="'.$vote_message_id_str.'" style="display:none">';
     $out_str.='</div>';
     return $out_str;
    }
    function get_avg_html($on_what_id)
    {

     if  ($this->is_avg_title ==FALSE) {
         $this->avg_title="";
      }
      $v_src=base_url()."images/";
      $avg_bal=$this->get_avg_bal($on_what_id);
      if ($avg_bal==0){return "";}
      $avg_int=intval($avg_bal);
      $avg_ost=$avg_bal-$avg_int;
      $avg_bal_str=strval($avg_bal);
      $out_str="";
      $out_str.='<div id="voting_avg">';
      $out_str.='<table  width="385px" cellspacing="0" cellpadding="0" border="0">';
      $out_str.='<tr style="height: 10px ! important">';
      $out_str.='<td><span class="reyt_txt">'.$this->avg_title.'&nbsp;</span></td>';
      for ($i=1; $i<=$avg_int; $i++)  {
        //$out_str.='<td><img alt="" src="'.$v_src.'v_'.$avg_int.'.jpg" title="'.lang('avg_title').' = '.$avg_bal.'" width="100%" height="100%" id="1"  /></td>';
      }
      $out_str.='<td bgcolor="#FFFFFF" align="left"><img src="'.$v_src.'v_'.$this->cellN(1,$avg_bal).'.jpg" title="'.lang('avg_title').' = '.$avg_bal.'" width="'.$this->fractN(1,$avg_bal,"w").'" height="16px" id="1"  /></td>';
      $out_str.='<td bgcolor="#FFFFFF" align="left"><img src="'.$v_src.'v_'.$this->cellN(2,$avg_bal).'.jpg" title="'.lang('avg_title').' = '.$avg_bal.'" width="'.$this->fractN(2,$avg_bal,"w").'" height="16px" id="2"  /></td>';
      $out_str.='<td bgcolor="#FFFFFF" align="left"><img src="'.$v_src.'v_'.$this->cellN(3,$avg_bal).'.jpg" title="'.lang('avg_title').' = '.$avg_bal.'" width="'.$this->fractN(3,$avg_bal,"w").'" height="16px" id="3"  /></td>';
      $out_str.='<td bgcolor="#FFFFFF" align="left"><img src="'.$v_src.'v_'.$this->cellN(4,$avg_bal).'.jpg" title="'.lang('avg_title').' = '.$avg_bal.'" width="'.$this->fractN(4,$avg_bal,"w").'" height="16px" id="4"  /></td>';
      $out_str.='<td bgcolor="#FFFFFF" align="left"><img src="'.$v_src.'v_'.$this->cellN(5,$avg_bal).'.jpg" title="'.lang('avg_title').' = '.$avg_bal.'" width="'.$this->fractN(5,$avg_bal,"w").'" height="16px" id="5"  /></td>';
      $out_str.='</tr>';
      $out_str.='<tr>';
      $out_str.='<td class="pos_reyt_txt" align="right"><span class="reyt_txt">0&nbsp;</span></td>';
      $out_str.='<td class="pos_reyt_txt" align="right"><span class="reyt_txt">1</span></td>';
      $out_str.='<td class="pos_reyt_txt" align="right"><span class="reyt_txt">2</span></td>';
      $out_str.='<td class="pos_reyt_txt" align="right"><span class="reyt_txt">3</span></td>';
      $out_str.='<td class="pos_reyt_txt" align="right"><span class="reyt_txt">4</span></td>';
      $out_str.='<td class="pos_reyt_txt" align="right"><span class="reyt_txt">5</span></td>';
      $out_str.='</tr>';
      $out_str.='</table>';
      $out_str.='</div>';
      return $out_str;
    }
    function cellN($n,$n_int)
    {
        $str="";
        if ($n > $n_int) {
            $str="0";
            $str=$this->fractN($n,$n_int);
        }else {
            $str=strval($n);
        }
        return $str;
    }
    function fractN($n,$n_int,$tp="")
    {
      $frt=$n-$n_int;
      if ($tp=="") {
         if (($frt>0)and ($frt<1)) {
           return strval($n);
         } else {return "0";}
      }
      //-----------------
      if ($tp=="w") {

        $str="100";
        if (($frt>0)and($frt<1)) {
          $str=strval(intval((1-$frt)*100));
          //mylogwrite("\nfractN=".$frt."str=".$str." n=".$n." n_int=".$n_int);
        }
        return $str."%";
      }
    }
    function show_view()
    {
      //Показать блок голосования
      ?>
      <div id="voting">
      <?php if  ($this->is_block_title ==TRUE) : ?>
      <h4> <?php echo $this->block_title; ?>  </h4>
      <?php endif; ?>
      <form name="voting_form" id="voting_form">
      <input type="hidden" name="action" value="voting"/>
      <input type="radio" name="votes[]" value="1"  />(+1)
      <input type="radio" name="votes[]" value="2"  />(+2)
      <input type="radio" name="votes[]" value="3"  />(+3)
      <input type="radio" name="votes[]" value="4"  />(+4)
      <input type="radio" name="votes[]" value="5"  />(+5)
      <input type="button" value="Send" title="<?php echo $this->btn_title; ?>" onclick="<?php echo  $this->vote_url; ?>">
      </form>
      <div id="div_vote_message_id" style="display:none">
      </div>
      </div>
      <?php
    }
    function getVotes($on_what,$on_what_id)
    {
        $cnt=0;
        if (empty($on_what_id)) {return 0;}
        $query_str="SELECT num_votes FROM votes WHERE on_what_id=".$on_what_id." AND on_what='".$on_what."'";
        $query=$this->CI->db->query($query_str);
        if ($query->num_rows() > 0) {
            $cnt=$query->row()->num_votes;
        }
        return $cnt;
    }
    function getUserVotes($photo_id,$user_id)
    {
      if (empty ($user_id)) {return 1;}
      $res=0;
      $query_str="SELECT sum(vote) as vote FROM user_votes WHERE user_id=".$user_id."  AND on_what_id=".$photo_id." AND on_what='foto'";
      $query=$this->CI->db->query($query_str);
      if ($query->num_rows() > 0) {
            $res=$query->row()->vote;
      }
      return $res;
    }
    function get_avg_bal($on_what_id)
    {
        $avg_vote=0;
        $query_str="SELECT average_vote FROM votes WHERE on_what_id=".$on_what_id;
        $query= $this->CI->db->query($query_str);
        if ($query->num_rows() > 0)
        {
            $row      = $query->row();
            $avg_vote = $row->average_vote;
        }
        return $avg_vote;
    }
    function get_own_id($on_what_id)
    {
     	$query_str="SELECT user_id FROM photos WHERE (photo_id=".$on_what_id.")";
        $query=$this->CI->db->query($query_str);
        if ($query->num_rows() > 0)
        {
           $row =$query->row();
           return  $row->user_id;
        } else {
           return 0;
        }
    }
    function check_place_rules($on_what_id,$user_ip)
    {
      	$res=TRUE;
        $foto_place=intval($this->CI->input->post("jury_place"));
        $query_str="SELECT on_what_id,user_id,vote FROM user_votes WHERE (on_what='place') and (on_what_id=".$on_what_id.") AND (ip='".$user_ip."')";
		$query= $this->CI->db->query($query_str);
        foreach ($query->result() as $row)
		{
			if (($row->user_id ==$this->user_id) or (($foto_place == $row->vote) and ($row->user_id ==$this->user_id))) {
				$res=FALSE;
				break;
			}
		}
        return $res;
    }
    function save_place($on_what,$on_what_id)
    {
       $res=array();
       $res[0] =1;
       $res[1] =$this->mes_place_done;
       $user_ip=$this->CI->input->ip_address();
       $vote_date=date("Y-m-d H:i:s");//date("m.d.y");
       $foto_place=$this->CI->input->post("jury_place");
       $user_id= $this->user_id;
       if (empty($foto_place)){$foto_place=-1;}
       if ($this->check_place_rules($on_what_id,$user_ip ) ) {
           $query_str="INSERT INTO user_votes (on_what,on_what_id,user_id,vote,vote_date,ip) VALUE ('".$on_what."',".$on_what_id.",".$user_id.",".$foto_place.",NOW(),'".$user_ip."')";

           $q=$this->CI->db->query($query_str);
       } else {
           $res[0] =0;
           $res[1] =$this->mes_retry;
       }

       return $res;
    }
    function save_vote($on_what,$on_what_id)
    {
      //сохранить голос пользователя
      if ($this->CI->input->post("action")=="voting")
        {
         $res=array();
         $res[0] =TRUE;
         $res[1] =$this->mes_done;
         $votes_arr=$this->CI->input->post("votes");
         //B:получить голос
         $vote=$votes_arr[0];
         $this->vote_cur=$vote;
         $vote_date=date("Y-m-d H:i:s");//date("m.d.y");
         $user_id=empty($this->user_id)?0:$this->user_id;
         $own_id=$this->get_own_id($on_what_id);
         //pr("\nVoting =".$user_id." on_what_id=".$on_what_id." own_id=".$own_id);
         if ($own_id == $user_id) {
            $res[0] =FALSE;
            $res[1]=$this->mes_own;
            return $res;
         }
         $user_ip=$this->CI->input->ip_address();
         if ($this->check_vote_rules($on_what_id,$user_ip ) ) {
              $query_str="INSERT INTO user_votes (on_what,on_what_id,user_id,vote,vote_date,ip) VALUE ('".$on_what."',".$on_what_id.",".$user_id.",".$vote.",NOW(),'".$user_ip."')";
              $this->CI->db->query($query_str);

            $this->calc_votes_total($on_what,$on_what_id);
            $res[1] =$this->mes_done;
         } else {
            $res[0] =FALSE;
            $res[1]=$this->mes_retry;
         }
        return $res;
         //E:получить голос
        }
    } //--end: save_vote
    function check_vote_rules($on_what_id,$user_ip)
    {
		$res=TRUE;
		if ( $this->vote_rules['many_from_ip']) { //---разрешено повторное голосование ?
			return $res;
		}
		//---проверить повторное голосование с одного ip за одно фото
		$vote_cnt=$this->vote_rules["vote_day_from_ip"]-1;
		//$query = $this->CI->db->get_where('user_votes', array('on_what_id' => $on_what_id,'ip' =>$user_ip));
		$query_str="SELECT user_id FROM user_votes WHERE (on_what_id=".$on_what_id.") AND (ip='".$user_ip."')";
		$query= $this->CI->db->query($query_str);
		foreach ($query->result() as $row)
		{
			if ($row->user_id ==$this->user_id) {
				$res=FALSE;
				break;
			}
		}
		return $res;
    }
	function calc_votes_total($on_what,$on_what_id)
	{
	//---подсчитать итоги по голосам
		$query = $this->CI->db->query("SELECT count(*) as votes_cnt,avg(vote) as votes_avg FROM user_votes WHERE on_what_id=".$on_what_id." and on_what='".$on_what."'");
		if ($query->num_rows() > 0)
		{
			$row        = $query->row();
			$votes_cnt  = $row->votes_cnt;
			$votes_avg  = $row->votes_avg;
		}
		//--Занести итоговые результаты в таблицу votes
		$query = $this->CI->db->query("SELECT on_what_id FROM votes WHERE on_what_id=".$on_what_id);
		if ($query->num_rows() > 0)  //--данные уже есть
		{
			$query_str="UPDATE votes SET num_votes=".$votes_cnt.",average_vote=".$votes_avg." WHERE on_what_id=".$on_what_id." and on_what='".$on_what."'";
		} else { //--первый раз голосуем
			$query_str="INSERT INTO votes (on_what,on_what_id,num_votes,average_vote) VALUE ('".$on_what."',".$on_what_id.",1,".$this->vote_cur.")";
		}
		$this->CI->db->query($query_str);
	}
    function get_places_list()
    {
//     	$query_str="SELECT  distinct v.vote,v.on_what_id as photo_id,u.user_id,u.login,ph.title,ph.date_added,ph.extension FROM user_votes as v,users as u,photos as ph,comments_tree as cm  WHERE (v.on_what = 'place') and (v.user_id=u.user_id) and (v.on_what_id=ph.photo_id) and (v.user_id=cm.user_id)  order by v.vote";
		$query_str = "select 
			distinct uv.vote,
			uv.on_what_id as photo_id,
			u.user_id,
			u.login,
			p.title,
			p.date_added,
			p.extension
		from	
			user_votes uv
		inner JOIN users u on u.user_id=uv.user_id
		inner JOIN photos p on p.photo_id=uv.on_what_id
		where
			uv.on_what='place'
		and uv.vote > 0
		order by uv.vote";
     	$query= $this->CI->db->query($query_str);
        return $query->result();
    }
    function clear_places($photo_id=0)
    {
       if (empty($photo_id)) {
            $query_str="UPDATE photos as ph,user_votes as v SET ph.score=v.vote WHERE (ph.photo_id=v.on_what_id) and (v.on_what='place')";
	    	$res=$this->CI->db->query($query_str);
            $query_str2="DELETE FROM user_votes WHERE (on_what='place')";
    		$query=$this->CI->db->query($query_str2);
        } else {
            $query_str2="DELETE FROM user_votes WHERE (on_what='place') and (on_what_id=".$photo_id.")";
    		$query=$this->CI->db->query($query_str2);
            $query_str="UPDATE photos as ph SET ph.score=0 WHERE (ph.photo_id=".$photo_id.") ";
	    	$res=$this->CI->db->query($query_str);
        }
        return true;
    }
}

?>