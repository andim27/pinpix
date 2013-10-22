<?php
/**
  * img_resize
  *
  * Make thumbs from JPEG, PNG, GIF source file
  *
  * @author  Popov
  * @class   Photo_mdl
  * @access  public
  * @param   array     	$tmpname	$_FILES['source']['tmp_name'];
  * @param   int     	$size		max width size
  * @param   int     	$save_dir	destination folder
  * @param   int     	$save_name	tnumb new name
  * @return  rettype  return
  */
function img_resize($tmpname, $size, $save_dir, $save_name) 
{
	 	
	 	$save_dir .= ( substr($save_dir,-1) != "/") ? "/" : "";
        $gis       = GetImageSize($tmpname);
	    $type       = $gis[2];
	    switch($type) {
	    	case "1": $imorig = imagecreatefromgif($tmpname); break;
	        case "2": $imorig = imagecreatefromjpeg($tmpname);break;
	        case "3": $imorig = imagecreatefrompng($tmpname); break;
	        default:  $imorig = imagecreatefromjpeg($tmpname);
		}
	
        $x = imageSX($imorig);
        $y = imageSY($imorig);
        if($gis[0] <= $size) {
	        $av = $x;
	        $ah = $y;
	        
	    } else {
            $yc = $y*1.3333333;
            $d = $x>$yc?$x:$yc;
            $c = $d>$size ? $size/$d : $size;
			$av = $x*$c;        //высота исходной картинки
			$ah = $y*$c;        //длина исходной картинки
		}   
        $im = imagecreate($av, $ah);
        $im = imagecreatetruecolor($av,$ah);
        
	    if (imagecopyresampled($im,$imorig , 0,0,0,0,$av,$ah,$x,$y))
	    {
	        if (imagejpeg($im, $save_dir.$save_name)) return TRUE;
	    }
	    
		return FALSE;
}
?>