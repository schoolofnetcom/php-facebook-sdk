<?php

$fb = require __DIR__.'/bootstrap.php';

$response = $fb->get('/me/accounts?fields=picture,cover,name,perms,access_token');
$response = $response->getDecodedBody();

unset($response['data'][0]);

echo '<ul>';
foreach ($response['data'] as $page) {
    echo '<li>';
    echo '<img src="' . $page['name'] . '">';
    echo $page['name'];
    echo ' - ';
    echo $page['access_token'];
    echo ' - ';
    echo $page['id'];
    echo '</li>';
}
echo '</ul>';
