<?php
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


// Define app routes
$app->get('/hello/{name}', function (Request $request, Response $response, $args) {
    $this->logger->addInfo("Ticket list");

    $name = $request->getAttribute('name');
    return $response->write("Hello, ". $name);
});


$app->get('/users', function (Request $request, Response $response) {
    $this->logger->addInfo("Users list");
    $mapper = new UserMapper($this->db);
    $users = $mapper->getUsers();

    $response->getBody()->write(var_export($users, true));
    return $response;
});

$app->get('/users/{id}', function (Request $request, Response $response, $args) {
    $id = (int)$args['id'];
    $mapper = new UserMapper($this->db);
    $user = $mapper->getUserById($id);

    $response->getBody()->write(var_export($user, true));
    return $response;
});

$app->post('/users/new', function (Request $request, Response $response) {
    $data = $request->getParsedBody();

    $msisdn = filter_var($data['msisdn'], FILTER_SANITIZE_STRING);

    $user = new UserEntity();
    $user->setMsisdn($msisdn);

    $mapper = new UserMapper($this->db);
    $id = $mapper->save($user);

    $response->getBody()->write(var_export($id, true));
    return $response;
});

// Run app
$app->run();
