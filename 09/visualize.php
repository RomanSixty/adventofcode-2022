<?php

function createSVG ( $part, $tail )
{
    $trail = [];

    $steps = count ( $tail );

    $min_x = $max_x = $min_y = $max_y = null;

    foreach ( array_keys ( $tail ) as $coords )
    {
        list ( $x, $y ) = explode ( '|', $coords );

        if ( $min_x === null )
        {
            $min_x = $max_x = $x;
            $min_y = $max_y = $y;
        }
        else
        {
            $min_x = min ( $min_x, $x );
            $max_x = max ( $max_x, $x );

            $min_y = min ( $min_y, $y );
            $max_y = max ( $max_y, $y );
        }

        $trail[] = [ $x, $y ];
    }

    $width = $max_x - $min_x + 3;
    $height = $max_y - $min_y + 3;

    $svg[] = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="' . ($min_x - 1) . ' ' . ($min_y - 1) . ' ' . $width . ' ' . $height . '" width="' . ($width*3) . '" height="' . ($height*3) . '">';

    $hue = 0;

    $hue_step = round ( 250 / $steps, 2);

    foreach ( $trail as $t )
    {
        $hue += $hue_step;

        $svg[] = '<rect x="' . $t [ 0 ] . '" y="' . $t [ 1 ] . '" width="1" height="1" fill="hsl(' . $hue . ', 100%, 40%)"/>';
    }

    $svg[] = '</svg>';

    file_put_contents ( __DIR__ . '/output_' . $part . '.svg', implode ( "\n", $svg ) );
}