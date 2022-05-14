<?php
namespace App\Controller;

// use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class HomeController
{
    // private $container;

    // constructor receives container instance
    // public function __construct(ContainerInterface $container)
    // {
    //     $this->container = $container;
    // }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $response->getBody()->write('__invoke OK');
        
        return $response;
    }

    // public function home(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    // {
    //     // your code to access items in the container... $this->container->get('');
    //     // var_dump('home');
    //     $response->getBody()->write('home OK');
        
    //     return $response;
    // }

    // public function contact(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    // {
    //     // your code to access items in the container... $this->container->get('');
    //     var_dump('contact');
        
    //     return $response;
    // }
}