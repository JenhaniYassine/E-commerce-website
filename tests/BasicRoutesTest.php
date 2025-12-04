<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BasicRoutesTest extends KernelTestCase
{
    public function testCanGenerateCommonUrls()
    {
        self::bootKernel();
        $router = self::$container->get('router');

        $routes = [
            'home' => [],
            'shop' => [],
            'search' => [],
            'contact' => [],
            'app_login' => [],
            'app_register' => [],
            'product_detail' => ['id' => 1],
        ];

        foreach ($routes as $name => $params) {
            try {
                $url = $router->generate($name, $params);
                $this->assertIsString($url, sprintf('Route "%s" should generate a URL.', $name));
                $this->assertNotEmpty($url, sprintf('Generated URL for "%s" should not be empty.', $name));
            } catch (\Exception $e) {
                $this->fail(sprintf('Failed to generate URL for route "%s": %s', $name, $e->getMessage()));
            }
        }
    }
}
