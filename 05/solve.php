<?php

$input = file_get_contents ( __DIR__ . '/input.txt' );

list ( $configuration, $procedure ) = explode ( "\n\n", $input );

// get the stacks as strings of characters

$configuration = explode ( "\n", $configuration );

array_pop ( $configuration ); // only numbers... we can infer those

$stacks1 = [];

while ( $row = array_pop ( $configuration ) )
{
    $crates = str_split ( $row, 4 );

    foreach ( $crates as $i => $crate )
        if ( preg_match ( '~^\[([A-Z])]~', $crate, $match ) )
            $stacks1 [ $i ][] = $match [ 1 ];
}

// now parse the tasks on two copies of the stacks

$stacks2 = $stacks1;

$tasks = [];

$procedure = explode ( "\n", $procedure );

foreach ( $procedure as $task )
{
    preg_match ( '~^move (\d+) from (\d+) to (\d+)~', $task, $matches );

    $crates_to_move = [];

    // offset stack numbers by 1 for obvs. reasons
    for ( $i = 0; $i < $matches [ 1 ]; $i++ )
    {
        // part 1: each crate individually
        $stacks1 [ $matches [ 3 ] - 1 ][] = array_pop ( $stacks1 [ $matches [ 2 ] - 1 ] );

        // part 2: move crate clusters
        $crates_to_move[] = array_pop ( $stacks2 [ $matches [ 2 ] - 1 ] );
    }

    while ( $ctm = array_pop ( $crates_to_move ) )
        $stacks2 [ $matches [ 3 ] - 1 ][] = $ctm;
}

$top_crates1 = getTopCrates ( $stacks1 );
$top_crates2 = getTopCrates ( $stacks2 );

echo "First part: $top_crates1\n";
echo "Second part: $top_crates2\n";

function getTopCrates ( $stacks )
{
    $top_crates = '';

    foreach ( $stacks as $stack )
        $top_crates .= end ( $stack );

    return $top_crates;
}