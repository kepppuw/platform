<?php
declare(strict_types=1);

use App\Controllers\{HomeController, ShopController, LocaleController};

return [
    ['GET', '/',              [HomeController::class,   'index']],
    ['GET', '/shop',          [ShopController::class,   'index']],
    ['GET', '/lang/{locale}', [LocaleController::class, 'switch']],
];