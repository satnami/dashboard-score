<?php

require('../vendor/autoload.php');

$connectionParamsSqlite = array(
    'driver' => 'pdo_sqlite',
    'path' => __DIR__.'/db/game.db',
);

$connectionParamsPostgres = array(
    'dbname' => 'd26hk8nf2ifc47',
    'user' => 'srbjhfordluehy',
    'password' => 'HWBAO1wlwfJRi_IeEweOSdziE9',
    'host' => 'ec2-54-243-249-137.compute-1.amazonaws.com',
    'port' => '5432',
    'driver' => 'pdo_pgsql',
);

$app = new Silex\Application();
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => $connectionParamsPostgres,
));

// Register the monolog logging service
$app->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.logfile' => 'php://stderr',
));

// Register view rendering
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));

// Our web handlers
$app->get('/', function() use($app) {
    $app['monolog']->addDebug('logging output.');
    return $app['twig']->render('index.twig');
});

// Signin
$app->get('/signin/{username}/{password}', function ($username, $password) use ($app) {
    $post = $app['db']->fetchAssoc("SELECT * FROM users WHERE username='" . $username . "' AND password='" . $password . "'");
    if($post){
        return "success|".$post['id']."|".$post['username']."|".$post['password']."|".$post['score']."|".$post['highscore'];
    }
    else
        return "error|not valid.";
});

// Signup
$app->get('/signup/{username}/{password}', function ($username, $password) use ($app) {
    $post = $app['db']->fetchAssoc("SELECT * FROM users WHERE username='" . $username . "'");
    if($post)
        return "error|already exist.";


    $app['db']->insert('users', array(
        'username' => $username,
        'password' => $password,
        'score' => 0,
        'highscore' => 0,
    ));
    $post = $app['db']->fetchAssoc("SELECT * FROM users WHERE username='" . $username . "' AND password='" . $password . "'");
    if($post){
        return "success|".$post['id']."|".$post['username']."|".$post['password']."|".$post['score']."|".$post['highscore'];
    }
    else
        return "error|unsuccessful";
});

// Score
$app->get('/score/{username}/{password}/{score}', function ($username, $password, $score) use ($app) {
    $post = $app['db']->fetchAssoc("SELECT * FROM users WHERE username='" . $username . "' AND password='" . $password . "'");
    if(!$post)
        return "error|user not exist.";

    $sql = "UPDATE users SET score=" .$score. " WHERE username ='" .$username. "'";
    $users = $app['db']->executeUpdate($sql, array(
        'score' => (int) $score,
        'username' => $username
    ));
    
    $sqlUpdate = "UPDATE users SET highscore=" .$score. " WHERE username ='" .$username. "' AND highscore < '" .$score. "'";
    $users = $app['db']->executeUpdate($sqlUpdate, array(
        'score' => (int) $score,
        'username' => $username
    ));
    return "success|done";
});

$app->run();