#!/usr/bin/php 
<?php
function is_alpha_png($fn){
    return (ord(@file_get_contents($fn, NULL, NULL, 25, 1)) == 6);
}
if ( $argc < 3 ) {
    echo "Usage. php encode filename text\n";
}
$filename = $argv[1];
$txtArray = str_split( $argv[2] );
list($w, $h, $type, $attr) = getimagesize( $filename );
$img = imagecreatefrompng( $filename ); 

if ( is_alpha_png( $filename ) ) {
    imagesavealpha($img, true); 
}; 

for ( $y=0; $y < $h; $y++ ) {
    for ( $x = 0; $x < $w; $x++ ) {
        $chrpos = intval(($y*$w+$x)/8);
        if ( $chrpos >= sizeof( $txtArray ) ) {
            break 2;
        }
        $bitpos = ($y*$w + $x) % 8;
        $colorIndex = imagecolorat($img, $x , $y );
	$colorInfo  = imagecolorsforindex($img, $colorIndex );
        $r = $colorInfo['red'];
        $g = $colorInfo['green'];
        $b = $colorInfo['blue'];
	$a = $colorInfo['alpha'];
        $o = (ord( $txtArray[$chrpos] ) >>$bitpos )& 1;
	// we put our message in blue channel 
	$b = ($b & 254) + $o; // bitpos counted from right to left
	$a = ($a == 127 ? 126 : $a); // png algorithm sets b to zero if alpha==127 i.e. full transparency
        $col = imagecolorallocatealpha($img, $r, $g, $b, $a ); 
	imagesetpixel($img, $x, $y, $col);
    }
}
imagepng($img, 'out.png',1);
?>

