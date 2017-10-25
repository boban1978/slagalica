<?php
use classes\GameMapper;
use classes\UserEntity;
use classes\UserMapper;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

// Create and configure Slim app

$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$config['db']['host']   = "localhost";
$config['db']['user']   = "root";
$config['db']['pass']   = "Qwerty123";
$config['db']['dbname'] = "slagalica";

$app = new \Slim\App(["settings" => $config]);

$container = $app->getContainer();

$container['logger'] = function($c) {
    $logger = new \Monolog\Logger('my_logger');
    $file_handler = new \Monolog\Handler\StreamHandler("../logs/app.log");
    $logger->pushHandler($file_handler);
    return $logger;
};

$container['db'] = function ($c) {
    $db = $c['settings']['db'];
    $pdo = new PDO("mysql:host=" . $db['host'] . ";dbname=" . $db['dbname'],
        $db['user'], $db['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};



//ROUTES
$app->get('/games', function (Request $request, Response $response) {
    $this->logger->addInfo("get_available_games");
    $mapper = new GameMapper($this->db);
    $games = $mapper->getGames();
    $response->getBody()->write(json_encode($games));
    return $response;
});


$app->post('/get_available_players', function (Request $request, Response $response) {
    $this->logger->addInfo("get_available_players");

    $data = $request->getParsedBody();
    $gamesId = filter_var($data['games_id'], FILTER_SANITIZE_STRING);

    $mapper = new UserMapper($this->db);
    $users = $mapper->getAvailableUsers($gamesId);

    $response->getBody()->write(json_encode($users));
    return $response;
});

/*
$app->get('/users/{id}', function (Request $request, Response $response, $args) {
    $id = (int) $args['id'];
    $mapper = new UserMapper($this->db);
    $user = $mapper->getUserById($id);

    $response->getBody()->write(var_dump($user));
    return $response;
});*/

$app->post('/join_game', function (Request $request, Response $response) {
    $data = $request->getParsedBody();

    $msisdn = filter_var($data['msisdn'], FILTER_SANITIZE_STRING);
    $gamesId = filter_var($data['games_id'], FILTER_SANITIZE_STRING);

    $user = new UserEntity();
    $user->id = $msisdn;
    $user->games_id = $gamesId;
    $user->timestamp = date('Y-m-d H:i:s');

    $mapper = new UserMapper($this->db);
    $id = $mapper->save($user);

    $response->getBody()->write(var_export($id, true));
    return $response;
});





// Run app
$app->run();
