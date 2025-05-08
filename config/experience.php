<?php

return [
    'points' => [
        'add_object' => env('EXPERIENCE_POINTS_ADD_OBJECT', 10),
        'update_object' => env('EXPERIENCE_POINTS_UPDATE_OBJECT', 5),
        'daily_login' => env('EXPERIENCE_POINTS_DAILY_LOGIN', 2),
        'profile_search' => env('EXPERIENCE_POINTS_PROFILE_SEARCH', 1),
        'object_search' => env('EXPERIENCE_POINTS_OBJECT_SEARCH', 1),
    ],
    'levels' => [
        'intermediate' => env('EXPERIENCE_LEVEL_INTERMEDIATE', 100),
        'advanced' => env('EXPERIENCE_LEVEL_ADVANCED', 250),
        'expert' => env('EXPERIENCE_LEVEL_EXPERT', 500),
    ],
];