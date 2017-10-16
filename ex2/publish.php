<?php

require __DIR__ . '/vendor/autoload.php';

$fbData = [
    'app_id'=>'511475802566275',
    'app_secret' => 'ee978927a7286c2ab04f9b139145b7f3',
    'default_graph_version' => 'v2.10',
];

$fb = new \Facebook\Facebook($fbData);

$db = [
    'driver' => 'mysql',
    'host' => 'localhost',
    'database' => 'curso_php_fb',
    'username' => 'root',
    'password' => '1234',
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => '',
];

$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($db);

$capsule->setAsGlobal();
$capsule->bootEloquent();

$now = (new Datetime('2017-10-17 20:31:25'))->format('Y-m-d H:i:s');

$posts = $capsule->table('posts')
    ->where('publish_date', '<=', $now)
    ->where('published', 0)
    ->get();

$count = 0;
echo 'Iniciando em ' . $now . PHP_EOL;

foreach ($posts as $post) {
    echo '- publicando '. $post->message . PHP_EOL;
    $response = $fb->post("/{$post->pageid}/feed", ['message' => $post->message], $post->access_token);

    $capsule->table('posts')
        ->where('id', $post->id)
        ->update(['published' => 1]);
    
    $count ++;
}

echo $count . ' posts publicados' . PHP_EOL;
