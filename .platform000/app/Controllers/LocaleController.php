<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\{App, Controller, Request, Response};

final class LocaleController extends Controller
{
    public function switch(Request $req, string $locale): string
    {
        App::i18n()->set($locale);
        (new Response())->redirect($_SERVER['HTTP_REFERER'] ?? '/');
    }
}