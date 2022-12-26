<?php

$input = file ( __DIR__ . '/input.txt', FILE_IGNORE_NEW_LINES );

$cubes = []; // Z|Y|X

foreach ( $input as $cube )
{
    $coords = explode ( ',', $cube );

    $cubes [ $coords [ 2 ]][ $coords [ 1 ]][ $coords [ 0 ]] = true;
}

// iterate over everything and count exposed sides

$exposed_sides = 0;

foreach ( $cubes as $z => $y_cube )
    foreach ( $y_cube as $y => $x_cube )
        foreach ( $x_cube as $x => $dummy )
        {
            if ( !isset ( $cubes [ $z - 1 ][ $y ][ $x ] ) ) $exposed_sides++;
            if ( !isset ( $cubes [ $z + 1 ][ $y ][ $x ] ) ) $exposed_sides++;

            if ( !isset ( $y_cube [ $y - 1 ][ $x ] ) ) $exposed_sides++;
            if ( !isset ( $y_cube [ $y + 1 ][ $x ] ) ) $exposed_sides++;

            if ( !isset ( $x_cube [ $x - 1 ] ) ) $exposed_sides++;
            if ( !isset ( $x_cube [ $x + 1 ] ) ) $exposed_sides++;
        }

echo "First part: $exposed_sides\n";