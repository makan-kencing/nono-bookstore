<?php

declare(strict_types=1);

use App\Core\Template;
use App\Core\View;

$template = new Template(
    'webstore/_base.php',
    ['title' => 'Shop Map']
);

?>

<?php $template->startFragment('header'); ?>

<style>
    body {
        gap: 0;
    }
</style>

<?php $template->endFragment(); ?>

<?php $template->start() ?>

    <div id="map" style="height: 700px"></div>

    <script>
        const map = L.map('map').setView([3.2149230285883568, 101.7258804110601], 14.5);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        const marker = L.marker([3.2149230285883568, 101.7258804110601]).addTo(map);
        const circle = L.circle([3.2149230285883568, 101.7258804110601], {
            color: 'red',
            fillColor: '#f03',
            fillOpacity: 0.5,
            radius: 300
        }).addTo(map);

        const popup = L.popup()
            .setLatLng([3.2165230285883566, 101.7258804110601])
            .setContent("We are here.")
            .openOn(map);
    </script>

<?= $template->end() ?>
