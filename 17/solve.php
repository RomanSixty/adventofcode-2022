<?php

//include __DIR__ . '/visualize.php';

$wind = str_split ( file_get_contents ( __DIR__ . '/input.txt' ) );

$rocks = [
    [ [ 0 => true, 1 => true, 2 => true, 3 => true ] ],
    [ [ 1 => true ], [ 0 => true, 1 => true, 2 => true ], [ 1 => true ] ],
    [ [ 0 => true, 1 => true, 2 => true ], [ 2 => true ], [ 2 => true ] ], // upside down
    [ [ 0 => true ], [ 0 => true ], [ 0 => true ], [ 0 => true ] ],
    [ [ 0 => true, 1 => true ], [ 0 => true, 1 => true ] ]
];

function run_simulation ( $rocks_to_drop )
{
    global $wind;

    $wind_length = count ( $wind );

    $cave = [];

    $rock_counter = $wind_counter = $rock_shape = 0;

    $rock = newRock ( $rock_shape, $cave );

    while ( $rock_counter < $rocks_to_drop )
    {
        $moved_rock = $rock;

        if ( $wind [ $wind_counter ] == '<' )
            $moved_rock [ 'left' ]--;
        else
            $moved_rock [ 'left' ]++;

        // hit a wall (or another rock): reset
        if ( !collision ( $cave, $moved_rock, true ) )
            $rock = $moved_rock;
        else
            $moved_rock = $rock;

        $moved_rock [ 'bottom' ]--;

        // hit the bottom (or another rock): drop a new rock
        if ( collision ( $cave, $moved_rock ) )
        {
            putRock ( $cave, $rock );

            $rock_shape = ( $rock_shape + 1 ) % 5;

            //draw ( $rock, $cave );

            $rock = newRock ( $rock_shape, $cave );

            $rock_counter++;
        }

        // no problem, next step
        else
            $rock = $moved_rock;

        $wind_counter = ( $wind_counter + 1 ) % $wind_length;
    }

    return count ( $cave );
}

echo 'First part: ' . run_simulation ( 2022 ) . "\n";

function newRock ( $shape, $cave )
{
    global $rocks;

    return [
        'shape' => $shape,
        'height' => count ( $rocks [ $shape ] ),
        'left' => 3, // padded for the left wall
        'bottom' => count ( $cave ) + 3
    ];
}

function collision ( &$cave, $rock )
{
    global $rocks;

    $rockshape = $rocks [ $rock [ 'shape' ]];

    $levelnr = 0;

    while ( $rockslice = array_shift ( $rockshape ) )
    {
        $y = $rock [ 'bottom' ] + $levelnr;

        foreach ( $rockslice as $key => $dummy )
        {
            $x = $key + $rock [ 'left' ];

            if ( $x == 0 || $x == 8 )
                return true;

            if ( $rock [ 'bottom' ] < 0 )
                return true;

            if ( isset ( $cave [ $y ][ $x ] ) )
                return true;
        }

        $levelnr++;
    }

    return false;
}

function putRock ( &$cave, $rock )
{
    global $rocks;

    $rockshape = $rocks [ $rock [ 'shape' ]];

    $level = 0;

    while ( $rockslice = array_shift ( $rockshape ) )
    {
        foreach ( $rockslice as $key => $dummy )
            $cave [ $rock [ 'bottom' ] + $level ][ $key + $rock [ 'left' ]] = true;

        $level++;
    }
}