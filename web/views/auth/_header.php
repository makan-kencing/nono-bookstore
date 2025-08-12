<?php

declare(strict_types=1);

?>
<header class="header" role="banner">
    <div class="header-inner">
        <div class="logo-section">
            <img src="./static/assets/nono-logo.jpg" alt="Novelty N' Nonsense Logo" class="logo">
            <span class="brand-text">Novelty N' Nonsense</span>
        </div>
        <a href="#" class="help-link" aria-label="Get help with this page">Need help?</a>
    </div>
</header>

<script>
    $(function () {
        let title = (document.title || '').trim();
        if (!title) return;

        let $badge = $('.header .register-text');
        if ($badge.length === 0) {
            let $brand = $('.header .brand-text').first();
            $badge = $('<span/>', {
                'class': 'badge-text page-badge',
                'aria-current': 'page'
            }).insertAfter($brand);
        }

        $badge.text(title);
    });
</script>
