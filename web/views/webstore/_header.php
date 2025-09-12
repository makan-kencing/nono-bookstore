<?php
declare(strict_types=1);

use App\Service\AuthService;

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
$context = AuthService::getLoginContext();
?>

<header>
    <div>
        <nav class="contents">
            <ul id="store-information" class="bar-list">
                <li><a href="/stores"><i class="fa-solid fa-location-dot"></i>Stores</a></li>
                <li><a href="/about">About</a></li>
            </ul>

            <ul id="store-account" class="bar-list">
                <?php if ($context): ?>
                    <li class="dropdown">
                        <a href="/account">
                            <i class="fa-solid fa-circle-user"></i>
                            <?= htmlspecialchars($context->username) ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="/account">My Account</a></li>
                            <li><a href="/">My Purchase</a></li>
                            <li><a href="#" id="logout-link">Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li><a href="/login">Login</a> | <a href="/register">Register</a></li>
                <?php endif; ?>
                <li><a href="/wishlist"><i class="fa-regular fa-heart show"></i>Wishlist</a></li>
            </ul>
        </nav>
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
        <nav class="contents">
            <ul id="store-categories" class="bar-list">
                <li><a href="">Bestsellers</a></li>
                <li><a href="">Fiction</a></li>
                <li><a href="">Non-Fiction</a></li>
                <li><a href="">Young Adults</a></li>
                <li><a href="">Children's</a></li>
            </ul>
        </nav>
    </div>
</header>

<script>
    $("#logout-link").on("click", function (e) {
        e.preventDefault();

        $.ajax({
            url: "/api/logout",
            type: "POST",
            xhrFields: { withCredentials: true }, // keep PHPSESSID
            success: function () {
                window.location.href = "/login";
            },
            error: function () {
                console.log("Logout failed, please try again.");
            }
        });
    });
</script>

<style>
    #store-account .dropdown {
        position: relative;
        display: inline-block;
    }

    #store-account .dropdown-menu {
        display: none;
        position: absolute;
        background: #fff;
        border: 1px solid #ccc;
        border-radius: 6px;
        min-width: 180px;
        padding: 10px 0;
        z-index: 1000;
    }

    #store-account .dropdown-menu li {
        list-style: none;
    }

    #store-account .dropdown-menu li a {
        display: block;
        padding: 8px 15px;
        text-decoration: none;
        color: #333;
    }

    #store-account .dropdown-menu li a:hover {
        background-color: #f4f4f4;
    }

    #store-account .dropdown:hover .dropdown-menu {
        display: block;
    }
</style>
