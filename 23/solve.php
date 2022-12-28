<?php

$input = file ( __DIR__ . '/input.txt', FILE_IGNORE_NEW_LINES );

// marginally better readability below
const Y = 0;
const X = 1;
const PROP = 2;

$elves = [];

$directions = [ 'N', 'S', 'W', 'E' ];

foreach ( $input as $y => $row )
    foreach ( str_split ( $row ) as $x => $cell )
        if ( $cell == '#' )
            $elves [ $x . '|' . $y ] = [ $y, $x ];

$round = 0;

while ( true )
{
    $round++;

    $proposals = [];

    // where should we go?
    foreach ( $elves as $key => $elf )
    {
        $elves [ $key ][ PROP ] = [];

        $N  = empty ( $elves [   $elf [ X ]       . '|' . ( $elf [ Y ] - 1 ) ] );
        $NE = empty ( $elves [ ( $elf [ X ] + 1 ) . '|' . ( $elf [ Y ] - 1 ) ] );
        $E  = empty ( $elves [ ( $elf [ X ] + 1 ) . '|' .   $elf [ Y ]       ] );
        $SE = empty ( $elves [ ( $elf [ X ] + 1 ) . '|' . ( $elf [ Y ] + 1 ) ] );
        $S  = empty ( $elves [   $elf [ X ]   .     '|' . ( $elf [ Y ] + 1 ) ] );
        $SW = empty ( $elves [ ( $elf [ X ] - 1 ) . '|' . ( $elf [ Y ] + 1 ) ] );
        $W  = empty ( $elves [ ( $elf [ X ] - 1 ) . '|' .   $elf [ Y ]       ] );
        $NW = empty ( $elves [ ( $elf [ X ] - 1 ) . '|' . ( $elf [ Y ] - 1 ) ] );

        if ( $N && $NE && $E && $SE && $S && $SW && $W && $NW )
            continue;

        foreach ( $directions as $direction )
        {
            switch ( $direction )
            {
                case 'N':
                    if ( $NE && $NW && $N )
                    {
                        $elves [ $key ][ PROP ] = [ $elf [ Y ] - 1, $elf [ X ] ];
                        break 2;
                    }
                    break;
                case 'S':
                    if ( $SE && $SW && $S )
                    {
                        $elves [ $key ][ PROP ] = [ $elf [ Y ] + 1, $elf [ X ] ];
                        break 2;
                    }
                    break;
                case 'W':
                    if ( $NW && $SW && $W )
                    {
                        $elves [ $key ][ PROP ] = [ $elf [ Y ], $elf [ X ] - 1 ];
                        break 2;
                    }
                    break;
                case 'E':
                    if ( $NE && $SE && $E )
                    {
                        $elves [ $key ][ PROP ] = [ $elf [ Y ], $elf [ X ] + 1 ];
                        break 2;
                    }
                    break;
            }
        }

        if ( !empty ( $elves [ $key ][ PROP ] ) )
        {
            // conflict handling: only two elves can claim the same target,
            // so if one of them already did, we can immediately cancel the whole shebang
            if ( !empty ( $proposals [ $elves [ $key ][ PROP ][ Y ]][ $elves [ $key ][ PROP ][ X ]] ) )
            {
                unset ( $elves [ $proposals [ $elves [ $key ][ PROP ][ Y ]][ $elves [ $key ][ PROP ][ X ]]][ PROP ] );
                unset (          $proposals [ $elves [ $key ][ PROP ][ Y ]][ $elves [ $key ][ PROP ][ X ]]          );
            }
            else
                $proposals [ $elves [ $key ][ PROP ][ Y ]][ $elves [ $key ][ PROP ][ X ]] = $key;
        }
    }

    // no conflicts found: then move
    foreach ( $proposals as $y => $row )
        foreach ( $row as $x => $elf )
        {
            $elves [ $x . '|' . $y ] = [ $y, $x ];

            unset ( $elves [ $elf ] );
        }

    $directions[] = array_shift ( $directions );

    if ( $round == 10 )
        echo 'First part: ' . count_empty ( $elves ) . "\n";

    if ( empty ( $proposals ) )
    {
        echo 'Second part: ' . $round . "\n";
        break;
    }
}

function get_bounds ( $elves )
{
    $elf = end($elves);

    $min_x = $max_x = $elf [ X ];
    $min_y = $max_y = $elf [ Y ];

    foreach ( $elves as $elf )
    {
        $min_x = min ( $min_x, $elf [ X ] );
        $max_x = max ( $max_x, $elf [ X ] );

        $min_y = min ( $min_y, $elf [ Y ] );
        $max_y = max ( $max_y, $elf [ Y ] );
    }

    return [
        $min_x,
        $max_x,
        $min_y,
        $max_y
    ];
}

function count_empty ( $elves )
{
    list ( $min_x, $max_x, $min_y, $max_y ) = get_bounds ( $elves );

    $width  = $max_x - $min_x + 1;
    $height = $max_y - $min_y + 1;

    return $width * $height - count ( $elves );
}