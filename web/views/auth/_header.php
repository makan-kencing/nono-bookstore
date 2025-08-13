<?php

declare(strict_types=1);

?>
<link rel="stylesheet" href="/static/styles/Auth/header.css"/>
<header class="header" role="banner">
    <div class="header-inner">
        <div class="logo-container">
            <a class="logo-section" href="/" aria-label="Go to home">
                <img src="./static/assets/nono-logo.jpg" alt="Novelty N' Nonsense Logo" class="logo">
                <span class="brand-text">Novelty N' Nonsense</span>
            </a>
            <span class="badge-text"></span>
        </div>
        <a href="#" class="help-link" aria-label="Get help with this page">Need help?</a>
    </div>
</header>

<script>
    $('.badge-text').text(document.title.trim());
</script>
