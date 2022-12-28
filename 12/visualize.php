<?php

function createSVG ( $part, $map, $path = [] )
{
    $width = count ( $map [ 0 ] );
    $height = count ( $map );

    $svg[] = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 ' . $width . ' ' . $height . '" width="' . ($width * 8) . '" height="' . ($height * 8) . '">';

    foreach ( $map as $y => $point )
        foreach ( $point as $x => $elevation )
        {
            if ( !empty ( $path ) && reached_target ( [ $x, $y ], $path ) )
                $svg[] = '<rect x="' . $x . '" y="' . $y . '" width="1" height="1" fill="red"/>';
            else
            {
                // color coded elevation
                $r = $elevation - 97;
                $r *= 10;

                $svg[] = '<rect x="' . $x . '" y="' . $y . '" width="1" height="1" fill="rgb(' . $r . ', ' . $r . ', ' . $r . ')"/>';
            }
        }

    $svg[] = '</svg>';

    file_put_contents ( __DIR__ . '/output_' . $part . '.svg', implode ( "\n", $svg ) );
}