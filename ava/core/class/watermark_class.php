<?php 
class Watermark{

function AddWatermark($img_file, $filetype, $watermark = 'watermark.png'){
    $offset = 5;//отступ от правого нижнего края
    $image = GetImageSize($img_file);
    $xImg = $image[0];
    $yImg = $image[1];
    switch ($image[2]) {
        case 1:
            $img=imagecreatefromgif($img_file);
        break;
        case 2:
            $img=imagecreatefromjpeg($img_file);
        break;
        case 3:
            $img=imagecreatefrompng($img_file);
        break;
        }

    $r = imagecreatefrompng($watermark);
    $x = imagesx($r);
    $y = imagesy($r);

    $xDest = $xImg - ($x + $offset);
    $yDest = $yImg - ($y + $offset);
    imageAlphaBlending($img,1);
    imageAlphaBlending($r,1);
    imagesavealpha($img,1);
    imagesavealpha($r,1);
    imagecopyresampled($img,$r,$xDest,$yDest,0,0,$x,$y,$x,$y);
    switch ($filetype) {
            case "jpg":
                imagejpeg($img,$img_file,100);
            break;
            case "jpeg":
                imagejpeg($img,$img_file,100);
            break;
            case "gif":
                imagegif($img,$img_file);
            break;
            case "png":
                imagepng($img,$img_file);
            break;
        }
    imagedestroy($r);
    imagedestroy($img);
}
}