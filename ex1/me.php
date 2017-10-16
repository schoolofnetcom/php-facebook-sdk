<?php

$fb = require __DIR__.'/bootstrap.php';

$response = $fb->get('/me?fields=id,name,email,birthday,cover,devices');
$response = $response->getDecodedBody();

var_dump($response);
