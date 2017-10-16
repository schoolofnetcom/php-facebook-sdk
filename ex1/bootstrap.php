<?php

require __DIR__ . '/vendor/autoload.php';

if (!session_id()) {
    session_start();
}

$accessToken = $_SESSION['fb_access_token'] ?? null;

$fbData = [
    'app_id'=>'511475802566275',
    'app_secret' => 'ee978927a7286c2ab04f9b139145b7f3',
    'default_graph_version' => 'v2.10',
];

if ($accessToken) {
    $fbData['default_access_token'] = $accessToken;
}

$fb = new \Facebook\Facebook($fbData);

return $fb;
