<?php

$rucksacks = file ( __DIR__ . '/input.txt' );

$prio_sum = 0;

foreach ( $rucksacks as &$rucksack )
{
    $rucksack = trim ( $rucksack );
    $compartment_size = strlen ( $rucksack ) / 2;

    $compartments = explode ( '+', chunk_split ( $rucksack, $compartment_size, '+' ) );

    // items are only counted once, even if they appear multiple times in the same compartment
    // hence we unify...

    $content = str_split ( $compartments [ 0 ] );

    $content = array_unique ( $content );

    foreach ( $content as $item )
        if ( strpos ( $compartments [ 1 ], $item ) !== false )
            $prio_sum += getPriority ( $item );
}

function getPriority ( $item )
{
    $val = ord ( $item );

    return ( $val > 90 ) ? $val - 96 : $val - 38;
}

echo "First part: $prio_sum\n";

$badge_sum = 0;

while ( count ( $rucksacks ) )
{
    $group = [
        array_pop ( $rucksacks ),
        array_pop ( $rucksacks ),
        array_pop ( $rucksacks )
    ];

    // sort by rucksack size to compare the fewest items

    usort ( $group, function ( $a, $b ){
        return strlen ( $a ) > strlen ( $b );
    });

    for ( $i = 0; $i < strlen ( $group [ 0 ] ); $i++ )
    {
        if (    strpos ( $group [ 1 ], $group [ 0 ][ $i ] ) !== false
             && strpos ( $group [ 2 ], $group [ 0 ][ $i ] ) !== false )
        {
            $badge_sum += getPriority ( $group [ 0 ][ $i ] );
            break;
        }
    }
}

echo "Second part: $badge_sum\n";