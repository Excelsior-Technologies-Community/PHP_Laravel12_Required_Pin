<?php

return [
    /**
     * int - Default pin
     */
    'default' => '123456',
    /**
     * bool - Allow a user to authenticate using the default pin
     */
    'allow_default_pin' => true,
    /**
     * int - Uses seconds. Make sure to update the 'expires_at'
     * column if you changed this value after migration
     */
    'duration' => 300,
    /**
     * boolean
     */
    'verify_sender' => true,
    /**
     * string - Name of form input
     */
    'input' => '_pin',
    /**
     * string - Name of URL param
     */
    'param' => '_uuid',
    /**
     * int - Max chars for pin
     */
    'max' => 6,
    /**
     * int - Min chars for pin
     */
    'min' => 6,
    /**
     * int|boolean - Check all or a specified number of
     * previous pins
     */
    'check_all' => true,
    /**
     * int - Number of previous pins to check
     */
    'number' => 6,
    /**
     * int - Pin authentication rate limit
     */
    'max_attempts' => 3,
    /**
     * int - Number of minutes a user is supposed to wait before
     * another attempt
     */
    'delay_minutes' => 1,
    /**
     * int - Number of times a user is allowed to try and authenticate
     * before the route is cancelled
     */
    'max_trial' => 3,
    /**
     * string - Route that will be displayed in the notification
     * that is sent when a user's pin has been changed
     */
    'change_pin_route' => 'change/pin',

    /**
     * Pin notification configurations
     */
    'notify' => [
        /**
         * boolean - Send a notification whenever pin is changed
         */
        'change' => true,
    ],

    /**
     * string - Route that will be displayed in the notification
     * that is sent when a user's pin has been changed
     */
    'auth_route_guard' => 'auth',

    /**
     * string - Route that will be displayed in the notification
     * that is sent when a user's pin has been changed
     */
    'auth_guard' => 'web',
    
    /**
     * sanctum/api - Route middleware of authenticated user
     */
    'auth_middleware' => 'web',
];
