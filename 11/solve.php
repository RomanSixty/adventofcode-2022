<?php

$input = file_get_contents ( __DIR__ . '/input.txt' );

$monkeys = [];

foreach ( explode ( "\n\n", $input ) as $monkey )
{
    preg_match ( '~^Monkey (\d+):.*Starting items: ([0-9, ]+).*Operation: new = (.*)\n.*Test: divisible by (\d+).*true: throw to monkey (\d+).* false: throw to monkey (\d+)$~s', $monkey, $matches );

    $monkeys [ $matches [ 1 ]] = [
        'items' => explode ( ', ', $matches [ 2 ] ),
        'operation' => '$item = ' . str_replace ( 'old', '$item', $matches [ 3 ] ) . ';',
        'modulo' => $matches [ 4 ],
        'true' => $matches [ 5 ],
        'false' => $matches [ 6 ],
        'inspections' => 0
    ];
}

function business ( $monkeys, $part )
{
    // in part 2 worry levels get very big very soon
    // but we need to keep them low
    // since all monkey's modulos are prime numbers
    // we can multiply them to get a combined modulo
    // using this we keep the numbers manageable
    // resulting worry levels are not real, but the
    // corresponding operations stay the same

    $fake_relief = 1;

    foreach ( $monkeys as $monkey )
        $fake_relief *= $monkey [ 'modulo' ];

    $max_rounds = $part == 1 ? 20 : 10000;

    $inspections = [];

    for ( $round = 0; $round < $max_rounds; $round++ )
        foreach ( $monkeys as &$monkey )
        {
            while ( $item = array_shift ( $monkey [ 'items' ] ) )
            {
                $monkey [ 'inspections' ]++;

                eval ( $monkey [ 'operation' ] );

                if ( $part == 1 )
                    $item = intval ( floor ( $item / 3 ) );
                else
                    $item %= $fake_relief;

                if ( $item % $monkey [ 'modulo' ] == 0 )
                    $monkeys [ $monkey [ 'true' ]][ 'items' ][] = $item;
                else
                    $monkeys [ $monkey [ 'false' ]][ 'items' ][] = $item;
            }

            if ( $round == $max_rounds - 1 )
                $inspections[] = $monkey [ 'inspections' ];
        }

    sort ( $inspections );

    return array_pop ( $inspections ) * array_pop ( $inspections );
}

echo 'First part: '  . business ( $monkeys, 1 ) . "\n";
echo 'Second part: ' . business ( $monkeys, 2 ) . "\n";