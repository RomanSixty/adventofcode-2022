<?php

$pairs = explode ( "\n\n", file_get_contents ( __DIR__ . '/input.txt' ) );

$all_packets = [];
$correctly_ordered_pairs = [];

foreach ( $pairs as $index => $pair )
{
    $pair = explode ( "\n", $pair );

    // don't try this at home:
    $left  = eval ( 'return ' . $pair [ 0 ] . ';' );
    $right = eval ( 'return ' . $pair [ 1 ] . ';' );

    $all_packets[] = $left;
    $all_packets[] = $right;

    if ( leftIsSmaller ( $left, $right ) )
        $correctly_ordered_pairs[] = $index + 1;
}

// next up... no need to sort all packets, when we just
// have to compare the divider packets with each
// input packet to see how many are "smaller"

$smaller__2 = 1; // both offset by one
$smaller__6 = 2; // this one an additional one because [[2]] will be smaller

foreach ( $all_packets as $packet )
{
    if ( leftIsSmaller ( $packet, [[2]] ) )
        $smaller__2++;

    if ( leftIsSmaller ( $packet, [[6]] ) )
        $smaller__6++;
}

function leftIsSmaller ( $left, $right )
{
    for ( $i = 0; $i < count ( $left ); $i++ )
    {
        if ( !isset ( $right [ $i ] ) )
            return false;

        if ( is_numeric ( $left [ $i ] ) && is_numeric ( $right [ $i ] ) )
        {
            if ( $left [ $i ] < $right [ $i ] )
                return true;
            elseif ( $left [ $i ] > $right [ $i ] )
                return false;
        }
        else
        {
            if ( is_numeric ( $left [ $i ] ) )
                $left [ $i ] = [ $left [ $i ]];
            if ( is_numeric ( $right [ $i ] ) )
                $right [ $i ] = [ $right [ $i ]];

            $ret = leftIsSmaller ( $left [ $i ], $right [ $i ] );

            if ( $ret !== null )
                return $ret;
        }
    }

    if ( isset ( $right [ $i ] ) )
        return true;

    return null;
}

echo 'First part: ' . array_sum ( $correctly_ordered_pairs ) . "\n";
echo 'Second part: ' . $smaller__2 * $smaller__6 . "\n";