<?php
namespace App\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use RedBeanPHP\R as R;

class UserController
{
    public function __construct()
    {
        R::setup( 'mysql:host='.$_ENV['DB_HOST'].';dbname='.$_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASS'] );
    }

    public function register(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $body = $request->getParsedBody();

        $user = R::dispense('users');
        $user->username = $body['username'];
        $user->password = $body['password'];
        $user->email    = $body['email'];

        $id = R::store($user);
        
        $response->getBody()->write(json_encode(['id' => $id]));
        $finalResponse = $response->withStatus(201);
        return $finalResponse;
    }

    public function getUser(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        if (isset($args['id']) && !empty($args['id'])) {
            # find one
            $user = R::findOne( 'users', ' id = :id ', [ ':id' => $args['id'] ] );
            unset($user['password']);

            $response->getBody()->write(json_encode($user));
        } else {
            $users = R::getAll("SELECT id, username, email FROM users LIMIT 5");
            $response->getBody()->write(json_encode($users));
        }

        return $response;
    }

    public function validate(array $args)
    {
        $user = R::findOne('users', 'username=:username AND password=:password', [
            ':username' => $args['user'],
            ':password' => $args['password'],
        ]);        

        if (!empty($user)) {
            return true;
        } else return false;
    }
}
