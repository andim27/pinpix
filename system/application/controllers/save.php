<?php
class Save extends Controller {

function Save() {
		parent::Controller();
 	$CI =& get_instance();
	$this->photo_uploads =  $CI->config->item('photo_uploads');
	$this->photo_uploads_temp =  $CI->config->slash_item('photo_uploads_temp');
    $this->resolution_arr = $this->config->item("resolution_foto");
}

function savef($date_added='', $photo='', $extension='', $size=''){

    if (empty($date_added)||empty($photo)||empty( $extension))
        show_404('page not found') ;

    $extension[0] = ".";
    $name =  $photo .'-md'. $extension;
    $phsrc = $this->photo_uploads . $date_added. '/' . $name;

    if (!file_exists($phsrc))
        show_404('photo not found') ;

    //if (empty ($size))
    //{
	//    header("Content-Disposition: attachment; filename=$name");
	//    header("Content-Type: application/x-force-download; name=\"$name\"");
	//    echo file_get_contents   ("$phsrc");
	//    exit();
    //}
    else
    $this->reCreateImg($photo,$date_added, $extension,$size);
    return;
    switch ($size){
    case 0:
                   $phsrc = $this->photo_uploads . $date_added. '/' . $photo .'-lg'. $extension;
                   $phsrc = $this->SetImgSize($phsrc, $photo, $extension, 1024, 768);
        break;
    case 1:        $phsrc = $this->photo_uploads . $date_added. '/' . $photo .'-lg'. $extension;
                   $phsrc = $this->SetImgSize($phsrc, $photo, $extension, 1280, 800);
        break;
    case 2:        $phsrc = $this->photo_uploads . $date_added. '/' . $photo .'-lg'. $extension;
                   $phsrc = $this->SetImgSize($phsrc, $photo, $extension, 1280, 1024);
        break;
    case 3:        $phsrc = $this->photo_uploads . $date_added. '/' . $photo .'-lg'. $extension;
                   $phsrc = $this->SetImgSize($phsrc, $photo, $extension, 1440, 900);
        break;
    default:   show_404('unexpected size') ;
    }
}
function reCreateImg($photo,$date_added, $extension,$size) {
  $l=count($this->resolution_arr)-1;
  if (($size >=0)and ($size <=$l)) {
      $img = $this->photo_uploads . $date_added. '/' . $photo .'-lg'. $extension;
      $extension = strtolower($extension);
      switch ($extension)
      {
          case ".jpg":
              $srcImage = @imagecreatefromjpeg ($img);
              break;
          case ".jpeg":
              $srcImage = @imagecreatefromjpeg ($img);
              break;
          case ".gif":
              $srcImage = imagecreatefromgif ($img);
              break;
          case ".png":
              $srcImage = imagecreatefrompng ($img);
              break;
          default:
              show_404('unexpected file extension') ;
              return -1;
          break;
       }
       $srcWidth  = imagesx($srcImage);
       $srcHeight = imagesy($srcImage);
       $k_src = $srcHeight/$srcWidth;
       $k_res = $this->resolution_arr[$size]["h"] /$this->resolution_arr[$size]["w"] ;
       if ($k_src <  $k_res){
             $destHeight  =intval($this->resolution_arr[$size]["h"]);
             $destWidth  = $srcWidth*$this->resolution_arr[$size]["w"]/$srcHeight  ;;
             $dh=0;
             $dx=($destWidth -$this->resolution_arr[$size]["w"])/2;;
       }else {
             $destHeight  =$srcHeight*$this->resolution_arr[$size]["w"]/$srcWidth  ;
             $destWidth = intval($this->resolution_arr[$size]["w"]);
             $dh=($destHeight -$this->resolution_arr[$size]["h"])/2;
             $dx=0;
       }
       $resImage = imagecreatetruecolor ($destWidth, $destHeight);
       imagecopyresampled ($resImage, $srcImage, 0, 0, 0, 0, $destWidth, $destHeight, $srcWidth, $srcHeight);

       imagedestroy($srcImage);

       //$mylog=new myLog;
       //$mylog->path="d:/downloads/";
       //$mylog->write("\n  destWidth=".$destWidth." destHeight=".$destHeight." k=".$k_src." dh=".$dh);

       $resImageLast = imagecreatetruecolor ($this->resolution_arr[$size]["w"], $this->resolution_arr[$size]["h"]);
       imagecopyresampled ($resImageLast, $resImage, 0, 0, $dx, $dh, $destWidth-$dx, $destHeight-$dh, $destWidth, $destHeight);

       $pfile = $photo ."_". $this->resolution_arr[$size]["w"] . "x" . $this->resolution_arr[$size]["h"] . $extension;
       $resource_src =  $this->photo_uploads_temp . "/" .$pfile;
       imagejpeg ($resImageLast, $resource_src, 100);

       //$mylog->write("\n resource_src=".$resource_src);

       imagedestroy($resImage);
       imagedestroy($resImageLast);

       header("Content-Disposition: attachment; filename=$pfile");
       header("Content-Type: application/x-force-download; name=\"$pfile\"");
       echo file_get_contents("$resource_src");
       unlink($resource_src);


  }else show_404('unexpected size') ;
}
function SetImgSize($img, $name, $extension, $width, $height){

$extension = strtolower($extension);

    switch ($extension)
    {
    case ".jpg":
        $srcImage = @imagecreatefromjpeg ($img);
        break;
    case ".jpeg":
        $srcImage = @imagecreatefromjpeg ($img);
        break;
    case ".gif":
        $srcImage = imagecreatefromgif ($img);
        break;
    case ".png":
        $srcImage = imagecreatefrompng ($img);
        break;
    default:
        return -1;
    break;
    }

   $srcWidth = imagesx($srcImage);
   $srcHeight = imagesy($srcImage);

   $destWidth = $width;
   $destHeight = $height;

   $resImage = imagecreatetruecolor ($destWidth, $destHeight);
   imagecopyresampled ($resImage, $srcImage, 0, 0, 0, 0, $destWidth, $destHeight, $srcWidth, $srcHeight);


   $pfile = $name ."_". $width . "x" . $height . $extension;
   $resource_src =  $this->photo_uploads_temp . "/" .$pfile;
   //log_message('debug', "resampled file:::".$resource_src);
   imagejpeg ($resImage, $resource_src, 100);
     //���������� ���

    switch ($extension)
    {
    case ".jpg":
        imagejpeg($resImage, $resource_src, 100);
        break;
    case ".gif":
        imagegif($resImage, $resource_src);
        break;
    case ".png":
        imagepng($resImage, $resource_src);
        break;
    }

    imagedestroy($srcImage);
    imagedestroy($resImage);

    header("Content-Disposition: attachment; filename=$pfile");
    header("Content-Type: application/x-force-download; name=\"$pfile\"");
    echo file_get_contents("$resource_src");

    unlink($resource_src);

    }
}
?>