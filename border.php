<?php

function make_bw(&$img,&$colors){
 $xmin=ImageSX($img);$xmax=0;
 $ymin=ImageSY($img);$ymax=0;
 for($j=0;$j<ImageSY($img);$j++){
  for($i=0;$i<ImageSX($img);$i++){
   $current=imagecolorat($img,$i,$j);
   if(in_array($current,$colors))
    imagesetpixel($img,$i,$j,0);
   else imagesetpixel($img,$i,$j,16777215);
  }
 }
 imagepng($img,'image.png');
}

function make_borders(&$img,&$color){
 $xmin=0;$ymin=0;
 $xmax=ImageSX($img)-1;
 $ymax=ImageSY($img)-1;
 for($y=0;$y<=$ymax;$y++){
  for($x=0;$x<=$xmax;$x++){
   $current=imagecolorat($img,$x,$y);
   //print $current."\n";
   if($current==$color['bg']){
    $sync=true;
    // BORDERS
    if($x==$xmin||$x==$xmax||$y==$ymin||$y==$ymax){imagesetpixel($img,$x,$y,$color['fg']);$sync=false;}
    // LEFT/TOP/DOWN/RIGHT
    if($sync==true)if($xmax>=$x+1){if(!in_array(imagecolorat($img,$x+1,$y),$color)){imagesetpixel($img,$x,$y,$color['fg']);$sync=false;}}
    if($sync==true)if($xmin<=$x-1){if(!in_array(imagecolorat($img,$x-1,$y),$color)){imagesetpixel($img,$x,$y,$color['fg']);$sync=false;}}
    if($sync==true)if($xmax>=$y+1){if(!in_array(imagecolorat($img,$x,$y+1),$color)){imagesetpixel($img,$x,$y,$color['fg']);$sync=false;}}
    if($sync==true)if($ymin<=$y-1){if(!in_array(imagecolorat($img,$x,$y-1),$color)){imagesetpixel($img,$x,$y,$color['fg']);$sync=false;}}
    // COURNERS
    if($sync==true)if($xmax>=$x+1&&$ymax>=$y+1){if(!in_array(imagecolorat($img,$x+1,$y+1),$color)){imagesetpixel($img,$x,$y,$color['fg']);$sync=false;}}
    if($sync==true)if($xmin<=$x-1&&$ymin<=$y-1){if(!in_array(imagecolorat($img,$x-1,$y-1),$color)){imagesetpixel($img,$x,$y,$color['fg']);$sync=false;}}
    if($sync==true)if($xmax>=$x+1&&$ymin<=$y-1){if(!in_array(imagecolorat($img,$x+1,$y-1),$color)){imagesetpixel($img,$x,$y,$color['fg']);$sync=false;}}
    if($sync==true)if($xmin<=$x-1&&$ymax>=$y+1){if(!in_array(imagecolorat($img,$x-1,$y+1),$color)){imagesetpixel($img,$x,$y,$color['fg']);$sync=false;}}

   }
  }
 }
 imagepng($img,'border_result.png');
}

function make_dib(&$img,&$color,&$dib,$x,$y){
 global $total;
 $xmin=0;$ymin=0;
 $xmax=ImageSX($img)-1;$ymax=ImageSY($img)-1;
 $dib[]=$x.':'.$y;$total[$x][$y]=true;
 while(true){
  $sync=true;
  if($sync==true)if($xmax>=$x+1){if(imagecolorat($img,$x+1,$y)==$color['fg']&&!in_array(($x+1).':'.$y,$dib)){$x++;$dib[]=$x.':'.$y;$sync=false;}}//to right
  if($sync==true)if($ymax>=$y+1){if(imagecolorat($img,$x,$y+1)==$color['fg']&&!in_array($x.':'.($y+1),$dib)){$y++;$dib[]=$x.':'.$y;$sync=false;}}//to down
  if($sync==true)if($xmin<=$x-1){if(imagecolorat($img,$x-1,$y)==$color['fg']&&!in_array(($x-1).':'.$y,$dib)){$x--;$dib[]=$x.':'.$y;$sync=false;}}//to left
  if($sync==true)if($ymin<=$y-1){if(imagecolorat($img,$x,$y-1)==$color['fg']&&!in_array($x.':'.($y-1),$dib)){$y--;$dib[]=$x.':'.$y;$sync=false;}}//to up

  if($sync==true)if($xmax>=$x+1&&$ymax>=$y+1){if(imagecolorat($img,$x+1,$y+1)==$color['fg']&&!in_array(($x+1).':'.($y+1),$dib)){$x++;$y++;$dib[]=$x.':'.$y;$sync=false;}}
  if($sync==true)if($xmin<=$x-1&&$ymin<=$y-1){if(imagecolorat($img,$x-1,$y-1)==$color['fg']&&!in_array(($x-1).':'.($y-1),$dib)){$x--;$y--;$dib[]=$x.':'.$y;$sync=false;}}
  if($sync==true)if($xmax>=$x+1&&$ymin<=$y-1){if(imagecolorat($img,$x+1,$y-1)==$color['fg']&&!in_array(($x+1).':'.($y-1),$dib)){$x++;$y--;$dib[]=$x.':'.$y;$sync=false;}}
  if($sync==true)if($xmin<=$x-1&&$ymax>=$y+1){if(imagecolorat($img,$x-1,$y+1)==$color['fg']&&!in_array(($x-1).':'.($y+1),$dib)){$x--;$y++;$dib[]=$x.':'.$y;$sync=false;}}

  if($sync==false)
   $total[$x][$y]=true;
  else break;
 }
}
function make_dibs(&$img,&$color,&$id,&$dibs){
 global $total;
 $xmin=0;$ymin=0;
 $xmax=ImageSX($img)-1;
 $ymax=ImageSY($img)-1;
 for($y=0;$y<=$ymax;$y++){
  for($x=0;$x<=$xmax;$x++){
   $current=imagecolorat($img,$x,$y);
   if($current==$color['fg']){
    if(!isset($total[$x][$y])){     $id++;$dibs[$id]=array();
     make_dib($img,$color,$dibs[$id],$x,$y);
    }
   }
  }
 }
 imagepng($img,'border_result.png');
}

$img=imagecreatefrompng('border.png');

for($i=0;$i<255;$i++) $colors[]=pow(($i+1),3)-1;// CREA LAS ESCALAS DE GRISES
make_bw($img,$colors);
$color=array('bg'=>imagecolorallocate($img,255,255,255),'fg'=>imagecolorallocate($img,255,0,0));
make_borders($img,$color);
/*
$id=-1;
$dibs=array();
$total=array();
make_dibs($img,$color,$id,$dibs);
//file_put_contents('filename.txt',print_r($dibs,true));
*/
?>