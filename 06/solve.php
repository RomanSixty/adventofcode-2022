<?php

$input = str_split ( file_get_contents ( __DIR__ . '/input.txt' ) );

for ( $markerpos1 = 4; $markerpos1 < count ( $input ); $markerpos1++ )
{
    if ( checkForMarker ( array_slice ( $input, $markerpos1 - 4, 4 ) ) )
    {
        echo "First part: $markerpos1\n";
        break;
    }
}

for ( $markerpos2 = 14; $markerpos2 < count ( $input ); $markerpos2++ )
{
    if ( checkForMarker ( array_slice ( $input, $markerpos2 - 14, 14 ) ) )
    {
        echo "Second part: $markerpos2\n";
        break;
    }
}

function checkForMarker ( $window )
{
    $uniq = array_unique ( $window );

    return count ( $uniq ) == count ( $window );
}