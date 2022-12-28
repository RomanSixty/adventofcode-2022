<?php

//include __DIR__ . '/visualize.php';

$input = file ( __DIR__ . '/input.txt', FILE_IGNORE_NEW_LINES );

$visible_trees = [];

$max_row = count ( $input ) - 1;
$max_col = strlen ( $input [ 0 ] ) - 1;

// we save the highest found tree from each direction so we just have
// to compare against those instead of every tree in all directions

$highest_trees = [ 'row' => [], 'col' => [] ];

for ( $i = 0; $i <= $max_col; $i++ )
{
    $highest_trees [ 'col' ][ $i ][ 'top'    ] = -1;
    $highest_trees [ 'col' ][ $i ][ 'bottom' ] = -1;
}
for ( $i = 0; $i <= $max_row; $i++ )
{
    $highest_trees [ 'row' ][ $i ][ 'left'  ] = -1;
    $highest_trees [ 'row' ][ $i ][ 'right' ] = -1;
}

// also we collect trees by height for the second part
$trees_by_height = [];

// first we can only check from top and left
for ( $y = 0; $y <= $max_row; $y++ )
    for ( $x = 0; $x <= $max_col; $x++ )
    {
        if ( $x > 0 && $x < $max_col && $y > 0 && $y < $max_row )
            $trees_by_height [ $input [ $y ][ $x ]][] = [ 'x' => $x, 'y' => $y ];

        if ( $highest_trees [ 'row' ][ $y ][ 'left' ] < $input [ $y ][ $x ] )
        {
            $visible_trees [ $x . '|' . $y ] = true;
            $highest_trees [ 'row' ][ $y ][ 'left' ] = $input [ $y ][ $x ];
        }

        if ( $highest_trees [ 'col' ][ $x ][ 'top' ] < $input [ $y ][ $x ] )
        {
            $visible_trees [ $x . '|' . $y ] = true;
            $highest_trees [ 'col' ][ $x ][ 'top' ] = $input [ $y ][ $x ];
        }
    }

// now the other way around
for ( $y = $max_row; $y >= 0; $y-- )
    for ( $x = $max_col; $x >= 0; $x-- )
    {
        if ( $highest_trees [ 'row' ][ $y ][ 'right' ] < $input [ $y ][ $x ] )
        {
            $visible_trees [ $x . '|' . $y ] = true;
            $highest_trees [ 'row' ][ $y ][ 'right' ] = $input [ $y ][ $x ];
        }

        if ( $highest_trees [ 'col' ][ $x ][ 'bottom' ] < $input [ $y ][ $x ] )
        {
            $visible_trees [ $x . '|' . $y ] = true;
            $highest_trees [ 'col' ][ $x ][ 'bottom' ] = $input [ $y ][ $x ];
        }
    }

echo 'First part: ' . count ( $visible_trees ) . "\n";

// we collected all trees by height, so we just have to compare tall
// trees to ones of equal or even larger sizes, that share one of both
// coordinates
// we can assume the distance to the border in all other cases

ksort ( $trees_by_height );

$max_scenic_score = 0;

$taller_trees = [];

$best_tree = null;

while ( $tall_trees = array_pop ( $trees_by_height ) )
{
    $taller_trees = array_merge ( $taller_trees, $tall_trees );

    foreach ( $tall_trees as $tree )
    {
        $distances = [
            'left'   => $tree [ 'x' ],
            'top'    => $tree [ 'y' ],
            'right'  => $max_row - $tree [ 'x' ],
            'bottom' => $max_col - $tree [ 'y' ]
        ];

        foreach ( $taller_trees as $compare )
        {
            // don't compare with myself
            if ( $tree [ 'x' ] == $compare [ 'x' ] && $tree [ 'y' ] == $compare [ 'y' ] )
                continue;

            if ( $tree [ 'x' ] == $compare [ 'x' ] )
            {
                $diff_y = $tree [ 'y' ] - $compare [ 'y' ];

                if ( $diff_y > 0 )
                    $distances [ 'top' ] = min ( $distances [ 'top' ], $diff_y );
                else
                    $distances [ 'bottom' ] = min ( $distances [ 'bottom' ], abs ( $diff_y ) );
            }

            if ( $tree [ 'y' ] == $compare [ 'y' ] )
            {
                $diff_x = $tree [ 'x' ] - $compare [ 'x' ];

                if ( $diff_x > 0 )
                    $distances [ 'left' ] = min ( $distances [ 'left' ], $diff_x );
                else
                    $distances [ 'right' ] = min ( $distances [ 'right' ], abs ( $diff_x ) );
            }
        }

        $tree_score = array_product ( $distances );

        if ( $tree_score > $max_scenic_score )
        {
            $best_tree = $tree;
            $max_scenic_score = $tree_score;
        }
    }
}

echo "Second part: $max_scenic_score\n";

//createSVG ( 1, $input );
//createSVG ( 2, $input, $best_tree );