<?php

$input = file ( __DIR__ . '/input.txt', FILE_IGNORE_NEW_LINES );

$placevals = [];

function snafu_add ( $snafu_1, $snafu_2 )
{
    $carry = 0;

    $split_1 = str_split ( $snafu_1 );
    $split_2 = str_split ( $snafu_2 );

    $sum = '';

    while ( true )
    {
        $summand_1 = array_pop ( $split_1 );
        $summand_2 = array_pop ( $split_2 );

        // note: '0' is evaluated to false, hence comparison with null
        if ( $summand_1 === null && $summand_2 === null && empty ( $carry ) )
            break;

        if ( $summand_1 === null ) $summand_1 = '0';
        if ( $summand_2 === null ) $summand_2 = '0';

        $added = get_dec_val ( $summand_1 ) + get_dec_val ( $summand_2 ) + $carry;

        if ( $added > 2 )
            $carry = 1;
        elseif ( $added < -2 )
            $carry = -1;
        else
            $carry = 0;

        $sum = get_snafu_val ( $added ) . $sum;
    }

    return $sum;
}

function get_dec_val ( $char )
{
    switch ( $char )
    {
        case '-':
            return -1;

        case '=':
            return -2;

        default:
            return (int) $char;
    }
}

function get_snafu_val ( $number )
{
    // a little more cumbersome, since PHP returns negative modulos

    $modulo = ( $number + 2 ) % 5;

    if ( $modulo < 0 )
        $modulo += 5;

    $mod_number = $modulo - 2;

    switch ( $mod_number )
    {
        case -1:
            return '-';

        case -2:
            return '=';

        default:
            return (string) $mod_number;
    }
}

$sum = '0';

foreach ( $input as $snafu_number )
    $sum = snafu_add ( $sum, $snafu_number );

echo "First part: $sum\n";