#!/usr/bin/php 
<?php
/* does not work with png image containing alpha channel */
if ( $argc < 3 ) {
    echo "Usage. php encode filename text\n";
}
$filename = $argv[1];
$txtArray = str_split( $argv[2] );
list($w, $h, $type, $attr) = getimagesize( $filename );
$img = imagecreatefrompng( $filename ); // imagecreatefromjpeg does not work

for ( $y=0; $y < $h; $y++ ) {
    for ( $x = 0; $x < $w; $x++ ) {
        $chrpos = intval(($y*$w+$x)/8);
        if ( $chrpos >= sizeof( $txtArray ) ) {
            break 2;
        }
        $bitpos = ($y*$w + $x) % 8;
        $rgb = imagecolorat($img, $x , $y );
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;
        $o = (ord( $txtArray[$chrpos] ) >>$bitpos )& 1;
        // we put our message in blue channel 
        $b = ($b & 254) + $o; // bitpos counted from right to left
        $col = imagecolorallocate($img, $r, $g, $b );
        imagesetpixel($img, $x, $y, $col);
    }
}
imagepng($img, 'out.png');
?>

