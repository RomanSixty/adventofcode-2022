<?php

$rounds = file ( __DIR__ . '/input.txt', FILE_IGNORE_NEW_LINES );

// there's 9 possible games... let's just list their outcomes
$games = [
    'A X' => 4,
    'A Y' => 8,
    'A Z' => 3,
    'B X' => 1,
    'B Y' => 5,
    'B Z' => 9,
    'C X' => 7,
    'C Y' => 2,
    'C Z' => 6
];

$points = 0;

foreach ( $rounds as $round )
    $points += $games [ $round ];

echo "First part: $points\n";

// alright... 9 different outcomes, still easy to precalculate
$games2 = [
    'A X' => 3,
    'A Y' => 4,
    'A Z' => 8,
    'B X' => 1,
    'B Y' => 5,
    'B Z' => 9,
    'C X' => 2,
    'C Y' => 6,
    'C Z' => 7
];

$points2 = 0;

foreach ( $rounds as $round )
    $points2 += $games2 [ $round ];

echo "Second part: $points2\n";