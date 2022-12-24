<?php

$input = file ( __DIR__ . '/input.txt', FILE_IGNORE_NEW_LINES );

$positions_of_tail = [];

$rope = [];

for ( $length = 0; $length < 10; $length++ )
    $rope[] = [ 0, 0 ]; // X|Y

foreach ( $input as $move )
{
    list ( $direction, $steps ) = explode ( ' ', $move );

    for ( $i = 0; $i < $steps; $i++ )
    {
        // move head
        switch ( $direction )
        {
            case 'L': $rope [ 0 ][ 0 ]--; break;
            case 'R': $rope [ 0 ][ 0 ]++; break;
            case 'D': $rope [ 0 ][ 1 ]--; break;
            case 'U': $rope [ 0 ][ 1 ]++; break;
        }

        // pull tail
        for ( $length = 1; $length < 10; $length++ )
        {
            $prev = $length - 1;

            // performance: if two consecutive knots overlap, we can break here
            // since the move has no effect on knots further down the rope

            if (    $rope [ $prev ][ 0 ] == $rope [ $length ][ 0 ]
                 && $rope [ $prev ][ 1 ] == $rope [ $length ][ 1 ] )
                break;

            // horizontally
            if ( $rope [ $prev ][ 0 ] == $rope [ $length ][ 0 ] )
            {
                $distance = $rope [ $prev ][ 1 ] - $rope [ $length ][ 1 ];

                if ( $distance == -2 )
                    $rope [ $length ][ 1 ]--;
                elseif ( $distance == 2 )
                    $rope [ $length ][ 1 ]++;
            }

            //vertically
            elseif ( $rope [ $prev ][ 1 ] == $rope [ $length ][ 1 ] )
            {
                $distance = $rope [ $prev ][ 0 ] - $rope [ $length ][ 0 ];

                if ( $distance == -2 )
                    $rope [ $length ][ 0 ]--;
                elseif ( $distance == 2 )
                    $rope [ $length ][ 0 ]++;
            }

            // diagonally
            else
            {
                $distance_x = $rope [ $prev ][ 0 ] - $rope [ $length ][ 0 ];
                $distance_y = $rope [ $prev ][ 1 ] - $rope [ $length ][ 1 ];

                if ( abs ( $distance_y ) == 2 )
                {
                    $rope [ $length ][ 1 ] += $distance_y/2;

                    if ( abs ( $distance_x ) == 1 )
                        $rope [ $length ][ 0 ] = $rope [ $prev ][ 0 ];
                }

                if ( abs ( $distance_x ) == 2 )
                {
                    $rope [ $length ][ 0 ] += $distance_x/2;

                    if ( abs ( $distance_y ) == 1 )
                        $rope [ $length ][ 1 ] = $rope [ $prev ][ 1 ];
                }
            }
        }

        // collect tail positions for the two rope lengths
        $positions_of_tail [ 1 ][ $rope [ 1 ][ 0 ] . '|' . $rope [ 1 ][ 1 ] ] = true;
        $positions_of_tail [ 9 ][ $rope [ 9 ][ 0 ] . '|' . $rope [ 9 ][ 1 ] ] = true;
    }
}

echo 'First part: ' .  count ( $positions_of_tail [ 1 ] ) . "\n";
echo 'Second part: ' . count ( $positions_of_tail [ 9 ] ) . "\n";