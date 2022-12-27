<?php

$input = file ( __DIR__ . '/input.txt', FILE_IGNORE_NEW_LINES );

$chain_length = count ( $input );

// position of value 0, so we don't have to look for it later
$index_0 = null;

/**
 * let's build a circular linked list using a simple array
 * all array keys stay the same, but each item knows the keys of its neighbors
 * this way we only touch 5 elements with each movement:
 * - 2 neighbors of current element are linked together
 * - 2 new neighbors someplace else are linked to current element
 * - 1 current element is linked to its new neighbors
 * NOTE: maybe SplDoublyLinkedList would also work...
 * but I'm too lazy to figure it out right now
 */
function build_chain ( $input, $decryption_key = 1 )
{
    global $index_0, $chain_length;

    $chain = [];

    foreach ( $input as $order => $number )
    {
        if ( $number == 0 )
            $index_0 = $order;

        // precalculate the number of moves
        // vastly reduces time for part 2

        $number *= $decryption_key;

        $moves = $number % ($chain_length - 1);

        // make everything positive, so we don't have to
        // deal with all the off by 1 BS

        if ( $moves < 0 )
            $moves += $chain_length - 1;

        $chain[] = [
            'order'  => $order,
            'number' => $number,
            'moves'  => $moves,
            'prev'   => $order == 0                 ? $chain_length - 1 : $order - 1,
            'next'   => $order == $chain_length - 1 ? 0                 : $order + 1
        ];
    }

    return $chain;
}

function mix_and_sum ( $chain, $times = 1 )
{
    global $index_0, $chain_length;

    for ( $i = 0; $i < $times; $i++ )
        for ( $order = 0; $order < $chain_length; $order++ )
        {
            $cur = $chain [ $order ];

            // now where shall we put it?
            if ( $cur [ 'moves' ] == 0 )
                continue;
            else
            {
                $next_item = $cur [ 'next' ];

                for ( $step = 0; $step < $cur [ 'moves' ]; $step++ )
                    $next_item = $chain [ $next_item ][ 'next' ];
            }

            // join previous neighbors together
            $chain [ $cur [ 'prev' ]][ 'next' ] = $cur [ 'next' ];
            $chain [ $cur [ 'next' ]][ 'prev' ] = $cur [ 'prev' ];

            // fit myself between my new neighbors
            $chain [ $order ][ 'prev' ] = $chain [ $next_item ][ 'prev' ];
            $chain [ $order ][ 'next' ] = $chain [ $next_item ][ 'order' ];

            $chain [ $chain [ $next_item ][ 'prev' ]][ 'next' ] = $cur [ 'order' ];
                     $chain [ $next_item ][ 'prev' ]            = $cur [ 'order' ];
        }

    $sum = 0;

    $cur_key = $index_0;

    for ( $i = 1; $i <= 3000; $i++ )
    {
        $cur_key = $chain [ $cur_key ][ 'next' ];

        if ( in_array ( $i, [ 1000, 2000, 3000 ] ) )
            $sum += $chain [ $cur_key ][ 'number' ];
    }

    return $sum;
}

echo 'First part: ' . mix_and_sum ( build_chain ( $input ) ) . "\n";
echo 'Second part: ' . mix_and_sum ( build_chain ( $input, 811589153 ), 10 ) . "\n";