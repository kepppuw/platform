<?php
declare(strict_types=1);

namespace App\Services;

use App\Core\Config;

final class I18n
{
    private static ?self $inst = null;
    private array $dict = [];
    private string $locale;
    public const ALLOWED = ['uk', 'ru', 'en'];

    public function __construct(private string $langPath)
    {
        self::$inst = $this;
        $req = $_SESSION['locale'] ?? null;
        $this->locale = in_array($req, self::ALLOWED, true) ? $req : (Config::get('app.default_locale') ?? 'uk');
    }

    public static function instance(): self
    {
        return self::$inst ??= new self(dirname(__DIR__, 2) . '/lang');
    }

    public function set(string $locale): void
    {
        if (!in_array($locale, self::ALLOWED, true)) return;
        $this->locale = $locale;
        $_SESSION['locale'] = $locale;
        $this->dict = [];
    }

    public function current(): string { return $this->locale; }

    public function dict(): array
    {
        if (!$this->dict) {
            $f = $this->langPath . '/' . $this->locale . '.php';
            $this->dict = is_file($f) ? require $f : [];
        }
        return $this->dict;
    }
}