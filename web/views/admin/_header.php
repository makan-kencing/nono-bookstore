<?php

declare(strict_types=1);

use App\Service\AuthService;

if (session_status() !== PHP_SESSION_ACTIVE)
    session_start();

$context = AuthService::getLoginContext();

?>

<header>
    <div class="header-left">
        <h1><a href="/admin">Nonsensical(TM) Dashboard</a></h1>
    </div>
    <nav>
        <ul>
            <li>
                <a href="/admin/users">
                    <i class="fa-solid fa-user"></i>
                    View Users
                </a>
            </li>
            <li>
                <a href="/admin/books">
                    <i class="fa-solid fa-book"></i>
                    View Books
                </a>
            </li>
            <li>
                <a href="/admin/orders">
                    <i class="fa-solid fa-truck-fast"></i>
                    View Orders
                </a>
            </li>
            <li class="dropdown">
                <span class="profile-link">
                  <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#EFEFEF"><path d="M234-276q51-39 114-61.5T480-360q69 0 132 22.5T726-276q35-41 54.5-93T800-480q0-133-93.5-226.5T480-800q-133 0-226.5 93.5T160-480q0 59 19.5 111t54.5 93Zm246-164q-59 0-99.5-40.5T340-580q0-59 40.5-99.5T480-720q59 0 99.5 40.5T620-580q0 59-40.5 99.5T480-440Zm0 360q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q53 0 100-15.5t86-44.5q-39-29-86-44.5T480-280q-53 0-100 15.5T294-220q39 29 86 44.5T480-160Zm0-360q26 0 43-17t17-43q0-26-17-43t-43-17q-26 0-43 17t-17 43q0 26 17 43t43 17Zm0-60Zm0 360Z"/></svg>
                </span>


                <ul class="dropdown-menu">
                    <li><span class="menu-title"><?= $context?->role->name ?></span></li>
                    <li><a href="/account">My Account</a></li>
                    <li><a href="/">Webstore</a></li>
                    <li><a href="/logout">Logout</a></li>
                </ul>
            </li>

        </ul>
    </nav>
</header>

<style>
    .dropdown {
        color: black;
        position: relative;
        display: inline-block;

        .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            background: #fff;
            border: 1px solid #ccc;
            border-radius: 6px;
            min-width: 180px;
            padding: 10px 0;
            z-index: 1000;

            .menu-title {
                display: block;
                padding: 8px 16px;  /* 和 a 的一致 */
                font-weight: bold;
            }

            li {
                list-style: none;

                a {
                    display: block;
                    padding: 8px 15px;
                    text-decoration: none;
                    color: #333;

                    &:hover {
                        background-color: #f4f4f4;
                    }
                }
            }
        }


        &:hover .dropdown-menu {
            display: block;
        }
    }
</style>
