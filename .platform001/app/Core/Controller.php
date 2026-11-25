<?php
declare(strict_types=1);

namespace App\Core;

abstract class Controller
{
    public function __construct(protected Container $container) {}

    protected function view(string $tpl, array $data = []): string
    {
        $i18n = $this->container->get(\App\Services\I18n::class);
        $data += [
            'lang'     => $i18n->current(),
            'isAuthed' => !empty($_SESSION['user_id']),
            'csrf'     => csrf_token(),
            'appName'  => Config::get('app.name'),
        ];
        $content = $this->container->get(View::class)->render($tpl, $data);
        return $this->container->get(View::class)->render('layout', $data + ['content' => $content]);
    }
}