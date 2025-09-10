<?php

declare(strict_types=1);

?>

<header>
    <div>
        <nav class="contents">
            <ul id="store-information" class="bar-list">
                <li><a href="/stores"><i class="fa-solid fa-location-dot"></i>Stores</a></li>
                <li><a href="/about">About</a></li>
            </ul>

            <ul id="store-account" class="bar-list">
                <li><a href="/account"><i class="fa-solid fa-circle-user"></i>Account</a></li>
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
