<?php

$input = file ( __DIR__ . '/input.txt', FILE_IGNORE_NEW_LINES );

$X = 1;
$cycles = [ $X ];

foreach ( $input as $instruction )
{
    if ( $instruction == 'noop' )
        $cycles[] = $X;
    else
    {
        $cycles[] = $X;

        list ( $operation, $value ) = explode ( ' ', $instruction );

        $X += $value;

        $cycles[] = $X;
    }
}

$signal_strength_sum = 0;

foreach ( [20, 60, 100, 140, 180, 220] as $cycle )
    $signal_strength_sum += $cycle * $cycles [ $cycle - 1 ];

echo "First part: $signal_strength_sum\n";

echo "Second part:";

for ( $pixel = 0; $pixel < 240; $pixel++ )
{
    if ( $pixel % 40 == 0 )
        echo "\n";

    $pixel_in_line = $pixel % 40;

    if (    $cycles [ $pixel ] >= $pixel_in_line - 1
         && $cycles [ $pixel ] <= $pixel_in_line + 1 )
        echo '█';
    else
        echo '∙';
}

echo "\n";