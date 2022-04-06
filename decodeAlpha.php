#!/usr/bin/php 
<?php
list($w, $h, $type, $attr) = getimagesize( $argv[1] );
$img = imagecreatefrompng( $argv[1] ); // jpeg does not work
$txt = '';
$byte = 0;
for ( $y=0; $y < $h; $y++ ) {
    for ( $x = 0; $x < $w; $x++ ) {
        $bitpos = ($y*$w + $x) % 8;
        $chrpos = intval(($y*$w+$x)/8);
        $colorIndex = imagecolorat($img, $x , $y );
	$colorInfo  = imagecolorsforindex($img, $colorIndex );
	$bit = $colorInfo['blue'] & 1;
        $byte = $byte | $bit<<$bitpos;
        if ( $bitpos == 7 ) {
            if ( $byte < 32 || $byte > 127 ) {
                break 2;
            }
            $txt .= chr( $byte );
            $byte = 0;
        } 
    }
}
echo $txt."\n";
?>

