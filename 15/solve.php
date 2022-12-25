<?php

$input = file ( __DIR__ . '/input.txt', FILE_IGNORE_NEW_LINES );

$sensors = $beacons = [];

foreach ( $input as $line )
{
    preg_match ( '~^Sensor at x=([0-9-]+), y=([0-9-]+): closest beacon is at x=([0-9-]+), y=([0-9-]+)$~', $line, $matches );

    $distance = abs ( $matches [ 1 ] - $matches [ 3 ] ) + abs ( $matches [ 2 ] - $matches [ 4 ] );

    $sensors[] = [ 'x' => $matches [ 1 ], 'y' => $matches [ 2 ], 'distance' => $distance ];
    $beacons[] = [ 'x' => $matches [ 3 ], 'y' => $matches [ 4 ] ];
}

function check_row_coverage ( $y, $limit_low = null, $limit_high = null )
{
    global $sensors, $beacons;

    $coverage_areas = [];

    // which sensors even have a chance to cover this row?
    foreach ( $sensors as $sensor )
    {
        $diff_y = abs ( $sensor [ 'y' ] - $y );

        if ( $diff_y <= $sensor [ 'distance' ] )
        {
            $remaining = $sensor [ 'distance' ] - $diff_y;

            $coverage_areas[] = [
                'from' => ( $limit_low  === null ) ? $sensor [ 'x' ] - $remaining : max ( $limit_low,  $sensor [ 'x' ] - $remaining ),
                'to'   => ( $limit_high === null ) ? $sensor [ 'x' ] + $remaining : min ( $limit_high, $sensor [ 'x' ] + $remaining )
            ];
        }
    }

    // merge areas
    do
    {
        $area_count = count ( $coverage_areas );

        foreach ( $coverage_areas as $key => &$area )
            for ( $key2 = $key + 1; $key2 < count ( $coverage_areas ); $key2++ )
            {
                // completely enclosed
                if ( $area [ 'from' ] <= $coverage_areas [ $key2 ][ 'from' ] && $area [ 'to' ] >= $coverage_areas [ $key2 ][ 'to' ] )
                {
                    array_splice ( $coverage_areas, $key2, 1 );
                    continue 3;
                }
                elseif ( $coverage_areas [ $key2 ][ 'from' ] <= $area [ 'from' ] && $coverage_areas [ $key2 ][ 'to' ] >= $area [ 'to' ] )
                {
                    $area [ 'from' ] = $coverage_areas [ $key2 ][ 'from' ];
                    $area [ 'to'   ] = $coverage_areas [ $key2 ][ 'to'   ];

                    array_splice ( $coverage_areas, $key2, 1 );
                    continue 3;
                }

                // expanding on either side
                elseif ( $area [ 'from' ] <= $coverage_areas [ $key2 ][ 'from' ] && $area [ 'to' ] + 1 >= $coverage_areas [ $key2 ][ 'from' ] )
                {
                    $area [ 'to' ] = $coverage_areas [ $key2 ][ 'to' ];
                    array_splice ( $coverage_areas, $key2, 1 );
                    continue 3;
                }
                elseif ( $area [ 'from' ] - 1 <= $coverage_areas [ $key2 ][ 'to' ] && $area [ 'to' ] >= $coverage_areas [ $key2 ][ 'to' ] )
                {
                    $area [ 'from' ] = $coverage_areas [ $key2 ][ 'from' ];
                    array_splice ( $coverage_areas, $key2, 1 );
                    continue 3;
                }
            }
    }
    while ( $area_count > 2 && $area_count > count ( $coverage_areas ) );

    // only first part: positions of actual beacons do not count
    if ( $limit_low === null && $limit_high === null )
        foreach ( $beacons as $beacon )
            if ( $beacon [ 'y' ] == $y )
                foreach ( $coverage_areas as $key => &$area )
                {
                    // first or last spot: resize area (or remove if area_size is 1)
                    if ( $area [ 'from' ] == $area [ 'to' ] && $beacon [ 'x' ] == $area [ 'from' ] )
                        array_splice ( $coverage_areas, $key, 1 );
                    elseif ( $beacon [ 'x' ] == $area [ 'from' ] )
                        $area [ 'from' ]++;
                    elseif ( $beacon [ 'x' ] == $area [ 'to' ] )
                        $area [ 'to' ]--;

                    // somewhere in the middle: split area
                    elseif ( $beacon [ 'x' ] >= $area [ 'from' ] && $beacon [ 'x' ] <= $area [ 'to' ] )
                    {
                        $coverage_areas[] = [
                            'from' => $beacon [ 'x' ] + 1,
                            'to'   => $area [ 'to' ]
                        ];

                        $area [ 'to' ] = $beacon [ 'x' ] - 1;
                    }
                }

    return $coverage_areas;
}

$count = 0;

foreach ( check_row_coverage ( 2000000 ) as $area )
    $count += $area [ 'to' ] - $area [ 'from' ] + 1; // limits are included, hence +1

echo "First part: $count\n";

// second part: this is messy
// currently it takes about 2 minutes on my machine
// maybe I'll optimize some time in the future

$limit_low  = 0;
$limit_high = 4000000;

$max_coverage = $limit_high - $limit_low;

for ( $y = $limit_low; $y <= $limit_high; $y++ )
{
    $coverage_on_row = check_row_coverage ( $y, $limit_low, $limit_high );

    // first entry with two seperate areas has to be the one...
    // well actually™ could also be a single area with size one less
    // than $limit_high if the very first or last column are empty
    // but see if I care...
    if ( count ( $coverage_on_row ) > 1 )
    {
        foreach ( $coverage_on_row as $area )
            if ( $area [ 'from' ] == 0 )
                $x = $area [ 'to' ] + 1;

        echo 'Second part: ' . ( $x * $limit_high + $y ) . "\n";

        break;
    }
}