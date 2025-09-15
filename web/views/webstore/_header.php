<?php
declare(strict_types=1);

use App\Service\AuthService;

$context = AuthService::getLoginContext();
?>

<header>
    <div>
        <ul id="store-information" class="bar-list">
            <li><a href="/map"><i class="fa-solid fa-location-dot"></i>Stores</a></li>
            <li><a href="/about">About</a></li>
        </ul>

        <ul id="store-account" class="bar-list">
            <?php if ($context): ?>
                <li class="dropdown">
                    <a class="account" href="/account">
                        <?= htmlspecialchars($context->username) ?>
                        <?php if ($context->image !== null): ?>
                            <img src="<?= $context->image->filepath ?>" alt="<?= $context->image->alt ?>">
                        <?php else: ?>
                            <i class="fa-solid fa-circle-user"></i>
                        <?php endif; ?>

                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="/account"><i class="fa-solid fa-user"></i> My Account</a></li>
                        <li><a href="/orders"><i class="fa-solid fa-box-open"></i> My Orders</a></li>
                        <?php if ($context->isStaff()): ?>
                            <li><a href="/admin"><i class="fa-solid fa-shield-halved"></i> Admin Dashboard</a></li>
                        <?php endif; ?>
                        <li><a href="/logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
                    </ul>
                </li>
            <?php else: ?>
                <li><a href="/login">Login</a> | <a href="/register">Register</a></li>
            <?php endif; ?>
        </ul>
    </div>

    <div>
        <h1 id="store-logo"><a href="/">Novelty N' Nonsense</a></h1>

        <search>
            <form action="/books/search" method="get">
                <label for="search"></label>
                <input type="search" name="query" id="search" placeholder="Search by Title, Author, Keyword, or ISBN">
                <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
            </form>
        </search>

        <a href="/cart" id="store-cart"><i class="fa-solid fa-cart-shopping"></i></a>
    </div>

    <div>
        <ul id="store-categories" class="bar-list">
            <li><a href="/books/search/?category_id=10">Comics, Graphic</a></li>
            <li><a href="/books/search/?category_id=2">Fiction</a></li>
            <li><a href="/books/search/?category_id=7">Non-Fiction</a></li>
            <li><a href="/books/search/?category_id=9">Young Adults</a></li>
            <li><a href="/books/search/?category_id=8">Children's</a></li>
        </ul>
    </div>
</header>
