<?php
/**
 * Created by PhpStorm.
 * User: mhaaz
 * Date: 11/24/2017
 * Time: 10:50 AM
 */

require 'vendor/autoload.php';
require 'include/functions.php';

$app = new Slim\App();

// Fetch DI Container
$container = $app->getContainer();

// Register Twig View helper
$container['view'] = function ($c) {
    $view = new \Slim\Views\Twig('include/views', [
        //'cache' => 'include/cache'
    ]);

    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $c['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new \Slim\Views\TwigExtension($c['router'], $basePath));

    return $view;
};

//Override the default Not Found Handler
$container['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        return $c['view']->render($response->withStatus(404), '404.html', [
            "myMagic" => "Let's roll"
        ]);
    };
};

$app->get('/', function ($request, $response, $args){
    $page = 1;
    $page = $page ? (int)$page : 1;

    $posts = find_posts($page);

    return $this->view->render($response, 'index.html', [
        'posts' => $posts
    ]);
    //return $response->write(print_r($posts,true));
});
$app->get('/page/{page:[0-9]+}', function ($request, $response, $args){
    if($args['page'] != 1){
        $page = $args['page'];
    }else{
        return $response->withRedirect('/');
    }
    $page = $page ? (int)$page : 1;

    $posts = find_posts($page);

    return $this->view->render($response, 'index.html', [
        'posts' => $posts
    ]);
    //return $response->write(print_r($posts,true));
});

$app->get('/{year:[0-9]{4}+}/{month:[0-9]{2}+}/{name}', function ($request, $response, $args){
    $post = find_post($args['year'],$args['month'],$args['name']);

    if(empty($post)){
        return $this->view->render($response->withStatus(404), "404.html");

    }

    return $this->view->render($response, 'post.html', [
        'post' => $post
    ]);
});


$app->run();