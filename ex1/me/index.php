<?php

$fb = require __DIR__.'/../bootstrap.php';

$helper = $fb->getRedirectLoginHelper();
$accessToken = $helper->getAccessToken();

$response = $fb->get('/me?fields=id,name,email,birthday,cover,devices', $accessToken);
$response = $response->getDecodedBody();

$oAuth2Client = $fb->getOAuth2Client();
$accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
$_SESSION['fb_access_token'] = (string) $accessToken;

var_dump($response);
