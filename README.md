# floggit
Easier logging to debug.log in WordPress.

Handles an arbitrary number of arguments.

Uses print_r to pretty print arrays and objects.

Also logs time stamp, file name and line number.

## Usage

Add to debug.log:

    loggit("Things", $to_log);
    
Delete existing contents of debug.log for this session:

    floggit("Things", $to_log);
