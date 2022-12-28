<?php

function draw_map ( $map, $viewport )
{
    for ( $y = $viewport [ 'top' ]; $y <= $viewport [ 'bottom' ]; $y++ )
    {
        for ( $x = $viewport [ 'left' ]; $x <= $viewport [ 'right' ]; $x++ )
            echo $map [ $y ][ $x ] ?? '.';

        echo "\n";
    }
}

function createSVG ( $part, $map, $viewport )
{
    $width = $viewport [ 'right' ] - $viewport [ 'left' ];
    $height = $viewport [ 'bottom' ];

    $svg[] = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="' . $viewport [ 'left' ] . ' 0 ' . $width . ' ' . $height . '" width="' . ($width*4) . '" height="' . ($height*4) . '">';

    // walls
    foreach ( $map as $y => $point )
        foreach ( $point as $x => $content )
            {
                if ( $content == 'X' )
                    $svg[] = '<rect x="' . $x . '" y="' . $y . '" width="1" height="1" fill="black"/>';
                else
                    $svg[] = '<rect x="' . $x . '" y="' . $y . '" width="1" height="1" fill="orange"/>';
            }

    $svg[] = '</svg>';

    file_put_contents ( __DIR__ . '/output_' . $part . '.svg', implode ( "\n", $svg ) );
}