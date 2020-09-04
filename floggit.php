<?php
/*
Plugin Name: Floggit
Plugin URI: https://www.designerandgeek.com
Description: Easier logging to debug.log.
Version: 1.1
Author: Jørn Støylen
Author URI: https://www.designerandgeek.com
*/

class FloggitClass {

    // Have we already emptied the log file?
    private static $flogged = false;

    /**
    * Easier logging to debug.log.
    *
    * Just a wrapper for error_log that pretty prints arrays and objects
    * and includes the filename and line number where the logging happened.
    *
    * Accepts multiple parameters, prints one line for each parameter.
    *
    * @param ...$logs One or more things to log.
    */
    public static function loggit ( ...$logs )
    {
        $backtrace = debug_backtrace();
        $level = 0;
        while(basename(__FILE__)==basename($backtrace[$level]['file'])) {
            $level++;
        }
        $backtrace = debug_backtrace()[$level];
        $bt_file = basename($backtrace['file']);
        $bt_line = $backtrace['line'];
        $bt_string = "[$bt_file:$bt_line] ";
        foreach( $logs as $log ) {
            if ( is_array( $log ) || is_object( $log ) ) {
                error_log( $bt_string . PHP_EOL . print_r( $log, true ) );
            } else {
                error_log( $bt_string . $log );
            }
        }
    }


    /**
    * Fresh loggit: Empty debug.log first, then loggit().
    *
    * Since this is an extra step in the call stack, set backtrace level to 2.
    *
    * @param ...$logs One or more things to log.
    */
    public static function floggit( ...$logs )
    {
        if (self::$flogged==false) {
            unlink( WP_CONTENT_DIR . '/debug.log' );
            self::$flogged = true;
        }
        loggit( ...$logs );
    }
}

function loggit(...$logs)
{
    return FloggitClass::loggit(...$logs);
}


function floggit(...$logs)
{
    return FloggitClass::floggit(...$logs);
}
