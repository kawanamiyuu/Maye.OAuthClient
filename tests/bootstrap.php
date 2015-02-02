<?php

require dirname(__DIR__) . '/vendor/autoload.php';

$_SERVER['REQUEST_URI'] = 'http://example.com/test?key=value';
$_SESSION = [];
