<?php

function draw ( $rock, $cave )
{
    global $rocks;

    $rockshape = $rocks [ $rock [ 'shape' ]];

    for ( $y = count ( $cave ) + count ( $rockshape ) + 4; $y >= 0; $y-- )
    {
        echo str_pad ( $y, 4, ' ' ) . ' ';

        if ( $y >= $rock [ 'bottom' ] && $y < $rock [ 'bottom' ] + count ( $rockshape ) )
            $rockslice = array_pop ( $rockshape );
        else
            $rockslice = [];

        for ( $x = 0; $x < 9; $x++ )
        {
            if ( $x == 0 || $x == 8 )
                echo '|';

            elseif ( isset ( $cave [ $y ][ $x ] ) )
                echo '#';
            elseif ( isset ( $rockslice [ $x - $rock [ 'left' ]] ) )
                echo '@';
            else
                echo ' ';
        }

        echo "\n";
    }

    echo "     +-------+\n\n";
}