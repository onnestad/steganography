<?php
$filename = $argv[1];
list($w, $h, $type, $attr) = getimagesize( $filename );
$img = imagecreatefrompng( $filename ); // jpeg does not work
$txt = '';
$byte = 0;
for ( $y=0; $y < $h; $y++ ) {
    for ( $x = 0; $x < $w; $x++ ) {
        $bitpos = ($y*$w + $x) % 8;
        // $chrpos = intval(($y*$w+$x)/8);
        $rgb    = imagecolorat($img, $x , $y );
        $r      = ($rgb >> 16) & 0xFF;
        $g      = ($rgb >> 8) & 0xFF;
        $b      = $rgb & 0xFF;
        $byte   = $byte | (($b&1)<<$bitpos);
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

