<?php

$input = file ( __DIR__ . '/input.txt', FILE_IGNORE_NEW_LINES );

$sensors = $beacons = [];

const FROM = 0;
const   TO = 1;
const    X = 0;
const    Y = 1;
const DIST = 2;

foreach ( $input as $line )
{
    preg_match ( '~^Sensor at x=([0-9-]+), y=([0-9-]+): closest beacon is at x=([0-9-]+), y=([0-9-]+)$~', $line, $matches );

    $distance = abs ( $matches [ 1 ] - $matches [ 3 ] ) + abs ( $matches [ 2 ] - $matches [ 4 ] );

    $sensors[] = [ $matches [ 1 ], $matches [ 2 ], $distance ];

    $beacons [ $matches [ 4 ]][ $matches [ 3 ]] = true;
}

function check_row_coverage ( $y, $limit_low = null, $limit_high = null )
{
    global $sensors;

    $coverage_areas = [];

    // which sensors even have a chance to cover this row?
    foreach ( $sensors as $sensor )
    {
        $diff_y = abs ( $sensor [ Y ] - $y );

        if ( $diff_y <= $sensor [ DIST ] )
        {
            $remaining = $sensor [ DIST ] - $diff_y;

            $from = $limit_low  === null ? $sensor [ X ] - $remaining : max ( $limit_low,  $sensor [ X ] - $remaining );
            $to   = $limit_high === null ? $sensor [ X ] + $remaining : min ( $limit_high, $sensor [ X ] + $remaining );

            foreach ( $coverage_areas as $key => $area )
            {
                // already found one that encloses us completely
                if ( $area [ FROM ] <= $from && $area [ TO ] >= $to )
                    continue 2;

                // or is completely enclosed by us
                elseif ( $from <= $area [ FROM ] && $to >= $area [ TO ] )
                {
                    unset ( $coverage_areas [ $key ] );

                    break;
                }
            }

            if ( isset ( $coverage_areas [ $from ] ) )
                $coverage_areas [ $from ][ TO ] = max ( $coverage_areas [ $from ][ TO ], $to );
            else
                $coverage_areas [ $from ] = [ $from, $to ];
        }
    }

    ksort ( $coverage_areas );

    return $coverage_areas;
}

function count_coverage ( $coverage )
{
    $latest = [];
    $sum = 0;

    foreach ( $coverage as $range )
    {
        if ( empty ( $latest ) )
            $latest = $range;
        else
        {
            if ( $range [ FROM ] <= $latest [ TO ] )
                $latest [ TO ] = $range [ TO ];
            else
            {
                $sum += $latest [ TO ] - $latest [ FROM ] + 1;

                $latest = $range;
            }
        }
    }

    if ( !empty ( $latest ) )
        $sum += $latest [ TO ] - $latest [ FROM ] + 1;

    return $sum;
}

$row_to_check = 2000000;

$count = count_coverage ( check_row_coverage ( $row_to_check ) );

// any beacons on that row? then subtract those
if ( !empty ( $beacons [ $row_to_check ] ) )
    $count -= count ( $beacons [ $row_to_check ] );

echo "First part: $count\n";

// second part: this is messy
// currently it takes about 2 minutes on my machine
// maybe I'll optimize some time in the future

$limit_low  = 0;
$limit_high = 4000000;

$max_coverage = $limit_high - $limit_low + 1;

for ( $y = $limit_low; $y <= $limit_high; $y++ )
{
    $coverage = check_row_coverage ( $y, $limit_low, $limit_high );

    if ( count_coverage ( $coverage ) < $max_coverage )
    {
        $latest_x = $limit_low;

        foreach ( $coverage as $range )
        {
            if ( $range [ FROM ] - 1 > $latest_x )
            {
                $x = $range [ FROM ] - 1;
                echo 'Second part: ' . ( $x * 4000000 + $y ) . "\n";

                break 2;
            }
            else
                $latest_x = $range [ TO ];
        }
    }
}