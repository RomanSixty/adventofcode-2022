<?php

//include __DIR__ . '/visualize.php';

$input = file ( __DIR__ . '/input.txt', FILE_IGNORE_NEW_LINES );

$viewport = [
    'top'    => 0,
    'left'   => 500,
    'right'  => 500,
    'bottom' => 0
];

$map = [];

foreach ( $input as $line )
{
    $points = [];

    $coords = explode ( ' -> ', $line );

    $point_a = array_pop ( $coords );

    while ( $point_b = array_pop ( $coords ) )
    {
        draw_line ( $point_a, $point_b );

        $point_a = $point_b;
    }
}

function draw_line ( $a, $b )
{
    global $map, $viewport;

    $a = explode ( ',', $a );
    $b = explode ( ',', $b );

    $viewport [ 'left' ] = min ( $viewport [ 'left' ], $a [ 0 ] );
    $viewport [ 'left' ] = min ( $viewport [ 'left' ], $b [ 0 ] );

    $viewport [ 'right' ] = max ( $viewport [ 'right' ], $a [ 0 ] );
    $viewport [ 'right' ] = max ( $viewport [ 'right' ], $b [ 0 ] );

    $viewport [ 'bottom' ] = max ( $viewport [ 'bottom' ], $a [ 1 ] );
    $viewport [ 'bottom' ] = max ( $viewport [ 'bottom' ], $b [ 1 ] );

    if ( $a [ 0 ] == $b [ 0 ] )
    {
        if ( $a [ 1 ] < $b [ 1 ] )
            for ( $y = $a [ 1 ]; $y <= $b [ 1 ]; $y++ )
                $map [ $y ][ $a [ 0 ]] = 'X';
        else
            for ( $y = $b [ 1 ]; $y <= $a [ 1 ]; $y++ )
                $map [ $y ][ $a [ 0 ]] = 'X';
    }
    else
    {
        if ( $a [ 0 ] < $b [ 0 ] )
            for ( $x = $a [ 0 ]; $x <= $b [ 0 ]; $x++ )
                $map [ $a [ 1 ]][ $x ] = 'X';
        else
            for ( $x = $b [ 0 ]; $x <= $a [ 0 ]; $x++ )
                $map [ $a [ 1 ]][ $x ] = 'X';
    }
}

function trickle ( &$map, $viewport, $sand )
{
    while ( true )
    {
        // out of bounds
        if ( $sand [ 1 ] > $viewport [ 'bottom' ] )
            return false;

        // down
        elseif ( empty ( $map [ $sand [ 1 ] + 1 ][ $sand [ 0 ]] ) )
        {
            $sand [ 1 ]++;
        }

        // left
        elseif ( empty ( $map [ $sand [ 1 ] + 1 ][ $sand [ 0 ] - 1 ] ) )
        {
            $sand [ 0 ]--;
            $sand [ 1 ]++;
        }

        // right
        elseif ( empty ( $map [ $sand [ 1 ] + 1 ][ $sand [ 0 ] + 1 ] ) )
        {
            $sand [ 0 ]++;
            $sand [ 1 ]++;
        }

        // stop
        else
        {
            $map [ $sand [ 1 ]][ $sand [ 0 ]] = 'o';

            return !( $sand [ 0 ] == 500 && $sand [ 1 ] == 0 );
        }
    }
}

function pour ( $map, $viewport, $part = 1 )
{
    $counter = 0;

    while ( trickle ( $map, $viewport, [ 500, 0 ] ) )
        $counter++;

    //draw_map ( $map, $viewport );
    //createSVG ( $map, $viewport, $part );

    return $counter;
}

echo 'First part: ' . pour ( $map, $viewport ) . "\n";

// let's build the floor making some pretentious assumptions about the maximum expansion
// if there were no obstacles a perfect pile's width would be double its height

$viewport [ 'bottom' ] += 2;

draw_line (
    500 - $viewport [ 'bottom' ] . ',' . $viewport [ 'bottom' ],
    500 + $viewport [ 'bottom' ] . ',' . $viewport [ 'bottom' ]
);

echo 'Second part: ' . pour ( $map, $viewport, 2 ) + 1 . "\n";