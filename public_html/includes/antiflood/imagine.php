<?php
session_start();
$font="fontflood/verdana.ttf"; 
$fontsize=14; 
function strrand($length)
{
	$str = "";
	while(strlen($str)<$length){
	$random=rand(48,122);
	if( ($random>47 && $random<58)){ //47->58: number; 65->90 : A-Z; 97->121: a-z
	$str.=chr($random);
	} 
	
	}
		
	return $str;
}
$text = $_SESSION['verificationcode']=strrand(5);
header("Content-type: image/png");
$im = imagecreatefrompng("back.png");
$black = imagecolorallocate($im, 0, 0, 0);
$color1=imagecolorallocate($im, 0, 0, 255 );
$color2=imagecolorallocate($im, 166, 27, 31 );
$color3=imagecolorallocate($im, 0, 0, 255 );
$white=imagecolorallocate($im, 255, 255, 255 );
imagettftext($im,  $fontsize, -3, 10, 16, $white, $font, $text );
imagepng($im);
imagedestroy($im);
?> 