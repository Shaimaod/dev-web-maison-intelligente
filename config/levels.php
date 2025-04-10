<?php

return [
    'levels' => [
        'débutant' => [
            'min_points' => 0,
            'max_points' => 9,
            'next_level' => 'intermédiaire',
            'points_needed' => 10,
        ],
        'intermédiaire' => [
            'min_points' => 10,
            'max_points' => 19,
            'next_level' => 'avancé',
            'points_needed' => 20,
        ],
        'avancé' => [
            'min_points' => 20,
            'max_points' => 29,
            'next_level' => 'expert',
            'points_needed' => 30,
        ],
        'expert' => [
            'min_points' => 30,
            'max_points' => null,
            'next_level' => null,
            'points_needed' => null,
        ],
    ],

    // Points gagnés pour différentes actions
    'points' => [
        'login' => 1,
        'profile_update' => 2,
        'email_verified' => 5,
        'object_added' => 3,
        'object_configured' => 5,
    ],
]; 