<div class="uk-section uk-text-center">
    <h1 class="uk-heading-large">500</h1>
    <p class="uk-text-lead"><?= t('errors.500') ?></p>
    <?php if (!empty($message)): ?>
        <pre class="uk-text-left uk-margin-top"><?= e($message) ?></pre>
    <?php endif; ?>
    <a class="uk-button uk-button-primary" href="/"><?= t('nav.home') ?></a>
</div>