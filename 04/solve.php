<?php

$input = file ( __DIR__ . '/input.txt' );

$fully_contained = $overlap = 0;

foreach ( $input as $pair )
{
    $pair = explode ( ',', $pair );

    foreach ( $pair as &$elf )
        $elf = explode ( '-', $elf );

    if (    ( $pair [ 0 ][ 0 ] >= $pair [ 1 ][ 0 ] && $pair [ 0 ][ 1 ] <= $pair [ 1 ][ 1 ] )
         || ( $pair [ 0 ][ 0 ] <= $pair [ 1 ][ 0 ] && $pair [ 0 ][ 1 ] >= $pair [ 1 ][ 1 ] ) )
    {
        $fully_contained++;
        $overlap++;
    }
    elseif (    ( $pair [ 0 ][ 1 ] >= $pair [ 1 ][ 0 ] && $pair [ 0 ][ 1 ] <= $pair [ 1 ][ 1 ] )
             || ( $pair [ 1 ][ 1 ] >= $pair [ 0 ][ 0 ] && $pair [ 1 ][ 1 ] <= $pair [ 0 ][ 1 ] ) )
        $overlap++;
}

echo "First part: $fully_contained\n";
echo "Second part: $overlap\n";