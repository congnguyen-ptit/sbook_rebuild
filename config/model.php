<?php

return [
    'reputation' => [
        'share_book' => 5,
        'vote' => '1',
        'follow' => 1,  
    ],

    'target_type' => [
        'book' => 'App\Eloquent\Book',
        'book_user' => 'App\Eloquent\BookUser',
        'user' => 'App\Eloquent\User',
        'review' => 'App\Eloquent\Review',
        'vote' => 'App\Eloquent\Vote',
        'follow' => 'App\Eloquent\FollowUser',
    ],

    'viewed' => [
        'false' => 0,
        'true' => 1,
    ],

    'book_user' => [
        'type' => [
            'waiting' => 'waiting',
            'reading' => 'reading',
            'returned' => 'returned',
            'abtExpire' => 'abtExpire',
        ],
    ],

    'approved' => [
        'default' => 0,
        'checked' => 1,
    ],

    'roles' => [
        'admin' => 'Admin',
        'user' => 'User',
    ],
];
