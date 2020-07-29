<?php


return [
    'baseUrl'     => 'http://paytest.local/',
    'title'       => 'Без фреймворков',
    'projectName' => 'Pay test',
    'components'  => [
        'db' => require __DIR__ . '/db.php',
    ]
];