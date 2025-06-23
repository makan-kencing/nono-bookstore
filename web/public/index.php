<?php

require 'vendor/autoload.php';

Flight::route('/', function() {
    echo 'hello world!';
});

Flight::route('/json', function() {
    Flight::json(['hello' => 'world']);
});

Flight::start();