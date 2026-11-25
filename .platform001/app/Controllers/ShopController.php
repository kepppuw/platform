<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\{Controller, Request};

final class ShopController extends Controller
{
    public function index(Request $req): string
    {
        return $this->view('shop', ['title' => t('nav.shop')]);
    }
}