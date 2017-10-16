<?php

$fb = require __DIR__.'/bootstrap.php';

$pageid = filter_input(INPUT_GET, 'pageid');
$message = filter_input(INPUT_GET, 'message');
$access_token = filter_input(INPUT_GET, 'access_token');

if (!$pageid || !$message || !$access_token) {
    throw new Exception('Por favor, informe todos os campos');
    die;
}

$response = $fb->post("/{$pageid}/feed", ['message' => $message], $access_token);
$response = $response->getDecodedBody();

var_dump($response);
