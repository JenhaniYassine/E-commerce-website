<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RoutesExistTest extends KernelTestCase
{
    public function testRoutesAreRegistered()
    {
        self::bootKernel();
        $router = self::$container->get('router');

        $routeNames = [
            'home',
            'shop',
            'product_detail',
            'search',
            'contact',
            'app_login',
            'app_register',
        ];

        foreach ($routeNames as $name) {
            $route = $router->getRouteCollection()->get($name);
            $this->assertNotNull($route, sprintf('Route "%s" should be registered.', $name));

            $defaults = $route->getDefaults();
            if (isset($defaults['_controller'])) {
                $controller = $defaults['_controller'];
                if (is_string($controller) && strpos($controller, '::') !== false) {
                    [$class] = explode('::', $controller);
                    $this->assertTrue(class_exists($class), sprintf('Controller class "%s" for route "%s" should exist.', $class, $name));
                }
            }
        }
    }

    public function testUrlGeneration()
    {
        self::bootKernel();
        $router = self::$container->get('router');

        $url = $router->generate('home');
        $this->assertIsString($url);
        $this->assertNotEmpty($url);
    }
}
