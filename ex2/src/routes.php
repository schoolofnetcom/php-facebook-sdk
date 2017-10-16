<?php

use Slim\Http\Request;
use Slim\Http\Response;

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

// Routes

$app->get('/login', function (Request $request, Response $response, array $args) use ($fb) {
    $helper = $fb->getRedirectLoginHelper();
    
    $permissions = ['email', 'user_birthday', 'pages_show_list', 'publish_pages'];
    
    $loginUrl = $helper->getLoginUrl('http://localhost:8000/me', $permissions);
    
    return $this->renderer->render($response, 'login.phtml', compact('loginUrl'));
});

$app->get('/me', function (Request $request, Response $response, array $args) use ($fb) {
    $helper = $fb->getRedirectLoginHelper();
    $accessToken = $helper->getAccessToken();
    
    $fb_response = $fb->get('/me?fields=id,name,email,birthday,cover,devices', $accessToken);
    $me = $fb_response->getDecodedBody();
    
    $oAuth2Client = $fb->getOAuth2Client();
    $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
    $_SESSION['fb_access_token'] = (string) $accessToken;

    return json_encode(compact('_SESSION', 'me'));
});

$app->get('/', function (Request $request, Response $response, array $args) use ($fb) {
    $fb_response = $fb->get('/me/accounts?fields=picture,cover,name,perms,access_token');
    $pages = $fb_response->getDecodedBody();
    
    unset($pages['data'][0]);

    return $this->renderer->render($response, 'index.phtml', compact('pages'));
});

$app->get('/posts', function (Request $request, Response $response, array $args) {
    $page = [
        'name' => filter_input(INPUT_GET, 'name'),
        'pageid' => filter_input(INPUT_GET, 'pageid'),
        'access_token' => filter_input(INPUT_GET, 'access_token'),
    ];

    $posts = $this->db->table('posts')->where('pageid', $page['pageid'])->get();

    $name = 'posts';
    return $this->renderer->render($response, 'posts.phtml', compact('posts', 'page'));
});

$app->post('/posts', function (Request $request, Response $response, array $args) {
    $pageid = filter_input(INPUT_POST, 'pageid');
    $message = filter_input(INPUT_POST, 'message');
    $access_token = filter_input(INPUT_POST, 'access_token');
    $publish_date = filter_input(INPUT_POST, 'publish_date');
    $published = 0;

    $this->db->table('posts')
        ->insert(compact('pageid', 'message', 'access_token', 'publish_date', 'published'));

    return $response->withStatus(302)->withHeader('Location', '/');
});
