<?php

return [

    /*
    |--------------------------------------------------------------------------
    | View Storage Paths
    |--------------------------------------------------------------------------
    |
    | Most templating systems load templates from disk. Here you may specify
    | an array of paths that should be checked for your views. Of course
    | the usual Laravel view path has already been registered for you.
    |
    */

    'paths' => [
        resource_path('views'),
    ],

    'image_paths' => [
        'img' => 'assets/img/',
        'logo' => 'assets/img/logo/',
        'flag' => 'assets/img/flag/',
        'slide' => 'assets/img/slider/',
        'banner' => 'assets/img/banner/',
        'product' => 'assets/img/product/',
        'post' => 'assets/img/post/',
        'user' => 'storage/img/user/avatar/',
        'book' => 'storage/img/book/',
        'logo_admin' => 'admin_asset/assets/demo/default/media/img/logo/',
        'detaultAvatar' => 'storage/img/user/avatar/1.png',
    ],

    'paginate' => [
        'user' => '10',
        'book' => '20',
        'book_profile' => 12,
        'follow_user' => 9,
        'book_request' => 6,
        'notifications' => 15,
        'review' => 3,
    ],

    'taking_numb' => [
        'best_sharing_book' => 5,
        'top_book' => 6,
        'latest_book' => 12,
        'author' => 10,
        'book_top' => 6,
    ],

    'random_numb' => [
        'book' => 3,
        'office' => 5,
    ],

    'vote' => [
        'login' => 2,
        'up' => 'up',
        'down' => 'down',
        'no' => 'no',
        'nodown' => 'updown',
        'noup' => 'noup',
        'upVote' => 'upVote',
        'downVote' => 'downVote',
    ],

    'status' => [
        'sharing' => 'sharing',
    ],

    'role' => [
        'admin' => 'Admin',
        'editor' => 'Editor',
    ],

    'request' => [
        'waiting' => 'waiting',
        'reading' => 'reading',
        'returning' => 'returning',
        'returned' => 'returned',
        'cancel' => 'cancel',
        'approve' => 1,
        'dismiss' => 0,
        'favorite' => 'favorite',
    ],

    'limit' => [
        'related_book' => 8,
        'notifications' => 3,
        'minutes' => 43200,
    ],

    'notifications' => [
        'review' => 'Review your book: ',
        'follow' => 'Follow you',
        'upvote' => 'Upvote your review for the book: ',
        'downvote' => 'Downvote your review for the book: ',
        'waiting' => 'Borrowing your book: ',
        'returning' => 'Return your book: ',
        'returned' => ' Has returned the book: ',
        'reading' => ' Has approve request to borrow: ',
        'prompt' => 'You will be returned book soon',
        'cancel' => ' Has refuse request borrow: ',
        'route' => [
            'book' => 'books.show',
            'user' => 'user',
            'review' => 'review.show',
            'owner_prompt' => 'my-request.index',
        ],
        'from' => ' from you',
    ],

    'links' => [
        'feedback' => 'https://forms.gle/DQCtB8DBMJ8WhL6a7',
        'instruction' => '',
        'banner1' => '/storage/img/banner/banner1.gif',
        'banner2' => '/storage/img/banner/banner2.jpg',
        'club' => 'https://www.facebook.com/groups/framgia.reading.book.club/',
        'wsm' => '.sun-asterisk.vn/',
        'http' => 'http://',
        'avatar' => "'https://edev.sun-asterisk.vn//assets/user_avatar_default-bc6c6c40940226d6cf0c35571663cd8d231a387d5ab1921239c2bd19653987b2.png'",
        'icon_header' => '/assets/img/favicon.png',
    ],

    'social' => [
        'facebook' => 'https://www.facebook.com/SunAsteriskVietnam',
        'linkedIn' => 'https://www.linkedin.com/company/sun-asterisk-vietnam/',
        'gitHub' => 'https://github.com/sun-asterisk',
    ],

    'text' => '<p><br><i><b><span><h1><h2><h3><h4><h5><h6><strong><em>',
    'time' => 3600,
    'start' => 150,
    'maxBanner' => 25200,
    'pageFull' => 10,
    'pageUser' => 7,
    /*
    |--------------------------------------------------------------------------
    | Compiled View Path
    |--------------------------------------------------------------------------
    |
    | This option determines where all the compiled Blade templates will be
    | stored for your application. Typically, this is within the storage
    | directory. However, as usual, you are free to change this value.
    |
    */

    'compiled' => realpath(storage_path('framework/views')),

    'prompt' => [
        'time' => '+2 days',
        'loop_time' => '0 3 * * *',
    ],

    'link' => config('app.url') . '/books/',
    'footer' => [
        'by' => '© 2020 By Sun-asterisk - Talent Development Office - Vietnam Education Unit - All rights reserved',
    ],

];
