<?php
/*
arg=array(
kat     каталог загрузки изображения
fname   POST-имя файла
url     если файл грузится с урла
name    новое имя файла (если пусто, имя не меняется)
rnd     если 1 то генерится новое имя файла
rx      ресайзинг по x
ry      ресайзинг по y
);

prew   массив для превью 
(pref=>префикс, kat => каталог, rx=> $ry=>, prop=> true|false) 
если каталог не указан, используется kat из основного массива
prop = сохранять пропорции изображения
*/


class Upload{
   var $ext;
   var $prew;
   var $quality=90; 
    function Upload(){
      $this->ext=array("jpg","gif","bmp","png","swf","jpeg");
      $this->prew=array('kat'=>'','rx'=>0,'ry'=>0, 'pref'=>'');
    }
    
function random_name($name){
$nm=explode(".",$name.".");
$name=$nm[0];
$nm=explode("_",$name."_");
$name=$nm[0]."_".(intval($nm[1])+1);
return $name;
}



function my_upload($arg){
    global $_root;


$result=array();

$upfile=$arg['fname'];
//echo $upfile."<br>";
$ar=array('prop'=>true);
$arg=array_merge($ar,$arg);
//print_r($ar);
if(isset($arg['prew'])) $this->prew=$arg['prew'];


if (!is_dir($_root.$arg['kat'])){
			 mkdir($_root.$arg['kat']);
			 chmod($_root.$arg['kat'], 0755);
			}
            
            
  if(trim($arg['url'])==''){          

if ($_FILES[$upfile]['size']==0) {
	$result=array("ok"=>2,"err"=> "Error : File ".$_FILES[$upfile]['name']." not upload");
	return;
	} else {

$pif=pathinfo($_FILES[$upfile]['name']);
$ext=strtolower($pif['extension']);	
if (!in_array($ext,$this->ext)) {
	$result=array("ok"=>0,"err"=> "Неверное расширение файла! ");
	return $result;
		}	
if($ext=="jpeg") $ext="jpg";

if($arg['name']==""){
 $fname=strtolower($_FILES[$upfile]['name']);

 }else{
 if($arg['rnd']==1)  $fname=$this->random_name($arg['name']);
     $fname=$arg['name'].".".$ext;
 }
 $savefile=$_root.$arg['kat']."/".$fname;
 $orign=$_root.$arg['kat']."/o_".$fname;

              
		if (move_uploaded_file($_FILES[$upfile]['tmp_name'], $savefile)) {
			chmod($savefile, 0644);
			$result=array("ok"=>1,"fname"=>$fname,"ext"=>$ext);
            copy($savefile, $orign);
            $result['orign']=$orign;
            
				} else {
		$result= "Error !!: ";
		switch($_FILES[$upfile]['error']) {
			case 0: //no error; possible file attack!
				$result=array("ok"=>0,"err"=> "Файл был загружен но нет прав на запись");
				break;
			case 1: //uploaded file exceeds the upload_max_filesize directive in php.ini
				$result=array("ok"=>0,"err"=> "The file you are trying to upload is too big.");
				break;
			case 2: //uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the html form
				$result=array("ok"=>0,"err"=> "The file you are trying to upload is too big.");
				break;
			case 3: //uploaded file was only partially uploaded
				$result= array("ok"=>0,"err"=>"The file you are trying upload was only partially uploaded.");
				break;
			case 4: //no file was uploaded
				$result=array("ok"=>0,"err"=> "You must select an image for upload.");
				break;
			default: //a default error, just in case!  :)
				$result=array("ok"=>0,"err"=> "There was a problem with your upload:".$_FILES[$upfile]['error']);
				break;
		} // end case
	} //end error
	
	} //end ok
    }else{ // если передан урл
        $result=$this->save_images_from_url($arg['url'], $_root.$arg['kat']."/", $arg['name']);
        if($result['ok']==0) return $result;
        $savefile=$_root.$arg['kat']."/".$result['fname']; 
        $fname=$result['fname'];
    }
    
// если требуется уменьшение размера	
  if(intval($arg['rx'])!=0) $this->resizeimg($savefile, $savefile, $arg['rx'], $arg['ry'],$arg['prop']);
  // создание превью
  if($this->prew['rx']>0){
    $prop=(isset($this->prew['prop']) ? $this->prew['prop'] : true);
    if($this->prew['kat']!=''){
        if (!is_dir($_root.$this->prew['kat'])){
			 mkdir($_root.$this->prew['kat']);
			 chmod($_root.$this->prew['kat'], 0755);
			}
    }
    $prew=$_root.($this->prew['kat']=='' ? $arg['kat'] : $this->prew['kat'])."/".$this->prew['pref'].$fname;
     $this->resizeimg($savefile, $prew, $this->prew['rx'], $this->prew['ry'], $prop);
     $result['prew']=$this->prew['pref'].$fname;
  }
     
return $result;
}

function crop($arg){
  global $_root;
  $p=$_root.$arg['path'];
  $source=preg_replace("/^(c_|o_){1}/", "", $arg['source']);
  $source=$p."o_".$source;
  $dest=$p.$arg['dest'];
  $x=intval($arg['x']);
  $y=intval($arg['y']);
  $x1=intval($arg['x1']);
  $y1=intval($arg['y1']);
  $w=intval($arg['w']);
  $h=intval($arg['h']);
  return $this->cropimage($source,$dest,$x,$y,$x1,$y1,$w,$h);

}

function cropimage($source,$dest,$x,$y,$x1,$y1,$w,$h){
  $size_img = getimagesize($source);
  //echo "w=$w h=$h <br>";
$dest_img = imagecreatetruecolor($w, $h);  
$white = imagecolorallocate($dest_img, 255, 255, 255);  

if ($size_img[2]==2){
 $src_img = imagecreatefromjpeg($source);
}elseif($size_img[2]==1){
    $src_img = imagecreatefromgif($source);
}elseif($size_img[2]==3){
    $src_img = imagecreatefrompng($source); 
}  


// масштабируем изображение функцией imagecopyresampled()  
// $dest_img - уменьшенная копия  
// $src_img - исходной изображение  
// $w - ширина уменьшенной копии  
// $h - высота уменьшенной копии  
// $ws - ширина копируемой области
// $ws - высота копируемой области
$ws=$x1-$x;
$wh=$y1-$y;

imagecopyresampled($dest_img, $src_img, 0, 0, $x, $y, $w, $h, $ws, $wh); 

if($size_img[2]==2){
  imagejpeg($dest_img, $dest,$this->quality);
}elseif($size_img[2]==1){ 
  imagegif($dest_img, $dest);  
}elseif($size_img[2]==3){
  imagepng($dest_img, $dest);
}  
// чистим память от созданных изображений  

imagedestroy($dest_img);  
imagedestroy($src_img);  
return true;   

}


function resizeimg($filename, $smallimage, $w, $h, $prop=true)  
{  
// определим коэффициент сжатия изображения, которое будем генерить  
$ratio = $w/$h;  
// получим размеры исходного изображения  

$size_img = getimagesize($filename);  
$old=array('w'=>$size_img[0], 'h'=>$size_img[1]);
if (($size_img[0]<$w) && ($size_img[1]<$h)) {copy($filename, $smallimage); return true;  } 

// получим коэффициент сжатия исходного изображения  

$src_ratio=$size_img[0]/$size_img[1];  

// Здесь вычисляем размеры уменьшенной копии, чтобы при масштабировании сохранились  
// пропорции исходного изображения  
if ($ratio<$src_ratio)  
{  
    if($prop){
         
$h = $w/$src_ratio;        
    }else{
$size_img[0]= $size_img[1]*$ratio;
    }
  
}  
elseif($ratio>$src_ratio)  
{  
    if($prop){
        $w = $h*$src_ratio;
    }else{
    $size_img[1] = $size_img[0]/$ratio;         
    }
 
}  

$sx=0;
$sy=0;

if(!$prop) {
    // центрируем
    if ($ratio<$src_ratio){
        $sx=($old['w']-$size_img[0])/2;
    //   $size_img[0]+=$sx;
    }elseif($ratio>$src_ratio) {
    $sy=($old['h']-$size_img[1])/2;
   //   $size_img[1]+=$sy;  
    }  
}
// создадим пустое изображение по заданным размерам  



//echo "w=$w h=$h <br>";
$dest_img = imagecreatetruecolor($w, $h);  

$white = imagecolorallocate($dest_img, 255, 255, 255);  

if ($size_img[2]==2) {

 $src_img = imagecreatefromjpeg($filename);  }





else if ($size_img[2]==1) $src_img = imagecreatefromgif($filename);  
else if ($size_img[2]==3) $src_img = imagecreatefrompng($filename);  
// масштабируем изображение функцией imagecopyresampled()  
// $dest_img - уменьшенная копия  
// $src_img - исходной изображение  
// $w - ширина уменьшенной копии  
// $h - высота уменьшенной копии  
// $size_img[0] - ширина исходного изображения  
// $size_img[1] - высота исходного изображения  

imagecopyresampled($dest_img, $src_img, 0, 0, $sx, $sy, $w, $h, $size_img[0], $size_img[1]);  
// сохраняем уменьшенную копию в файл  
if ($size_img[2]==2)
imagejpeg($dest_img, $smallimage,$this->quality);  
else if ($size_img[2]==1) imagegif($dest_img, $smallimage);  
else if ($size_img[2]==3) imagepng($dest_img, $smallimage);  

// чистим память от созданных изображений  

imagedestroy($dest_img);  
imagedestroy($src_img);  
return true;  
	}
    
