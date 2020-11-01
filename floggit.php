<?php
/*
Plugin Name: Floggit
Plugin URI: https://www.designerandgeek.com
Description: Easier logging to debug.log.
Version: 1.1
Author: Jørn Støylen
Author URI: https://www.designerandgeek.com
*/
namespace PrikkPrikkPrikk {
    class FloggitClass
    {

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
        public static function loggit(...$logs)
        {
            // Don't log if logging is off
            if (WP_DEBUG_LOG==false) {
                return;
            }
            $backtrace = debug_backtrace();
            $level = 0;
            while (basename(__FILE__)==basename($backtrace[$level]['file'])) {
                $level++;
            }
            $backtrace = debug_backtrace()[$level];
            $bt_file = basename($backtrace['file']);
            $bt_line = $backtrace['line'];
            $bt_string = "[$bt_file:$bt_line] ";
            foreach ($logs as $log) {
                if (is_array($log) || is_object($log)) {
                    error_log($bt_string . PHP_EOL . print_r($log, true));
                } else {
                    error_log($bt_string . $log);
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
        public static function floggit(...$logs)
        {
            // Don't log if logging is off
            if (WP_DEBUG_LOG==false) { return; }
            // Static class variable flag keeps track of whether debug.log has been purged or not
            if (self::$flogged==false) {
                $logfile = (is_bool(WP_DEBUG_LOG)) ? (WP_CONTENT_DIR . '/debug.log') : WP_DEBUG_LOG;
                if(file_exists($logfile)) {
					unlink($logfile);
				}
                self::$flogged = true;
            }
            loggit(...$logs);
        }
    }
}

namespace {

    function loggit(...$logs)
    {
        return \PrikkPrikkPrikk\FloggitClass::loggit(...$logs);
    }


    function floggit(...$logs)
    {
        return \PrikkPrikkPrikk\FloggitClass::floggit(...$logs);
    }

}
