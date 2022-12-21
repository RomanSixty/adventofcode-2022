<?php

$input = file ( __DIR__ . '/input.txt' );

$monkeys_done = $monkeys_todo = [];

foreach ( $input as $line )
{
    list ( $name, $yell ) = explode ( ': ', $line );

    if ( is_numeric ( $yell ) )
        $monkeys_done [ $name ] = intval ( $yell );
    else
    {
        preg_match ( '~^([a-z]{4}) (.) ([a-z]{4})~', $yell, $matches );

        $monkeys_todo [ $name ] = [
            $matches [ 1 ], // first monkey name
            $matches [ 2 ], // operator
            $matches [ 3 ]  // second monkey name
        ];
    }
}

function first_part ( $monkeys_todo, $monkeys_done )
{
    while ( count ( $monkeys_todo ) )
        foreach ( $monkeys_todo as $name => $calculation )
            if ( !empty ( $monkeys_done [ $calculation [ 0 ]] ) && !empty ( $monkeys_done [ $calculation [ 2 ]] ) )
            {
                // don't try this at home
                eval ( '$res = ' . $monkeys_done [ $calculation [ 0 ]] . $calculation [ 1 ] . $monkeys_done [ $calculation [ 2 ]] . ';' );

                $monkeys_done [ $name ] = $res;

                unset ( $monkeys_todo [ $name ] );
            }

    echo "First part: $monkeys_done[root]\n";
}

function second_part ( $monkeys_todo, $monkeys_done )
{
    unset ( $monkeys_done [ 'humn' ] );

    // first let's see how far we can get with known numbers independent of our own input

    $last_count = count ( $monkeys_todo ) + 1;

    while ( count ( $monkeys_todo ) < $last_count )
    {
        $last_count = count ( $monkeys_todo );

        foreach ( $monkeys_todo as $name => $calculation )
            if ( !empty ( $monkeys_done [ $calculation [ 0 ]] ) && !empty ( $monkeys_done [ $calculation [ 2 ]] ) )
            {
                // don't try this at home
                eval ( '$res = ' . $monkeys_done [ $calculation [ 0 ]] . $calculation [ 1 ] . $monkeys_done [ $calculation [ 2 ]] . ';' );

                $monkeys_done [ $name ] = $res;

                unset ( $monkeys_todo [ $name ] );
            }
    }

    // assuming that only one monkey depends on our own (humn) input
    // one of the two components of root's equation should already be known...
    // so we just have to descend the other direction inverting the equations

    if ( !empty ( $monkeys_done [ $monkeys_todo [ 'root' ][ 0 ]] ) )
    {
        $target = $monkeys_done [ $monkeys_todo [ 'root' ][ 0 ]];

        $monkey_name = $monkeys_todo [ 'root' ][ 2 ];
    }
    else
    {
        $target = $monkeys_done [ $monkeys_todo [ 'root' ][ 2 ]];

        $monkey_name = $monkeys_todo [ 'root' ][ 0 ];
    }

    while ( $monkey_name != 'humn' )
    {
        if ( !empty ( $monkeys_done [ $monkeys_todo [ $monkey_name ][ 0 ]] ) )
        {
            $value = $monkeys_done [ $monkeys_todo [ $monkey_name ][ 0 ]];

            $next_monkey_name = $monkeys_todo [ $monkey_name ][ 2 ];

            $first_known = true;
        }
        else
        {
            $value = $monkeys_done [ $monkeys_todo [ $monkey_name ][ 2 ]];

            $next_monkey_name = $monkeys_todo [ $monkey_name ][ 0 ];

            $first_known = false;
        }

        switch ( $monkeys_todo [ $monkey_name ][ 1 ] )
        {
            case '+': $target -= $value; break;
            case '*': $target /= $value; break;
            case '-': if ( $first_known ) $target = $value - $target; else $target += $value; break;
            case '/': if ( $first_known ) $target /= $value;          else $target *= $value; break;
        }

        $monkey_name = $next_monkey_name;
    }

    echo "Second part: $target\n";
}

first_part  ( $monkeys_todo, $monkeys_done );
second_part ( $monkeys_todo, $monkeys_done );