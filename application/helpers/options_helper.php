<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Check value to find if it was serialized.
 *
 * If $data is not an string, then returned value will always be false.
 * Serialized data is always a string.
 *
 * @link https://codex.wordpress.org/Function_Reference/is_serialized
 * @since 2.0.5
 *
 * @param mixed $data Value to check to see if was serialized.
 * @return bool False if not serialized and true if it was.
 */
function is_serialized( $data ) 
{
    // if it isn't a string, it isn't serialized
    if ( ! is_string( $data ) )
        return false;
    $data = trim( $data );
    if ( 'N;' == $data )
        return true;
    $length = strlen( $data );
    if ( $length < 4 )
        return false;
    if ( ':' !== $data[1] )
        return false;
    $lastc = $data[$length-1];
    if ( ';' !== $lastc && '}' !== $lastc )
        return false;
    $token = $data[0];
    switch ( $token ) {
        case 's' :
            if ( '"' !== $data[$length-2] )
                return false;
        case 'a' :
        case 'O' :
            return (bool) preg_match( "/^{$token}:[0-9]+:/s", $data );
        case 'b' :
        case 'i' :
        case 'd' :
            return (bool) preg_match( "/^{$token}:[0-9.E-]+;\$/", $data );
    }
    return false;
}