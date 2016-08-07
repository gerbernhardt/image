<?php

function image_porc(&$img1,&$img2){
 $xmin=ImageSX($img1);$xmax=0;
 $ymin=ImageSY($img1);$ymax=0;
 $red=imagecolorallocate($img2,255,0,0);
 $total=$xmin*$ymin;
 $positive=0;
 $negative=0;
 for($j=0;$j<ImageSY($img1);$j++){
  for($i=0;$i<ImageSX($img1);$i++){
   $rgb1=imagecolorat($img1,$i,$j);
   $rgb2=imagecolorat($img2,$i,$j);
   if($rgb1!=$rgb2){
    $negative++;
   } else $positive++;
  }
 }
 return round($positive/$total*100,2);
}

function image_compare(&$img1,&$img2){
 $xmin=ImageSX($img1);$xmax=0;
 $ymin=ImageSY($img1);$ymax=0;
 for($j=0;$j<ImageSY($img1);$j++){
  for($i=0;$i<ImageSX($img1);$i++){
   $rgb1=imagecolorat($img1,$i,$j);
   $rgb2=imagecolorat($img2,$i,$j);
   if($rgb1!=$rgb2){
    return false;
   }
  }
 }
 return true;
}
function image_convert(&$img,&$colors){
 $xmin=ImageSX($img);$xmax=0;
 $ymin=ImageSY($img);$ymax=0;
 for($j=0;$j<ImageSY($img);$j++){
  for($i=0;$i<ImageSX($img);$i++){   $current=imagecolorat($img,$i,$j);
   if(in_array($current,$colors))    imagesetpixel($img,$i,$j,0);//print imagecolorallocate($img,255,255,255)."\n";
   else imagesetpixel($img,$i,$j,16777215);
  }
 }
 imagepng($img,'image.png');
}


$img=imagecreatefrompng('test.png');
for($i=0;$i<10;$i++) $imgs[$i]=imagecreatefrompng($i.'.png');// CARGA LA IMAGENES
for($i=0;$i<255;$i++) $colors[]=pow(($i+1),3)-1;// CREA LAS ESCALAS DE GRISES
image_convert($img,$colors);

$p=0;$w=8;$h=10;$text='';$numbers=array(0,1,3,4,6,7,8,9,11,12,14,15,17,18);
for($i=0;$i<(19*$w);$i+=$w){ $image=imagecreatetruecolor($w,10);
 imagecopy($image,$img,0,0,$i,0,$w,$h);
 if(in_array($p,$numbers)){  for($j=0;$j<9;$j++){  	if(image_compare($imgs[$j],$image)){  	 $text.=$j;break;
  	}
  }
 }
 if($p==2||$p==5){$text.='/';}
 if($p==10){$text.=' ';}
 if($p==13||$p==16){$text.=':';}
 $p++;}
print $text;


?>