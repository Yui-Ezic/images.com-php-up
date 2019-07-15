<?php
return [
    'adminEmail' => 'admin@example.com',
    
    'maxFileSize' => 1024 * 1024 * 4, // 4 megabites
    'storagePath' => '@frontend/web/uploads/',
    'profilePicture' => [
        'maxWidth' => 1280,
        'maxHeight' => 1024,
    ],
    'postPicture' => [
        'maxWidth' => 1024,
        'maxHeight' => 768,
    ],
    
    'feedPostLimit' => 20,
];
