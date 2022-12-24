<?php

$input = file ( __DIR__ . '/input.txt',  FILE_IGNORE_NEW_LINES );

$cur_dir = '/';
$directories = [];

foreach ( $input as $line )
{
    // don't need that
    if ( $line == '$ ls' || str_starts_with ( $line, 'dir ' ) )
        continue;

    // add directories to stack, array key is the directory path
    elseif ( preg_match ( '~^\$ cd (.+)$~', $line, $matches ) )
    {
        if ( $matches [ 1 ] == '..' )
            $cur_dir = preg_replace ( '~/[^/]+/$~', '/', $cur_dir );
        elseif ( $matches [ 1 ] == '/' )
            $cur_dir = '/';
        else
            $cur_dir = $cur_dir . $matches [ 1 ] . '/';

        if ( empty ( $directories [ $cur_dir ] ) )
            $directories [ $cur_dir ] = 0;
    }

    // everything else must be a file size
    else
    {
        list ( $filesize, $filename ) = explode ( ' ', $line );

        $dir = $cur_dir;

        // add file size to directory and all of its parents
        $directories [ $dir ] += $filesize;

        while ( $dir != '/' )
        {
            $dir = preg_replace ( '~/[^/]+/$~', '/', $dir );

            $directories [ $dir ] += $filesize;
        }
    }
}

// now both parts in one
// iterate through the sorted directories in ascending order and pull the correct info

$part1_sum = $part2_sum = 0;

$space_available         = 70000000 - $directories [ '/' ];
$additional_space_needed = 30000000 - $space_available;

sort ( $directories );

foreach ( $directories as $size )
{
    if ( $size < 100000 )
        $part1_sum += $size;

    if ( empty ( $part2_sum ) && $size >= $additional_space_needed )
        $part2_sum = $size;

    if ( $size >= 100000 && !empty ( $part2_sum ) )
        break;
}

echo "First part: $part1_sum\n";
echo "Second part: $part2_sum\n";