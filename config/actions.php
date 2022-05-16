<?php

return [
    '/' => [\Aigletter\App\Controllers\HomeController::class, 'index'],
    '/page' => [\Aigletter\App\Controllers\PageController::class, 'infoClass'],
    '/some' => [\Aigletter\App\Controllers\SomeController::class, 'action'],
    '/some/arr' => [\Aigletter\App\Controllers\SomeController::class, 'arr'],
    '/some/new' => [\Aigletter\App\Controllers\SomeController::class, 'newObject']
];
