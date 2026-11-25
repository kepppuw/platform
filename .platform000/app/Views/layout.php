<?php /** @var string $content */ ?>
<!DOCTYPE html>
<html lang="<?= e($lang ?? 'uk') ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title ?? $appName ?? 'Federation') ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.21.6/dist/css/uikit.min.css">
    <link rel="stylesheet" href="/assets/app.css">
</head>
<body>
<nav class="uk-navbar-container uk-margin-bottom" uk-navbar>
    <div class="uk-container uk-container-expand">
        <div uk-navbar>
            <div class="uk-navbar-left">
                <a class="uk-navbar-item uk-logo" href="/"><?= e($appName ?? 'Federation') ?></a>
                <ul class="uk-navbar-nav">
                    <li><a href="/"><?= t('nav.home') ?></a></li>
                    <li><a href="/shop"><?= t('nav.shop') ?></a></li>
                </ul>
            </div>
            <div class="uk-navbar-right">
                <ul class="uk-navbar-nav">
                    <li>
                        <a href="#"><?= strtoupper(e($lang ?? 'uk')) ?></a>
                        <div class="uk-navbar-dropdown">
                            <ul class="uk-nav uk-navbar-dropdown-nav">
                                <li><a href="/lang/uk">Українська</a></li>
                                <li><a href="/lang/ru">Русский</a></li>
                                <li><a href="/lang/en">English</a></li>
                            </ul>
                        </div>
                    </li>
                    <?php if (!empty($isAuthed)): ?>
                        <li><a href="/admin"><?= t('nav.cabinet') ?></a></li>
                        <li>
                            <form method="post" action="/logout" style="display:inline">
                                <?= csrf_field() ?>
                                <button class="uk-button uk-button-text" type="submit"><?= t('nav.logout') ?></button>
                            </form>
                        </li>
                    <?php else: ?>
                        <li><a href="/login"><?= t('nav.login') ?></a></li>
                        <li><a href="/register"><?= t('nav.register') ?></a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</nav>

<main class="uk-container uk-margin-large-bottom">
    <?= $content ?>
</main>

<script src="https://cdn.jsdelivr.net/npm/uikit@3.21.6/dist/js/uikit.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/uikit@3.21.6/dist/js/uikit-icons.min.js"></script>
<script src="/assets/app.js"></script>
</body>
</html>