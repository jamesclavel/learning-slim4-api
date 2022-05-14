<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

# authentication
use Tuupola\Middleware\HttpBasicAuthentication\AuthenticatorInterface;
use Tuupola\Middleware\HttpBasicAuthentication;

use Dotenv\Dotenv;
// use RedBeanPHP\R as R;

# PDO database
use App\Model\DB;

# controllers
use App\Controller\HomeController;
use App\Controller\UserController;

require __DIR__ . '/../vendor/autoload.php';

Dotenv::createImmutable(__DIR__.'/../')->load();

class RandomAuthenticator implements AuthenticatorInterface {
    public function __invoke(array $args): bool {
        $userController = new UserController();
        return $userController->validate($args);
    }
}

$app = AppFactory::create();
$app->addRoutingMiddleware();
$app->addBodyParsingMiddleware();
$app->addErrorMiddleware(true, true, true);

$app->add(new HttpBasicAuthentication([
    "path" => ['/test'],
    // "ignore" => ['/'],
    "secure" => true,
    "realm" => "Protected",
    "relaxed" => ["localhost", "headers"],
    "authenticator" => new RandomAuthenticator,
]));


// $app->get('/', function (Request $request, Response $response, $args) {
//     $response->getBody()->write("Hello world!");
//     return $response;
// });

$app->get('/', HomeController::class);

$app->post('/register', UserController::class . ':register');

$app->any('/users[/{id}]', UserController::class . ':getUser');

// $app->post('/register', function (Request $request, Response $response, $args) {
//     $body = $request->getParsedBody();

//     $user = R::dispense('users');
//     $user->username = $body['username'];
//     $user->password = $body['password'];
//     $user->email = $body['email'];

//     $id = R::store($user);

//     $finalResponse = $response->withStatus(201);
//     return $finalResponse;
// });

// $app->get('/user/{id}', function (Request $request, Response $response, $args) {
//     $db = new DB;
//     $conn = $db->connect();

//     $userId = $args['id'];
//     $sql = "SELECT * FROM users WHERE id=:id";
//     if ($stmt = $conn->prepare($sql)) {
//         $stmt->execute([':id' => $userId]);
//         $row = $stmt->fetch(PDO::FETCH_ASSOC);
//     }
//     var_dump($row);

//     $response->getBody()->write('OK');
//     return $response;
// });

$app->get('/test', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Protected endpoint");
    return $response;
});



$app->run();