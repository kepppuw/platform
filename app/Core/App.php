<?php
declare(strict_types=1);

namespace App\Core;

use App\Services\{I18n, Audit};

final class App
{
    private static ?App $instance = null;
    private Container $container;
    private Router $router;

    public function __construct(private string $basePath)
    {
        self::$instance = $this;
        $this->loadEnv();
        Config::load($basePath . '/config');
        $this->container = new Container();
        $this->registerBindings();
        $this->router = new Router($this->container, require $basePath . '/config/routes.php');
    }

    public static function instance(): self { return self::$instance; }
    public static function i18n(): I18n      { return self::instance()->container->get(I18n::class); }

    private function loadEnv(): void
    {
        $envFile = $this->basePath . '/.env';
        if (!is_file($envFile)) return;
        foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) continue;
            [$k, $v] = array_pad(explode('=', $line, 2), 2, '');
            $_ENV[trim($k)] = trim($v, " \t\n\r\0\x0B\"'");
        }
    }

    private function registerBindings(): void
    {
        $c = $this->container;
        $c->set(Container::class, fn() => $c);
        $c->set(DB::class,        fn() => new DB(Config::get('db')));
        $c->set(Session::class,   fn() => new Session(Config::get('app.session')));
        $c->set(Logger::class,    fn() => new Logger($this->basePath . '/storage/logs/app.log'));
        $c->set(Request::class,   fn() => Request::fromGlobals());
        $c->set(Response::class,  fn() => new Response());
        $c->set(View::class,      fn() => new View($this->basePath . '/app/Views'));
        $c->set(I18n::class,      fn() => new I18n($this->basePath . '/lang'));
        $c->set(Audit::class,     fn($c) => new Audit($c->get(DB::class)));
    }

    public function run(): void
    {
        session_name(Config::get('app.session.name'));
        session_start();

        try {
            $this->router->dispatch($this->container->get(Request::class));
        } catch (\Throwable $e) {
            $this->container->get(Logger::class)->error($e->getMessage(), [
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            $debug = Config::get('app.debug');
            (new Response())
                ->status(500)
                ->html($this->container->get(View::class)->render('errors/500', [
                    'message' => $debug ? $e->getMessage() : null,
                    'lang'    => I18n::instance()->current(),
                ]))
                ->send();
        }
    }
}