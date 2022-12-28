<?php

if ( empty ( $argv [ 1 ] ) )
    die ( "Usage: php profile.php <day>\n" );

$filename = __DIR__ . '/' . str_pad ( (int) $argv [ 1 ], 2, '0', STR_PAD_LEFT ) . '/solve.php';

if ( !file_exists ( $filename ) )
    die ( "This solition does not exist - yet?\n" );

memory_reset_peak_usage();
$start_time = microtime(true);

include $filename;

echo "\n";
echo 'Execution time: ' . round ( microtime ( true ) - $start_time, 4) . " seconds\n";
echo '   Peak memory: ' . round ( memory_get_peak_usage() / pow ( 2, 20 ), 4 ) . " MiB\n";