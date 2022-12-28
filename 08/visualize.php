<?php

function createSVG ( $part, $map, $best_tree = null )
{
    $width = strlen ( $map [ 0 ] );
    $height = count ( $map );

    $svg[] = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 ' . $width . ' ' . $height . '" width="' . ($width * 8) . '" height="' . ($height * 8) . '">';

    foreach ( $map as $y => $row )
        foreach ( str_split ( $row ) as $x => $tree )
        {
            // color coded elevation
            $r = 90 - $tree * 8;

            $svg[] = '<rect x="' . $x . '" y="' . $y . '" width="1" height="1" fill="hsl(120, ' . $r . '%, '. $r . '%)"/>';
        }

    if ( !empty ( $best_tree ) )
        $svg[] = '<circle cx="' . $best_tree [ 'x' ] . '.5" cy="' . $best_tree [ 'y' ] . '.5" r="1" fill="none" stroke="red" stroke-width=".5"/>';

    $svg[] = '</svg>';

    file_put_contents ( __DIR__ . '/output_' . $part . '.svg', implode ( "\n", $svg ) );
}