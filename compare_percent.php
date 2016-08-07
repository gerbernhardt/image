<?php
function image_convert(&$img,&$colors){
 $xmin=ImageSX($img['test']);$xmax=0;
 $ymin=ImageSY($img['test']);$ymax=0;
 $red=imagecolorallocate($img['test'],255,0,0);
 for($j=0;$j<ImageSY($img['test']);$j++){
  for($i=0;$i<ImageSX($img['test']);$i++){
   $current=imagecolorat($img['test'],$i,$j);
   if(in_array($current,$colors))
    imagesetpixel($img['test'],$i,$j,0);
   else imagesetpixel($img['test'],$i,$j,16777215);
  }
 }
 imagepng($img['test'],'image.png');
}
function image_compare(&$img,$x){
 $xmin=ImageSX($img['test']);$xmax=0;
 $ymin=ImageSY($img['test']);$ymax=0;
 $red=imagecolorallocate($img[$x],255,0,0);
 $total=$xmin*$ymin;
 $positive=0;
 $negative=0;
 for($j=0;$j<ImageSY($img['test']);$j++){
  for($i=0;$i<ImageSX($img['test']);$i++){
   $rgb1=imagecolorat($img['test'],$i,$j);
   $rgb2=imagecolorat($img[$x],$i,$j);
   if($rgb1!=$rgb2){
    $negative++;
   } else $positive++;
  }
 }
 print 'Imagen: '.$x.'.png / ';
 print 'Diferencias: '.round($negative/$total*100,2).'% / ' ;
 print 'Coincidencias: '.round($positive/$total*100,2)."%\n";
}

$imgs['test']=imagecreatefrompng('test1.png');
for($i=0;$i<255;$i++) $colors[]=pow(($i+1),3)-1;
image_convert($imgs,$colors);
for($i=0;$i<10;$i++) $imgs[$i]=imagecreatefrompng($i.'.png');
for($i=0;$i<10;$i++) image_compare($imgs,$i);


?>