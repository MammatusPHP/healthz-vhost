<?php declare(strict_types=1);

namespace ReactiveApps\Command\HttpServer;

use Cake\Collection\Collection;
use Composed\Package;
use function Composed\packages;
use Doctrine\Common\Annotations\AnnotationReader;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;
use function igorw\get_in;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use ReactiveApps\Command\HttpServer\Annotations\Method;
use ReactiveApps\Command\HttpServer\Annotations\Route;
use Roave\BetterReflection\BetterReflection;
use Roave\BetterReflection\Reflector\ClassReflector;
use Roave\BetterReflection\SourceLocator\Type\SingleFileSourceLocator;

final class ControllerMiddleware
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var Dispatcher
     */
    private $router;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        $this->router = simpleDispatcher(function (RouteCollector $routeCollector) {
            foreach ($this->routes() as $route) {
                $routeCollector->addRoute(...$route);
            }
        });
    }

    private function routes(): iterable
    {
        foreach ($this->locations() as $controller) {
            yield from $this->controllerRoutes($controller);
        }
    }

    private function locations(): iterable
    {
        /** @var Package $package */
        foreach (packages(true) as $package) {
            $config = $package->getConfig('extra');

            if ($config === null) {
                continue;
            }

            $controllers = get_in(
                $config,
                [
                    'reactive-apps',
                    'http-controller',
                ]
            );

            if ($controllers === null) {
                continue;
            }

            yield from $controllers;
        }
    }

    private function controllerRoutes(string $controller)
    {
        $annotationReader = new AnnotationReader();
        $betterReflection = new BetterReflection();
        $astLocator = $betterReflection->astLocator();
        $reflector = new ClassReflector(new SingleFileSourceLocator($controller, $astLocator));
        foreach ($reflector->getAllClasses() as $class) {
            foreach ($class->getMethods() as $method) {
                $annotations = (new  Collection($annotationReader->getMethodAnnotations((new \ReflectionClass($class->getName()))->getMethod($method->getShortName()))))
                    ->indexBy(function (object $annotation) {
                        return get_class($annotation);
                    });


                if (!isset($annotations[Method::class]) || !isset($annotations[Route::class])) {
                    continue;
                }

                yield [
                    $annotations[Method::class]->getMethod(),
                    $annotations[Route::class]->getRoute(),
                    $class->getName() . '::' . $method->getName(),
                ];
            }
        }
    }

    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        $route = $this->router->dispatch($request->getMethod(), $request->getUri()->getPath());

        if ($route[0] === Dispatcher::NOT_FOUND) {
            return Factory::createResponse(404);
        }

        if ($route[0] === Dispatcher::METHOD_NOT_ALLOWED) {
            return Factory::createResponse(405)->withHeader('Allow', implode(', ', $route[1]));
        }

        foreach ($route[2] as $name => $value) {
            $request = $request->withAttribute($name, $value);
        }

        $request = $request->withAttribute('request-handler', $route[1]);

        return $next($request);
    }
}