<?php

declare(strict_types=1);

use App\Core\Template;
use App\Core\View;

$template = new Template(
    'webstore/_base.php',
    ['title' => 'Shop Map']
);

?>

<?php $template->start() ?>

    <div id="map" style="height: 600px"></div>

    <script>
        var map = L.map('map').setView([3.2149230285883568, 101.7258804110601], 14.5);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        var marker = L.marker([3.2149230285883568, 101.7258804110601]).addTo(map);
        var circle = L.circle([3.2149230285883568, 101.7258804110601], {
            color: 'red',
            fillColor: '#f03',
            fillOpacity: 0.5,
            radius: 300
        }).addTo(map);

        var popup = L.popup()
            .setLatLng([3.2165230285883566, 101.7258804110601])
            .setContent("We are here.")
            .openOn(map);

    </script>

<?= $template->end() ?>
