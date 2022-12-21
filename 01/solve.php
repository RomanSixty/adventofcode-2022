<?php

$input = file_get_contents ( __DIR__ . '/input.txt' );

$calories_by_elf = explode ( "\n\n", $input );

$sums = [];

foreach ( $calories_by_elf as $elf_nr => $cbf )
    $sums[] = array_sum ( explode ( "\n", $cbf ) );

echo 'First part: ' . max ( $sums ) . "\n";

rsort ( $sums );

$topthree = 0;

for ( $i = 0; $i < 3; $i++ )
    $topthree += $sums [ $i ];

echo "Second part: $topthree\n";