 // скачивание и запись файла картинки по ссылке  
function save_images_from_url($url, $kat, $new_name) // savedir - полный путь
{ 
	global $_root, $Log;
  //  echo $url;
    if(trim($url)=='') return array('ok'=>0, 'err'=>"пустая ссылка на изображение");
    if(strpos($url,'http://')===false) return array('ok'=>0, 'err'=>"неверная ссылка на изображение");
	$name=explode("/",$url);
    $image_name=end($name); 
	if(trim($image_name)=='') return array('ok'=>0,'err'=>'no name file'); 
    
    $save_file=$kat.$image_name;
    $ch = curl_init ($url); 
 //   echo $url." save_file ".$save_file."<br>";
    $fp = fopen ($save_file, "wb"); 
    if (!$fp){
       $Log.='Не удалось открыть файл для сохранения изображения ' . $url."<br>"; 
        return array('ok'=>0,'err'=>'Не удалось открыть файл для сохранения изображения ' . $image_name.'<br>'); 
    } 
    curl_setopt ($ch, CURLOPT_FILE, $fp);  
    curl_setopt ($ch, CURLOPT_HEADER, 0); 
    curl_setopt ($ch, CURLOPT_TIMEOUT, 5); 
    curl_exec ($ch); 
    curl_close ($ch); 
    fclose ($fp);
    $arr=array();
    if(is_file($save_file)){
        $ext=strtolower(end(explode(".",$image_name)));
        if(in_array($ext,$this->ext)){
            @unlink($kat.$new_name.".".$ext);
           rename($save_file,$kat.$new_name.".".$ext); 
        //   echo "rename to ".$kat.$new_name.".".$ext."<br>";
          $arr=array('ok'=>1,'fname'=>$new_name.".".$ext);
            
        }else{
            unlink($save_file);
          $arr=array('ok'=>0,'err'=>'неверное расширение файла '.$ext);   
        }
    }else{
        $arr=array('ok'=>0,'err'=>'file not upload');
    }
   unset($ch); 
	return $arr; 
}     
    
    
    

}

